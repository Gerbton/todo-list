<?php

namespace Tests\Feature\Controllers\Api\V1;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected const API_URL = '/api/v1/tasks';

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        Task::factory(5)->create(['user_id' => $this->user->id]);
    }

    public function testIndex(): void
    {
        $response = $this->getJson(self::API_URL);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'title',
                'description',
                'status',
                'user_id',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    public function testShow()
    {
        $response = $this->getJson(self::API_URL.'/'. 1);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'title',
            'description',
            'status',
            'user_id',
            'created_at',
            'updated_at',
        ]);
    }

    public function testStore()
    {
        $taskData = [
            'title' => fake()->title(),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['pending', 'done']),
            'user_id' => $this->user->id,
        ];

        $response = $this->postJson(self::API_URL, $taskData);

        $response->assertStatus(201);
        $response->assertJson($taskData);
    }

    public function testUpdate()
    {
        $taskData = [
            'title' => fake()->title(),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['pending', 'done']),
        ];

        $response = $this->putJson(self::API_URL.'/2/edit', $taskData);

        $response->assertStatus(200);
        $response->assertJson($taskData);
    }

    public function testDestroy()
    {
        $response = $this->deleteJson(self::API_URL.'/3');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => 'Task deleted successfully',
        ]);
    }
}
