<?php

namespace App\Http\Controllers;

use App\Models\ServiceBooking;
use App\Models\ServiceCenter;
use App\Models\ServiceCenterHour;
use App\Models\ServiceCenterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MyServiceCenterController extends Controller
{
    public function dashboard(): RedirectResponse|View
    {
        $center = ServiceCenter::where('owner_id', auth()->id())->first();

        if (!$center) {
            return redirect()->route('my-service-center.register');
        }

        $center->load([
            'services' => fn ($query) => $query->orderBy('name'),
            'hours' => fn ($query) => $query->orderBy('day_of_week'),
        ]);

        $center->setRelation('bookings', $center->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('scheduled_at', '>=', now()->subDay())
            ->orderBy('scheduled_at')
            ->limit(40)
            ->get());

        $bookingStats = [
            'pending' => $center->bookings()->where('status', 'pending')->count(),
            'confirmed' => $center->bookings()->where('status', 'confirmed')->count(),
            'completed' => $center->bookings()->where('status', 'completed')->count(),
        ];

        return view('my-service-center.dashboard', compact('center', 'bookingStats'));
    }

    
public function bookings(): RedirectResponse|View
{
    $center = $this->ownedCenter();

    $bookings = $center->bookings()
        ->orderByDesc('scheduled_at')
        ->paginate(15);

    return view('my-service-center.bookings', compact('center', 'bookings'));
}

public function services(): RedirectResponse|View
{
    $center = $this->ownedCenter();

    $services = $center->services()
        ->orderBy('name')
        ->get();

    return view('my-service-center.services', compact('center', 'services'));
}

public function hours(): RedirectResponse|View
{
    $center = $this->ownedCenter();

    $hours = $center->hours()
        ->orderBy('day_of_week')
        ->get();

    return view('my-service-center.hours', compact('center', 'hours'));
}

public function profile(): RedirectResponse|View
{
    $center = $this->ownedCenter();

    return view('my-service-center.profile', compact('center'));
}


    public function register(): RedirectResponse|View
    {
        if (ServiceCenter::where('owner_id', auth()->id())->exists()) {
            return redirect()->route('my-service-center.dashboard');
        }

        return view('my-service-center.register');
    }

    public function store(Request $request): RedirectResponse
    {
        if (ServiceCenter::where('owner_id', auth()->id())->exists()) {
            return redirect()->route('my-service-center.dashboard');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:3000'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'address' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($data) {
            $baseSlug = Str::slug($data['name']) ?: 'service-center';
            $slug = $baseSlug;
            while (ServiceCenter::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . Str::lower(Str::random(5));
            }

            $center = ServiceCenter::create([
                ...$data,
                'owner_id' => auth()->id(),
                'slug' => $slug,
                'is_active' => false,
                'is_verified' => false,
                'approval_status' => 'pending',
            ]);

            // 09:00–18:00 Monday to Saturday; Sunday closed. Owner can edit these later.
            foreach (range(0, 6) as $day) {
                ServiceCenterHour::create([
                    'service_center_id' => $center->id,
                    'day_of_week' => $day,
                    'opens_at' => $day === 0 ? null : '09:00',
                    'closes_at' => $day === 0 ? null : '18:00',
                    'is_closed' => $day === 0,
                ]);
            }
        });

        return redirect()->route('my-service-center.dashboard')
            ->with('success', 'სახელოსნოს განაცხადი გაიგზავნა ადმინისტრატორთან. დამტკიცებამდე ის საჯარო კატალოგში არ გამოჩნდება. შეგიძლია მანამდე დაამატო მომსახურებები და სამუშაო საათები.');
    }

    public function storeService(Request $request): RedirectResponse
    {
        $center = $this->ownedCenter();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:1500'],
            'price_type' => ['required', Rule::in(['fixed', 'from', 'quote'])],
            'price' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:1440'],
        ]);

        if ($data['price_type'] !== 'quote' && (!isset($data['price']) || $data['price'] === '')) {
            return back()->withErrors(['price' => 'ფიქსირებული ან საწყისი ფასისთვის მიუთითე თანხა.'])->withInput();
        }

        $center->services()->create([
            ...$data,
            'price' => $data['price_type'] === 'quote' ? null : $data['price'],
            'is_active' => true,
        ]);

        return back()->with('success', 'მომსახურება დაემატა.');
    }

    public function updateService(Request $request, ServiceCenterService $service): RedirectResponse
    {
        $this->ensureServiceOwned($service);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:1500'],
            'price_type' => ['required', Rule::in(['fixed', 'from', 'quote'])],
            'price' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:1440'],
            'is_active' => ['required', 'boolean'],
        ]);

        if ($data['price_type'] !== 'quote' && (!isset($data['price']) || $data['price'] === '')) {
            return back()->withErrors(['price' => 'ფასის ტიპის მიხედვით მიუთითე თანხა.']);
        }

        $service->update([
            ...$data,
            'price' => $data['price_type'] === 'quote' ? null : $data['price'],
        ]);

        return back()->with('success', 'მომსახურება განახლდა.');
    }

    public function deleteService(ServiceCenterService $service): RedirectResponse
    {
        $this->ensureServiceOwned($service);
        $service->update(['is_active' => false]);

        return back()->with('success', 'მომსახურება დამალულია და ახალი ჯავშნებისთვის აღარ გამოჩნდება.');
    }

    public function updateHours(Request $request): RedirectResponse
    {
        $center = $this->ownedCenter();

        $data = $request->validate([
            'hours' => ['required', 'array'],
            'hours.*.is_closed' => ['required', 'boolean'],
            'hours.*.opens_at' => ['nullable', 'date_format:H:i'],
            'hours.*.closes_at' => ['nullable', 'date_format:H:i'],
        ]);

        foreach (range(0, 6) as $day) {
            $row = $data['hours'][$day] ?? null;
            if (!$row) {
                continue;
            }

            $closed = (bool) $row['is_closed'];
            if (!$closed) {
                if (empty($row['opens_at']) || empty($row['closes_at']) || $row['opens_at'] >= $row['closes_at']) {
                    return back()->withErrors([
                        "hours.$day.opens_at" => 'გახსნისა და დახურვის სწორი დრო მიუთითე.',
                    ]);
                }
            }

            ServiceCenterHour::updateOrCreate(
                ['service_center_id' => $center->id, 'day_of_week' => $day],
                [
                    'opens_at' => $closed ? null : $row['opens_at'],
                    'closes_at' => $closed ? null : $row['closes_at'],
                    'is_closed' => $closed,
                ]
            );
        }

        return back()->with('success', 'სამუშაო საათები შენახულია.');
    }

    public function updateBookingStatus(Request $request, ServiceBooking $booking): RedirectResponse
    {
        $center = $this->ownedCenter();
        abort_unless($booking->service_center_id === $center->id, 403);

        $data = $request->validate([
            'status' => ['required', Rule::in(['confirmed', 'declined', 'cancelled', 'completed'])],
        ]);

        $booking->update(['status' => $data['status']]);

        return back()->with('success', 'ჯავშნის სტატუსი განახლდა.');
    }

    private function ownedCenter(): ServiceCenter
    {
        return ServiceCenter::where('owner_id', auth()->id())->firstOrFail();
    }

    private function ensureServiceOwned(ServiceCenterService $service): void
    {
        abort_unless($service->serviceCenter()->where('owner_id', auth()->id())->exists(), 403);
    }
}
