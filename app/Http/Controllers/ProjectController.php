<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::where('is_completed', 'false')
            ->orderBy('created_at', 'desc')
            ->withCount(['tasks' => function ($query) {
                $query->where('is_completed', 'false');
            }])
            ->get();

        return $projects->toJson();
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'description' => 'required'
            ]);
        } catch (ValidationException $e) {
            return response()->json('Validation exception error: ' . $e->getMessage());
        }

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return response()->json('Project created!');
    }

    public function show($id)
    {
        $project = Project::with(['tasks' => function ($query) {
            $query->where('is_completed', false);
        }])->find($id);

        return $project->toJson();
    }

    public function markAsCompleted(Project $project)
    {
        $project->is_completed = true;
        $project->update();

        return response()->json('Project marked as complete!');
    }
}
