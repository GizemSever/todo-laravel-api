<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Project\ProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $projects = new ProjectCollection($this->getUserProject()->get());
        return response()->success($projects, Response::HTTP_OK);
    }

    /**
     * @return Project|Builder
     */
    private function getUserProject()
    {
        $userId = Auth::user()->id;
        return Project::where('user_id', $userId);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProjectRequest $storeProjectRequest
     * @return Response
     */
    public function store(ProjectRequest $storeProjectRequest)
    {
        $project = new Project($storeProjectRequest->validated());
        $project->user_id = $storeProjectRequest->user()->id;
        $project->save();

        return response()->success(new ProjectResource($project), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $project = $this->getUserProject()->findOrFail($id);
        return response()->success(new ProjectResource($project), Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProjectRequest $request
     * @param int $id
     * @return Response
     */
    public function update(ProjectRequest $request, $id)
    {
        $project = $this->getUserProject()->findOrFail($id);
        $project->name = $request->name;
        $project->save();
        return response()->success(new ProjectResource($project), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $project = $this->getUserProject()->findOrFail($id);
        $project->delete();
        return response()->success(null, Response::HTTP_NO_CONTENT);
    }
}
