<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RentalBooking;
use App\Models\RentalScooter;
use App\Models\ServiceBooking;
use App\Models\ServiceCenter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MobileBookingController extends Controller
{
    public function rentalBooking(Request $request, RentalScooter $rentalScooter)
    {
        abort_unless($rentalScooter->status === 'active', 404);

        $data = $request->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^(?=.*\d)[0-9+\-\s()]+$/'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $start = Carbon::parse($data['start_date'])->startOfDay();
        $end = Carbon::parse($data['end_date'])->startOfDay();
        $days = (int) $start->diffInDays($end);
        if ($days < 1) {
            throw ValidationException::withMessages(['end_date' => ['დასრულების თარიღი უნდა იყოს დაწყების თარიღის შემდეგ.']]);
        }

        $booking = DB::transaction(function () use ($rentalScooter, $request, $data, $start, $end, $days) {
            RentalScooter::whereKey($rentalScooter->id)->lockForUpdate()->firstOrFail();
            $conflict = $rentalScooter->bookings()
                ->whereIn('status', ['pending', 'confirmed'])
                ->whereDate('start_date', '<', $end->toDateString())
                ->whereDate('end_date', '>', $start->toDateString())
                ->exists();

            if ($conflict) {
                throw ValidationException::withMessages([
                    'start_date' => ['ამ პერიოდში მოტოციკლი უკვე დაჯავშნილია ან მოთხოვნა ელოდება დადასტურებას.'],
                ]);
            }

            return RentalBooking::create([
                'rental_scooter_id' => $rentalScooter->id,
                'user_id' => $request->user()->id,
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
                'days' => $days,
                'daily_rate' => $rentalScooter->daily_rate,
                'total_price' => round($days * (float) $rentalScooter->daily_rate, 2),
                'customer_name' => $data['customer_name'],
                'phone' => $data['phone'],
                'notes' => $data['notes'] ?? null,
                'status' => 'pending',
            ]);
        });

        return response()->json([
            'message' => 'ჯავშნის მოთხოვნა გაიგზავნა და დადასტურებას ელოდება.',
            'booking' => [
                'id' => $booking->id, 'status' => $booking->status,
                'start_date' => $booking->start_date->toDateString(),
                'end_date' => $booking->end_date->toDateString(),
                'days' => $booking->days, 'total_price' => $booking->total_price,
            ],
        ], 201);
    }

    public function serviceBooking(Request $request, ServiceCenter $serviceCenter)
    {
        abort_unless($serviceCenter->is_active && $serviceCenter->approval_status === 'approved' && $serviceCenter->canUseFeature('bookings'), 404);

        $data = $request->validate([
            'service_id' => ['required', 'integer'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'customer_name' => ['required', 'string', 'max:160'],
            'customer_phone' => ['required', 'string', 'max:40', 'regex:/^(?=.*\d)[0-9+\-\s()]+$/'],
            'motorcycle' => ['nullable', 'string', 'max:180'],
            'notes' => ['nullable', 'string', 'max:1500'],
        ]);

        $service = $serviceCenter->services()->where('is_active', true)->findOrFail($data['service_id']);
        $start = Carbon::parse($data['scheduled_at']);
        $end = $start->copy()->addMinutes((int) $service->duration_minutes);

        $hours = $serviceCenter->hours()->where('day_of_week', $start->dayOfWeek)->first();
        if (!$hours || $hours->is_closed || !$hours->opens_at || !$hours->closes_at) {
            throw ValidationException::withMessages(['scheduled_at' => ['ამ დღეს სახელოსნო დაკეტილია. აირჩიე სხვა დღე.']]);
        }

        $date = $start->toDateString();
        $opensAt = Carbon::parse($date . ' ' . $hours->opens_at);
        $closesAt = Carbon::parse($date . ' ' . $hours->closes_at);
        if ($start->lt($opensAt) || $end->gt($closesAt)) {
            throw ValidationException::withMessages(['scheduled_at' => ['არჩეული დრო სამუშაო საათებს სცდება.']]);
        }

        $booking = DB::transaction(function () use ($serviceCenter, $service, $request, $data, $start, $end) {
            ServiceCenter::whereKey($serviceCenter->id)->lockForUpdate()->firstOrFail();
            $existing = ServiceBooking::query()
                ->where('service_center_id', $serviceCenter->id)
                ->whereDate('scheduled_at', $start->toDateString())
                ->whereIn('status', ServiceBooking::ACTIVE_STATUSES)
                ->get(['scheduled_at', 'duration_minutes']);

            foreach ($existing as $item) {
                $existingStart = Carbon::parse($item->scheduled_at);
                $existingEnd = $existingStart->copy()->addMinutes((int) $item->duration_minutes);
                if ($start->lt($existingEnd) && $end->gt($existingStart)) {
                    throw ValidationException::withMessages(['scheduled_at' => ['ეს დრო უკვე დაკავებულია. აირჩიე სხვა დრო.']]);
                }
            }

            return ServiceBooking::create([
                'service_center_id' => $serviceCenter->id,
                'service_center_service_id' => $service->id,
                'user_id' => $request->user()->id,
                'service_name' => $service->name,
                'price_at_booking' => $service->price,
                'duration_minutes' => $service->duration_minutes,
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'motorcycle' => $data['motorcycle'] ?? null,
                'scheduled_at' => $start,
                'notes' => $data['notes'] ?? null,
                'status' => 'pending',
            ]);
        });

        return response()->json([
            'message' => 'სერვისის ჯავშნის მოთხოვნა გაიგზავნა.',
            'booking' => [
                'id' => $booking->id, 'service_name' => $booking->service_name,
                'scheduled_at' => $booking->scheduled_at?->toIso8601String(),
                'status' => $booking->status, 'price' => $booking->price_at_booking,
            ],
        ], 201);
    }

    public function workshopBooking(Request $request, string $slug)
    {
        $workshop = \App\Models\Partner::query()->where('type','workshop')
            ->where('application_status','approved')->where('is_active',true)->where('slug',$slug)->firstOrFail();
        $data = $request->validate([
            'service_id'=>['required','integer'],'motorcycle_brand'=>['nullable','string','max:100'],
            'motorcycle_model'=>['nullable','string','max:100'],'motorcycle_year'=>['nullable','integer','between:1950,2100'],
            'problem_description'=>['required','string','max:3000'],'customer_name'=>['required','string','max:255'],
            'phone'=>['required','string','max:40'],'email'=>['nullable','email','max:255'],
            'preferred_date'=>['nullable','date','after_or_equal:today'],
        ]);
        $service = DB::table('services')->where('partner_id',$workshop->id)->where('is_active',true)->where('id',$data['service_id'])->first();
        abort_unless($service,404);
        $id = DB::table('service_bookings')->insertGetId([
            'partner_id'=>$workshop->id,'service_id'=>$service->id,'service_price'=>$service->price,
            'service_type'=>$service->title,'motorcycle_brand'=>$data['motorcycle_brand']??null,
            'motorcycle_model'=>$data['motorcycle_model']??null,'motorcycle_year'=>$data['motorcycle_year']??null,
            'problem_description'=>$data['problem_description'],'customer_name'=>$data['customer_name'],
            'phone'=>$data['phone'],'email'=>$data['email']??null,'preferred_date'=>$data['preferred_date']??null,
            'status'=>'new','created_at'=>now(),'updated_at'=>now(),
        ]);
        return response()->json(['message'=>'სახელოსნოში ჯავშნის მოთხოვნა გაიგზავნა.','booking_id'=>$id],201);
    }

}
