<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CommunityPost;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Motorcycle;
use App\Models\Part;
use App\Models\PartListing;
use App\Models\TransportOffer;
use App\Models\TransportRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MobileExtendedController extends Controller
{
    public function garage(Request $request)
    {
        $user = $request->user();
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
            ->latest('expense_date')->limit(50)
            ->select('motorcycle_expenses.*', 'motorcycles.title as motorcycle_title')->get();

        return response()->json([
            'motorcycles' => $motorcycles,
            'reminders' => $reminders,
            'expenses' => $expenses,
            'total_expenses' => (float) DB::table('motorcycle_expenses')->where('user_id', $user->id)->sum('amount'),
            'month_expenses' => (float) DB::table('motorcycle_expenses')->where('user_id', $user->id)
                ->whereBetween('expense_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])->sum('amount'),
        ]);
    }

    public function storeReminder(Request $request)
    {
        $data = $request->validate([
            'motorcycle_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:160'],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
            'due_mileage' => ['nullable', 'integer', 'min:0', 'max:2000000'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $request->user()->motorcycles()->findOrFail($data['motorcycle_id']);
        if (empty($data['due_date']) && empty($data['due_mileage'])) {
            throw ValidationException::withMessages(['due_date' => 'მიუთითე თარიღი ან გარბენი მაინც.']);
        }
        $id = DB::table('motorcycle_maintenance_reminders')->insertGetId([
            'user_id' => $request->user()->id, 'motorcycle_id' => $data['motorcycle_id'],
            'title' => $data['title'], 'due_date' => $data['due_date'] ?? null,
            'due_mileage' => $data['due_mileage'] ?? null, 'notes' => $data['notes'] ?? null,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        return response()->json(['message' => 'შეხსენება დაემატა ავტოფარეხს.', 'id' => $id], 201);
    }

    public function completeReminder(Request $request, int $reminder)
    {
        $updated = DB::table('motorcycle_maintenance_reminders')->where('id', $reminder)
            ->where('user_id', $request->user()->id)->whereNull('completed_at')
            ->update(['completed_at' => now(), 'updated_at' => now()]);
        abort_unless($updated, 404);
        return response()->json(['message' => 'შეხსენება შესრულებულად მოინიშნა.']);
    }

    public function storeExpense(Request $request)
    {
        $data = $request->validate([
            'motorcycle_id' => ['required', 'integer'], 'category' => ['required', 'string', 'max:80'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:999999999'],
            'expense_date' => ['required', 'date', 'before_or_equal:today'],
            'vendor' => ['nullable', 'string', 'max:160'], 'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $request->user()->motorcycles()->findOrFail($data['motorcycle_id']);
        $id = DB::table('motorcycle_expenses')->insertGetId($data + [
            'user_id' => $request->user()->id, 'created_at' => now(), 'updated_at' => now(),
        ]);
        return response()->json(['message' => 'ხარჯი დაემატა.', 'id' => $id], 201);
    }

    public function deleteExpense(Request $request, int $expense)
    {
        $deleted = DB::table('motorcycle_expenses')->where('id', $expense)->where('user_id', $request->user()->id)->delete();
        abort_unless($deleted, 404);
        return response()->json(['message' => 'ხარჯის ჩანაწერი წაიშალა.']);
    }

    public function favorites(Request $request)
    {
        $userId = $request->user()->id;
        $motorcycles = Motorcycle::query()->join('motorcycle_favorites', 'motorcycle_favorites.motorcycle_id', '=', 'motorcycles.id')
            ->where('motorcycle_favorites.user_id', $userId)->where('motorcycles.status', 'published')
            ->with('images')->select('motorcycles.*')->latest('motorcycle_favorites.created_at')->get();
        $parts = DB::table('part_favorites')->where('user_id', $userId)->latest()->get()->map(function ($favorite) {
            $item = $favorite->source === 'store'
                ? Part::where('is_active', true)->find($favorite->part_id)
                : PartListing::where('status', 'published')->find($favorite->part_id);
            return $item ? ['source' => $favorite->source, 'id' => $item->id, 'title' => $item->title, 'price' => $item->price, 'brand' => $item->brand] : null;
        })->filter()->values();
        return response()->json(['motorcycles' => $motorcycles, 'parts' => $parts]);
    }

    public function toggleMotorcycleFavorite(Request $request, int $motorcycle)
    {
        $item = Motorcycle::where('status', 'published')->findOrFail($motorcycle);
        $query = DB::table('motorcycle_favorites')->where('user_id', $request->user()->id)->where('motorcycle_id', $item->id);
        if ($query->exists()) { $query->delete(); $saved = false; }
        else { DB::table('motorcycle_favorites')->insert(['user_id' => $request->user()->id, 'motorcycle_id' => $item->id, 'created_at' => now(), 'updated_at' => now()]); $saved = true; }
        return response()->json(['saved' => $saved, 'message' => $saved ? 'ფავორიტებში დაემატა.' : 'ფავორიტებიდან ამოიშალა.']);
    }

    public function togglePartFavorite(Request $request, string $source, int $part)
    {
        abort_unless(in_array($source, ['store', 'marketplace'], true), 404);
        $item = $source === 'store' ? Part::where('is_active', true)->findOrFail($part) : PartListing::where('status', 'published')->findOrFail($part);
        $query = DB::table('part_favorites')->where('user_id', $request->user()->id)->where('source', $source)->where('part_id', $item->id);
        if ($query->exists()) { $query->delete(); $saved = false; }
        else { DB::table('part_favorites')->insert(['user_id' => $request->user()->id, 'source' => $source, 'part_id' => $item->id, 'created_at' => now(), 'updated_at' => now()]); $saved = true; }
        return response()->json(['saved' => $saved, 'message' => $saved ? 'ფავორიტებში დაემატა.' : 'ფავორიტებიდან ამოიშალა.']);
    }

    public function compare(Request $request)
    {
        $ids = collect((array) $request->query('ids', []))->filter(fn ($id) => is_scalar($id) && ctype_digit((string) $id))->map(fn ($id) => (int) $id)->unique()->take(3);
        return response()->json(['motorcycles' => Motorcycle::where('status', 'published')->whereIn('id', $ids)->with('images')->get()]);
    }

    public function community(Request $request)
    {
        $userId = $request->user()->id;
        $posts = CommunityPost::with(['user:id,name,avatar_path', 'motorcycle:id,title,brand,model', 'comments.user:id,name,avatar_path'])
            ->withCount(['comments', 'likes'])
            ->withExists(['likes as liked_by_me' => fn ($q) => $q->where('users.id', $userId)])
            ->latest()->paginate(20);
        $posts->getCollection()->transform(function ($post) { $post->image_url = $post->image_path ? Storage::disk('public')->url($post->image_path) : null; return $post; });
        return response()->json($posts);
    }

    public function storePost(Request $request)
    {
        $data = $request->validate(['body' => ['required', 'string', 'min:2', 'max:3000'], 'motorcycle_id' => ['nullable', 'integer', 'exists:motorcycles,id'], 'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120']]);
        if (!empty($data['motorcycle_id'])) {
            $request->user()->motorcycles()->where('status', 'published')->findOrFail($data['motorcycle_id']);
        }
        $imagePath = $request->file('image')?->store('community/posts','public');
        $post = CommunityPost::create(['user_id' => $request->user()->id, 'motorcycle_id' => $data['motorcycle_id'] ?? null, 'body' => trim($data['body']), 'image_path' => $imagePath]);
        return response()->json(['message' => 'პოსტი გამოქვეყნდა.', 'post' => $post->load('user:id,name,avatar_path')], 201);
    }

    public function commentPost(Request $request, CommunityPost $post)
    {
        $data = $request->validate(['body' => ['required', 'string', 'min:1', 'max:1000']]);
        $comment = $post->comments()->create(['user_id' => $request->user()->id, 'body' => trim($data['body'])]);
        return response()->json(['message' => 'კომენტარი დაემატა.', 'comment' => $comment->load('user:id,name,avatar_path')], 201);
    }

    public function likePost(Request $request, CommunityPost $post)
    {
        $changes = $post->likes()->toggle($request->user()->id);
        return response()->json(['liked' => !empty($changes['attached']), 'likes_count' => $post->likes()->count()]);
    }

    public function deletePost(Request $request, CommunityPost $post)
    {
        abort_unless($post->user_id === $request->user()->id, 403);
        if ($post->image_path) Storage::disk('public')->delete($post->image_path);
        $post->delete();
        return response()->json(['message' => 'პოსტი წაიშალა.']);
    }

    public function clubs(Request $request)
    {
        $userId = $request->user()->id;
        $clubs = DB::table('moto_clubs')->leftJoin('moto_club_members', function ($join) {
            $join->on('moto_club_members.club_id', '=', 'moto_clubs.id')->where('moto_club_members.status', '=', 'active');
        })->select('moto_clubs.id', 'moto_clubs.name', 'moto_clubs.slug', 'moto_clubs.city', 'moto_clubs.description', 'moto_clubs.is_public', 'moto_clubs.owner_id')
            ->selectRaw('COUNT(moto_club_members.id) as members_count')
            ->where(function ($q) use ($userId) {
                $q->where('moto_clubs.is_public', true)->orWhere('moto_clubs.owner_id', $userId)
                    ->orWhereExists(fn ($sub) => $sub->selectRaw('1')->from('moto_club_members as mine')->whereColumn('mine.club_id', 'moto_clubs.id')->where('mine.user_id', $userId)->where('mine.status', 'active'));
            })->groupBy('moto_clubs.id', 'moto_clubs.name', 'moto_clubs.slug', 'moto_clubs.city', 'moto_clubs.description', 'moto_clubs.is_public')
            ->orderByDesc('moto_clubs.created_at')->get();
        $events = DB::table('moto_events')->join('moto_clubs', 'moto_clubs.id', '=', 'moto_events.club_id')
            ->where('moto_events.starts_at', '>=', now())->orderBy('moto_events.starts_at')
            ->select('moto_events.*', 'moto_clubs.name as club_name', 'moto_clubs.slug as club_slug')->limit(30)->get();
         $pendingMembers = DB::table('moto_club_members')->join('moto_clubs','moto_clubs.id','=','moto_club_members.club_id')->join('users','users.id','=','moto_club_members.user_id')->where('moto_clubs.owner_id',$userId)->where('moto_club_members.status','pending')->select('moto_club_members.id','moto_club_members.club_id','moto_clubs.name as club_name','users.name as user_name')->get();
        return response()->json(['clubs' => $clubs, 'events' => $events, 'pending_members' => $pendingMembers]);
    }

    public function storeClub(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:140'], 'city' => ['nullable', 'string', 'max:100'], 'description' => ['nullable', 'string', 'max:4000'], 'is_public' => ['required', 'boolean']]);
        $clubId = DB::transaction(function () use ($request, $data) {
            $slug = Str::slug($data['name']).'-'.Str::lower(Str::random(5));
            $id = DB::table('moto_clubs')->insertGetId(['owner_id' => $request->user()->id, 'name' => $data['name'], 'slug' => $slug, 'city' => $data['city'] ?? null, 'description' => $data['description'] ?? null, 'is_public' => (bool) $data['is_public'], 'created_at' => now(), 'updated_at' => now()]);
            DB::table('moto_club_members')->insert(['club_id' => $id, 'user_id' => $request->user()->id, 'role' => 'owner', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()]);
            return $id;
        });
        return response()->json(['message' => 'მოტოკლუბი შეიქმნა.', 'club' => DB::table('moto_clubs')->find($clubId)], 201);
    }

    public function joinClub(Request $request, int $club)
    {
        $item = DB::table('moto_clubs')->where('is_public', true)->find($club);
        abort_unless($item, 404);
        DB::table('moto_club_members')->updateOrInsert(['club_id' => $club, 'user_id' => $request->user()->id], ['role' => 'member', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()]);
        return response()->json(['message' => 'კლუბს შეუერთდი.']);
    }

    public function attendEvent(Request $request, int $event)
    {
        $item = DB::table('moto_events')->where('id',$event)->first();
        abort_unless($item,404);
        $member = DB::table('moto_club_members')->where('club_id',$item->club_id)
            ->where('user_id',$request->user()->id)->where('status','active')->exists();
        abort_unless($member,403);
        DB::transaction(function () use ($item,$request) {
            $locked = DB::table('moto_events')->where('id',$item->id)->lockForUpdate()->first();
            $alreadyGoing = DB::table('moto_event_attendees')->where('event_id',$locked->id)
                ->where('user_id',$request->user()->id)->where('status','going')->exists();
            if (!$alreadyGoing && $locked->capacity) {
                $count = DB::table('moto_event_attendees')->where('event_id',$locked->id)->where('status','going')->count();
                if ($count >= $locked->capacity) throw ValidationException::withMessages(['event'=>'ღონისძიებაზე თავისუფალი ადგილები აღარ არის.']);
            }
            DB::table('moto_event_attendees')->updateOrInsert(
                ['event_id'=>$locked->id,'user_id'=>$request->user()->id],
                ['status'=>'going','created_at'=>now(),'updated_at'=>now()]
            );
        });
        return response()->json(['message'=>'ღონისძიებაზე მონაწილეობა დადასტურდა.']);
    }

    public function conversations(Request $request)
    {
        $user = $request->user();
        $items = $user->conversations()->with(['participants:id,name,avatar_path', 'motorcycle:id,title', 'latestMessage.sender:id,name'])
            ->withCount(['messages as unread_count' => fn ($q) => $q->whereNull('read_at')->where('sender_id', '!=', $user->id)])
            ->orderByDesc('last_message_at')->limit(50)->get();
        return response()->json(['conversations' => $items]);
    }

    public function conversation(Request $request, Conversation $conversation)
    {
        $this->authorizeConversation($request, $conversation);
        $messages = $conversation->messages()->with('sender:id,name,avatar_path')->oldest()->limit(100)->get();
        $conversation->messages()->where('sender_id', '!=', $request->user()->id)->whereNull('read_at')->update(['read_at' => now()]);
        $conversation->participants()->updateExistingPivot($request->user()->id, ['last_read_at' => now()]);
        return response()->json(['conversation' => $conversation->load(['participants:id,name,avatar_path', 'motorcycle:id,title']), 'messages' => $messages]);
    }

    public function startConversation(Request $request)
    {
        $data = $request->validate(['recipient_id' => ['required', 'integer', 'exists:users,id'], 'body' => ['required', 'string', 'min:1', 'max:5000'], 'motorcycle_id' => ['nullable', 'integer', 'exists:motorcycles,id'], 'subject' => ['nullable', 'string', 'max:160']]);
        abort_if((int) $data['recipient_id'] === $request->user()->id, 422, 'საკუთარ თავს შეტყობინებას ვერ გაუგზავნით.');
        $recipient = User::findOrFail($data['recipient_id']);
        $motorcycle = null;
        if (!empty($data['motorcycle_id'])) {
            $motorcycle = Motorcycle::where('status', 'published')->findOrFail($data['motorcycle_id']);
            abort_unless($motorcycle->user_id === $recipient->id, 422, 'მოტოციკლის გამყიდველი არ ემთხვევა მიმღებს.');
        }
        $conversation = DB::transaction(function () use ($request, $recipient, $motorcycle, $data) {
            $conversation = Conversation::create(['created_by_user_id' => $request->user()->id, 'motorcycle_id' => $motorcycle?->id, 'subject' => $data['subject'] ?? ($motorcycle ? 'მოტოციკლი: '.$motorcycle->title : 'პირადი მიმოწერა'), 'last_message_at' => now()]);
            $conversation->participants()->attach([$request->user()->id => ['last_read_at' => now()], $recipient->id => ['last_read_at' => null]]);
            $conversation->messages()->create(['sender_id' => $request->user()->id, 'body' => trim($data['body'])]);
            return $conversation;
        });
        return response()->json(['message' => 'შეტყობინება გაიგზავნა.', 'conversation_id' => $conversation->id], 201);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $this->authorizeConversation($request, $conversation);
        $data = $request->validate(['body' => ['required', 'string', 'min:1', 'max:5000']]);
        $message = $conversation->messages()->create(['sender_id' => $request->user()->id, 'body' => trim($data['body'])]);
        $conversation->forceFill(['last_message_at' => $message->created_at])->save();
        $conversation->participants()->updateExistingPivot($request->user()->id, ['last_read_at' => now()]);
        return response()->json(['message' => 'გაიგზავნა.', 'item' => $message->load('sender:id,name,avatar_path')], 201);
    }

    private function authorizeConversation(Request $request, Conversation $conversation): void
    {
        abort_unless($conversation->participants()->whereKey($request->user()->id)->exists(), 404);
    }

    public function transportIndex(Request $request)
    {
        $companies = DB::table('partners')->where('type', 'transport')->where('application_status', 'approved')->where('is_active', true)
            ->select('id', 'name', 'slug', 'phone', 'address', 'description')->orderBy('name')->get();
        $requests = TransportRequest::where('user_id', $request->user()->id)->with(['offers.partner:id,name,phone'])->latest()->limit(30)->get();
        return response()->json(['companies' => $companies, 'requests' => $requests]);
    }

    public function storeTransportRequest(Request $request)
    {
        $data = $request->validate([
            'motorcycle_brand' => ['required', 'string', 'max:120'], 'motorcycle_model' => ['required', 'string', 'max:120'],
            'motorcycle_year' => ['nullable', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'pickup_city' => ['required', 'string', 'max:150'], 'pickup_address' => ['nullable', 'string', 'max:255'],
            'destination_city' => ['required', 'string', 'max:150'], 'destination_address' => ['nullable', 'string', 'max:255'],
            'preferred_date' => ['nullable', 'date', 'after_or_equal:today'], 'motorcycle_details' => ['nullable', 'string', 'max:3000'],
            'customer_name' => ['required', 'string', 'max:150'], 'customer_phone' => ['required', 'string', 'max:40'],
            'customer_email' => ['nullable', 'email', 'max:255'], 'customer_notes' => ['nullable', 'string', 'max:3000'],
        ]);
        $requestItem = TransportRequest::create($data + ['request_number' => 'MT-'.Str::upper(Str::random(10)), 'user_id' => $request->user()->id, 'status' => 'pending']);
        return response()->json(['message' => 'ტრანსპორტირების მოთხოვნა გაიგზავნა.', 'request' => $requestItem], 201);
    }

    public function acceptTransportOffer(Request $request, TransportOffer $offer)
    {
        DB::transaction(function () use ($request, $offer) {
            $transportRequest = TransportRequest::whereKey($offer->transport_request_id)->lockForUpdate()->firstOrFail();
            abort_unless($transportRequest->user_id === $request->user()->id, 403);
            if (!in_array($transportRequest->status, ['pending', 'quoted'], true) || $offer->status !== 'pending') {
                throw ValidationException::withMessages(['offer' => 'ამ შეთავაზების მიღება აღარ შეიძლება.']);
            }
            TransportOffer::where('transport_request_id', $transportRequest->id)->where('status', 'pending')->update(['status' => 'rejected']);
            $offer->update(['status' => 'accepted', 'accepted_at' => now()]);
            $transportRequest->update(['status' => 'accepted']);
        });
        return response()->json(['message' => 'შეთავაზება მიღებულია.']);
    }

    public function diagnose(Request $request)
    {
        $data = $request->validate(['motorcycle' => ['required', 'string', 'max:160'], 'symptom' => ['required', 'string', 'max:3000'], 'when' => ['nullable', 'in:cold,warm,always,under_load'], 'warning_signs' => ['nullable', 'array', 'max:5'], 'warning_signs.*' => ['string', 'in:oil_pressure,overheating,brake_issue,fuel_leak,smoke,none']]);
        $text = mb_strtolower($data['symptom']);
        $results = []; $urgent = false;
        if (str_contains($text, 'brake') || str_contains($text, 'მუხრუჭ') || in_array('brake_issue', $data['warning_signs'] ?? [], true)) { $results[] = ['title' => 'მუხრუჭის სისტემა', 'detail' => 'შესაძლოა პრობლემა იყოს ხუნდებში, სითხის დონეში, ჰაერში ან გაჟონვაში. მუხრუჭის ეფექტიანობის ეჭვისას მოტოციკლი არ მართო და სერვისამდე გადაიყვანე ტრანსპორტირებით.']; $urgent = true; }
        if (str_contains($text, 'oil') || str_contains($text, 'ზეთი') || in_array('oil_pressure', $data['warning_signs'] ?? [], true)) { $results[] = ['title' => 'ზეთის დონე ან წნევა', 'detail' => 'შეამოწმე ზეთის დონე მხოლოდ მწარმოებლის პროცედურით. ზეთის წნევის ნათურის ან მეტალის ხმისას ძრავა გამორთე და აღარ ამუშაო.']; $urgent = true; }
        if (str_contains($text, 'heat') || str_contains($text, 'overheat') || str_contains($text, 'ტემპერატ') || str_contains($text, 'ცხელ') || in_array('overheating', $data['warning_signs'] ?? [], true)) { $results[] = ['title' => 'გაგრილების სისტემა', 'detail' => 'შესაძლო მიზეზებია დაბალი სითხე, ვენტილატორი, თერმოსტატი ან გაჟონვა. ცხელ რადიატორს არ შეეხო; გადახურებისას გააჩერე ძრავა.']; $urgent = true; }
        if (str_contains($text, 'fuel') || str_contains($text, 'gas') || str_contains($text, 'ბენზინ') || str_contains($text, 'საწვავ') || in_array('fuel_leak', $data['warning_signs'] ?? [], true)) { $results[] = ['title' => 'საწვავის სისტემა', 'detail' => 'შესაძლოა პრობლემა იყოს შლანგში, შეერთებაში ან ინჟექტორში. საწვავის გაჟონვისას არ დაქოქო და მოარიდე ცეცხლს.']; $urgent = true; }
        if (str_contains($text, 'start') || str_contains($text, 'ქოქ') || str_contains($text, 'ბატარე') || str_contains($text, 'battery')) $results[] = ['title' => 'დაქოქვის სისტემა', 'detail' => 'შესამოწმებელია აკუმულატორი, კონტაქტები, დამუხტვა, ფიუზები და სტარტერის წრე.'];
        if (str_contains($text, 'chain') || str_contains($text, 'ჯაჭვ') || str_contains($text, 'კლაჩ') || str_contains($text, 'clutch') || str_contains($text, 'ვიბრ') || str_contains($text, 'ხმაურ') || str_contains($text, 'noise')) $results[] = ['title' => 'გადაცემათა ნაწილი / ხმაური', 'detail' => 'შესამოწმებელია ჯაჭვის დაჭიმულობა და შეზეთვა, კლაჩის თავისუფალი სვლა და ხმაურის წყარო.'];
        if (!$results) $results[] = ['title' => 'საჭიროა დამატებითი დიაგნოსტიკა', 'detail' => 'აღწერე სიმპტომის დრო, ცივი/ცხელი ძრავა, სიჩქარე/ბრუნი და გამაფრთხილებელი ნათურები. საბოლოო დიაგნოზი ადგილზე შემოწმებას საჭიროებს.'];
        if (in_array('smoke', $data['warning_signs'] ?? [], true)) { $urgent = true; $results[] = ['title' => 'კვამლი', 'detail' => 'გააჩერე უსაფრთხო ადგილას და გამორთე ძრავა. კვამლის წყაროს დადგენამდე მოძრაობა არ გააგრძელო.']; }
        return response()->json(['motorcycle' => $data['motorcycle'], 'urgent' => $urgent, 'results' => $results]);
    }

    public function approveClubMember(Request $request, int $club, int $member)
    {
        $clubRow = DB::table('moto_clubs')->where('id', $club)->where('owner_id', $request->user()->id)->first();
        abort_unless($clubRow, 403);
        $updated = DB::table('moto_club_members')->where('id', $member)->where('club_id', $club)->where('status', 'pending')
            ->update(['status' => 'active', 'updated_at' => now()]);
        abort_unless($updated, 404);
        return response()->json(['message' => 'გაწევრიანების მოთხოვნა დადასტურდა.']);
    }

    public function storeEvent(Request $request, int $club)
    {
        $clubRow = DB::table('moto_clubs')->where('id', $club)->where('owner_id', $request->user()->id)->first();
        abort_unless($clubRow, 403);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'], 'description' => ['nullable', 'string', 'max:3000'],
            'location' => ['required', 'string', 'max:180'], 'starts_at' => ['required', 'date', 'after:now'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'], 'capacity' => ['nullable', 'integer', 'min:2', 'max:100000'],
        ]);
        $id = DB::table('moto_events')->insertGetId($data + [
            'club_id' => $club, 'created_by' => $request->user()->id,
            'starts_at' => \Illuminate\Support\Carbon::parse($data['starts_at'])->format('Y-m-d H:i:s'),
            'ends_at' => !empty($data['ends_at']) ? \Illuminate\Support\Carbon::parse($data['ends_at'])->format('Y-m-d H:i:s') : null,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        return response()->json(['message' => 'ღონისძიება გამოქვეყნდა.', 'event' => DB::table('moto_events')->find($id)], 201);
    }

    public function serviceCenterDashboard(Request $request)
    {
        $center = \App\Models\ServiceCenter::where('owner_id', $request->user()->id)->firstOrFail();
        $orders = \App\Models\WorkOrder::where('service_center_id', $center->id)
            ->with(['serviceBooking', 'technician', 'bay', 'parts.inventoryItem', 'payments'])->latest()->limit(60)->get();
        return response()->json([
            'center' => $center->load(['services', 'hours']),
            'bookings' => $center->bookings()->with(['service','workOrder'])->latest('scheduled_at')->limit(60)->get(),
            'orders' => $orders,
            'technicians' => $center->technicians()->orderBy('name')->get(),
            'bays' => $center->bays()->orderBy('name')->get(),
            'inventory' => $center->inventory()->orderBy('name')->get(),
            'payments' => $center->payments()->with('workOrder')->latest('paid_at')->limit(40)->get(),
        ]);
    }

    public function storeTechnician(Request $request)
    {
        $center = \App\Models\ServiceCenter::where('owner_id', $request->user()->id)->firstOrFail();
        $data = $request->validate(['name' => ['required','string','max:160'], 'phone' => ['nullable','string','max:40'], 'specialty' => ['nullable','string','max:160']]);
        return response()->json(['message' => 'ხელოსანი დაემატა.', 'technician' => $center->technicians()->create($data)], 201);
    }

    public function storeBay(Request $request)
    {
        $center = \App\Models\ServiceCenter::where('owner_id', $request->user()->id)->firstOrFail();
        $data = $request->validate(['name' => ['required','string','max:100'], 'description' => ['nullable','string','max:255']]);
        return response()->json(['message' => 'სამუშაო ბოქსი დაემატა.', 'bay' => $center->bays()->create($data)], 201);
    }

    public function storeInventory(Request $request)
    {
        $center = \App\Models\ServiceCenter::where('owner_id', $request->user()->id)->firstOrFail();
        $data = $request->validate([
            'sku'=>['nullable','string','max:80'],'name'=>['required','string','max:180'],'brand'=>['nullable','string','max:120'],
            'category'=>['nullable','string','max:120'],'fitment'=>['nullable','string','max:255'],'quantity'=>['required','integer','min:0'],
            'min_quantity'=>['required','integer','min:0'],'cost_price'=>['required','numeric','min:0'],'sale_price'=>['required','numeric','min:0'],
            'location'=>['nullable','string','max:120'],
        ]);
        $item = DB::transaction(function () use ($center, $data, $request) {
            $item = $center->inventory()->create($data);
            if ($item->quantity > 0) DB::table('service_center_inventory_movements')->insert([
                'service_center_id'=>$center->id,'inventory_item_id'=>$item->id,'user_id'=>$request->user()->id,
                'type'=>'in','quantity'=>$item->quantity,'unit_cost'=>$item->cost_price,'note'=>'საწყობის საწყისი ნაშთი',
                'created_at'=>now(),'updated_at'=>now(),
            ]);
            return $item;
        });
        return response()->json(['message' => 'ნაწილი დაემატა საწყობს.', 'item' => $item], 201);
    }

    public function assignWorkOrder(Request $request, int $workOrder)
    {
        $center = \App\Models\ServiceCenter::where('owner_id', $request->user()->id)->firstOrFail();
        $order = \App\Models\WorkOrder::where('service_center_id', $center->id)->findOrFail($workOrder);
        $data = $request->validate(['assigned_technician_id'=>['nullable','integer'],'service_center_bay_id'=>['nullable','integer']]);
        if (!empty($data['assigned_technician_id'])) abort_unless($center->technicians()->whereKey($data['assigned_technician_id'])->exists(), 422);
        if (!empty($data['service_center_bay_id'])) abort_unless($center->bays()->whereKey($data['service_center_bay_id'])->exists(), 422);
        $order->update(['service_center_technician_id'=>$data['assigned_technician_id'] ?? null,'service_center_bay_id'=>$data['service_center_bay_id'] ?? null]);
        return response()->json(['message' => 'ხელოსანი და ბოქსი მიენიჭა შეკვეთას.']);
    }

    public function addWorkOrderPart(Request $request, int $workOrder)
    {
        $center = \App\Models\ServiceCenter::where('owner_id', $request->user()->id)->firstOrFail();
        $order = \App\Models\WorkOrder::where('service_center_id', $center->id)->findOrFail($workOrder);
        $data = $request->validate(['inventory_item_id'=>['required','integer'],'quantity'=>['required','integer','min:1']]);
        DB::transaction(function () use ($center, $order, $data, $request) {
            $item = $center->inventory()->whereKey($data['inventory_item_id'])->lockForUpdate()->firstOrFail();
            if ($item->quantity < $data['quantity']) throw ValidationException::withMessages(['quantity'=>'საწყობში საკმარისი რაოდენობა არ არის.']);
            $line = \App\Models\ServiceCenterWorkOrderPart::firstOrNew(['work_order_id'=>$order->id,'inventory_item_id'=>$item->id]);
            $line->quantity = ($line->exists ? $line->quantity : 0) + (int)$data['quantity'];
            $line->unit_price = $item->sale_price; $line->total = $line->quantity * $item->sale_price; $line->save();
            $item->decrement('quantity', (int)$data['quantity']);
            DB::table('service_center_inventory_movements')->insert(['service_center_id'=>$center->id,'inventory_item_id'=>$item->id,'work_order_id'=>$order->id,'user_id'=>$request->user()->id,'type'=>'out','quantity'=>$data['quantity'],'unit_cost'=>$item->cost_price,'note'=>'სამუშაო შეკვეთა '.$order->order_number,'created_at'=>now(),'updated_at'=>now()]);
            $partsTotal = $order->parts()->sum('total');
            $order->update(['parts_cost'=>$partsTotal,'total_cost'=>(float)$order->labor_cost+(float)$partsTotal]);
        });
        return response()->json(['message' => 'ნაწილი შეკვეთას მიება და საწყობიდან ჩამოიწერა.']);
    }

    public function recordWorkOrderPayment(Request $request, int $workOrder)
    {
        $center = \App\Models\ServiceCenter::where('owner_id', $request->user()->id)->firstOrFail();
        $order = \App\Models\WorkOrder::where('service_center_id', $center->id)->findOrFail($workOrder);
        $data = $request->validate(['amount'=>['required','numeric','gt:0'],'method'=>['required','in:cash,card,transfer,other'],'reference'=>['nullable','string','max:120'],'note'=>['nullable','string','max:2000']]);
        $due = round((float)$order->total_cost-(float)$order->payments()->sum('amount'),2);
        if ((float)$data['amount'] > $due) throw ValidationException::withMessages(['amount'=>'გადასახდელ ნაშთზე მეტი თანხა არ შეიძლება.']);
        \App\Models\ServiceCenterPayment::create($data+['service_center_id'=>$center->id,'work_order_id'=>$order->id,'received_by'=>$request->user()->id,'paid_at'=>now()]);
        return response()->json(['message' => 'გადახდა აღირიცხა.']);
    }

    public function updateWorkOrderStatus(Request $request, int $workOrder)
    {
        $center = \App\Models\ServiceCenter::where('owner_id', $request->user()->id)->firstOrFail();
        $order = \App\Models\WorkOrder::where('service_center_id', $center->id)->findOrFail($workOrder);
        $data = $request->validate(['status'=>['required','in:received,diagnostics,waiting_for_parts,in_repair,completed,handed_over,cancelled']]);
        $changes = ['status'=>$data['status']];
        if ($data['status']==='completed') $changes['completed_at']=now();
        if ($data['status']==='handed_over') $changes['handed_over_at']=now();
        $order->update($changes);
        return response()->json(['message'=>'შეკვეთის სტატუსი განახლდა.']);
    }

    public function workshopDashboard(Request $request)
    {
        $partner = \App\Models\Partner::where('user_id',$request->user()->id)->where('type','workshop')->where('application_status','approved')->where('is_active',true)->firstOrFail();
        $customers = $partner->customers()->with('motorcycles')->latest()->limit(100)->get();
        $motorcycles = \App\Models\Motorcycle::where('partner_id',$partner->id)->with('customer')->latest()->limit(100)->get();
        $records = \App\Models\MotorcycleServiceRecord::whereHas('motorcycle',fn($q)=>$q->where('partner_id',$partner->id))->with('motorcycle')->latest('service_date')->limit(100)->get();
        return response()->json(['partner'=>$partner,'customers'=>$customers,'motorcycles'=>$motorcycles,'service_records'=>$records,'unassigned_motorcycles'=>\App\Models\Motorcycle::where('partner_id',$partner->id)->whereNull('customer_id')->orderBy('brand')->get(['id','title','brand','model','year'])]);
    }

    public function transportPartnerDashboard(Request $request)
    {
        $partner = \App\Models\Partner::where('user_id',$request->user()->id)->where('type','transport')->where('application_status','approved')->where('is_active',true)->firstOrFail();
        $availableRequests = TransportRequest::query()
            ->whereIn('status',['pending','quoted'])
            ->where(fn($q)=>$q->whereNull('partner_id')->orWhere('partner_id',$partner->id))
            ->whereDoesntHave('offers',fn($q)=>$q->where('partner_id',$partner->id))
            ->with('user:id,name')
            ->latest()->limit(100)->get();
        $offers = TransportOffer::where('partner_id',$partner->id)->with(['transportRequest','user'])->latest()->limit(100)->get();
        $orders = TransportRequest::whereIn('status',['accepted','in_transit','delivered'])
            ->whereHas('offers',fn($q)=>$q->where('partner_id',$partner->id)->where('status','accepted'))
            ->with(['offers'=>fn($q)=>$q->where('partner_id',$partner->id)->where('status','accepted'),'user'])
            ->latest()->limit(100)->get();
        return response()->json(['partner'=>$partner,'available_requests'=>$availableRequests,'offers'=>$offers,'orders'=>$orders]);
    }

    public function createTransportOffer(Request $request, int $transportRequest)
    {
        $partner = \App\Models\Partner::where('user_id',$request->user()->id)->where('type','transport')->where('application_status','approved')->where('is_active',true)->firstOrFail();
        $data = $request->validate(['price'=>['required','numeric','gt:0','max:99999999'],'estimated_days'=>['required','integer','min:1','max:365'],'message'=>['nullable','string','max:2000']]);
        $offer = DB::transaction(function () use ($partner,$transportRequest,$data,$request) {
            $order = TransportRequest::whereKey($transportRequest)->lockForUpdate()->firstOrFail();
            if ($order->partner_id !== null && (int)$order->partner_id !== (int)$partner->id) {
                throw ValidationException::withMessages(['transport'=>'ეს მოთხოვნა სხვა სატრანსპორტო კომპანიისთვის არის განკუთვნილი.']);
            }
            if (!in_array($order->status,['pending','quoted'],true)) {
                throw ValidationException::withMessages(['transport'=>'ამ მოთხოვნაზე შეთავაზებების გაგზავნა აღარ შეიძლება.']);
            }
            if (TransportOffer::where('transport_request_id',$order->id)->where('partner_id',$partner->id)->exists()) {
                throw ValidationException::withMessages(['transport'=>'ამ მოთხოვნაზე უკვე გაგზავნილი გაქვს შეთავაზება.']);
            }
            $offer = TransportOffer::create(['transport_request_id'=>$order->id,'partner_id'=>$partner->id,'user_id'=>$request->user()->id]+$data);
            $order->update(['partner_id'=>$partner->id,'status'=>'quoted']);
            return $offer;
        });
        return response()->json(['message'=>'შეთავაზება გაიგზავნა.','offer'=>$offer],201);
    }

    public function updateTransportOrderStatus(Request $request, int $transportRequest)
    {
        $partner = \App\Models\Partner::where('user_id',$request->user()->id)->where('type','transport')->where('application_status','approved')->where('is_active',true)->firstOrFail();
        $order = TransportRequest::where('partner_id',$partner->id)->findOrFail($transportRequest);
        $data = $request->validate(['status'=>['required','in:in_transit,delivered']]);
        $order->update(['status'=>$data['status']]);
        return response()->json(['message'=>'ტრანსპორტირების სტატუსი განახლდა.']);
    }


    public function updateServiceBookingStatus(Request $request, int $booking)
    {
        $center = \App\Models\ServiceCenter::where('owner_id',$request->user()->id)->firstOrFail();
        $item = $center->bookings()->findOrFail($booking);
        $data = $request->validate(['status'=>['required','in:confirmed,declined,cancelled,completed']]);
        $item->update($data);
        return response()->json(['message'=>'ჯავშნის სტატუსი განახლდა.']);
    }

    public function storeWorkshopCustomer(Request $request)
    {
        $partner = \App\Models\Partner::where('user_id',$request->user()->id)->where('type','workshop')->where('application_status','approved')->where('is_active',true)->firstOrFail();
        $data = $request->validate(['name'=>['required','string','max:150'],'phone'=>['required','string','max:30'],'email'=>['nullable','email','max:255'],'address'=>['nullable','string','max:255'],'notes'=>['nullable','string','max:5000']]);
        return response()->json(['message'=>'კლიენტი დაემატა.','customer'=>$partner->customers()->create($data)],201);
    }

    public function storeWorkshopServiceRecord(Request $request, int $customer, int $motorcycle)
    {
        $partner = \App\Models\Partner::where('user_id',$request->user()->id)->where('type','workshop')->where('application_status','approved')->where('is_active',true)->firstOrFail();
        $customerRow = $partner->customers()->findOrFail($customer);
        $bike = \App\Models\Motorcycle::where('partner_id',$partner->id)->where('customer_id',$customerRow->id)->findOrFail($motorcycle);
        $data = $request->validate([
            'service_date'=>['required','date','before_or_equal:today'],'mileage'=>['nullable','integer','min:0'],
            'category'=>['required','string','max:100'],'title'=>['required','string','max:255'],
            'description'=>['nullable','string','max:5000'],'cost'=>['required','numeric','min:0','max:99999999.99'],
            'notes'=>['nullable','string','max:5000'],
        ]);
        return response()->json(['message'=>'მომსახურების ჩანაწერი დაემატა.','record'=>$bike->serviceRecords()->create($data)],201);
    }


    public function registerServiceCenter(Request $request)
    {
        abort_if(\App\Models\ServiceCenter::where('owner_id',$request->user()->id)->exists(), 409, 'სერვისცენტრი უკვე გაქვს.');
        $data = $request->validate(['name'=>['required','string','max:160'],'description'=>['nullable','string','max:3000'],'phone'=>['required','string','max:40'],'email'=>['nullable','email','max:255'],'city'=>['required','string','max:120'],'address'=>['required','string','max:255']]);
        $center = DB::transaction(function () use ($request,$data) {
            $base = Str::slug($data['name']) ?: 'service-center'; $slug = $base;
            while (\App\Models\ServiceCenter::where('slug',$slug)->exists()) $slug = $base.'-'.Str::lower(Str::random(5));
            $center = \App\Models\ServiceCenter::create($data+['owner_id'=>$request->user()->id,'slug'=>$slug,'is_active'=>false,'is_verified'=>false,'approval_status'=>'pending']);
            foreach(range(0,6) as $day) \App\Models\ServiceCenterHour::create(['service_center_id'=>$center->id,'day_of_week'=>$day,'opens_at'=>$day===0?null:'09:00','closes_at'=>$day===0?null:'18:00','is_closed'=>$day===0]);
            return $center;
        });
        return response()->json(['message'=>'სერვისცენტრის განაცხადი გაიგზავნა დასამტკიცებლად.','center'=>$center],201);
    }

    public function updateCenterProfile(Request $request)
    {
        $center = \App\Models\ServiceCenter::where('owner_id',$request->user()->id)->firstOrFail();
        $data = $request->validate(['name'=>['required','string','max:160'],'description'=>['nullable','string','max:3000'],'phone'=>['required','string','max:40'],'email'=>['nullable','email','max:255'],'city'=>['required','string','max:120'],'address'=>['required','string','max:255']]);
        $center->update($data);
        return response()->json(['message'=>'სერვისცენტრის პროფილი განახლდა.','center'=>$center]);
    }

    public function storeCenterService(Request $request)
    {
        $center = \App\Models\ServiceCenter::where('owner_id',$request->user()->id)->firstOrFail();
        $data = $request->validate(['name'=>['required','string','max:160'],'description'=>['nullable','string','max:1500'],'price_type'=>['required','in:fixed,from,quote'],'price'=>['nullable','numeric','min:0','max:99999999'],'duration_minutes'=>['required','integer','min:5','max:1440']]);
        if($data['price_type']!=='quote' && !isset($data['price'])) throw ValidationException::withMessages(['price'=>'ფასი აუცილებელია.']);
        $service = $center->services()->create($data+['price'=>$data['price_type']==='quote'?null:$data['price'],'is_active'=>true]);
        return response()->json(['message'=>'მომსახურება დაემატა.','service'=>$service],201);
    }

    public function updateCenterService(Request $request, int $service)
    {
        $center = \App\Models\ServiceCenter::where('owner_id',$request->user()->id)->firstOrFail();
        $item = $center->services()->findOrFail($service);
        $data = $request->validate(['name'=>['required','string','max:160'],'description'=>['nullable','string','max:1500'],'price_type'=>['required','in:fixed,from,quote'],'price'=>['nullable','numeric','min:0','max:99999999'],'duration_minutes'=>['required','integer','min:5','max:1440'],'is_active'=>['required','boolean']]);
        if($data['price_type']!=='quote' && !isset($data['price'])) throw ValidationException::withMessages(['price'=>'ფასი აუცილებელია.']);
        $item->update($data);
        return response()->json(['message'=>'მომსახურება განახლდა.']);
    }

    public function updateCenterHours(Request $request)
    {
        $center = \App\Models\ServiceCenter::where('owner_id',$request->user()->id)->firstOrFail();
        $data = $request->validate(['hours'=>['required','array'],'hours.*.is_closed'=>['required','boolean'],'hours.*.opens_at'=>['nullable','date_format:H:i'],'hours.*.closes_at'=>['nullable','date_format:H:i']]);
        foreach(range(0,6) as $day){
            $row=$data['hours'][$day]??null; if(!$row) continue;
            if(!$row['is_closed'] && (!$row['opens_at']||!$row['closes_at']||$row['opens_at'] >= $row['closes_at'])) throw ValidationException::withMessages(['hours.'.$day.'.opens_at'=>'სამუშაო დრო სწორად მიუთითე.']);
            $center->hours()->updateOrCreate(['day_of_week'=>$day],['is_closed'=>$row['is_closed'],'opens_at'=>$row['is_closed']?null:$row['opens_at'],'closes_at'=>$row['is_closed']?null:$row['closes_at']]);
        }
        return response()->json(['message'=>'სამუშაო საათები განახლდა.']);
    }

    public function applyWorkshop(Request $request)
    {
        abort_if(\App\Models\Partner::where('user_id',$request->user()->id)->where('type','workshop')->exists(),409,'სახელოსნოს განაცხადი უკვე არსებობს.');
        $data=$request->validate(['name'=>['required','string','max:160'],'phone'=>['required','string','max:40'],'email'=>['nullable','email','max:255'],'address'=>['required','string','max:255'],'description'=>['nullable','string','max:3000']]);
        $slug=Str::slug($data['name']) ?: 'workshop';
        while(\App\Models\Partner::where('slug',$slug)->exists()) $slug=Str::slug($data['name']).'-'.Str::lower(Str::random(5));
        $partner=\App\Models\Partner::create($data+['user_id'=>$request->user()->id,'slug'=>$slug,'type'=>'workshop','is_active'=>false,'application_status'=>'pending']);
        return response()->json(['message'=>'სახელოსნოს განაცხადი გაიგზავნა დასამტკიცებლად.','partner'=>$partner],201);
    }


    public function createServiceWorkOrder(Request $request)
    {
        $center = \App\Models\ServiceCenter::where('owner_id',$request->user()->id)->firstOrFail();
        $data = $request->validate([
            'service_booking_id'=>['required','integer','exists:service_center_bookings,id'],
            'intake_odometer'=>['nullable','integer','min:0'],'intake_condition'=>['nullable','string','max:5000'],
            'diagnosis'=>['nullable','string','max:10000'],'work_description'=>['nullable','string','max:10000'],
            'notes'=>['nullable','string','max:10000'],'labor_cost'=>['nullable','numeric','min:0'],'parts_cost'=>['nullable','numeric','min:0'],
        ]);
        $booking = \App\Models\ServiceBooking::where('service_center_id',$center->id)->findOrFail($data['service_booking_id']);
        abort_if(\App\Models\WorkOrder::where('service_booking_id',$booking->id)->exists(),409,'ამ ჯავშანზე სამუშაო შეკვეთა უკვე არსებობს.');
        $labor=(float)($data['labor_cost']??0);$parts=(float)($data['parts_cost']??0);
        $order=\App\Models\WorkOrder::create([
            'order_number'=>'WO-'.now()->format('Ymd').'-'.strtoupper(bin2hex(random_bytes(4))),
            'service_booking_id'=>$booking->id,'service_center_id'=>$center->id,'status'=>'received',
            'intake_odometer'=>$data['intake_odometer']??null,'intake_condition'=>$data['intake_condition']??null,
            'diagnosis'=>$data['diagnosis']??null,'work_description'=>$data['work_description']??null,
            'notes'=>$data['notes']??null,'labor_cost'=>$labor,'parts_cost'=>$parts,'total_cost'=>$labor+$parts,
        ]);
        return response()->json(['message'=>'სამუშაო შეკვეთა შეიქმნა: '.$order->order_number,'work_order'=>$order],201);
    }


    public function notifications(Request $request)
    {
        $user=$request->user();
        return response()->json([
            'items'=>$user->notifications()->latest()->limit(40)->get()->map(fn($n)=>[
                'id'=>$n->id,'type'=>$n->type,'data'=>$n->data,'read_at'=>$n->read_at,'created_at'=>$n->created_at,
            ]),
            'unread_count'=>$user->unreadNotifications()->count(),
        ]);
    }

    public function markNotificationsRead(Request $request)
    {
        $request->user()->unreadNotifications()->update(['read_at'=>now()]);
        return response()->json(['message'=>'შეტყობინებები წაკითხულად მოინიშნა.']);
    }


    public function updateWorkshopCustomer(Request $request, int $customer)
    {
        $partner=\App\Models\Partner::where('user_id',$request->user()->id)->where('type','workshop')->where('application_status','approved')->where('is_active',true)->firstOrFail();
        $item=$partner->customers()->findOrFail($customer);
        $data=$request->validate(['name'=>['required','string','max:150'],'phone'=>['required','string','max:30'],'email'=>['nullable','email','max:255'],'address'=>['nullable','string','max:255'],'notes'=>['nullable','string','max:5000']]);
        $item->update($data);
        return response()->json(['message'=>'კლიენტის მონაცემები განახლდა.']);
    }

    public function deleteWorkshopCustomer(Request $request, int $customer)
    {
        $partner=\App\Models\Partner::where('user_id',$request->user()->id)->where('type','workshop')->where('application_status','approved')->where('is_active',true)->firstOrFail();
        $item=$partner->customers()->findOrFail($customer);
        $item->delete();
        return response()->json(['message'=>'კლიენტი წაიშალა.']);
    }

    public function attachWorkshopMotorcycle(Request $request, int $customer)
    {
        $partner=\App\Models\Partner::where('user_id',$request->user()->id)->where('type','workshop')->where('application_status','approved')->where('is_active',true)->firstOrFail();
        $customerRow=$partner->customers()->findOrFail($customer);
        $data=$request->validate(['motorcycle_id'=>['required','integer','min:1']]);
        $bike=Motorcycle::where('partner_id',$partner->id)->whereNull('customer_id')->findOrFail($data['motorcycle_id']);
        $bike->update(['customer_id'=>$customerRow->id]);
        return response()->json(['message'=>'მოტოციკლი კლიენტს მიება.']);
    }

    public function detachWorkshopMotorcycle(Request $request, int $customer, int $motorcycle)
    {
        $partner=\App\Models\Partner::where('user_id',$request->user()->id)->where('type','workshop')->where('application_status','approved')->where('is_active',true)->firstOrFail();
        $customerRow=$partner->customers()->findOrFail($customer);
        $bike=Motorcycle::where('partner_id',$partner->id)->where('customer_id',$customerRow->id)->findOrFail($motorcycle);
        $bike->update(['customer_id'=>null]);
        return response()->json(['message'=>'მოტოციკლი კლიენტს მოეხსნა.']);
    }

    public function updateWorkshopServiceRecord(Request $request, int $customer, int $motorcycle, int $record)
    {
        $partner=\App\Models\Partner::where('user_id',$request->user()->id)->where('type','workshop')->where('application_status','approved')->where('is_active',true)->firstOrFail();
        $customerRow=$partner->customers()->findOrFail($customer);
        $bike=Motorcycle::where('partner_id',$partner->id)->where('customer_id',$customerRow->id)->findOrFail($motorcycle);
        $item=$bike->serviceRecords()->findOrFail($record);
        $data=$request->validate(['service_date'=>['required','date','before_or_equal:today'],'mileage'=>['nullable','integer','min:0'],'category'=>['required','string','max:100'],'title'=>['required','string','max:255'],'description'=>['nullable','string','max:5000'],'cost'=>['required','numeric','min:0','max:99999999.99'],'notes'=>['nullable','string','max:5000']]);
        $item->update($data);
        return response()->json(['message'=>'მომსახურების ჩანაწერი განახლდა.']);
    }

    public function deleteWorkshopServiceRecord(Request $request, int $customer, int $motorcycle, int $record)
    {
        $partner=\App\Models\Partner::where('user_id',$request->user()->id)->where('type','workshop')->where('application_status','approved')->where('is_active',true)->firstOrFail();
        $customerRow=$partner->customers()->findOrFail($customer);
        $bike=Motorcycle::where('partner_id',$partner->id)->where('customer_id',$customerRow->id)->findOrFail($motorcycle);
        $bike->serviceRecords()->findOrFail($record)->delete();
        return response()->json(['message'=>'მომსახურების ჩანაწერი წაიშალა.']);
    }

}
