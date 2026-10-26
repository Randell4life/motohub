<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\TransportOffer;
use App\Models\TransportRequest as MotorcycleTransportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransportPartnerController extends Controller
{
    private function partner(): Partner
    {
        $partner = Partner::query()
            ->where('user_id', Auth::id())
            ->where('type', 'transport')
            ->where('application_status', 'approved')
            ->where('is_active', true)
            ->first();

        abort_unless($partner, 403, 'გადამზიდავის ანგარიში დამტკიცებული და აქტიური უნდა იყოს.');

        return $partner;
    }



public function index(Request $request)
{
    $partner = $this->partner();

    // მოთხოვნები, რომლებიც ამ კომპანიას შეუძლია დაამუშაოს
    $availableRequests = MotorcycleTransportRequest::query()
        ->whereIn('status', ['pending', 'quoted'])
        ->where(function ($query) use ($partner) {
            $query->whereNull('partner_id')
                ->orWhere('partner_id', $partner->id);
        });

    // ახალი მოთხოვნები, რომლებზეც კომპანიას ჯერ შეთავაზება არ გაუგზავნია
    $newRequestsCount = (clone $availableRequests)
        ->whereDoesntHave('offers', function ($query) use ($partner) {
            $query->where('partner_id', $partner->id);
        })
        ->count();

    // ამ კომპანიის ყველა შეთავაზება
    $offersCount = TransportOffer::query()
        ->where('partner_id', $partner->id)
        ->count();

    // მიმდინარე გადაზიდვები — მხოლოდ ამ კომპანიის მიღებული შეთავაზებებით
    $inTransitCount = MotorcycleTransportRequest::query()
        ->where('status', 'in_transit')
        ->whereHas('offers', function ($query) use ($partner) {
            $query->where('partner_id', $partner->id)
                ->where('status', 'accepted');
        })
        ->count();

    // დასრულებული გადაზიდვები — მხოლოდ ამ კომპანიისთვის მიღებული შეთავაზებებით
    $deliveredCount = MotorcycleTransportRequest::query()
        ->where('status', 'delivered')
        ->whereHas('offers', function ($query) use ($partner) {
            $query->where('partner_id', $partner->id)
                ->where('status', 'accepted');
        })
        ->count();

    // მოთხოვნების სია
    $requests = (clone $availableRequests)
        ->whereDoesntHave('offers', function ($query) use ($partner) {
            $query->where('partner_id', $partner->id);
        })
        ->when($request->filled('pickup_city'), function ($query) use ($request) {
            $query->where(
                'pickup_city',
                'like',
                '%' . trim($request->input('pickup_city')) . '%'
            );
        })
        ->when($request->filled('destination_city'), function ($query) use ($request) {
            $query->where(
                'destination_city',
                'like',
                '%' . trim($request->input('destination_city')) . '%'
            );
        })
        ->orderBy('preferred_date')
        ->orderByDesc('created_at')
        ->paginate(12)
        ->withQueryString();

    return view('transport.partner.index', compact(
        'partner',
        'requests',
        'newRequestsCount',
        'offersCount',
        'inTransitCount',
        'deliveredCount'
    ));
}


    public function offers()
    {
        $partner = $this->partner();

        $offers = TransportOffer::query()
            ->with('transportRequest')
            ->where('partner_id', $partner->id)
            ->latest()
            ->paginate(15);

        return view('transport.partner.offers', compact('partner', 'offers'));
    }

    
public function orders(Request $request)
{
    $partner = $this->partner();

    $status = $request->input('status');

    $allowedStatuses = [
        'accepted',
        'in_transit',
        'delivered',
    ];

    $orders = MotorcycleTransportRequest::query()
        ->with([
            'offers' => function ($query) use ($partner) {
                $query->where('partner_id', $partner->id)
                    ->where('status', 'accepted');
            },
        ])
        ->whereIn('status', $allowedStatuses)
        ->whereHas('offers', function ($query) use ($partner) {
            $query->where('partner_id', $partner->id)
                ->where('status', 'accepted');
        })
        ->when(
            in_array($status, $allowedStatuses, true),
            fn ($query) => $query->where('status', $status)
        )
        ->latest()
        ->paginate(12)
        ->withQueryString();

    return view('transport.partner.orders', compact(
        'partner',
        'orders',
        'status'
    ));
}


public function showOrder(MotorcycleTransportRequest $transportRequest)
{
    $partner = $this->partner();

    $order = MotorcycleTransportRequest::query()
        ->whereKey($transportRequest->id)
        ->whereIn('status', ['accepted', 'in_transit', 'delivered'])
        ->whereHas('offers', function ($query) use ($partner) {
            $query->where('partner_id', $partner->id)
                ->where('status', 'accepted');
        })
        ->with([
            'offers' => function ($query) use ($partner) {
                $query->where('partner_id', $partner->id)
                    ->where('status', 'accepted');
            },
        ])
        ->firstOrFail();

    $offer = $order->offers->firstOrFail();

    return view('transport.partner.order-show', compact(
        'partner',
        'order',
        'offer'
    ));
}



    public function storeOffer(Request $request, MotorcycleTransportRequest $transportRequest)
    {
        $partner = $this->partner();

        $validated = $request->validate([
            'price' => ['required', 'numeric', 'min:0.01', 'max:99999999'],
            'estimated_days' => ['required', 'integer', 'min:1', 'max:365'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($partner, $transportRequest, $validated) {
            $lockedRequest = MotorcycleTransportRequest::query()
                ->whereKey($transportRequest->id)
                ->lockForUpdate()
                ->firstOrFail();

            
if (
    $lockedRequest->partner_id !== null
    && (int) $lockedRequest->partner_id !== (int) $partner->id
) {
    throw ValidationException::withMessages([
        'transport' => 'ეს მოთხოვნა სხვა სატრანსპორტო კომპანიისთვის არის განკუთვნილი.',
    ]);
}
    

            if (! in_array($lockedRequest->status, ['pending', 'quoted'], true)) {
                throw ValidationException::withMessages([
                    'transport' => 'ამ მოთხოვნაზე შეთავაზებების გაგზავნა აღარ შეიძლება.',
                ]);
            }

            $alreadyOffered = TransportOffer::query()
                ->where('transport_request_id', $lockedRequest->id)
                ->where('partner_id', $partner->id)
                ->exists();

            if ($alreadyOffered) {
                throw ValidationException::withMessages([
                    'transport' => 'ამ მოთხოვნაზე უკვე გაქვს გაგზავნილი შეთავაზება.',
                ]);
            }

            TransportOffer::create([
                'transport_request_id' => $lockedRequest->id,
                'partner_id' => $partner->id,
                'user_id' => Auth::id(),
                'price' => $validated['price'],
                'estimated_days' => $validated['estimated_days'],
                'message' => $validated['message'] ?? null,
                'status' => 'pending',
            ]);

            $lockedRequest->update(['status' => 'quoted']);
        });

        return redirect()
            ->route('transport.partner.offers')
            ->with('success', 'შეთავაზება წარმატებით გაიგზავნა.');
    }
   public function updateStatus(Request $request, MotorcycleTransportRequest $transportRequest)
{
    $partner = $this->partner();

    $validated = $request->validate([
        'status' => [
            'required',
            'in:in_transit,delivered',
        ],
    ]);

    DB::transaction(function () use ($partner, $transportRequest, $validated) {
        $lockedRequest = MotorcycleTransportRequest::query()
            ->whereKey($transportRequest->id)
            ->lockForUpdate()
            ->firstOrFail();

        $acceptedOfferExists = TransportOffer::query()
            ->where('transport_request_id', $lockedRequest->id)
            ->where('partner_id', $partner->id)
            ->where('status', 'accepted')
            ->exists();

        abort_unless($acceptedOfferExists, 403, 'ამ შეკვეთაზე სტატუსის შეცვლის უფლება არ გაქვს.');

        if (
            $validated['status'] === 'in_transit'
            && $lockedRequest->status !== 'accepted'
        ) {
            throw ValidationException::withMessages([
                'status' => 'შეკვეთა ჯერ მიღებული უნდა იყოს.',
            ]);
        }

        if (
            $validated['status'] === 'delivered'
            && $lockedRequest->status !== 'in_transit'
        ) {
            throw ValidationException::withMessages([
                'status' => 'შეკვეთა ჯერ ტრანსპორტირების პროცესში უნდა იყოს.',
            ]);
        }

        $lockedRequest->update([
            'status' => $validated['status'],
        ]);
    });

    return back()->with('success', 'შეკვეთის სტატუსი წარმატებით განახლდა.');
} 
}
