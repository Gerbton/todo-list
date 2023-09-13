<?php

namespace Tests\Unit\Services\Api\V1;

use App\Exceptions\TaskNotFoundException;
use App\Models\Task;
use App\Models\User;
use App\Services\Api\V1\TaskService;
use Database\Seeders\TaskSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TaskService $taskService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->taskService = app(TaskService::class);
    }

    public function testGetAllTasks(): void
    {
        $user = User::factory()->create();
        Task::factory(3)->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $tasks = $this->taskService->getAll();

        $this->assertCount(3, $tasks);
    }

    public function testGetTaskById(): void
    {
        $this->seed([
            UserSeeder::class,
            TaskSeeder::class,
        ]);

        $taskId = 1;
        $retrievedTask = $this->taskService->getById($taskId);

        $this->assertEquals($taskId, $retrievedTask->id);
    }

    public function testCreateTask(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $taskData = [
            'title' => fake()->title(),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['pending', 'done']),
            'user_id' => $user->id,
        ];

        $createdTask = $this->taskService->create($taskData);

        $this->assertEquals($taskData['title'], $createdTask->title);
        $this->assertEquals($taskData['description'], $createdTask->description);
        $this->assertEquals($taskData['status'], $createdTask->status);
        $this->assertEquals($taskData['user_id'], $createdTask->user_id);
    }

    public function testUpdateTask(): void
    {
        $this->seed([
            UserSeeder::class
        ]);

        $task = Task::factory()->create();

        $updatedData = [
            'title' => fake()->title(),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['pending', 'done']),
        ];

        $updatedTask = $this->taskService->update($task->id, $updatedData);

        $this->assertEquals($updatedData['title'], $updatedTask->title);
        $this->assertEquals($updatedData['description'], $updatedTask->description);
        $this->assertEquals($updatedData['status'], $updatedTask->status);
    }

    public function testDeleteTask(): void
    {
        $this->seed([
            UserSeeder::class
        ]);

        $task = Task::factory()->create();

        $this->taskService->delete($task->id);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function testGetTaskByIdThrowsExceptionWhenTaskNotFound(): void
    {
        $this->expectException(TaskNotFoundException::class);
        $this->taskService->getById(99999);
    }

    public function testUpdateTaskThrowsExceptionWhenTaskNotFound(): void
    {
        $this->expectException(TaskNotFoundException::class);
        $this->taskService->update(99999, []);
    }

    public function testDeleteTaskThrowsExceptionWhenTaskNotFound()
    {
        $this->expectException(TaskNotFoundException::class);
        $this->taskService->delete(99999);
    }
}
