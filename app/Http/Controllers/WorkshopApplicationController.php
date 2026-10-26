<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WorkshopApplicationController extends Controller
{
    public function create(): View
    {
        $partner = Partner::where('user_id', auth()->id())
            ->where('type', 'workshop')
            ->latest('id')
            ->first();

        return view('workshops.apply', compact('partner'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:3000'],
        ]);

        $partner = Partner::where('user_id', $request->user()->id)
            ->where('type', 'workshop')
            ->latest('id')
            ->first();

        if ($partner && $partner->application_status === 'approved') {
            return redirect()
                ->route('workshop.dashboard')
                ->with('success', 'თქვენი სახელოსნო უკვე დამტკიცებულია.');
        }

        $baseSlug = Str::slug($validated['name']) ?: 'workshop';
        $slug = $baseSlug;
        $suffix = 1;

        while (
            Partner::where('slug', $slug)
                ->when($partner, fn ($query) => $query->where('id', '!=', $partner->id))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $suffix++;
        }

        $data = [
            ...$validated,
            'user_id' => $request->user()->id,
            'slug' => $slug,
            'type' => 'workshop',
            'is_active' => false,
            'application_status' => 'pending',
        ];

        if ($partner) {
            $partner->update($data);
        } else {
            Partner::create($data);
        }

        return redirect()
            ->route('workshop.apply')
            ->with('success', 'განაცხადი მიღებულია. ადმინისტრატორის დადასტურებას ელოდება.');
    }
}
