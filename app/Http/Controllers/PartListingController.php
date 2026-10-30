<?php

namespace App\Http\Controllers;

use App\Models\PartListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PartListingController extends Controller
{

public function index(Request $request, ?string $condition = null)
{
    $condition = $condition
        ?? $request->route()?->defaults['condition']
        ?? $request->query('condition');

    abort_unless(in_array($condition, ['new', 'used'], true), 404);

    $validated = $request->validate([
        'q' => ['nullable', 'string', 'max:150'],
        'brand' => ['nullable', 'string', 'max:100'],
        'category' => ['nullable', 'string', 'max:100'],
        'min_price' => ['nullable', 'numeric', 'min:0'],
        'max_price' => ['nullable', 'numeric', 'min:0'],
        'compatible_motorcycle' => ['nullable', 'string', 'max:150'],
    ]);

    $listings = PartListing::query()
        ->where('status', 'published')
        ->where('condition', $condition)

        ->when($request->filled('q'), function ($query) use ($request) {
            $search = trim($request->input('q'));

            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('title', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('part_number', 'like', "%{$search}%")
                    ->orWhere('compatible_motorcycle', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        })

        ->when($request->filled('brand'), function ($query) use ($request) {
            $query->where('brand', $request->input('brand'));
        })

        ->when($request->filled('category'), function ($query) use ($request) {
            $query->where('category', $request->input('category'));
        })

        ->when($request->filled('compatible_motorcycle'), function ($query) use ($request) {
            $query->where(
                'compatible_motorcycle',
                'like',
                '%' . trim($request->input('compatible_motorcycle')) . '%'
            );
        })

        ->when($request->filled('min_price'), function ($query) use ($request) {
            $query->where('price', '>=', $request->input('min_price'));
        })

        ->when($request->filled('max_price'), function ($query) use ($request) {
            $query->where('price', '<=', $request->input('max_price'));
        })

        ->latest()
        ->paginate(12)
        ->withQueryString();

    $brands = PartListing::query()
        ->where('status', 'published')
        ->where('condition', $condition)
        ->whereNotNull('brand')
        ->where('brand', '!=', '')
        ->distinct()
        ->orderBy('brand')
        ->pluck('brand');

    $categories = PartListing::query()
        ->where('status', 'published')
        ->where('condition', $condition)
        ->whereNotNull('category')
        ->where('category', '!=', '')
        ->distinct()
        ->orderBy('category')
        ->pluck('category');

    return view('part-listings.index', compact(
        'listings',
        'condition',
        'brands',
        'categories'
    ));
}


    public function accountIndex(Request $request)
    {
        $listings = $request->user()
            ->partListings()
            ->latest()
            ->paginate(12);

        return view('account.part-listings.index', compact('listings'));
    }

    public function create()
    {
        return view('account.part-listings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'condition' => ['required', Rule::in(['new', 'used'])],
            'part_number' => ['nullable', 'string', 'max:255'],
            'compatible_motorcycle' => ['nullable', 'string', 'max:255'],
            'part_type' => ['nullable', Rule::in(['oem', 'aftermarket', 'used'])],
            'motorcycle_brand' => ['nullable', 'string', 'max:100'],
            'motorcycle_model' => ['nullable', 'string', 'max:120'],
            'motorcycle_year' => ['nullable', 'integer', 'between:1900,2100'],
            'engine_cc' => ['nullable', 'integer', 'min:1', 'max:10000'],
            'description' => ['nullable', 'string', 'max:10000'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('part-listings', 'public')
            : null;

        $title = $validated['title'];

        $listing = $request->user()->partListings()->create([
            'title' => $title,
            'slug' => $this->uniqueSlug($title),
            'brand' => $validated['brand'] ?? null,
            'category' => $validated['category'] ?? null,
            'condition' => $validated['condition'],
            'part_number' => $validated['part_number'] ?? null,
            'compatible_motorcycle' => $validated['compatible_motorcycle'] ?? null,
            'part_type' => $validated['part_type'] ?? null,
            'motorcycle_brand' => $validated['motorcycle_brand'] ?? null,
            'motorcycle_model' => $validated['motorcycle_model'] ?? null,
            'motorcycle_year' => $validated['motorcycle_year'] ?? null,
            'engine_cc' => $validated['engine_cc'] ?? null,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'image_path' => $imagePath,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('account.part-listings.index')
            ->with('success', 'განცხადება დაემატა და მოდერაციას ელოდება.');
    }

    public function edit(Request $request, PartListing $partListing)
    {
        $listing = $request->user()
            ->partListings()
            ->findOrFail($partListing->id);

        return view('account.part-listings.edit', compact('listing'));
    }

    public function update(Request $request, PartListing $partListing)
    {
        $listing = $request->user()
            ->partListings()
            ->findOrFail($partListing->id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'condition' => ['required', Rule::in(['new', 'used'])],
            'part_number' => ['nullable', 'string', 'max:255'],
            'compatible_motorcycle' => ['nullable', 'string', 'max:255'],
            'part_type' => ['nullable', Rule::in(['oem', 'aftermarket', 'used'])],
            'motorcycle_brand' => ['nullable', 'string', 'max:100'],
            'motorcycle_model' => ['nullable', 'string', 'max:120'],
            'motorcycle_year' => ['nullable', 'integer', 'between:1900,2100'],
            'engine_cc' => ['nullable', 'integer', 'min:1', 'max:10000'],
            'description' => ['nullable', 'string', 'max:10000'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        $imagePath = $listing->image_path;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('part-listings', 'public');

            if ($listing->image_path) {
                Storage::disk('public')->delete($listing->image_path);
            }
        }

        $listing->update([
            'title' => $validated['title'],
            'slug' => $this->uniqueSlug($validated['title'], $listing->id),
            'brand' => $validated['brand'] ?? null,
            'category' => $validated['category'] ?? null,
            'condition' => $validated['condition'],
            'part_number' => $validated['part_number'] ?? null,
            'compatible_motorcycle' => $validated['compatible_motorcycle'] ?? null,
            'part_type' => $validated['part_type'] ?? null,
            'motorcycle_brand' => $validated['motorcycle_brand'] ?? null,
            'motorcycle_model' => $validated['motorcycle_model'] ?? null,
            'motorcycle_year' => $validated['motorcycle_year'] ?? null,
            'engine_cc' => $validated['engine_cc'] ?? null,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'image_path' => $imagePath,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('account.part-listings.index')
            ->with('success', 'ცვლილებები შენახულია. განცხადება ხელახლა გაივლის მოდერაციას.');
    }

    public function destroy(Request $request, PartListing $partListing)
    {
        $listing = $request->user()
            ->partListings()
            ->findOrFail($partListing->id);

        if ($listing->image_path) {
            Storage::disk('public')->delete($listing->image_path);
        }

        $listing->delete();

        return redirect()
            ->route('account.part-listings.index')
            ->with('success', 'განცხადება წაიშალა.');
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);

        if ($base === '') {
            $base = 'part';
        }

        $slug = $base;
        $counter = 2;

        while (
            PartListing::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }
}
