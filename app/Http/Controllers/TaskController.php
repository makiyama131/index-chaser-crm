<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity; // Add this


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate the input
        $request->validate([
            'title' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
        ]);

        // 2. Create and save the task
        Task::create([
            'title' => $request->title,
            'customer_id' => $request->customer_id,
            'user_id' => Auth::id(), // Assign to the logged-in user
            'due_date' => $request->due_date, // Add this line

        ]);

        // 3. Redirect back to the customer's detail page
        return redirect()->route('customers.show', $request->customer_id)
            ->with('success', 'Task added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function showCompleteForm(Task $task)
    {
        return view('tasks.complete', compact('task'));
    }


    public function complete(Request $request, Task $task)
    {
        // Authorization
        if (Auth::id() !== $task->user_id) {
            abort(403);
        }

        // 1. Validate the memo
        $request->validate([
            'note' => 'required|string',
        ]);

        // 2. Create a new Activity Log
        Activity::create([
            'customer_id' => $task->customer_id,
            'user_id' => Auth::id(),
            'type' => 'タスク完了', // Set a specific type
            'note' => $request->note,
            'task_id' => $task->id, // ▼▼▼ ADD THIS LINE ▼▼▼

        ]);

        // 3. Mark the task as complete
        $task->status = '完了';
        $task->save();

        // 4. Redirect to the customer's page
        return redirect()->route('customers.show', $task->customer_id)->with('success', 'Task completed and activity logged!');
    }
}
