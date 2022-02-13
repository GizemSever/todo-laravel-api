<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Board\BoardRequest;
use App\Http\Resources\BoardResource;
use App\Models\Board;
use App\Models\Project;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Project $project
     * @return Response
     */
    public function index(Project $project)
    {
        $boards = $project->boards()->get();
        return response()->success($boards->collect(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Project $project
     * @param BoardRequest $request
     * @return Response
     */
    public function store(Project $project, BoardRequest $request)
    {
        if ($this->boardTypeNotExist($project, $request->type)) {
            $board = new Board();
            $board->project_id = $project->id;
            $board->type = $request->type;
            $board->name = $request->name;
            $board->save();

            return response(new BoardResource($board), Response::HTTP_CREATED);
        }
    }

    /**
     * Check default board type is already exist
     *
     * @param Project $project
     * @param string|null $type
     * @return bool
     * @throws ConflictHttpException
     */
    private function boardTypeNotExist(Project $project, ?string $type)
    {
        $board = $project->boards()->where('type', $type)->first();
        if (($type) && ($board)) {
            throw new ConflictHttpException(__('api.resource_already_exist'));
        }
        return true;
    }

    /**
     * Display the specified resource.
     *
     * @param Project $project
     * @param int $id
     * @return Response
     */
    public function show(Project $project, $id)
    {
        $board = $project->boards()->findOrFail($id);
        return response()->success(new BoardResource($board), Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Project $project
     * @param BoardRequest $request
     * @param int $id
     * @return Response
     */
    public function update(Project $project, BoardRequest $request, $id)
    {
        if ($this->boardTypeNotExist($project, $request->type)) {
            $board = $project->boards()->findOrFail($id);
            $board->name = $request->name;
            $board->type = $request->type;
            $board->save();
            return response()->success(new BoardResource($board), Response::HTTP_OK);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @param int $id
     * @return Response
     */
    public function destroy(Project $project, $id)
    {
        $board = $project->boards()->findOrFail($id);
        $board->delete();
        return response()->success(null, Response::HTTP_NO_CONTENT);
    }
}
