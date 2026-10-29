<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Motorcycle;
use App\Models\PartRequest;
use App\Models\RentalBooking;
use App\Models\ServiceBooking;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MobileAdminController extends Controller
{
    private function authorizeAdmin(Request $request): void
    {
        $user = $request->user();

        abort_unless(
            $user && (bool) $user->is_admin && strtolower((string) $user->email) === 'braincell2003@gmail.com',
            403,
            'ადმინისტრატორის უფლებები არ გაქვს.'
        );
    }

    public function dashboard(Request $request)
    {
        $this->authorizeAdmin($request);

        return response()->json([
            'counts' => [
                'pending_motorcycles' => Motorcycle::whereIn('status', ['draft', 'pending'])->count(),
                'open_part_requests' => PartRequest::whereIn('status', ['new', 'in_progress'])->count(),
                'pending_rental_bookings' => RentalBooking::where('status', 'pending')->count(),
                'pending_service_bookings' => ServiceBooking::where('status', 'pending')->count(),
            ],
            'motorcycles' => Motorcycle::with('user:id,name,email')->whereIn('status', ['draft', 'pending'])->latest()->limit(30)->get()
                ->map(fn ($item) => ['id' => $item->id, 'title' => $item->title, 'brand' => $item->brand, 'model' => $item->model, 'year' => $item->year, 'price' => $item->price, 'status' => $item->status, 'owner' => $item->user?->name, 'email' => $item->user?->email]),
            'part_requests' => PartRequest::whereIn('status', ['new', 'in_progress'])->latest()->limit(30)->get()
                ->map(fn ($item) => ['id' => $item->id, 'part_name' => $item->part_name, 'motorcycle' => trim(($item->motorcycle_brand ?? '') . ' ' . ($item->motorcycle_model ?? '') . ' ' . ($item->motorcycle_year ?? '')), 'customer_name' => $item->customer_name, 'phone' => $item->phone, 'email' => $item->email, 'details' => $item->details, 'status' => $item->status]),
            'rental_bookings' => RentalBooking::with('rentalScooter:id,title')->whereIn('status', ['pending', 'confirmed'])->latest()->limit(30)->get()
                ->map(fn ($item) => ['id' => $item->id, 'title' => $item->rentalScooter?->title ?? 'მოპედი', 'customer_name' => $item->customer_name, 'phone' => $item->phone, 'start_date' => $item->start_date?->toDateString(), 'end_date' => $item->end_date?->toDateString(), 'total_price' => $item->total_price, 'status' => $item->status]),
            'service_bookings' => ServiceBooking::with('serviceCenter:id,name')->whereIn('status', ['pending', 'confirmed'])->latest('scheduled_at')->limit(30)->get()
                ->map(fn ($item) => ['id' => $item->id, 'title' => $item->service_name, 'center' => $item->serviceCenter?->name, 'customer_name' => $item->customer_name, 'phone' => $item->customer_phone, 'scheduled_at' => $item->scheduled_at?->toIso8601String(), 'status' => $item->status]),
        ]);
    }

    public function updateStatus(Request $request, string $type, int $id)
    {
        $this->authorizeAdmin($request);

        $rules = [
            'motorcycles' => ['published', 'rejected'],
            'part-requests' => ['in_progress', 'completed', 'rejected'],
            'rental-bookings' => ['confirmed', 'declined', 'cancelled', 'completed'],
            'service-bookings' => ['confirmed', 'declined', 'cancelled', 'completed'],
        ];

        abort_unless(isset($rules[$type]), 404, 'განყოფილება ვერ მოიძებნა.');

        $data = $request->validate([
            'status' => ['required', 'string', Rule::in($rules[$type])],
        ]);

        $model = match ($type) {
            'motorcycles' => Motorcycle::query()->findOrFail($id),
            'part-requests' => PartRequest::query()->findOrFail($id),
            'rental-bookings' => RentalBooking::query()->findOrFail($id),
            'service-bookings' => ServiceBooking::query()->findOrFail($id),
        };

        $changes = ['status' => $data['status']];

        if ($type === 'motorcycles') {
            $changes['published_at'] = $data['status'] === 'published' ? now() : null;
        }

        $model->forceFill($changes)->save();

        return response()->json([
            'message' => 'სტატუსი წარმატებით განახლდა.',
            'status' => $model->status,
        ]);
    }
}
