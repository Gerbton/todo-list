<?php

namespace App\Services\Api\V1;

use App\Exceptions\TaskNotFoundException;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    public function getAll(): Collection
    {
        $user = auth()->user();

        return Task::all()->where('user_id', $user->id);
    }

    public function getById(int $id): Task
    {
        return Task::findOrFail($id);
    }

    public function create(array $data): Task
    {
        $user = auth()->user();
        $data['user_id'] = $user->id;

        return Task::create($data);
    }

    /**
     * @throws TaskNotFoundException
     */
    public function update(int $id, array $data): Task
    {
        $task = Task::findOrFail($id);
        $task->update($data);

        return $task;
    }

    /**
     * @throws TaskNotFoundException
     */
    public function delete(int $id): void
    {
        $task = Task::findOrFail($id);
        $task->delete();
    }
}
