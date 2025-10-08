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
    $user = User::where('role','admin')->first();

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/tasks', [
            'title' => 'Test Task',
            'description' => 'desc',
            'status' => 'todo',                  
            'due_date' => now()->format('Y-m-d'), 
            'user_id' => $user->id       
        ]);

    $response->assertStatus(201)
             ->assertJsonPath('status',201);

    $this->assertDatabaseHas('tasks', [
        'title' => 'Test Task',
        'user_id' => $user->id,
        'status' => 'todo'
    ]);
}

}
