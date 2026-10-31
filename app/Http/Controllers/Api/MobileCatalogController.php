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
            ->with(['images', 'user:id,name,phone,avatar_path', 'partner:id,name,phone,logo_path'])
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
            ->when($request->filled('min_year') && is_numeric($request->query('min_year')), fn ($builder) => $builder->where('year', '>=', (int) $request->query('min_year')))
            ->when($request->filled('max_year') && is_numeric($request->query('max_year')), fn ($builder) => $builder->where('year', '<=', (int) $request->query('max_year')))
            ->when($request->filled('min_cc') && is_numeric($request->query('min_cc')), fn ($builder) => $builder->where('engine_cc', '>=', (int) $request->query('min_cc')))
            ->when($request->filled('max_cc') && is_numeric($request->query('max_cc')), fn ($builder) => $builder->where('engine_cc', '<=', (int) $request->query('max_cc')))
            ->when($request->filled('min_mileage') && is_numeric($request->query('min_mileage')), fn ($builder) => $builder->where('mileage', '>=', (int) $request->query('min_mileage')))
            ->when($request->filled('max_mileage') && is_numeric($request->query('max_mileage')), fn ($builder) => $builder->where('mileage', '<=', (int) $request->query('max_mileage')))
            ->when(in_array($request->query('condition'), ['new', 'used'], true), fn ($builder) => $builder->where('condition', $request->query('condition')))
            ->when($request->filled('location'), fn ($builder) => $builder->where('location', 'like', '%' . trim((string) $request->query('location')) . '%'))
            ->when($request->boolean('featured'), fn ($builder) => $builder->where('is_featured', true))
            ->when($request->query('sort') === 'price_asc', fn ($builder) => $builder->orderBy('price'))
            ->when($request->query('sort') === 'price_desc', fn ($builder) => $builder->orderByDesc('price'))
            ->when($request->query('sort') === 'year_desc', fn ($builder) => $builder->orderByDesc('year'))
            ->when($request->query('sort') === 'mileage_asc', fn ($builder) => $builder->orderBy('mileage'))
            ->when($request->query('sort') === 'mileage_desc', fn ($builder) => $builder->orderByDesc('mileage'))
            ->when($request->query('sort') === 'oldest', fn ($builder) => $builder->orderBy('published_at'))
            ->when(!in_array($request->query('sort'), ['price_asc','price_desc','year_desc','mileage_asc','mileage_desc','oldest'], true), fn ($builder) => $builder->orderByDesc('is_featured')->orderByDesc('published_at')->orderByDesc('created_at'));

        $page = $query->paginate(min(max((int) $request->query('per_page', 12), 1), 50));
        $page->getCollection()->transform(fn ($item) => [
            'id' => $item->id, 'user_id' => $item->user_id, 'title' => $item->title, 'slug' => $item->slug,
            'brand' => $item->brand, 'model' => $item->model, 'year' => $item->year,
            'price' => $item->price, 'mileage' => $item->mileage, 'engine_cc' => $item->engine_cc,
            'condition' => $item->condition, 'location' => $item->location,
            'description' => $item->description, 'image_url' => $this->storageUrl($item->image_path),
            'images' => $item->images->map(fn ($image) => [
                'id' => $image->id,
                'url' => $this->storageUrl($image->image_path ?? $image->path ?? null),
            ])->values(),
            'is_featured' => (bool) $item->is_featured,
            'published_at' => $item->published_at?->toIso8601String(),
            'seller' => [
                'name' => $item->partner?->name ?: ($item->user?->name ?: 'MotoHub გამყიდველი'),
                'phone' => $item->user?->phone ?: $item->partner?->phone,
                'avatar_url' => $this->storageUrl($item->partner?->logo_path ?: $item->user?->avatar_path),
                'type' => $item->partner?->type,
            ],
        ]);
        return response()->json($page);
    }


    public function workshops(Request $request)
    {
        $items = \Illuminate\Support\Facades\DB::table('partners')
            ->where('type','workshop')->where('application_status','approved')->where('is_active',true)
            ->when($request->filled('q'),fn($q)=>$q->where(function($sub)use($request){$term='%'.trim($request->query('q')).'%';$sub->where('name','like',$term)->orWhere('address','like',$term)->orWhere('description','like',$term);}))
            ->orderBy('name')->paginate(min(max((int)$request->query('per_page',12),1),50));
        $items->getCollection()->transform(function($item){
            $item->services=\Illuminate\Support\Facades\DB::table('services')->where('partner_id',$item->id)->where('is_active',true)->orderBy('title')->get(['id','title','category','description','price']);
            return $item;
        });
        return response()->json($items);
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
