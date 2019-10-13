<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'title' => 'required'
            ]);
        } catch (ValidationException $e) {
            return response()->json('Validation exception occured: ' . $e->getMessage());
        }

        $task = Task::create([
            'title' => $request->title,
            'project_id' => $request->project_id,
        ]);

        return $task->toJson();
    }

    public function markAsCompleted(Task $task)
    {
        $task->is_completed = true;
        $task->update();

        return response()->json('Task updated!');
    }
}
