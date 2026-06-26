<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        $category = $user
            ->category()
            ->withCount('task')
            ->orderBy('name')
            ->get();

        $taskQuery = $user
            ->task()
            ->with('category');

        if ($request->filled('category_id')) {
            $taskQuery->where('category_id', $request->category_id);
        }

        if ($request->filled('priority')) {
            $taskQuery->where('priority', $request->priority);
        }

        if ($request->filled('status')) {
            $taskQuery->where('status', $request->status);
        }

        if ($request->filled('task_date')) {
            $taskQuery->whereDate('task_date', $request->task_date);
        }

        $task = $taskQuery
            ->orderByRaw("
                CASE priority
                    WHEN 'High' THEN 1
                    WHEN 'Medium' THEN 2
                    WHEN 'Low' THEN 3
                    ELSE 4
                END
            ")
            ->orderBy('task_date')
            ->orderBy('task_time')
            ->paginate(6)
            ->withQueryString();

        $total_task = $user
            ->task()
            ->count();

        $pending_task = $user
            ->task()
            ->where('status', 'Pending')
            ->count();

        $completed_task = $user
            ->task()
            ->where('status', 'Completed')
            ->count();

        $overdue_task = $user
            ->task()
            ->where('status', 'Pending')
            ->whereDate('task_date', '<', today())
            ->count();

        return view('dashboard', compact(
            'category',
            'task',
            'pending_task',
            'completed_task',
            'total_task',
            'overdue_task'
        ));
    }

    public function create()
    {
        $categories = Auth::user()
            ->category()
            ->orderBy('name')
            ->get();

        if ($categories->isEmpty()) {
            return redirect()
                ->route('category.create')
                ->with(
                    'info',
                    'Create your first category before adding a task.'
                );
        }

        return view('task.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => [
                'required',
                Rule::exists('category', 'id')
                    ->where('user_id', Auth::id()),
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High',
            ],

            'task_date' => [
                'required',
                'date',
            ],

            'task_time' => [
                'nullable',
                'date_format:H:i',
            ],
        ]);

        Auth::user()->task()->create([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'],
            'task_date' => $validated['task_date'],
            'task_time' => $validated['task_time'] ?? null,
            'status' => 'Pending',
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Task added successfully!');
    }

    public function edit(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Auth::user()
            ->category()
            ->orderBy('name')
            ->get();

        $task->load('category');

        return view('task.edit', compact(
            'task',
            'categories'
        ));
    }

    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'category_id' => [
                'required',
                Rule::exists('category', 'id')
                    ->where('user_id', Auth::id()),
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High',
            ],

            'task_date' => [
                'required',
                'date',
            ],

            'task_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'status' => [
                'required',
                'in:Pending,Completed',
            ],
        ]);

        $task->update([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'],
            'task_date' => $validated['task_date'],
            'task_time' => $validated['task_time'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Task updated successfully!');
    }

    public function complete(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $task->update([
            'status' => 'Completed',
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Task completed successfully!');
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $task->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Task deleted successfully!');
    }
}