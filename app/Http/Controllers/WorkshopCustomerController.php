<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Motorcycle;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WorkshopCustomerController extends Controller
{
    private function getWorkshop(Request $request): Partner
    {
        return Partner::query()
            ->where('user_id', $request->user()->id)
            ->where('type', 'workshop')
            ->where('application_status', 'approved')
            ->where('is_active', true)
            ->firstOrFail();
    }

public function index(Request $request): View
{
    $partner = $this->getWorkshop($request);

    $customers = $partner->customers()
        ->with([
            'motorcycles' => function ($query) use ($partner) {
                $query->where('partner_id', $partner->id);
            }
        ])
        ->latest()
        ->paginate(15);

    $availableMotorcycles = Motorcycle::query()
        ->where('partner_id', $partner->id)
        ->whereNull('customer_id')
        ->orderBy('brand')
        ->orderBy('model')
        ->get();

    return view('workshops.customers.index', compact(
        'partner',
        'customers',
        'availableMotorcycles'
    ));
}

    public function store(Request $request): RedirectResponse
    {
        $partner = $this->getWorkshop($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $partner->customers()->create($data);

        return redirect()
            ->route('workshop.customers.index')
            ->with('success', 'მომხმარებელი წარმატებით დაემატა.');
    }

    public function edit(Request $request, int $customer): View
    {
        $partner = $this->getWorkshop($request);

        $customer = $partner->customers()->findOrFail($customer);

        return view('workshops.customers.edit', compact(
            'partner',
            'customer'
        ));
    }

    public function update(
        Request $request,
        int $customer
    ): RedirectResponse {
        $partner = $this->getWorkshop($request);

        $customer = $partner->customers()->findOrFail($customer);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $customer->update($data);

        return redirect()
            ->route('workshop.customers.index')
            ->with('success', 'მომხმარებლის მონაცემები განახლდა.');
    }

    public function destroy(
        Request $request,
        int $customer
    ): RedirectResponse {
        $partner = $this->getWorkshop($request);

        $customer = $partner->customers()->findOrFail($customer);
        $customer->delete();

        return redirect()
            ->route('workshop.customers.index')
            ->with('success', 'მომხმარებელი წაიშალა.');
    }
}