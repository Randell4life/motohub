<?php

namespace App\Http\Controllers;

use App\Models\RentalBooking;
use App\Models\RentalScooter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RentalController extends Controller
{
    public function index(Request $request): View
    {
        $scooters = RentalScooter::query()
            ->where('status', 'active')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = trim((string) $request->query('q'));
                $query->where(fn ($where) => $where->where('title', 'like', "%{$term}%")
                    ->orWhere('brand', 'like', "%{$term}%")
                    ->orWhere('model', 'like', "%{$term}%"));
            })
            ->when($request->filled('location'), fn ($query) => $query->where('location', 'like', '%' . trim((string) $request->query('location')) . '%'))
            ->when($request->filled('max_rate') && is_numeric($request->query('max_rate')), fn ($query) => $query->where('daily_rate', '<=', (float) $request->query('max_rate')))
            ->when($request->filled('min_cc') && is_numeric($request->query('min_cc')), fn ($query) => $query->where('engine_cc', '>=', (int) $request->query('min_cc')))
            ->when($request->query('sort') === 'price_asc', fn ($query) => $query->orderBy('daily_rate'))
            ->when($request->query('sort') === 'price_desc', fn ($query) => $query->orderByDesc('daily_rate'))
            ->when($request->query('sort') === 'engine_desc', fn ($query) => $query->orderByDesc('engine_cc'))
            ->when(!in_array($request->query('sort'), ['price_asc', 'price_desc', 'engine_desc'], true), fn ($query) => $query->latest())
            ->paginate(9)->withQueryString();

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