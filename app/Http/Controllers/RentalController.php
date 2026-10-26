<?php

namespace App\Http\Controllers;

use App\Models\RentalBooking;
use App\Models\RentalScooter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RentalController extends Controller
{
    public function index(): View
    {
        $scooters = RentalScooter::query()
            ->where('status', 'active')
            ->latest()
            ->paginate(9);

        return view('rentals.index', compact('scooters'));
    }

    public function show(RentalScooter $rentalScooter): View
    {
        abort_unless($rentalScooter->status === 'active', 404);

        return view('rentals.show', compact('rentalScooter'));
    }

    public function store(Request $request, RentalScooter $rentalScooter)
    {
        abort_unless($rentalScooter->status === 'active', 404);

        $validated = $request->validate([
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'end_date' => [
                'required',
                'date',
                'after:start_date',
            ],
            'customer_name' => [
                'required',
                'string',
                'max:255',
            ],
            'phone' => [
                'required',
                'string',
                'max:30',
                'regex:/^(?=.*\d)[0-9+\-\s()]+$/',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        /*
         * დაბრუნების თარიღი გაქირავების დასრულების მომენტია.
         * მაგალითად, 21-დან 22 ოქტომბრამდე = 1 დღე.
         *
         * მოლოდინში მყოფი და დადასტურებული ჯავშნები
         * ხელმისაწვდომობის შემოწმებაში მონაწილეობს.
         */
        $hasConflict = $rentalScooter->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('start_date', '<', $validated['end_date'])
            ->whereDate('end_date', '>', $validated['start_date'])
            ->exists();

        if ($hasConflict) {
            return back()
                ->withInput()
                ->withErrors([
                    'start_date' => 'ამ პერიოდში მოპედი უკვე დაჯავშნილია ან სხვა მოთხოვნა ელოდება დადასტურებას.',
                ]);
        }

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        $days = (int) $startDate->diffInDays($endDate);
        $dailyRate = (float) $rentalScooter->daily_rate;

        RentalBooking::create([
            'rental_scooter_id' => $rentalScooter->id,
            'user_id' => $request->user()->id,

            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],

            'days' => $days,
            'daily_rate' => $dailyRate,
            'total_price' => round($days * $dailyRate, 2),

            'customer_name' => $validated['customer_name'],
            'phone' => $validated['phone'],
            'notes' => $validated['notes'] ?? null,

            'status' => 'pending',
            'admin_note' => null,
        ]);

        return redirect()
            ->route('rentals.show', $rentalScooter->slug)
            ->with(
                'success',
                'ჯავშნის მოთხოვნა გაიგზავნა. კომპანია მოთხოვნას გადაამოწმებს და დაგიკავშირდება.'
            );
    }
}