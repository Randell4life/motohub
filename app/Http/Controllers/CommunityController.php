<?php

namespace App\Http\Controllers;

use App\Models\CommunityPost;
use App\Models\Motorcycle;
use App\Notifications\CommunityActivityNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CommunityController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()?->id ?? 0;
        $posts = CommunityPost::query()
            ->with(['user', 'motorcycle', 'comments.user'])
            ->withCount(['comments', 'likes'])
            ->withExists(['likes as liked_by_me' => fn ($query) => $query->where('users.id', $userId)])
            ->latest()->paginate(12)->withQueryString();

        $myMotorcycles = $request->user()
            ? Motorcycle::where('user_id', $userId)->where('status', 'published')->latest()->get()
            : collect();

        $notifications = $request->user()?->notifications()->latest()->limit(8)->get() ?? collect();
        $unreadNotifications = $request->user()?->unreadNotifications()->count() ?? 0;

        return view('community.index', compact('posts', 'myMotorcycles', 'notifications', 'unreadNotifications'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'min:2', 'max:3000'],
            'motorcycle_id' => ['nullable', 'integer', 'exists:motorcycles,id'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $motorcycleId = null;
        if (!empty($validated['motorcycle_id'])) {
            $motorcycleId = Motorcycle::where('user_id', $request->user()->id)
                ->where('status', 'published')->findOrFail($validated['motorcycle_id'])->id;
        }

        $imagePath = $request->file('image')?->store('community/posts', 'public');
        CommunityPost::create([
            'user_id' => $request->user()->id,
            'motorcycle_id' => $motorcycleId,
            'body' => trim($validated['body']),
            'image_path' => $imagePath,
        ]);

        return redirect()->route('community.index')->with('success', 'პოსტი წარმატებით გამოქვეყნდა.');
    }

    public function uploadAvatar(Request $request): RedirectResponse
    {
        $request->validate(['avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']]);
        $user = $request->user();
        if ($user->avatar_path) Storage::disk('public')->delete($user->avatar_path);
        $user->forceFill(['avatar_path' => $request->file('avatar')->store('community/avatars', 'public')])->save();

        return back()->with('success', 'პროფილის ფოტო განახლდა.');
    }

    public function comment(Request $request, CommunityPost $post): RedirectResponse
    {
        $validated = $request->validate(['body' => ['required', 'string', 'min:1', 'max:1000']]);
        $post->comments()->create(['user_id' => $request->user()->id, 'body' => trim($validated['body'])]);

        if ($post->user_id !== $request->user()->id) {
            $post->user->notify(new CommunityActivityNotification(
                $request->user()->name . ' კომენტარი დატოვა შენს პოსტზე.',
                route('community.index') . '#post-' . $post->id,
                $request->user()->name,
                'comment'
            ));
        }

        return redirect()->to(route('community.index') . '#post-' . $post->id)->with('success', 'კომენტარი დაემატა.');
    }

    public function toggleLike(Request $request, CommunityPost $post): RedirectResponse
    {
        $changes = $post->likes()->toggle($request->user()->id);
        if (!empty($changes['attached']) && $post->user_id !== $request->user()->id) {
            $post->user->notify(new CommunityActivityNotification(
                $request->user()->name . ' მოიწონა შენი პოსტი.',
                route('community.index') . '#post-' . $post->id,
                $request->user()->name,
                'like'
            ));
        }
        return redirect()->to(route('community.index') . '#post-' . $post->id);
    }

    public function markNotificationsRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'შეტყობინებები წაკითხულად მოინიშნა.');
    }

    public function destroy(Request $request, CommunityPost $post): RedirectResponse
    {
        abort_unless($post->user_id === $request->user()->id, 403);
        if ($post->image_path) Storage::disk('public')->delete($post->image_path);
        $post->delete();
        return redirect()->route('community.index')->with('success', 'პოსტი წაიშალა.');
    }
}
