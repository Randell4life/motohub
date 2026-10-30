<?php

namespace App\Http\Controllers;

use App\Models\Motorcycle;
use App\Models\Part;
use App\Models\PartListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MotoHubFeaturesController extends Controller
{
    public function hub(): View
    {
        $userId = auth()->id();
        $stats = [
            'motorcycles' => $userId ? Motorcycle::where('user_id', $userId)->count() : Motorcycle::where('status', 'published')->count(),
            'service_centers' => DB::table('service_centers')->where('is_active', true)->where('approval_status', 'approved')->count(),
            'clubs' => DB::table('moto_clubs')->where('is_public', true)->count(),
            'events' => DB::table('moto_events')->where('starts_at', '>=', now())->count(),
        ];

        $clubs = DB::table('moto_clubs')
            ->leftJoin('moto_club_members', function ($join) {
                $join->on('moto_club_members.club_id', '=', 'moto_clubs.id')
                    ->where('moto_club_members.status', '=', 'active');
            })
            ->select('moto_clubs.*')
            ->selectRaw('COUNT(moto_club_members.id) as members_count')
            ->where('moto_clubs.is_public', true)
            ->groupBy('moto_clubs.id')
            ->latest('moto_clubs.created_at')
            ->limit(4)
            ->get();

        $events = DB::table('moto_events')
            ->join('moto_clubs', 'moto_clubs.id', '=', 'moto_events.club_id')
            ->where('moto_events.starts_at', '>=', now())
            ->orderBy('moto_events.starts_at')
            ->limit(4)
            ->select('moto_events.*', 'moto_clubs.name as club_name', 'moto_clubs.slug as club_slug')
            ->get();

        return view('features.hub', compact('stats', 'clubs', 'events'));
    }

    public function garage(): View
    {
        $user = auth()->user();
        $motorcycles = $user->motorcycles()->with(['images', 'serviceRecords'])->latest()->get();
        $reminders = DB::table('motorcycle_maintenance_reminders')
            ->join('motorcycles', 'motorcycles.id', '=', 'motorcycle_maintenance_reminders.motorcycle_id')
            ->where('motorcycle_maintenance_reminders.user_id', $user->id)
            ->whereNull('motorcycle_maintenance_reminders.completed_at')
            ->orderByRaw('motorcycle_maintenance_reminders.due_date IS NULL')
            ->orderBy('motorcycle_maintenance_reminders.due_date')
            ->select('motorcycle_maintenance_reminders.*', 'motorcycles.title as motorcycle_title')
            ->get();
        $expenses = DB::table('motorcycle_expenses')
            ->join('motorcycles', 'motorcycles.id', '=', 'motorcycle_expenses.motorcycle_id')
            ->where('motorcycle_expenses.user_id', $user->id)
            ->orderByDesc('expense_date')
            ->limit(30)
            ->select('motorcycle_expenses.*', 'motorcycles.title as motorcycle_title')
            ->get();
        $totalExpenses = DB::table('motorcycle_expenses')->where('user_id', $user->id)->sum('amount');
        $monthExpenses = DB::table('motorcycle_expenses')->where('user_id', $user->id)
            ->whereBetween('expense_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
            ->sum('amount');

        return view('features.garage', compact('motorcycles', 'reminders', 'expenses', 'totalExpenses', 'monthExpenses'));
    }

    public function storeReminder(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'motorcycle_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:160'],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
            'due_mileage' => ['nullable', 'integer', 'min:0', 'max:2000000'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $motorcycle = $request->user()->motorcycles()->findOrFail($data['motorcycle_id']);
        if (empty($data['due_date']) && empty($data['due_mileage'])) {
            return back()->withErrors(['due_date' => 'მიუთითე თარიღი ან გარბენი მაინც.'])->withInput();
        }
        DB::table('motorcycle_maintenance_reminders')->insert([
            'user_id' => $request->user()->id,
            'motorcycle_id' => $motorcycle->id,
            'title' => $data['title'],
            'due_date' => $data['due_date'] ?? null,
            'due_mileage' => $data['due_mileage'] ?? null,
            'notes' => $data['notes'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'შეხსენება დაემატა ავტოფარეხს.');
    }

    public function completeReminder(Request $request, int $reminder): RedirectResponse
    {
        $updated = DB::table('motorcycle_maintenance_reminders')
            ->where('id', $reminder)
            ->where('user_id', $request->user()->id)
            ->whereNull('completed_at')
            ->update(['completed_at' => now(), 'updated_at' => now()]);
        abort_unless($updated, 404);

        return back()->with('success', 'შეხსენება შესრულებულად მოინიშნა.');
    }

    public function storeExpense(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'motorcycle_id' => ['required', 'integer'],
            'category' => ['required', 'string', 'max:80'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:999999999'],
            'expense_date' => ['required', 'date', 'before_or_equal:today'],
            'vendor' => ['nullable', 'string', 'max:160'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $motorcycle = $request->user()->motorcycles()->findOrFail($data['motorcycle_id']);
        DB::table('motorcycle_expenses')->insert([
            'user_id' => $request->user()->id,
            'motorcycle_id' => $motorcycle->id,
            'category' => $data['category'],
            'amount' => $data['amount'],
            'expense_date' => $data['expense_date'],
            'vendor' => $data['vendor'] ?? null,
            'notes' => $data['notes'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'ხარჯი დაემატა.');
    }

    public function destroyExpense(Request $request, int $expense): RedirectResponse
    {
        $deleted = DB::table('motorcycle_expenses')
            ->where('id', $expense)
            ->where('user_id', $request->user()->id)
            ->delete();
        abort_unless($deleted, 404);

        return back()->with('success', 'ხარჯის ჩანაწერი წაიშალა.');
    }

    public function favorites(): View
    {
        $userId = auth()->id();
        $motorcycles = Motorcycle::query()
            ->join('motorcycle_favorites', 'motorcycle_favorites.motorcycle_id', '=', 'motorcycles.id')
            ->where('motorcycle_favorites.user_id', $userId)
            ->with('images')
            ->select('motorcycles.*')
            ->latest('motorcycle_favorites.created_at')
            ->get();

        $partFavorites = DB::table('part_favorites')->where('user_id', $userId)->latest()->get()
            ->map(function ($favorite) {
                $item = $favorite->source === 'store'
                    ? Part::where('is_active', true)->find($favorite->part_id)
                    : PartListing::where('status', 'published')->find($favorite->part_id);
                if (!$item) {
                    return null;
                }
                return (object) [
                    'source' => $favorite->source,
                    'id' => $item->id,
                    'title' => $item->title,
                    'price' => $item->price,
                    'brand' => $item->brand,
                ];
            })->filter()->values();

        return view('features.favorites', compact('motorcycles', 'partFavorites'));
    }

    public function toggleMotorcycleFavorite(Request $request, int $motorcycle): RedirectResponse
    {
        $item = Motorcycle::where('status', 'published')->findOrFail($motorcycle);
        $favorite = DB::table('motorcycle_favorites')
            ->where('user_id', $request->user()->id)
            ->where('motorcycle_id', $item->id)
            ->first();

        if ($favorite) {
            DB::table('motorcycle_favorites')->where('id', $favorite->id)->delete();
            $message = 'მოტოციკლი ფავორიტებიდან ამოიშალა.';
        } else {
            DB::table('motorcycle_favorites')->insert([
                'user_id' => $request->user()->id,
                'motorcycle_id' => $item->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $message = 'მოტოციკლი ფავორიტებში დაემატა.';
        }

        return back()->with('success', $message);
    }

    public function togglePartFavorite(Request $request, string $source, int $part): RedirectResponse
    {
        abort_unless(in_array($source, ['store', 'marketplace'], true), 404);
        $item = $source === 'store'
            ? Part::where('is_active', true)->findOrFail($part)
            : PartListing::where('status', 'published')->findOrFail($part);

        $favorite = DB::table('part_favorites')
            ->where('user_id', $request->user()->id)
            ->where('source', $source)
            ->where('part_id', $item->id)
            ->first();

        if ($favorite) {
            DB::table('part_favorites')->where('id', $favorite->id)->delete();
            $message = 'ნაწილი ფავორიტებიდან ამოიშალა.';
        } else {
            DB::table('part_favorites')->insert([
                'user_id' => $request->user()->id,
                'source' => $source,
                'part_id' => $item->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $message = 'ნაწილი ფავორიტებში დაემატა.';
        }

        return back()->with('success', $message);
    }

    public function compareMotorcycles(Request $request): View
    {
        $ids = collect((array) $request->query('ids', []))
            ->filter(fn ($id) => is_scalar($id) && ctype_digit((string) $id))
            ->map(fn ($id) => (int) $id)->unique()->take(3)->values();
        $motorcycles = $ids->isEmpty()
            ? collect()
            : Motorcycle::where('status', 'published')->whereIn('id', $ids)->with('images')->get();

        return view('features.compare', compact('motorcycles'));
    }

    public function clubs(): View
    {
        $clubs = DB::table('moto_clubs')
            ->leftJoin('moto_club_members', function ($join) {
                $join->on('moto_club_members.club_id', '=', 'moto_clubs.id')
                    ->where('moto_club_members.status', '=', 'active');
            })
            ->leftJoin('users', 'users.id', '=', 'moto_clubs.owner_id')
            ->select('moto_clubs.*', 'users.name as owner_name')
            ->selectRaw('COUNT(moto_club_members.id) as members_count')
            ->where(function ($query) {
                $query->where('moto_clubs.is_public', true);
                if (auth()->check()) {
                    $query->orWhere('moto_clubs.owner_id', auth()->id())
                        ->orWhereExists(function ($sub) {
                            $sub->selectRaw('1')->from('moto_club_members as mine')
                                ->whereColumn('mine.club_id', 'moto_clubs.id')
                                ->where('mine.user_id', auth()->id());
                        });
                }
            })
            ->groupBy('moto_clubs.id', 'users.name')
            ->orderByDesc('moto_clubs.created_at')
            ->paginate(12);

        return view('features.clubs.index', compact('clubs'));
    }

    public function createClub(): View
    {
        return view('features.clubs.create');
    }

    public function storeClub(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:140'],
            'city' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:4000'],
            'is_public' => ['required', 'boolean'],
        ]);
        $clubId = DB::transaction(function () use ($request, $data) {
            $slug = Str::slug($data['name']) . '-' . Str::lower(Str::random(5));
            $id = DB::table('moto_clubs')->insertGetId([
                'owner_id' => $request->user()->id,
                'name' => $data['name'],
                'slug' => $slug,
                'city' => $data['city'] ?? null,
                'description' => $data['description'] ?? null,
                'is_public' => (bool) $data['is_public'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('moto_club_members')->insert([
                'club_id' => $id,
                'user_id' => $request->user()->id,
                'role' => 'owner',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return $id;
        });

        return redirect()->route('clubs.show', $slug = DB::table('moto_clubs')->where('id', $clubId)->value('slug'))
            ->with('success', 'მოტოკლუბი შეიქმნა. ახლა შეგიძლია ღონისძიების დამატება.');
    }

    public function showClub(string $slug): View
    {
        $club = DB::table('moto_clubs')->where('slug', $slug)->firstOrFail();
        $isMember = auth()->check() && DB::table('moto_club_members')
            ->where('club_id', $club->id)->where('user_id', auth()->id())->where('status', 'active')->exists();
        abort_unless($club->is_public || $isMember || (auth()->check() && $club->owner_id === auth()->id()), 404);

        $members = DB::table('moto_club_members')
            ->join('users', 'users.id', '=', 'moto_club_members.user_id')
            ->where('moto_club_members.club_id', $club->id)
            ->where('moto_club_members.status', 'active')
            ->select('users.name', 'moto_club_members.role')
            ->orderBy('moto_club_members.created_at')
            ->limit(30)->get();
        $events = DB::table('moto_events')
            ->leftJoin('moto_event_attendees', function ($join) {
                $join->on('moto_event_attendees.event_id', '=', 'moto_events.id')
                    ->where('moto_event_attendees.status', '=', 'going');
            })
            ->where('moto_events.club_id', $club->id)
            ->select('moto_events.*')
            ->selectRaw('COUNT(moto_event_attendees.id) as attendees_count')
            ->groupBy('moto_events.id')
            ->orderByDesc('moto_events.starts_at')->get();
        $myEventIds = auth()->check()
            ? DB::table('moto_event_attendees')->where('user_id', auth()->id())->where('status', 'going')->pluck('event_id')->all()
            : [];

        return view('features.clubs.show', compact('club', 'isMember', 'members', 'events', 'myEventIds'));
    }

    public function joinClub(Request $request, int $club): RedirectResponse
    {
        $clubRow = DB::table('moto_clubs')->find($club);
        abort_unless($clubRow, 404);
        $status = $clubRow->is_public ? 'active' : 'pending';
        DB::table('moto_club_members')->updateOrInsert(
            ['club_id' => $club, 'user_id' => $request->user()->id],
            ['role' => 'member', 'status' => $status, 'updated_at' => now(), 'created_at' => now()]
        );

        return back()->with('success', $status === 'active' ? 'კლუბს შეუერთდი.' : 'გაწევრიანების მოთხოვნა გაიგზავნა.');
    }

    public function storeEvent(Request $request, int $club): RedirectResponse
    {
        $clubRow = DB::table('moto_clubs')->where('id', $club)->where('owner_id', $request->user()->id)->first();
        abort_unless($clubRow, 403);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:3000'],
            'location' => ['required', 'string', 'max:180'],
            'starts_at' => ['required', 'date', 'after:now'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'capacity' => ['nullable', 'integer', 'min:2', 'max:100000'],
        ]);
        DB::table('moto_events')->insert([
            'club_id' => $club,
            'created_by' => $request->user()->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'location' => $data['location'],
            'starts_at' => $data['starts_at'],
            'ends_at' => $data['ends_at'] ?? null,
            'capacity' => $data['capacity'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'ღონისძიება გამოქვეყნდა.');
    }

    public function attendEvent(Request $request, int $event): RedirectResponse
    {
        $eventRow = DB::table('moto_events')->where('id', $event)->first();
        abort_unless($eventRow, 404);
        $member = DB::table('moto_club_members')->where('club_id', $eventRow->club_id)
            ->where('user_id', $request->user()->id)->where('status', 'active')->exists();
        abort_unless($member, 403);

        DB::transaction(function () use ($eventRow, $request) {
            $locked = DB::table('moto_events')->where('id', $eventRow->id)->lockForUpdate()->first();
            $alreadyGoing = DB::table('moto_event_attendees')->where('event_id', $locked->id)
                ->where('user_id', $request->user()->id)->where('status', 'going')->exists();
            if (!$alreadyGoing && $locked->capacity) {
                $count = DB::table('moto_event_attendees')->where('event_id', $locked->id)->where('status', 'going')->count();
                if ($count >= $locked->capacity) {
                    abort(422, 'ღონისძიებაზე თავისუფალი ადგილები აღარ არის.');
                }
            }
            DB::table('moto_event_attendees')->updateOrInsert(
                ['event_id' => $locked->id, 'user_id' => $request->user()->id],
                ['status' => 'going', 'updated_at' => now(), 'created_at' => now()]
            );
        });

        return back()->with('success', 'ღონისძიებაზე მონაწილეობა დადასტურდა.');
    }
}
