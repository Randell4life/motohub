<?php

namespace App\Http\Controllers;

use App\Models\CommunityPost;
use App\Models\Motorcycle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommunityController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()?->id ?? 0;

        $posts = CommunityPost::query()
            ->with(['user', 'motorcycle', 'comments.user'])
            ->withCount(['comments', 'likes'])
            ->withExists([
                'likes as liked_by_me' => fn ($query) => $query->where('users.id', $userId),
            ])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $myMotorcycles = $request->user()
            ? Motorcycle::query()
                ->where('user_id', $request->user()->id)
                ->where('status', 'published')
                ->latest()
                ->get()
            : collect();

        return view('community.index', compact('posts', 'myMotorcycles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'min:2', 'max:3000'],
            'motorcycle_id' => ['nullable', 'integer', 'exists:motorcycles,id'],
        ]);

        $motorcycleId = null;

        if (! empty($validated['motorcycle_id'])) {
            $motorcycle = Motorcycle::query()
                ->where('user_id', $request->user()->id)
                ->where('status', 'published')
                ->findOrFail($validated['motorcycle_id']);

            $motorcycleId = $motorcycle->id;
        }

        CommunityPost::create([
            'user_id' => $request->user()->id,
            'motorcycle_id' => $motorcycleId,
            'body' => trim($validated['body']),
        ]);

        return redirect()->route('community.index')
            ->with('success', 'პოსტი წარმატებით გამოქვეყნდა.');
    }

    public function comment(Request $request, CommunityPost $post): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'min:1', 'max:1000'],
        ]);

        $post->comments()->create([
            'user_id' => $request->user()->id,
            'body' => trim($validated['body']),
        ]);

        return redirect()->to(route('community.index') . '#post-' . $post->id)
            ->with('success', 'კომენტარი დაემატა.');
    }

    public function toggleLike(Request $request, CommunityPost $post): RedirectResponse
    {
        $post->likes()->toggle($request->user()->id);

        return redirect()->to(route('community.index') . '#post-' . $post->id);
    }

    public function destroy(Request $request, CommunityPost $post): RedirectResponse
    {
        abort_unless($post->user_id === $request->user()->id, 403);

        $post->delete();

        return redirect()->route('community.index')
            ->with('success', 'პოსტი წაიშალა.');
    }
}
