<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = QueryBuilder::for(Project::class)
                                ->allowedIncludes('tasks')
                                ->paginate();

        return new ProjectCollection($projects);
    }

    public function store(storeProjectRequest $request): ProjectResource
    {
        $validated = $request->validated();

        $project = Auth::user()->projects()->create($validated);

        return new ProjectResource($project);
    }

    public function show(Project $project): Project
    {
        return (new ProjectResource($project))->load('tasks');
    }

    public function update(UpdateProjectRequest $request, Project $project): ProjectResource
    {
        $validated = $request->validated();

        $project->update($validated);

        return new ProjectResource($project);
    }

    public function destroy(Project $project): Response
    {
        $project->delete();

        return response()->noContent();
    }

}
