<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function dashboard(){
        $task = Auth::user()
            ->task()
            ->orderBy('task_date')
            ->orderBy('task_time')
            ->get();

        $pending_task = $task->where('status', 'Pending')->count();
        $completed_task = $task->where('status', 'Completed')->count();
        $total_task = $task->count();

        return view('dashboard', compact(
            'task',
            'pending_task',
            'completed_task',
            'total_task'
        ));
    }

    public function create(){
        return view('task.create');
    }

    public function store(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:Study,Work,Personal,Health,Other',
            'priority' => 'required|in:Low,Medium,High',
            'task_date' => 'required|date',
            'task_time' => 'nullable',
        ]);
        Auth::user()->task()->create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'priority' => $request->priority,
            'task_date' => $request->task_date,
            'task_time' => $request->task_time,
            'status' => 'Pending',
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Task added succesfully!');
    }

    public function edit(Task $task){
        if($task->user_id !== Auth::id()){
            abort(403);
        }

        return view('task.edit', compact('task'));
    }

    public function update(Request $request, Task $task){
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:Study,Work,Personal,Health,Other',
            'priority' => 'required|in:Low,Medium,High',
            'task_date' => 'required|date',
            'task_time' => 'nullable',
            'status' => 'required|in:Pending,Completed',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'priority' => $request->priority,
            'task_date' => $request->task_date,
            'task_time' => $request->task_time,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Task updated successfully!');
    }
    
    public function complete(Task $task){
        if($task->user_id !== Auth::id()){
            abort(403);
        }

        $task->update([
            'status' => 'Completed',
        ]);

        return redirect()
            ->route('dashboard')
            ->with('sucess', 'Task completed successfully!');
    }

    public function destroy(Task $task){
        if($task->user_id !== Auth::id()){
            abort(403);
        }

        $task->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Task deleted successfully!');
        }
}
