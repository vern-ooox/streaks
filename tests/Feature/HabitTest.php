<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HabitTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_habit(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/habits', [
            'name' => 'Read',
            'description' => 'Read 10 pages',
            'color' => '#112233',
        ]);

        $response->assertRedirect('/habits');
        $this->assertDatabaseHas('habits', ['name' => 'Read', 'user_id' => $user->id]);
    }

    public function test_name_is_required(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/habits', ['name' => '', 'color' => '#112233'])
            ->assertSessionHasErrors('name');
    }

    public function test_user_cannot_edit_another_users_habit(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $habit = $owner->habits()->create(['name' => 'Mine', 'color' => '#112233']);

        $this->actingAs($other)->get("/habits/{$habit->id}/edit")->assertForbidden();
        $this->actingAs($other)->delete("/habits/{$habit->id}")->assertForbidden();
        $this->assertDatabaseHas('habits', ['id' => $habit->id]);
    }

    public function test_check_in_only_counts_once_per_day(): void
    {
        $user = User::factory()->create();
        $habit = $user->habits()->create(['name' => 'Walk', 'color' => '#112233']);

        $this->actingAs($user)->post("/habits/{$habit->id}/check-in");
        $this->actingAs($user)->post("/habits/{$habit->id}/check-in");

        $this->assertSame(1, $habit->logs()->count());
    }

    public function test_streak_counts_consecutive_days(): void
    {
        $user = User::factory()->create();
        $habit = $user->habits()->create(['name' => 'Code', 'color' => '#112233']);

        $habit->logs()->create(['completed_on' => today()]);
        $habit->logs()->create(['completed_on' => today()->subDay()]);
        $habit->logs()->create(['completed_on' => today()->subDays(2)]);
        $habit->logs()->create(['completed_on' => today()->subDays(5)]);

        $this->assertSame(3, $habit->currentStreak());
        $this->assertSame(3, $habit->longestStreak());
    }
}