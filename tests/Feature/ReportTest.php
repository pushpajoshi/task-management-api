<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ReportTest extends TestCase
{
    /**
     * A basic feature test example.
     */
   public function testReportsTasksSummaryCached()
    {
        $user = User::factory()->create();
        $this->actingAs($user,'sanctum')
            ->getJson('/api/reports/tasks-summary')
            ->assertStatus(200)
            ->assertJsonPath('status','success');
    }
}
