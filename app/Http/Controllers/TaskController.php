<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use Inertia\Inertia;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks for a specific project.
     */
    public function index(Request $request)
    {
        $projects = Project::all();
        $selectedProject = $request->get('project_id');
        // If no project is selected, return all tasks
        if (!$selectedProject) {
            $tasks = Task::orderBy('priority')->get();
        } else {
            $tasks = Task::where('project_id', $selectedProject)->orderBy('priority')->get();
        }

        return Inertia::render('Tasks/Index', [
            'projects' => $projects,
            'project_id' => $selectedProject,
            'tasks' => $tasks
        ]);
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        // Sleep to simulate a delay for testing purposes
        sleep(0.5);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Ensure project_id is either provided or new_project_name is provided
            'project_id' => 'required_without:new_project_name|nullable|exists:projects,id',
            // Ensure new_project_name is provided if project_id is not
            'new_project_name' => 'required_without:project_id|nullable|string|max:255',
        ], [
            'project_id.required_without' => 'Please select a project.',
            'new_project_name.required_without' => 'Please enter a new project name.',
        ]);

        // Create new project if needed
        if (!$validated['project_id'] && !empty($validated['new_project_name'])) {
            // Check if a project with the same name already exists
            $existingProject = Project::where('name', $validated['new_project_name'])->first();
            if (!$existingProject) {
                $project = Project::create(['name' => $validated['new_project_name']]);
            } else {
                $project = $existingProject;
            }
            $validated['project_id'] = $project->id;
        }

        // Determine next priority (1 is highest)
        $maxPriority = Task::max('priority') ?? 0;
        $nextPriority = $maxPriority + 1;

        // Create the task
        Task::create([
            'name' => $validated['name'],
            'project_id' => $validated['project_id'],
            'priority' => $nextPriority,
        ]);

        return redirect()->back()->with('success', 'Task added.');
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate(['name' => 'required']);
        $task->update(['name' => $request->name]);
        return back();
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return back();
    }

    /**
     * Reorder tasks based on the provided order.
     */
    public function reorder(Request $request)
    {
        foreach ($request->order as $index => $id) {
            Task::where('id', $id)->update(['priority' => $index + 1]);
        }
        return redirect()->back();
        //return response()->json(['status' => 'ok']);
    }
}
