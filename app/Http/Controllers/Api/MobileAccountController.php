<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MobileAccountController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:30', 'regex:/^[0-9+\-\s()]+$/'],
        ]);
        $user = $request->user();
        $user->fill($data);
        $user->save();

        return response()->json([
            'message' => 'პროფილი განახლდა.',
            'user' => [
                'id' => $user->id, 'name' => $user->name,
                'email' => $user->email, 'phone' => $user->phone,
            ],
        ]);
    }

    public function listings(Request $request)
    {
        $items = $request->user()->motorcycles()
            ->latest()->paginate(20)
            ->through(fn ($item) => [
                'id' => $item->id, 'title' => $item->title, 'slug' => $item->slug,
                'brand' => $item->brand, 'model' => $item->model, 'year' => $item->year,
                'price' => $item->price, 'status' => $item->status,
                'image_url' => $item->image_path ? url('/storage/' . ltrim($item->image_path, '/')) : null,
            ]);
        return response()->json($items);
    }

    public function bookings(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'rental_bookings' => $user->rentalBookings()->with('rentalScooter:id,title,slug,image_path')
                ->latest()->limit(50)->get()->map(fn ($booking) => [
                    'id' => $booking->id, 'type' => 'rental',
                    'title' => $booking->rentalScooter?->title,
                    'slug' => $booking->rentalScooter?->slug,
                    'start_date' => $booking->start_date?->toDateString(),
                    'end_date' => $booking->end_date?->toDateString(),
                    'days' => $booking->days, 'total_price' => $booking->total_price,
                    'status' => $booking->status,
                ]),
            'service_bookings' => $user->serviceBookings()->with('serviceCenter:id,name,slug')
                ->latest('scheduled_at')->limit(50)->get()->map(fn ($booking) => [
                    'id' => $booking->id, 'type' => 'service',
                    'title' => $booking->service_name,
                    'service_center' => $booking->serviceCenter?->name,
                    'scheduled_at' => $booking->scheduled_at?->toIso8601String(),
                    'status' => $booking->status,
                    'price' => $booking->price_at_booking,
                ]),
        ]);
    }
}
