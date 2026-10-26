<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MotorcycleServiceRecordController extends Controller
{
    public function index(Request $request, int $motorcycle): View
    {
        $motorcycle = $request->user()
            ->motorcycles()
            ->findOrFail($motorcycle);

        $serviceRecords = $motorcycle->serviceRecords()->paginate(10);

        return view('account.motorcycles.service', compact(
            'motorcycle',
            'serviceRecords'
        ));
    }

    public function store(Request $request, int $motorcycle): RedirectResponse
    {
        $motorcycle = $request->user()
            ->motorcycles()
            ->findOrFail($motorcycle);

        $validated = $request->validate([
            'service_date' => ['required', 'date', 'before_or_equal:today'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'category' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'cost' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $motorcycle->serviceRecords()->create($validated);

        return redirect()
            ->route('account.motorcycles.service.index', $motorcycle->id)
            ->with('success', 'სერვისის ჩანაწერი წარმატებით დაემატა.');
    }
}