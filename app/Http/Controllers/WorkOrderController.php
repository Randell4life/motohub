<?php

namespace App\Http\Controllers;

use App\Models\ServiceBooking;
use App\Models\ServiceCenter;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkOrderController extends Controller
{
public function index()
{
    $center = ServiceCenter::where(
        'owner_id',
        auth()->id()
    )->firstOrFail();

    $workOrders = WorkOrder::where(
        'service_center_id',
        $center->id
    )
        ->with(['serviceBooking', 'assignedTechnician'])
        ->latest()
        ->paginate(20);

    $bookings = ServiceBooking::where(
        'service_center_id',
        $center->id
    )
        ->whereIn('status', ['pending', 'confirmed'])
        ->whereDoesntHave('workOrder')
        ->orderBy('scheduled_at')
        ->get();

    return view(
        'my-service-center.work-orders.index',
        compact('center', 'workOrders', 'bookings')
    );
}

    public function store(Request $request)
    {
        $center = ServiceCenter::where(
            'owner_id',
            auth()->id()
        )->firstOrFail();

        $validated = $request->validate([
            'service_booking_id' => [
                'nullable',
                'integer',
                'exists:service_center_bookings,id',
            ],
            'intake_odometer' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'intake_condition' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'diagnosis' => [
                'nullable',
                'string',
                'max:10000',
            ],
            'work_description' => [
                'nullable',
                'string',
                'max:10000',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:10000',
            ],
            'labor_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'parts_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ]);

        $bookingId = $validated['service_booking_id'] ?? null;

        if ($bookingId !== null) {
            $booking = ServiceBooking::where(
                'service_center_id',
                $center->id
            )->findOrFail($bookingId);

            if (WorkOrder::where(
                'service_booking_id',
                $booking->id
            )->exists()) {
                return back()
                    ->withErrors([
                        'service_booking_id' =>
                            'ამ ჯავშანზე სამუშაო შეკვეთა უკვე არსებობს.',
                    ])
                    ->withInput();
            }
        }

        $laborCost = (float) ($validated['labor_cost'] ?? 0);
        $partsCost = (float) ($validated['parts_cost'] ?? 0);

        $workOrder = DB::transaction(function () use (
            $center,
            $validated,
            $bookingId,
            $laborCost,
            $partsCost
        ) {
            return WorkOrder::create([
                'order_number' => 'WO-'
                    . now()->format('Ymd')
                    . '-'
                    . strtoupper(bin2hex(random_bytes(4))),
                'service_booking_id' => $bookingId,
                'service_center_id' => $center->id,
                'status' => 'received',
                'intake_odometer' =>
                    $validated['intake_odometer'] ?? null,
                'intake_condition' =>
                    $validated['intake_condition'] ?? null,
                'diagnosis' => $validated['diagnosis'] ?? null,
                'work_description' =>
                    $validated['work_description'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'labor_cost' => $laborCost,
                'parts_cost' => $partsCost,
                'total_cost' => $laborCost + $partsCost,
            ]);
        });

        return redirect()
            ->route('my-service-center.work-orders.index')
            ->with('success', 'სამუშაო შეკვეთა შეიქმნა: '
                . $workOrder->order_number);
    }


public function updateStatus(Request $request, WorkOrder $workOrder)
{
    $center = ServiceCenter::where(
        'owner_id',
        auth()->id()
    )->firstOrFail();

    // შეკვეთა აუცილებლად ამ სახელოსნოს უნდა ეკუთვნოდეს.
    abort_unless(
        $workOrder->service_center_id === $center->id,
        404
    );

    $validated = $request->validate([
        'status' => [
            'required',
            'string',
            'in:' . implode(',', WorkOrder::STATUSES),
        ],
    ]);

    $status = $validated['status'];

    $workOrder->status = $status;

    if ($status === 'completed' && !$workOrder->completed_at) {
        $workOrder->completed_at = now();
    }

    if ($status === 'handed_over' && !$workOrder->handed_over_at) {
        $workOrder->handed_over_at = now();

        if (!$workOrder->completed_at) {
            $workOrder->completed_at = now();
        }
    }

    $workOrder->save();

    return redirect()
        ->route('my-service-center.work-orders.index')
        ->with('success', 'შეკვეთის სტატუსი წარმატებით შეიცვალა.');
}

public function show(WorkOrder $workOrder)
{
    $center = ServiceCenter::where(
        'owner_id',
        auth()->id()
    )->firstOrFail();

    // სხვა სახელოსნოს შეკვეთის ნახვა დაუშვებელია.
    abort_unless(
        $workOrder->service_center_id === $center->id,
        404
    );

    $workOrder->load([
        'serviceBooking.service',
        'assignedTechnician',
    ]);

    return view(
        'my-service-center.work-orders.show',
        compact('center', 'workOrder')
    );
}


public function edit(WorkOrder $workOrder)
{
    $center = ServiceCenter::where(
        'owner_id',
        auth()->id()
    )->firstOrFail();

    abort_unless(
        $workOrder->service_center_id === $center->id,
        404
    );

    $workOrder->load([
        'serviceBooking',
        'assignedTechnician',
    ]);

    return view(
        'my-service-center.work-orders.edit',
        compact('center', 'workOrder')
    );
}

public function update(Request $request, WorkOrder $workOrder)
{
    $center = ServiceCenter::where(
        'owner_id',
        auth()->id()
    )->firstOrFail();

    abort_unless(
        $workOrder->service_center_id === $center->id,
        404
    );

    $validated = $request->validate([
        'intake_odometer' => [
            'nullable',
            'integer',
            'min:0',
        ],
        'intake_condition' => [
            'nullable',
            'string',
            'max:5000',
        ],
        'diagnosis' => [
            'nullable',
            'string',
            'max:10000',
        ],
        'work_description' => [
            'nullable',
            'string',
            'max:10000',
        ],
        'notes' => [
            'nullable',
            'string',
            'max:10000',
        ],
                'intake_photos' => [
            'sometimes',
            'array',
            'max:10',
        ],
        'intake_photos.*' => [
            'image',
            'mimes:jpeg,png,webp',
            'max:5120',
        ],
        'labor_cost' => [
            'required',
            'numeric',
            'min:0',
            'max:99999999',
        ],
        'parts_cost' => [
            'required',
            'numeric',
            'min:0',
            'max:99999999',
        ],
    ]);

    $laborCost = (float) $validated['labor_cost'];
    $partsCost = (float) $validated['parts_cost'];

    $workOrder->fill([
        'intake_odometer' => $validated['intake_odometer'] ?? null,
        'intake_condition' => $validated['intake_condition'] ?? null,
        'diagnosis' => $validated['diagnosis'] ?? null,
        'work_description' => $validated['work_description'] ?? null,
        'notes' => $validated['notes'] ?? null,
        'labor_cost' => $laborCost,
        'parts_cost' => $partsCost,
        'total_cost' => round($laborCost + $partsCost, 2),
    ]);

   $existingPhotos = $workOrder->intake_photos ?? [];

    $newPhotos = [];

    if ($request->hasFile('intake_photos')) {
        foreach ($request->file('intake_photos') as $photo) {
            $newPhotos[] = $photo->store(
                'work-orders/' . $workOrder->id,
                'public'
            );
        }
    }

    $workOrder->intake_photos = array_values(
        array_merge($existingPhotos, $newPhotos)
    );

    $workOrder->save();

    return redirect()
        ->route('my-service-center.work-orders.show', $workOrder)
        ->with('success', 'სამუშაო შეკვეთა წარმატებით განახლდა.');
}


}