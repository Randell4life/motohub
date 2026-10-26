<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class Workshop1Controller extends Controller
{
    /**
     * ახალი სახელოსნოების კატალოგი.
     */
    public function index(Request $request)
    {
        $workshops = Partner::query()
            ->where('type', 'workshop')
            ->where('application_status', 'approved')
            ->where('is_active', true)
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = trim($request->input('q'));

                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
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

        return view('workshops1.catalog', compact('workshops'));
    }

    /**
     * კონკრეტული სახელოსნოს დეტალური გვერდი.
     */
    public function show(string $slug)
    {
        $workshop = Partner::query()
            ->where('type', 'workshop')
            ->where('application_status', 'approved')
            ->where('is_active', true)
            ->where('slug', $slug)
            ->with([
                'services' => function ($query) {
                    $query
                        ->where('is_active', true)
                        ->orderBy('category')
                        ->orderBy('title');
                },
            ])
            ->firstOrFail();

        return view('workshops1.show', compact('workshop'));
    }
}