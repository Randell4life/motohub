<?php

namespace App\Http\Controllers;

use App\Models\Motorcycle;
use App\Models\MotorcycleServiceRecord;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WorkshopServiceRecordController extends Controller
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

    private function getMotorcycle(
        Request $request,
        int $customer,
        int $motorcycle
    ): Motorcycle {
        $partner = $this->getWorkshop($request);

        $customerRecord = $partner->customers()
            ->findOrFail($customer);

        return Motorcycle::query()
            ->where('partner_id', $partner->id)
            ->where('customer_id', $customerRecord->id)
            ->whereKey($motorcycle)
            ->firstOrFail();
    }

    
    public function edit(
        Request $request,
        int $customer,
        int $motorcycle,
        int $record
    ): View {
        $motorcycleRecord = $this->getMotorcycle(
            $request,
            $customer,
            $motorcycle
        );

        $serviceRecord = $motorcycleRecord
            ->serviceRecords()
            ->findOrFail($record);

        return view('workshops.service.edit', [
            'motorcycle' => $motorcycleRecord,
            'customerId' => $customer,
            'serviceRecord' => $serviceRecord,
        ]);
    }

    public function update(
        Request $request,
        int $customer,
        int $motorcycle,
        int $record
    ): RedirectResponse {
        $motorcycleRecord = $this->getMotorcycle(
            $request,
            $customer,
            $motorcycle
        );

        $serviceRecord = $motorcycleRecord
            ->serviceRecords()
            ->findOrFail($record);

        $validated = $request->validate([
            'service_date' => ['required', 'date', 'before_or_equal:today'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'category' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'cost' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $serviceRecord->update($validated);

        return redirect()
            ->route('workshop.customers.motorcycles.service.index', [
                'customer' => $customer,
                'motorcycle' => $motorcycleRecord->id,
            ])
            ->with('success', 'მომსახურების ჩანაწერი განახლდა.');
    }

    public function destroy(
        Request $request,
        int $customer,
        int $motorcycle,
        int $record
    ): RedirectResponse {
        $motorcycleRecord = $this->getMotorcycle(
            $request,
            $customer,
            $motorcycle
        );

        $serviceRecord = $motorcycleRecord
            ->serviceRecords()
            ->findOrFail($record);

        $serviceRecord->delete();

        return redirect()
            ->route('workshop.customers.motorcycles.service.index', [
                'customer' => $customer,
                'motorcycle' => $motorcycleRecord->id,
            ])
            ->with('success', 'მომსახურების ჩანაწერი წაიშალა.');
    }


public function index(
    Request $request,
    int $customer,
    int $motorcycle
): View {
    $motorcycleRecord = $this->getMotorcycle(
        $request,
        $customer,
        $motorcycle
    );

    $customerRecord = $motorcycleRecord->customer;

    $serviceRecords = $motorcycleRecord
        ->serviceRecords()
        ->latest('service_date')
        ->paginate(10);

    $totalCost = $motorcycleRecord
        ->serviceRecords()
        ->sum('cost');

    $totalServices = $motorcycleRecord
        ->serviceRecords()
        ->count();

    $latestService = $motorcycleRecord
        ->serviceRecords()
        ->max('service_date');

    return view('workshops.service.index', [
        'motorcycle' => $motorcycleRecord,
        'customerId' => $customer,
        'customer' => $customerRecord,
        'serviceRecords' => $serviceRecords,
        'totalCost' => $totalCost,
        'totalServices' => $totalServices,
        'latestService' => $latestService,
    ]);
}

    public function store(
        Request $request,
        int $customer,
        int $motorcycle
    ): RedirectResponse {
        $motorcycleRecord = $this->getMotorcycle(
            $request,
            $customer,
            $motorcycle
        );

        $validated = $request->validate([
            'service_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'category' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'cost' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);
        $motorcycleRecord->serviceRecords()->create($validated);

        return redirect()
            ->route('workshop.customers.motorcycles.service.index', [
                'customer' => $customer,
                'motorcycle' => $motorcycleRecord->id,
            ])
            ->with('success', 'მომსახურების ჩანაწერი წარმატებით დაემატა.');
    }
}
