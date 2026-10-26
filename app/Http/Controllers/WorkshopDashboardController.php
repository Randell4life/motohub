<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Customer;
use App\Models\Motorcycle;
use App\Models\MotorcycleServiceRecord;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkshopDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $partner = Partner::where('user_id', $request->user()->id)
            ->where('type', 'workshop')
            ->where('application_status', 'approved')
            ->where('is_active', true)
            ->latest('id')
            ->first();

        if (! $partner) {
            abort(403, 'სახელოსნო ჯერ არ არის გააქტიურებული.');
        }

        $customersCount = Customer::where('partner_id', $partner->id)
            ->count();

        $motorcyclesCount = Motorcycle::where('partner_id', $partner->id)
            ->count();

        $servicesCount = MotorcycleServiceRecord::whereHas(
            'motorcycle',
            fn ($query) => $query->where('partner_id', $partner->id)
        )->count();

        $recentServices = MotorcycleServiceRecord::with([
            'motorcycle.customer',
        ])
            ->whereHas(
                'motorcycle',
                fn ($query) => $query->where('partner_id', $partner->id)
            )
            ->orderByDesc('service_date')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $monthlyRevenue = MotorcycleServiceRecord::whereHas(
            'motorcycle',
            fn ($query) => $query->where('partner_id', $partner->id)
        )
            ->whereYear('service_date', now()->year)
            ->whereMonth('service_date', now()->month)
            ->sum('cost');

        return view('workshops.dashboard', compact(
            'partner',
            'customersCount',
            'motorcyclesCount',
            'servicesCount',
            'recentServices',
            'monthlyRevenue'
        ));
    }
}
