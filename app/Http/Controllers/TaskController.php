<?php

namespace App\Http\Controllers;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $tasks)
    {
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Task::class);

        $tasks = $request->user()
            ->tasks()
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    public function trash(Request $request): View
    {
        $this->authorize('viewAny', Task::class);

        $tasks = $request->user()
            ->tasks()
            ->onlyTrashed()
            ->with('category')
            ->latest('deleted_at')
            ->paginate(10);

        return view('tasks.trash', compact('tasks'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Task::class);

        return view('tasks.create', $this->formData($request));
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $this->tasks->create($request->user(), $request->validated());

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function show(Task $task): View
    {
        $this->authorize('view', $task);

        $task->load('category');

        return view('tasks.show', compact('task'));
    }

    public function edit(Request $request, Task $task): View
    {
        $this->authorize('update', $task);

        return view('tasks.edit', array_merge(
            ['task' => $task],
            $this->formData($request)
        ));
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $this->tasks->update($task, $request->validated());

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task moved to trash.');
    }

    public function complete(Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $this->tasks->complete($task);

        return back()->with('success', 'Task marked as completed.');
    }

    public function reopen(Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $this->tasks->reopen($task);

        return back()->with('success', 'Task reopened.');
    }

    public function duplicate(Task $task): RedirectResponse
    {
        $this->authorize('duplicate', $task);

        $copy = $this->tasks->duplicate($task);

        return redirect()
            ->route('tasks.show', $copy)
            ->with('success', 'Task duplicated successfully.');
    }

    public function restore(Task $task): RedirectResponse
    {
        $this->authorize('restore', $task);

        $this->tasks->restore($task);

        return redirect()
            ->route('tasks.trash')
            ->with('success', 'Task restored successfully.');
    }

    public function forceDelete(Task $task): RedirectResponse
    {
        $this->authorize('forceDelete', $task);

        $this->tasks->forceDelete($task);

        return redirect()
            ->route('tasks.trash')
            ->with('success', 'Task permanently deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(Request $request): array
    {
        return [
            'categories' => $request->user()->categories()->orderBy('name')->get(),
            'priorities' => TaskPriority::cases(),
            'statuses' => TaskStatus::cases(),
        ];
    }
}
