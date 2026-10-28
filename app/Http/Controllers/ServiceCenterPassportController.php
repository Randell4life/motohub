<?php

namespace App\Http\Controllers;

use App\Models\Motorcycle;
use App\Models\MotorcycleServiceRecord;
use App\Models\ServiceCenter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceCenterPassportController extends Controller
{
    private function ownedCenter(): ServiceCenter
    {
        return ServiceCenter::query()
            ->where('owner_id', auth()->id())
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->firstOrFail();
    }

    public function create(): View
    {
        $center = $this->ownedCenter();

        return view('my-service-center.passport-record-create', compact('center'));
    }

    public function store(Request $request): RedirectResponse
    {
        $center = $this->ownedCenter();

        $data = $request->validate([
            'motorcycle_id' => ['required', 'integer', 'exists:motorcycles,id'],
            'service_date' => ['required', 'date', 'before_or_equal:today'],
            'mileage' => ['required', 'integer', 'min:0'],
            'category' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'cost' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'verification_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $motorcycle = Motorcycle::query()->findOrFail($data['motorcycle_id']);

        $motorcycle->serviceRecords()->create([
            'service_center_id' => $center->id,
            'service_date' => $data['service_date'],
            'mileage' => $data['mileage'],
            'category' => $data['category'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'cost' => $data['cost'],
            'verification_status' => 'verified',
            'verified_at' => now(),
            'verified_by_user_id' => auth()->id(),
            'verification_note' => $data['verification_note'] ?? null,
            'notes' => null,
        ]);

        return redirect()
            ->route('motorcycles.passport', $motorcycle->slug)
            ->with('success', 'სერვისის ჩანაწერი დაემატა და დადასტურდა თქვენი ავტორიზებული სახელოსნოს სახელით.');
    }
}
