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
                'year' => $item->year, 'condition' => $item->condition,
                'mileage' => $item->mileage, 'engine_cc' => $item->engine_cc,
                'location' => $item->location, 'description' => $item->description,
                'image_url' => $item->image_path ? url('/storage/' . ltrim($item->image_path, '/')) : null,
            ]);
        return response()->json($items);
    }


    public function storeListing(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'engine_cc' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'condition' => ['required', 'in:new,used'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('motorcycles', 'public')
            : null;

        $base = Str::slug($data['title']) ?: 'motorcycle';
        $slug = $base;
        $suffix = 2;
        while (Motorcycle::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $suffix++;
        }

        $motorcycle = $request->user()->motorcycles()->create([
            'partner_id' => null,
            'title' => $data['title'],
            'slug' => $slug,
            'brand' => $data['brand'],
            'model' => $data['model'],
            'year' => $data['year'],
            'price' => $data['price'],
            'mileage' => $data['mileage'] ?? null,
            'engine_cc' => $data['engine_cc'] ?? null,
            'condition' => $data['condition'],
            'location' => $data['location'] ?? null,
            'description' => $data['description'] ?? null,
            'image_path' => $imagePath,
            'status' => 'draft',
            'is_featured' => false,
            'published_at' => null,
        ]);

        return response()->json([
            'message' => 'განცხადება შეიქმნა და გამოქვეყნებამდე შემოწმებას დაელოდება.',
            'listing' => ['id' => $motorcycle->id, 'title' => $motorcycle->title, 'status' => $motorcycle->status],
        ], 201);
    }

    public function updateListing(Request $request, int $motorcycle)
    {
        $item = $request->user()->motorcycles()->findOrFail($motorcycle);
        $data = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'brand' => ['sometimes', 'required', 'string', 'max:255'],
            'model' => ['sometimes', 'required', 'string', 'max:255'],
            'year' => ['sometimes', 'required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'price' => ['sometimes', 'required', 'numeric', 'min:0', 'max:9999999999.99'],
            'mileage' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'engine_cc' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:65535'],
            'condition' => ['sometimes', 'required', 'in:new,used'],
            'location' => ['sometimes', 'nullable', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:10000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if (array_key_exists('title', $data) && $data['title'] !== $item->title) {
            $base = Str::slug($data['title']) ?: 'motorcycle';
            $slug = $base;
            $suffix = 2;
            while (Motorcycle::where('slug', $slug)->where('id', '!=', $item->id)->exists()) {
                $slug = $base . '-' . $suffix++;
            }
            $data['slug'] = $slug;
        }

        if ($request->hasFile('image')) {
            if ($item->image_path) Storage::disk('public')->delete($item->image_path);
            $data['image_path'] = $request->file('image')->store('motorcycles', 'public');
        }

        $data['status'] = 'draft';
        $data['is_featured'] = false;
        $data['published_at'] = null;
        $item->fill($data)->save();

        return response()->json(['message' => 'განცხადება განახლდა და ხელახლა შემოწმებას დაელოდება.']);
    }

    public function deleteListing(Request $request, int $motorcycle)
    {
        $item = $request->user()->motorcycles()->findOrFail($motorcycle);
        if ($item->image_path) Storage::disk('public')->delete($item->image_path);
        $item->images()->get()->each(fn ($image) => Storage::disk('public')->delete($image->image_path));
        $item->delete();

        return response()->json(['message' => 'განცხადება წაიშალა.']);
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
