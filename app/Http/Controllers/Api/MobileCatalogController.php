<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Motorcycle;
use App\Models\Part;
use App\Models\PartListing;
use App\Models\RentalScooter;
use App\Models\ServiceCenter;
use Illuminate\Http\Request;

class MobileCatalogController extends Controller
{
    public function motorcycles(Request $request)
    {
        $query = Motorcycle::query()
            ->with('images')
            ->where('status', 'published')
            ->when($request->filled('q'), function ($builder) use ($request) {
                $term = trim((string) $request->query('q'));
                $builder->where(fn ($where) => $where->where('title', 'like', "%{$term}%")
                    ->orWhere('brand', 'like', "%{$term}%")
                    ->orWhere('model', 'like', "%{$term}%"));
            })
            ->when($request->filled('brand'), fn ($builder) => $builder->where('brand', $request->query('brand')))
            ->when($request->filled('min_price') && is_numeric($request->query('min_price')), fn ($builder) => $builder->where('price', '>=', (float) $request->query('min_price')))
            ->when($request->filled('max_price') && is_numeric($request->query('max_price')), fn ($builder) => $builder->where('price', '<=', (float) $request->query('max_price')))
            ->orderByDesc('is_featured')->orderByDesc('published_at')->orderByDesc('created_at');

        $page = $query->paginate(min(max((int) $request->query('per_page', 12), 1), 50));
        $page->getCollection()->transform(fn ($item) => [
            'id' => $item->id, 'title' => $item->title, 'slug' => $item->slug,
            'brand' => $item->brand, 'model' => $item->model, 'year' => $item->year,
            'price' => $item->price, 'mileage' => $item->mileage, 'engine_cc' => $item->engine_cc,
            'condition' => $item->condition, 'location' => $item->location,
            'description' => $item->description, 'image_url' => $this->storageUrl($item->image_path),
            'images' => $item->images->map(fn ($image) => [
                'id' => $image->id,
                'url' => $this->storageUrl($image->image_path ?? $image->path ?? null),
            ])->values(),
            'is_featured' => (bool) $item->is_featured,
        ]);
        return response()->json($page);
    }

    public function parts(Request $request)
    {
        $parts = Part::query()->where('is_active', true)
            ->when($request->filled('q'), function ($builder) use ($request) {
                $term = trim((string) $request->query('q'));
                $builder->where(fn ($where) => $where->where('title', 'like', "%{$term}%")
                    ->orWhere('brand', 'like', "%{$term}%")
                    ->orWhere('part_number', 'like', "%{$term}%")
                    ->orWhere('compatible_motorcycle', 'like', "%{$term}%"));
            })->latest()->paginate(min(max((int) $request->query('per_page', 12), 1), 50));
        $parts->getCollection()->transform(fn ($item) => [
            'id' => $item->id, 'title' => $item->title, 'slug' => $item->slug,
            'brand' => $item->brand, 'category' => $item->category, 'part_number' => $item->part_number,
            'compatible_motorcycle' => $item->compatible_motorcycle, 'description' => $item->description,
            'price' => $item->price, 'stock' => $item->stock, 'image_url' => $this->storageUrl($item->image_path),
        ]);
        return response()->json($parts);
    }

    public function partListings(Request $request)
    {
        $listings = PartListing::query()->where('status', 'published')
            ->when(in_array($request->query('condition'), ['new', 'used'], true), fn ($builder) => $builder->where('condition', $request->query('condition')))
            ->when($request->filled('q'), function ($builder) use ($request) {
                $term = trim((string) $request->query('q'));
                $builder->where(fn ($where) => $where->where('title', 'like', "%{$term}%")
                    ->orWhere('brand', 'like', "%{$term}%")->orWhere('part_number', 'like', "%{$term}%"));
            })->latest()->paginate(min(max((int) $request->query('per_page', 12), 1), 50));
        $listings->getCollection()->transform(fn ($item) => [
            'id' => $item->id, 'title' => $item->title, 'slug' => $item->slug,
            'brand' => $item->brand, 'category' => $item->category, 'condition' => $item->condition,
            'part_number' => $item->part_number, 'compatible_motorcycle' => $item->compatible_motorcycle,
            'description' => $item->description, 'price' => $item->price, 'image_url' => $this->storageUrl($item->image_path),
        ]);
        return response()->json($listings);
    }

    public function rentals(Request $request)
    {
        $scooters = RentalScooter::query()->where('status', 'active')
            ->when($request->filled('q'), function ($builder) use ($request) {
                $term = trim((string) $request->query('q'));
                $builder->where(fn ($where) => $where->where('title', 'like', "%{$term}%")
                    ->orWhere('brand', 'like', "%{$term}%")->orWhere('model', 'like', "%{$term}%"));
            })->latest()->paginate(min(max((int) $request->query('per_page', 12), 1), 50));
        $scooters->getCollection()->transform(fn ($item) => [
            'id' => $item->id, 'title' => $item->title, 'slug' => $item->slug,
            'brand' => $item->brand, 'model' => $item->model, 'year' => $item->year,
            'engine_cc' => $item->engine_cc, 'daily_rate' => $item->daily_rate,
            'location' => $item->location, 'description' => $item->description,
            'image_url' => $this->storageUrl($item->image_path),
        ]);
        return response()->json($scooters);
    }

    public function serviceCenters(Request $request)
    {
        $centers = ServiceCenter::query()->active()->with([
            'services' => fn ($builder) => $builder->where('is_active', true)->orderBy('name'),
            'hours' => fn ($builder) => $builder->orderBy('day_of_week'),
        ])->when($request->filled('q'), function ($builder) use ($request) {
            $term = trim((string) $request->query('q'));
            $builder->where(fn ($where) => $where->where('name', 'like', "%{$term}%")
                ->orWhere('city', 'like', "%{$term}%")->orWhere('address', 'like', "%{$term}%")
                ->orWhereHas('services', fn ($services) => $services->where('is_active', true)->where('name', 'like', "%{$term}%")));
        })->when($request->filled('city'), fn ($builder) => $builder->where('city', 'like', '%' . trim((string) $request->query('city')) . '%'))
            ->orderByDesc('is_verified')->orderBy('name')
            ->paginate(min(max((int) $request->query('per_page', 12), 1), 50));
        $centers->getCollection()->transform(fn ($center) => [
            'id' => $center->id, 'name' => $center->name, 'slug' => $center->slug,
            'description' => $center->description, 'phone' => $center->phone, 'email' => $center->email,
            'city' => $center->city, 'address' => $center->address, 'is_verified' => (bool) $center->is_verified,
            'services' => $center->services->map(fn ($service) => [
                'id' => $service->id, 'name' => $service->name, 'description' => $service->description,
                'price' => $service->price, 'price_type' => $service->price_type,
                'duration_minutes' => $service->duration_minutes,
            ])->values(),
            'hours' => $center->hours->map(fn ($hour) => [
                'day_of_week' => $hour->day_of_week, 'opens_at' => $hour->opens_at,
                'closes_at' => $hour->closes_at, 'is_closed' => (bool) $hour->is_closed,
            ])->values(),
        ]);
        return response()->json($centers);
    }

    private function storageUrl(?string $path): ?string
    {
        if (!$path) return null;
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) return $path;
        return request()->getSchemeAndHttpHost() . '/storage/' . ltrim(str_replace('public/', '', $path), '/');
    }
}
