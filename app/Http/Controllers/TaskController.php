<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Events\TaskCreated;
use App\Events\TaskUpdated;
use App\Events\TaskDeleted;

class TaskController extends Controller
{
    public function index()
    {
        return Task::orderBy('created_at', 'desc')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
        ]);

        $task = Task::create($validated);

        // 🔴 Emitir evento en tiempo real
        event(new TaskCreated($task));

        return $task; // ✅ solo un return
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|max:255',
            'description' => 'nullable',
            'completed' => 'boolean',
        ]);

        $task->update($validated);

        // 🔴 Emitir evento en tiempo real
        event(new TaskUpdated($task));

        return $task;
    }

    public function destroy(Task $task)
    {
        $task->delete();

        // 🔴 Emitir evento en tiempo real
        event(new TaskDeleted($task));

        return response()->json(['message' => 'Task deleted']);
    }
}
