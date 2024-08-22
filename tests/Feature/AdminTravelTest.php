<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Travel;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTravelTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_cannot_access_adding_travel(): void
    {
        $response = $this->postJson('/api/v1/admin/travels');

        $response->assertStatus(401);
    }

    public function test_non_admin_user_cannot_access_adding_travel(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'user')->value('id'));
        $response = $this->actingAs($user)->postJson('/api/v1/admin/travels');
        $response->assertStatus(403);
    }

    public function test_saves_travel_successfully_with_valid_data(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'admin')->value('id'));
        $response = $this->actingAs($user)->postJson('/api/v1/admin/travels', [
            'name' => 'Travel Name',
        ]);
        $response->assertStatus(422);
        $response = $this->actingAs($user)->postJson('/api/v1/admin/travels', [
            'name' => 'Travel Name',
            'is_public' => true,
            'description' => 'Travel Description',
            'number_of_days' => '2',
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'Travel Name']);
    }

    public function test_updates_travel_successfully_with_valid_data(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'user')->value('id'));
        $travel = Travel::factory()->create();
        $response = $this->actingAs($user)->putJson('/api/v1/admin/travels/'.$travel->id, [
            'name' => 'Travel Name',
        ]);
        $response->assertStatus(422);
        $response = $this->actingAs($user)->putJson('/api/v1/admin/travels/'.$travel->id, [
            'name' => 'Travel Name updated',
            'is_public' => 1,
            'description' => 'Travel Description',
            'number_of_days' => 5,
        ]);

        $response->assertStatus(200);

        $response = $this->get('/api/v1/travels');
        $response->assertJsonFragment(['name' => 'Travel Name updated']);
    }
}
