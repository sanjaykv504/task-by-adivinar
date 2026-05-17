<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Web & API: Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Task::class);

        $filters = $request->only(['search', 'status', 'priority']);
        $tasks = $this->taskService->all($filters);

        if ($request->wantsJson() || $request->is('api/*')) {
            return TaskResource::collection($tasks);
        }

        $analytics = $this->taskService->getAnalytics();

        return view('tasks.index', compact('tasks', 'analytics'));
    }

    /**
     * Web: Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Task::class);
        $analytics = $this->taskService->getAnalytics();
        return view('tasks.create', compact('analytics'));
    }

    /**
     * Web & API: Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        Gate::authorize('create', Task::class);

        $task = $this->taskService->store($request->validated());

        if ($request->wantsJson() || $request->is('api/*')) {
            return new TaskResource($task);
        }

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    /**
     * Web & API: Display the specified resource.
     */
    public function show(Request $request, int $id)
    {
        $task = $this->taskService->find($id);
        Gate::authorize('view', $task);

        if ($request->wantsJson() || $request->is('api/*')) {
            return new TaskResource($task);
        }

        $analytics = $this->taskService->getAnalytics();
        return view('tasks.show', compact('task', 'analytics'));
    }

    /**
     * Web: Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $task = $this->taskService->find($id);
        Gate::authorize('update', $task);

        $analytics = $this->taskService->getAnalytics();
        return view('tasks.edit', compact('task', 'analytics'));
    }

    /**
     * Web & API: Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, int $id)
    {
        $task = $this->taskService->find($id);
        Gate::authorize('update', $task);

        $updatedTask = $this->taskService->update($id, $request->validated());

        if ($request->wantsJson() || $request->is('api/*')) {
            return new TaskResource($updatedTask);
        }

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    /**
     * Web & API: Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id)
    {
        $task = $this->taskService->find($id);
        Gate::authorize('delete', $task);

        $this->taskService->delete($id);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(null, 204);
        }

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    /**
     * API: Update status directly
     */
    public function updateStatus(Request $request, int $id)
    {
        $request->validate(['status' => 'required|in:pending,in_progress,completed']);
        $task = $this->taskService->find($id);
        Gate::authorize('update', $task);

        $updatedTask = $this->taskService->update($id, ['status' => $request->status]);

        if ($request->wantsJson() || $request->is('api/*')) {
            return new TaskResource($updatedTask);
        }

        return back()->with('success', 'Status updated');
    }

    /**
     * API: Get or refresh AI Summary
     */
    public function aiSummary(Request $request, int $id)
    {
        $task = $this->taskService->find($id);
        Gate::authorize('view', $task);

        $updatedTask = $this->taskService->refreshAiSummary($id);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'ai_summary' => $updatedTask->ai_summary,
                'ai_priority' => $updatedTask->ai_priority,
            ]);
        }

        return back()->with('success', 'AI Summary Refreshed');
    }
}
