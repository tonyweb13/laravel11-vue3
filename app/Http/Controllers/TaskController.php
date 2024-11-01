<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::all();
        return response()->json($tasks, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request): JsonResponse
    {
        try {
            $task = Task::create($request->validated());
            return response()->json(['message' => 'Task created successfully', 'task' => $task], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Task creation failed', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $task = Task::find($id);
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task): JsonResponse
    {
        try {
            $task->update($request->validated());
            return response()->json(['message' => 'Task updated successfully', 'task' => $task], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Task update failed', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): JsonResponse
    {
        try {
            $task->delete();
            return response()->json(['message' => 'Task deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Task deletion failed', 'message' => $e->getMessage()], 500);
        }
    }
}
