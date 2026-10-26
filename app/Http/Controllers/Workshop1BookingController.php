<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class Workshop1BookingController extends Controller
{
    /**
     * ახალი სისტემის დაჯავშნის გვერდი.
     */
    public function create(string $slug)
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

        $services = $workshop->services;

        return view(
            'workshops1.booking',
            compact('workshop', 'services')
        );
    }

    /**
     * დაჯავშნის შენახვა დაემატება მონაცემთა ბაზის
     * სტრუქტურის შემოწმების შემდეგ.
     */
    public function store(Request $request, string $slug)
    {
        return back()
            ->withInput()
            ->withErrors([
                'booking' => 'დაჯავშნის შენახვის ფუნქცია ჯერ არ არის ჩართული.',
            ]);
    }
}
