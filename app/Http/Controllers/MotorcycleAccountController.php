<?php

namespace App\Http\Controllers;

use App\Models\Motorcycle;
use App\Services\RewardsService;
use App\Models\MotorcycleImage;
use App\Models\RentalBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MotorcycleAccountController extends Controller
{
public function index(Request $request): View
{
    $user = $request->user();

    $motorcycles = $user
        ->motorcycles()
        ->latest()
        ->paginate(9);

    $rentalBookings = $user
        ->rentalBookings()
        ->with('rentalScooter')
        ->latest()
        ->paginate(5, ['*'], 'bookings_page');

    return view('account.index', compact(
        'motorcycles',
        'rentalBookings'
    ));
}

    public function create(): View
    {
        return view('account.form', [
            'motorcycle' => new Motorcycle(),
        ]);
    }

    public function store(Request $request, RewardsService $rewards)
    {
        $validated = $request->validate($this->rules());

        $image = $validated['image'] ?? null;
        unset($validated['image']);

        $validated['slug'] = $this->uniqueSlug($validated['title']);
        $validated['partner_id'] = null;
        $validated['status'] = 'draft';
        $validated['is_featured'] = false;
        $validated['published_at'] = null;
        $validated['image_path'] = $image
            ? $image->store('motorcycles', 'public')
            : null;

// განცხადება ავტომატურად მიეკუთვნება შესულ მომხმარებელს.
$motorcycle = $request->user()->motorcycles()->create($validated);

$rewards->onFirstMotorcycleListing($request->user(), (int) $motorcycle->id);

// დამატებითი ფოტოების შენახვა.
foreach ($request->file('images', []) as $index => $photo) {
    $motorcycle->images()->create([
        'image_path' => $photo->store('motorcycles', 'public'),
        'sort_order' => $index,
    ]);
}
        return redirect()
            ->route('account.index')
            ->with('success', 'განცხადება წარმატებით დაემატა. გამოქვეყნებამდე საჭიროა შემოწმება.');
    }

    public function edit(Request $request, int $motorcycle): View
    {
        // ვეძებთ მხოლოდ მიმდინარე მომხმარებლის განცხადებებს.
        $ownedMotorcycle = $request->user()
            ->motorcycles()
            ->findOrFail($motorcycle);

        return view('account.form', [
            'motorcycle' => $ownedMotorcycle,
        ]);
    }

    public function update(Request $request, int $motorcycle)
    {
        // სხვისი განცხადების ID-ის მითითება წვდომას არ იძლევა.
        $ownedMotorcycle = $request->user()
            ->motorcycles()
            ->findOrFail($motorcycle);

        $validated = $request->validate($this->rules());

        $image = $validated['image'] ?? null;
        unset($validated['image']);

        $validated['slug'] = $this->uniqueSlug(
            $validated['title'],
            $ownedMotorcycle->id
        );

        // ცვლილების შემდეგ განცხადება ხელახლა შესამოწმებლად გადავა.
        $validated['status'] = 'draft';
        $validated['published_at'] = null;
        $validated['is_featured'] = false;

        if ($image) {
            if ($ownedMotorcycle->image_path) {
                Storage::disk('public')->delete(
                    $ownedMotorcycle->image_path
                );
            }

            $validated['image_path'] = $image->store(
                'motorcycles',
                'public'
            );
        }

        $ownedMotorcycle->update($validated);
        // ახალი დამატებითი ფოტოების შენახვა.
$currentImageCount = $ownedMotorcycle->images()->count();

foreach ($request->file('images', []) as $photo) {
    $ownedMotorcycle->images()->create([
        'image_path' => $photo->store('motorcycles', 'public'),
        'sort_order' => $currentImageCount++,
    ]);
}

        return redirect()
            ->route('account.index')
            ->with('success', 'განცხადება განახლდა და ხელახლა შემოწმებას დაელოდება.');
    }

    public function destroy(Request $request, int $motorcycle)
    {
        $ownedMotorcycle = $request->user()
            ->motorcycles()
            ->findOrFail($motorcycle);

        if ($ownedMotorcycle->image_path) {
            Storage::disk('public')->delete(
                $ownedMotorcycle->image_path
            );
        }

        $ownedMotorcycle->delete();

        return redirect()
            ->route('account.index')
            ->with('success', 'განცხადება წაიშალა.');
    }

public function updateProfile(Request $request)
{
    $validated = $request->validate([
        'phone' => [
            'nullable',
            'string',
            'max:30',
            'regex:/^[0-9+\-\s()]+$/',
        ],
        'avatar' => [
            'nullable',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:5120',
        ],
    ]);

    $user = $request->user();

    $user->phone = $validated['phone'] ?? null;

    if ($request->hasFile('avatar')) {
        $oldAvatar = $user->avatar_path;
        $newAvatar = $request->file('avatar')->store('avatars', 'public');

        if ($newAvatar === false) {
            return back()
                ->withErrors(['avatar' => 'ფოტოს შენახვა ვერ მოხერხდა.'])
                ->withInput();
        }

        $user->avatar_path = $newAvatar;
        $user->save();

        if ($oldAvatar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($oldAvatar);
        }
    } else {
        $user->save();
    }

    return redirect()
        ->route('account.index')
        ->with('success', 'პროფილის მონაცემები წარმატებით შეინახა.');
}
    private function rules(): array
    {
        return [
        'title' => ['required', 'string', 'max:255'],
        'brand' => ['required', 'string', 'max:255'],
        'model' => ['required', 'string', 'max:255'],
        'year' => [
            'required',
            'integer',
            'min:1900',
            'max:' . (date('Y') + 1),
        ],
        'price' => [
            'required',
            'numeric',
            'min:0',
            'max:9999999999.99',
        ],
        'mileage' => ['nullable', 'integer', 'min:0'],
        'engine_cc' => ['nullable', 'integer', 'min:1', 'max:65535'],
        'condition' => ['required', Rule::in(['new', 'used'])],
        'location' => ['nullable', 'string', 'max:255'],
        'description' => ['nullable', 'string', 'max:10000'],

        'image' => [
            'nullable',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:5120',
        ],

        'images' => ['nullable', 'array', 'max:10'],
        'images.*' => [
            'nullable',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:5120',
            ],
        ];
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);

        if ($base === '') {
            $base = 'motorcycle';
        }

        $slug = $base;
        $suffix = 2;

        while (true) {
            $query = Motorcycle::query()->where('slug', $slug);

            if ($ignoreId !== null) {
                $query->where('id', '!=', $ignoreId);
            }

            if (! $query->exists()) {
                return $slug;
            }

            $slug = $base . '-' . $suffix;
            $suffix++;
        }
    }
}