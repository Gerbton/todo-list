<?php

namespace App\Exceptions;

use Exception;

class TaskNotFoundException extends Exception
{
    public function render()
    {
        return response()->json(['error' => 'Task not found'], 404);
    }
}
