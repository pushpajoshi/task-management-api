<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class TaskTest extends TestCase
{
    /**
     * A basic feature test example.
     */

public function testTaskCreatedByUser()
{
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/tasks', [
            'title' => 'Test Task',
            'description' => 'desc',
            'status' => 'todo',                  // required
            'due_date' => now()->format('Y-m-d'), // required
            'user_id' => $user->id               // required for non-admin
        ]);

    $response->assertStatus(201)
             ->assertJsonPath('status', 'success');

    $this->assertDatabaseHas('tasks', [
        'title' => 'Test Task',
        'user_id' => $user->id,
        'status' => 'todo'
    ]);
}

}
