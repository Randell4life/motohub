<?php

namespace App\Http\Controllers;

use App\Models\Motorcycle;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MotorcycleCatalogController extends Controller
{
    public function index(Request $request): View
    {
        $motorcycles = Motorcycle::with('images')
            ->where('status', 'published')

            ->when($request->filled('q'), function ($query) use ($request) {
                $search = trim($request->input('q'));

                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%");
                });
            })

            ->when($request->filled('brand'), function ($query) use ($request) {
                $query->where('brand', $request->input('brand'));
            })

            ->when($request->filled('min_price') && is_numeric($request->input('min_price')), function ($query) use ($request) {
                $query->where('price', '>=', (float) $request->input('min_price'));
            })

            ->when($request->filled('max_price') && is_numeric($request->input('max_price')), function ($query) use ($request) {
                $query->where('price', '<=', (float) $request->input('max_price'));
            })

            ->when($request->filled('min_year') && is_numeric($request->input('min_year')), function ($query) use ($request) {
                $query->where('year', '>=', (int) $request->input('min_year'));
            })

            ->when($request->filled('max_year') && is_numeric($request->input('max_year')), function ($query) use ($request) {
                $query->where('year', '<=', (int) $request->input('max_year'));
            })

            ->when($request->filled('min_cc') && is_numeric($request->input('min_cc')), fn ($query) => $query->where('engine_cc', '>=', (int) $request->input('min_cc')))
            ->when($request->filled('max_cc') && is_numeric($request->input('max_cc')), fn ($query) => $query->where('engine_cc', '<=', (int) $request->input('max_cc')))
            ->when($request->filled('min_mileage') && is_numeric($request->input('min_mileage')), fn ($query) => $query->where('mileage', '>=', (int) $request->input('min_mileage')))
            ->when($request->filled('max_mileage') && is_numeric($request->input('max_mileage')), fn ($query) => $query->where('mileage', '<=', (int) $request->input('max_mileage')))
            ->when($request->boolean('featured'), fn ($query) => $query->where('is_featured', true))

            ->when(
                in_array($request->input('type'), ['new', 'used'], true),
                function ($query) use ($request) {
                    $query->where('condition', $request->input('type'));
                }
            )

            ->when($request->filled('location'), function ($query) use ($request) {
                $query->where('location', 'like', '%' . trim($request->input('location')) . '%');
            })

            ->when($request->input('sort') === 'price_asc', function ($query) {
                $query->orderBy('price');
            })

            ->when($request->input('sort') === 'price_desc', function ($query) {
                $query->orderByDesc('price');
            })

            ->when($request->input('sort') === 'year_desc', fn ($query) => $query->orderByDesc('year'))
            ->when($request->input('sort') === 'mileage_asc', fn ($query) => $query->orderBy('mileage'))
            ->when($request->input('sort') === 'mileage_desc', fn ($query) => $query->orderByDesc('mileage'))
            ->when($request->input('sort') === 'oldest', fn ($query) => $query->orderBy('published_at'))

            ->when(!in_array($request->input('sort'), ['price_asc', 'price_desc', 'year_desc', 'mileage_asc', 'mileage_desc', 'oldest'], true), function ($query) {
                $query->orderByDesc('is_featured')
                    ->orderByDesc('published_at')
                    ->orderByDesc('created_at');
            })

            ->paginate(12)
            ->withQueryString();

        $brands = Motorcycle::where('status', 'published')
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand');

        return view('motorcycles.index', compact('motorcycles', 'brands'));
    }
}