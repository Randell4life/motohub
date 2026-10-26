<?php

namespace App\Http\Controllers;

use App\Models\TransportOffer;
use App\Models\TransportRequest as MotorcycleTransportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransportAccountController extends Controller
{
    public function index()
    {
        $requests = MotorcycleTransportRequest::query()
            ->where('user_id', Auth::id())
            ->with([
                'offers.partner',
                'offers.user',
            ])
            ->latest()
            ->paginate(10);

        return view('transport.account', compact('requests'));
    }

    

    public function show(MotorcycleTransportRequest $transportRequest)
{
    abort_unless(
        $transportRequest->user_id === Auth::id(),
        403
    );

    $transportRequest->load([
        'offers.partner',
        'offers.user',
    ]);

    $acceptedOffer = $transportRequest->offers
        ->firstWhere('status', 'accepted');

    return view('transport.account-show', compact(
        'transportRequest',
        'acceptedOffer'
    ));
}

    public function acceptOffer(
        Request $request,
        TransportOffer $offer
    ) {
        DB::transaction(function () use ($offer) {
            $transportRequest = MotorcycleTransportRequest::query()
                ->whereKey($offer->transport_request_id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $transportRequest->user_id === Auth::id(),
                403
            );

            if (! in_array(
                $transportRequest->status,
                ['pending', 'quoted'],
                true
            )) {
                throw ValidationException::withMessages([
                    'offer' => 'ამ მოთხოვნაზე შეთავაზების მიღება აღარ შეიძლება.',
                ]);
            }

            $selectedOffer = TransportOffer::query()
                ->whereKey($offer->id)
                ->where('transport_request_id', $transportRequest->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($selectedOffer->status !== 'pending') {
                throw ValidationException::withMessages([
                    'offer' => 'ეს შეთავაზება უკვე აღარ არის ხელმისაწვდომი.',
                ]);
            }

            TransportOffer::query()
                ->where('transport_request_id', $transportRequest->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'rejected',
                ]);

            $selectedOffer->update([
                'status' => 'accepted',
                'accepted_at' => now(),
            ]);

            $transportRequest->update([
                'status' => 'accepted',
            ]);
        });

        return redirect()
            ->route('account.transport.index')
            ->with('success', 'შეთავაზება წარმატებით მიიღე.');
    }
}
