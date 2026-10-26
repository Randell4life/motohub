<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class WorkshopCatalogController extends Controller
{
    public function index(Request $request)
    {
        $workshops = Partner::query()
            ->where('type', 'workshop')
            ->where('application_status', 'approved')
            ->where('is_active', true)
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = trim($request->input('q'));

                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->withCount([
                'services as active_services_count' => function ($query) {
                    $query->where('is_active', true);
                },
            ])
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

$html = view('workshops.catalog_rebuilt', compact('workshops'))->render();

return response()->json([
    'controller' => __METHOD__,
    'view_path' => app('view')->getFinder()->find('workshops.catalog_rebuilt'),
    'correct_title' => str_contains($html, 'სახელოსნოები | MotoHub'),
    'test_marker' => str_contains($html, 'WORKSHOPS-REBUILT-RENDER-CHECK'),
    'contains_garage_33' => str_contains($html, 'Garage 33'),
    'contains_motorcycle_variable' => str_contains($html, '$motorcycle'),
    'preview' => substr($html, 0, 200),
]);
    }

    public function show(string $slug)
    {
        $workshop = Partner::query()
            ->where('type', 'workshop')
            ->where('application_status', 'approved')
            ->where('is_active', true)
            ->where('slug', $slug)
            ->with([
                'services' => function ($query) {
                    $query->where('is_active', true)
                        ->orderBy('category')
                        ->orderBy('title');
                },
            ])
            ->firstOrFail();

        return view('workshops.show', compact('workshop'));
    }
}