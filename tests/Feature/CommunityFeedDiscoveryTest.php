<?php

namespace Tests\Feature;

use App\Models\CommunityComment;
use App\Models\CommunityPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityFeedDiscoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_community_feed_can_search_post_text_and_author_name(): void
    {
        $author = User::factory()->create(['name' => 'Rider Searchable']);
        $other = User::factory()->create(['name' => 'Another Rider']);

        CommunityPost::create([
            'user_id' => $author->id,
            'body' => 'Looking for Yamaha R1 track setup advice',
        ]);
        CommunityPost::create([
            'user_id' => $other->id,
            'body' => 'Weekend ride was excellent',
        ]);

        $this->get(route('community.index', ['q' => 'Yamaha R1']))
            ->assertOk()
            ->assertSee('Looking for Yamaha R1 track setup advice')
            ->assertDontSee('Weekend ride was excellent');

        $this->get(route('community.index', ['q' => 'Rider Searchable']))
            ->assertOk()
            ->assertSee('Looking for Yamaha R1 track setup advice')
            ->assertDontSee('Weekend ride was excellent');
    }

    public function test_community_feed_can_filter_my_posts_and_photo_posts(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $mine = CommunityPost::create([
            'user_id' => $owner->id,
            'body' => 'My ride photo post',
            'image_path' => 'community/posts/my-ride.jpg',
        ]);

        CommunityPost::create([
            'user_id' => $owner->id,
            'body' => 'My text-only post',
        ]);

        CommunityPost::create([
            'user_id' => $other->id,
            'body' => 'Other rider photo post',
            'image_path' => 'community/posts/other-ride.jpg',
        ]);

        $this->actingAs($owner)
            ->get(route('community.index', ['filter' => 'mine']))
            ->assertOk()
            ->assertSee('My ride photo post')
            ->assertSee('My text-only post')
            ->assertDontSee('Other rider photo post');

        $this->get(route('community.index', ['filter' => 'photos']))
            ->assertOk()
            ->assertSee('My ride photo post')
            ->assertSee('Other rider photo post')
            ->assertDontSee('My text-only post');
    }

    public function test_community_feed_popular_sort_prioritizes_likes_then_comments(): void
    {
        $owner = User::factory()->create();
        $first = CommunityPost::create(['user_id' => $owner->id, 'body' => 'Popular post']);
        $second = CommunityPost::create(['user_id' => $owner->id, 'body' => 'Less popular post']);
        $third = CommunityPost::create(['user_id' => $owner->id, 'body' => 'Newest but no engagement']);

        $first->likes()->attach(User::factory()->create()->id);
        $first->likes()->attach(User::factory()->create()->id);
        $second->likes()->attach(User::factory()->create()->id);
        CommunityComment::create([
            'community_post_id' => $second->id,
            'user_id' => User::factory()->create()->id,
            'body' => 'Helpful comment',
        ]);

        $response = $this->get(route('community.index', ['sort' => 'popular']))->assertOk();
        $response->assertSeeInOrder([
            'Popular post',
            'Less popular post',
            'Newest but no engagement',
        ]);
    }

    public function test_invalid_feed_filters_are_rejected(): void
    {
        $this->get(route('community.index', ['sort' => 'random']))
            ->assertSessionHasErrors('sort');
    }
}
