<?php

namespace App\Http\Controllers;

use App\Models\TransportRequest as MotorcycleTransportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Partner;

class TransportController extends Controller
{
    public function index(Request $request)
    {
        $requests = MotorcycleTransportRequest::query()
            ->whereIn('status', ['pending', 'quoted'])
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

        return view('transport.index', compact('requests'));
    }

    public function companies(Request $request)
{
    $companies = Partner::query()
        ->where('type', 'transport')
        ->where('application_status', 'approved')
        ->where('is_active', true)
        ->when($request->filled('q'), function ($query) use ($request) {
            $search = trim($request->input('q'));

            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        })
        ->orderBy('name')
        ->paginate(12)
        ->withQueryString();

    return view('transport.companies', compact('companies'));
}

public function company(string $slug)
{
    $company = Partner::query()
        ->where('type', 'transport')
        ->where('application_status', 'approved')
        ->where('is_active', true)
        ->where('slug', $slug)
        ->firstOrFail();

    return view('transport.company-show', compact('company'));
}
public function create(Request $request)
{
    $company = null;

    if ($request->filled('partner')) {
        $company = Partner::query()
            ->where('type', 'transport')
            ->where('application_status', 'approved')
            ->where('is_active', true)
            ->findOrFail($request->integer('partner'));
    }

    return view('transport.create', compact('company'));
}


public function store(Request $request)
{
    $validated = $request->validate([
        'partner_id' => ['nullable', 'integer'],
        'motorcycle_brand' => ['required', 'string', 'max:100'],
        'motorcycle_model' => ['required', 'string', 'max:100'],
        'motorcycle_year' => [
            'nullable',
            'integer',
            'min:1900',
            'max:' . (date('Y') + 1),
        ],
        'pickup_city' => ['required', 'string', 'max:150'],
        'pickup_address' => ['nullable', 'string', 'max:255'],
        'destination_city' => ['required', 'string', 'max:150'],
        'destination_address' => ['nullable', 'string', 'max:255'],
        'preferred_date' => ['nullable', 'date', 'after_or_equal:today'],
        'motorcycle_details' => ['nullable', 'string', 'max:3000'],
        'customer_name' => ['required', 'string', 'max:150'],
        'customer_phone' => ['required', 'string', 'max:40'],
        'customer_email' => ['nullable', 'email', 'max:255'],
        'customer_notes' => ['nullable', 'string', 'max:3000'],
    ]);

    $partnerId = $validated['partner_id'] ?? null;

    if ($partnerId !== null) {
        $company = Partner::query()
            ->where('type', 'transport')
            ->where('application_status', 'approved')
            ->where('is_active', true)
            ->findOrFail($partnerId);
    }

    unset($validated['partner_id']);

    $validated['request_number'] = 'MT-' . Str::upper(Str::random(10));
    $validated['user_id'] = Auth::id();
    $validated['partner_id'] = $partnerId;
    $validated['status'] = 'pending';

    MotorcycleTransportRequest::create($validated);

    return redirect()
        ->route('account.transport.index')
        ->with('success', 'ტრანსპორტირების მოთხოვნა წარმატებით გაიგზავნა.');
}

}
