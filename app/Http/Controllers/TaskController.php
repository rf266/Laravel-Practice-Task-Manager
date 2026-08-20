<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Show all tasks
    public function index()
    {
        $userid = session('user_id');


        $tasks = Task::where('user_id',$userid)->get(); //creates a query equivalent to showing the tasks for this particular user
        return view('tasks.index', compact('tasks'));
    }

    // Show form to create a new task
    public function create()
    {
        return view('tasks.create');
    }

    // Save the task to the database
    public function store(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $validated['user_id'] = session('user_id'); //gets the userid from session info for db storage

        // Create and save the task
        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    // Show form to edit a task
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    // Update the task in the database
    public function update(Request $request, Task $task)
    {
        // Validate the input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_completed' => 'boolean',
        ]);

        // Update the task
        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    // Delete a task
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }
}