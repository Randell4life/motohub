<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\PartListing;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PartController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:150'],
            'brand' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
            'part_type' => ['nullable', 'in:oem,aftermarket,used'],
            'motorcycle_brand' => ['nullable', 'string', 'max:100'],
            'motorcycle_model' => ['nullable', 'string', 'max:120'],
            'motorcycle_year' => ['nullable', 'integer', 'between:1900,2100'],
            'engine_cc' => ['nullable', 'integer', 'min:1', 'max:10000'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
            'condition' => ['nullable', 'in:new,used'],
            'source' => ['nullable', 'in:all,store,marketplace'],
        ]);

        $search = trim((string) ($filters['q'] ?? ''));
        $apply = function ($query) use ($filters, $search) {
            return $query
                ->when($search !== '', fn ($q) => $q->where(fn ($s) => $s
                    ->where('title', 'like', "%{$search}%")->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")->orWhere('part_number', 'like', "%{$search}%")
                    ->orWhere('compatible_motorcycle', 'like', "%{$search}%")
                    ->orWhere('motorcycle_brand', 'like', "%{$search}%")->orWhere('motorcycle_model', 'like', "%{$search}%")))
                ->when(!empty($filters['brand']), fn ($q) => $q->where('brand', $filters['brand']))
                ->when(!empty($filters['category']), fn ($q) => $q->where('category', $filters['category']))
                ->when(!empty($filters['part_type']), fn ($q) => $q->where('part_type', $filters['part_type']))
                ->when(!empty($filters['motorcycle_brand']), fn ($q) => $q->where(fn ($s) => $s->where('motorcycle_brand', $filters['motorcycle_brand'])->orWhere('compatible_motorcycle', 'like', '%'.$filters['motorcycle_brand'].'%')))
                ->when(!empty($filters['motorcycle_model']), fn ($q) => $q->where(fn ($s) => $s->where('motorcycle_model', 'like', '%'.$filters['motorcycle_model'].'%')->orWhere('compatible_motorcycle', 'like', '%'.$filters['motorcycle_model'].'%')))
                ->when(!empty($filters['motorcycle_year']), fn ($q) => $q->where(fn ($s) => $s->where('motorcycle_year', $filters['motorcycle_year'])->orWhere('compatible_motorcycle', 'like', '%'.$filters['motorcycle_year'].'%')))
                ->when(!empty($filters['engine_cc']), fn ($q) => $q->where('engine_cc', $filters['engine_cc']))
                ->when(isset($filters['min_price']), fn ($q) => $q->where('price', '>=', $filters['min_price']))
                ->when(isset($filters['max_price']), fn ($q) => $q->where('price', '<=', $filters['max_price']));
        };

        $items = collect();
        if (($filters['source'] ?? 'all') !== 'marketplace') {
            $items = $items->concat($apply(Part::query()->where('is_active', true))->latest()->get()->map(fn ($p) => [
                'id'=>$p->id,'source'=>'store','title'=>$p->title,'brand'=>$p->brand,'category'=>$p->category,
                'condition'=>'new','part_type'=>$p->part_type,'part_number'=>$p->part_number,
                'compatible_motorcycle'=>$p->compatible_motorcycle,'motorcycle_brand'=>$p->motorcycle_brand,
                'motorcycle_model'=>$p->motorcycle_model,'motorcycle_year'=>$p->motorcycle_year,'engine_cc'=>$p->engine_cc,
                'price'=>$p->price,'image_url'=>$p->image_path ? Storage::disk('public')->url($p->image_path) : null,
                'stock'=>$p->stock,'seller_name'=>'MotoHub-ის მაღაზია','seller_phone'=>null,'created_at'=>$p->created_at,
            ]));
        }
        if (($filters['source'] ?? 'all') !== 'store') {
            $items = $items->concat($apply(PartListing::query()->with('user')->where('status','published'))
                ->when(isset($filters['condition']), fn ($q) => $q->where('condition',$filters['condition']))
                ->latest()->get()->map(fn ($p) => [
                    'id'=>$p->id,'source'=>'marketplace','title'=>$p->title,'brand'=>$p->brand,'category'=>$p->category,
                    'condition'=>$p->condition,'part_type'=>$p->part_type,'part_number'=>$p->part_number,
                    'compatible_motorcycle'=>$p->compatible_motorcycle,'motorcycle_brand'=>$p->motorcycle_brand,
                    'motorcycle_model'=>$p->motorcycle_model,'motorcycle_year'=>$p->motorcycle_year,'engine_cc'=>$p->engine_cc,
                    'price'=>$p->price,'image_url'=>$p->image_path ? Storage::disk('public')->url($p->image_path) : null,
                    'stock'=>null,'seller_name'=>$p->user?->name ?? 'გამყიდველი','seller_phone'=>$p->user?->phone,'created_at'=>$p->created_at,
                ]));
        }

        $items = $items->sortByDesc('created_at')->values();
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 12;
        $parts = new LengthAwarePaginator($items->slice(($page-1)*$perPage,$perPage)->values(), $items->count(), $perPage, $page, [
            'path'=>LengthAwarePaginator::resolveCurrentPath(),'query'=>$request->query()
        ]);

        $brands = Part::where('is_active',true)->whereNotNull('brand')->pluck('brand')
            ->merge(PartListing::where('status','published')->whereNotNull('brand')->pluck('brand'))->filter()->unique()->sort()->values();
        $categories = Part::where('is_active',true)->whereNotNull('category')->pluck('category')
            ->merge(PartListing::where('status','published')->whereNotNull('category')->pluck('category'))->filter()->unique()->sort()->values();
        $motorcycleBrands = Part::where('is_active',true)->whereNotNull('motorcycle_brand')->pluck('motorcycle_brand')
            ->merge(PartListing::where('status','published')->whereNotNull('motorcycle_brand')->pluck('motorcycle_brand'))->filter()->unique()->sort()->values();

        return view('parts.index', ['parts'=>$parts,'search'=>$search,'brands'=>$brands,'categories'=>$categories,'motorcycleBrands'=>$motorcycleBrands,'filters'=>$filters]);
    }

    public function show(string $source, int $id): View
    {
        abort_unless(in_array($source,['store','marketplace'],true),404);
        if ($source === 'store') {
            $p = Part::where('is_active',true)->findOrFail($id);
            $item = ['id'=>$p->id,'source'=>'store','title'=>$p->title,'brand'=>$p->brand,'category'=>$p->category,'condition'=>'new','part_type'=>$p->part_type,'part_number'=>$p->part_number,'compatible_motorcycle'=>$p->compatible_motorcycle,'motorcycle_brand'=>$p->motorcycle_brand,'motorcycle_model'=>$p->motorcycle_model,'motorcycle_year'=>$p->motorcycle_year,'engine_cc'=>$p->engine_cc,'price'=>$p->price,'image_url'=>$p->image_path ? Storage::disk('public')->url($p->image_path) : null,'stock'=>$p->stock];
            $description=$p->description; $sellerName='MotoHub-ის მაღაზია'; $sellerPhone=null;
        } else {
            $p=PartListing::with('user')->where('status','published')->findOrFail($id);
            $item=['id'=>$p->id,'source'=>'marketplace','title'=>$p->title,'brand'=>$p->brand,'category'=>$p->category,'condition'=>$p->condition,'part_type'=>$p->part_type,'part_number'=>$p->part_number,'compatible_motorcycle'=>$p->compatible_motorcycle,'motorcycle_brand'=>$p->motorcycle_brand,'motorcycle_model'=>$p->motorcycle_model,'motorcycle_year'=>$p->motorcycle_year,'engine_cc'=>$p->engine_cc,'price'=>$p->price,'image_url'=>$p->image_path ? Storage::disk('public')->url($p->image_path) : null,'stock'=>null];
            $description=$p->description; $sellerName=$p->user?->name ?? 'გამყიდველი'; $sellerPhone=$p->user?->phone;
        }
        return view('parts.show',compact('item','description','sellerName','sellerPhone'));
    }
}