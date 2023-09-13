<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\TaskNotFoundException;
use App\Http\Controllers\Controller;
use App\Services\Api\V1\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        return response()->json($this->taskService->getAll());
    }

    public function show(int $taskId)
    {
        return response()->json($this->taskService->getById($taskId));
    }

    public function store(Request $request)
    {
        return response()->json($this->taskService->create($request->all()), 201);
    }

    /**
     * @throws TaskNotFoundException
     */
    public function update(Request $request, int $taskId)
    {
        return response()->json($this->taskService->update($taskId, $request->all()));
    }

    /**
     * @throws TaskNotFoundException
     */
    public function destroy(int $taskId)
    {
        $this->taskService->delete($taskId);
        return response()->json(['success' => 'Task deleted successfully']);
    }
}
