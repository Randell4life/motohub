<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Motorcycle;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $conversations = $user->conversations()
            ->with(['participants', 'motorcycle', 'latestMessage'])
            ->withCount(['messages as unread_count' => fn ($query) => $query
                ->whereNull('read_at')
                ->where('sender_id', '!=', $user->id)])
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->paginate(15);

        return view('messages.index', compact('conversations'));
    }

    public function show(Request $request, Conversation $conversation): View
    {
        $this->authorizeParticipant($request, $conversation);
        $user = $request->user();

        $conversation->load(['participants', 'motorcycle']);
        $messages = $conversation->messages()->with('sender')->oldest()->paginate(60);

        $conversation->messages()
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $conversation->participants()->updateExistingPivot($user->id, [
            'last_read_at' => now(),
        ]);

        $sidebarConversations = $user->conversations()
            ->with(['participants', 'motorcycle', 'latestMessage'])
            ->withCount(['messages as unread_count' => fn ($query) => $query
                ->whereNull('read_at')
                ->where('sender_id', '!=', $user->id)])
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get();

        $unreadTotal = $user->conversations()
            ->withCount(['messages as unread_count' => fn ($query) => $query
                ->whereNull('read_at')
                ->where('sender_id', '!=', $user->id)])
            ->get()
            ->sum('unread_count');

        return view('messages.show', compact('conversation', 'messages', 'sidebarConversations', 'unreadTotal'));
    }

    public function start(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'recipient_id' => ['required', 'integer', 'exists:users,id', 'different:sender_id'],
            'body' => ['required', 'string', 'min:1', 'max:5000'],
            'motorcycle_id' => ['nullable', 'integer', 'exists:motorcycles,id'],
            'subject' => ['nullable', 'string', 'max:160'],
        ]);

        $sender = $request->user();
        abort_if((int) $validated['recipient_id'] === $sender->id, 422, 'საკუთარ თავს შეტყობინებას ვერ გაუგზავნით.');

        $recipient = User::findOrFail($validated['recipient_id']);
        abort_if($recipient->id === $sender->id, 422);

        $motorcycle = null;
        if (! empty($validated['motorcycle_id'])) {
            $motorcycle = Motorcycle::query()
                ->where('status', 'published')
                ->findOrFail($validated['motorcycle_id']);
            abort_if($motorcycle->user_id !== $recipient->id, 422, 'მოტოციკლის გამყიდველი არ ემთხვევა მიმღებს.');
        }

        $conversation = Conversation::create([
            'created_by_user_id' => $sender->id,
            'motorcycle_id' => $motorcycle?->id,
            'subject' => $validated['subject'] ?? ($motorcycle ? 'მოტოციკლი: '.$motorcycle->title : 'პირადი მიმოწერა'),
            'last_message_at' => now(),
        ]);

        $conversation->participants()->attach([
            $sender->id => ['last_read_at' => now()],
            $recipient->id => ['last_read_at' => null],
        ]);

        $conversation->messages()->create([
            'sender_id' => $sender->id,
            'body' => trim($validated['body']),
        ]);

        return redirect()->route('messages.show', $conversation)
            ->with('success', 'შეტყობინება გაიგზავნა.');
    }

    public function store(Request $request, Conversation $conversation): RedirectResponse
    {
        $this->authorizeParticipant($request, $conversation);

        $validated = $request->validate([
            'body' => ['required', 'string', 'min:1', 'max:5000'],
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => $request->user()->id,
            'body' => trim($validated['body']),
        ]);

        $conversation->forceFill(['last_message_at' => $message->created_at])->save();
        $conversation->participants()->updateExistingPivot($request->user()->id, [
            'last_read_at' => now(),
        ]);

        return redirect()->to(route('messages.show', $conversation).'#message-'.$message->id);
    }

    private function authorizeParticipant(Request $request, Conversation $conversation): void
    {
        abort_unless(
            $conversation->participants()->whereKey($request->user()->id)->exists(),
            404
        );
    }
}
