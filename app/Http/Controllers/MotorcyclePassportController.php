<?php

namespace App\Http\Controllers;

use App\Models\Motorcycle;
use Illuminate\View\View;

class MotorcyclePassportController extends Controller
{
    public function show(Motorcycle $motorcycle): View
    {
        abort_unless($motorcycle->status === 'published', 404);

        $motorcycle->load(['serviceRecords' => function ($query) {
            $query->orderByDesc('service_date');
        }]);

        return view('motorcycles.passport', [
            'motorcycle' => $motorcycle,
            'serviceRecords' => $motorcycle->serviceRecords,
        ]);
    }
}