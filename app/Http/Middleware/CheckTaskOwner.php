<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTaskOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $taskId = $request->route('task');
        $task = $request->user()->tasks()->find($taskId);

        if (!$task) {
            return response()->json([
                'error' => 'Task not found or you are not the owner of this task'
            ], 403);
        }

        return $next($request);
    }
}
