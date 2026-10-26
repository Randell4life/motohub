<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Service;
use App\Models\ServiceBooking;
use Illuminate\Http\Request;

class WorkshopBookingController extends Controller
{
    public function create(string $slug, Request $request)
    {
        $workshop = $this->findWorkshop($slug);

        $service = $workshop->services()
            ->where('is_active', true)
            ->findOrFail($request->integer('service'));

        return view('workshops.booking', compact('workshop', 'service'));
    }

    public function store(Request $request, string $slug)
    {
        $workshop = $this->findWorkshop($slug);

        $validated = $request->validate([
            'service_id' => ['required', 'integer'],
            'motorcycle_brand' => ['nullable', 'string', 'max:100'],
            'motorcycle_model' => ['nullable', 'string', 'max:100'],
            'motorcycle_year' => ['nullable', 'integer', 'between:1950,2100'],
            'problem_description' => ['required', 'string', 'max:3000'],
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:255'],
            'preferred_date' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        $service = $workshop->services()
            ->where('is_active', true)
            ->findOrFail($validated['service_id']);

        ServiceBooking::create([
            'partner_id' => $workshop->id,
            'service_id' => $service->id,
            'service_price' => $service->price,
            'service_type' => $service->title,
            'motorcycle_brand' => $validated['motorcycle_brand'] ?? null,
            'motorcycle_model' => $validated['motorcycle_model'] ?? null,
            'motorcycle_year' => $validated['motorcycle_year'] ?? null,
            'problem_description' => $validated['problem_description'],
            'customer_name' => $validated['customer_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'preferred_date' => $validated['preferred_date'] ?? null,
            'status' => 'new',
        ]);

        return redirect()
            ->route('workshops.show', ['slug' => $workshop->slug])
            ->with('booking_success', 'თქვენი დაჯავშნის მოთხოვნა წარმატებით გაიგზავნა!');
    }

    private function findWorkshop(string $slug): Partner
    {
        return Partner::query()
            ->where('type', 'workshop')
            ->where('application_status', 'approved')
            ->where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();
    }
}
