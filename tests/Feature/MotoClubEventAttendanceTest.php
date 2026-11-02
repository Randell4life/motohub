<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MotoClubEventAttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_cancel_event_attendance_and_release_the_reserved_place(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        $clubId = DB::table('moto_clubs')->insertGetId([
            'owner_id' => $owner->id,
            'name' => 'Weekend Riders',
            'slug' => 'weekend-riders',
            'city' => 'Tbilisi',
            'is_public' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('moto_club_members')->insert([
            'club_id' => $clubId,
            'user_id' => $owner->id,
            'role' => 'owner',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('moto_club_members')->insert([
            'club_id' => $clubId,
            'user_id' => $member->id,
            'role' => 'member',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $eventId = DB::table('moto_events')->insertGetId([
            'club_id' => $clubId,
            'created_by' => $owner->id,
            'title' => 'Sunday Ride',
            'location' => 'Tbilisi - Kojori',
            'starts_at' => now()->addDays(2),
            'capacity' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('moto_event_attendees')->insert([
            'event_id' => $eventId,
            'user_id' => $member->id,
            'status' => 'going',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($member)
            ->delete(route('clubs.events.leave', $eventId))
            ->assertRedirect();

        $this->assertDatabaseHas('moto_event_attendees', [
            'event_id' => $eventId,
            'user_id' => $member->id,
            'status' => 'cancelled',
        ]);

        $this->assertSame(0, DB::table('moto_event_attendees')
            ->where('event_id', $eventId)
            ->where('status', 'going')
            ->count());
    }

    public function test_member_cannot_cancel_another_users_event_attendance(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $attendee = User::factory()->create();

        $clubId = DB::table('moto_clubs')->insertGetId([
            'owner_id' => $owner->id,
            'name' => 'Track Riders',
            'slug' => 'track-riders',
            'is_public' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ([$owner, $member, $attendee] as $user) {
            DB::table('moto_club_members')->insert([
                'club_id' => $clubId,
                'user_id' => $user->id,
                'role' => $user->id === $owner->id ? 'owner' : 'member',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $eventId = DB::table('moto_events')->insertGetId([
            'club_id' => $clubId,
            'created_by' => $owner->id,
            'title' => 'Track Day',
            'location' => 'Rustavi',
            'starts_at' => now()->addDays(3),
            'capacity' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('moto_event_attendees')->insert([
            'event_id' => $eventId,
            'user_id' => $attendee->id,
            'status' => 'going',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($member)
            ->delete(route('clubs.events.leave', $eventId))
            ->assertNotFound();

        $this->assertDatabaseHas('moto_event_attendees', [
            'event_id' => $eventId,
            'user_id' => $attendee->id,
            'status' => 'going',
        ]);
    }
}
