<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\BoardType;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Task\StoreTaskRequest;
use App\Http\Requests\V1\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Board;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Project $project
     * @param Board $board
     * @return Response
     */
    public function index(Project $project, Board $board)
    {
        $tasks = $board->tasks()->get();
        return response()->success($tasks->collect(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Project $project
     * @param Board $board
     * @param StoreTaskRequest $request
     * @return Response
     */
    public function store(Project $project, Board $board, StoreTaskRequest $request)
    {
        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description ?? '';
        $task->board_id = $board->id;
        $task->completed_at = $this->getCompletedAt($board);
        $task->save();

        return response()->success(new TaskResource($task), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Project $project
     * @param Board $board
     * @param int $id
     * @return Response
     */
    public function show(Project $project, Board $board, $id)
    {
        $task = $board->tasks()->findOrFail($id);

        return response()->success(new TaskResource($task), Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Project $project
     * @param Board $board
     * @param UpdateTaskRequest $request
     * @param int $id
     * @return Response
     */
    public function update(Project $project, Board $board, UpdateTaskRequest $request, $id)
    {
        $task = $board->tasks()->findOrFail($id);
        $task->title = $request->title;
        $task->description = $request->description ?? '';
        $task->board_id = $request->board_id;
        $task->completed_at = $this->getCompletedAt($board);
        $task->save();

        return response()->success(new TaskResource($task), Response::HTTP_OK);
    }

    /**
     * If board type is done, set completed_at value
     *
     * @param Board $board
     * @return string|null
     */
    private function getCompletedAt(Board $board)
    {
        if ($board->type === BoardType::DONE) {
            return Carbon::now()->toDateTimeString();
        }
        return null;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(Project $project, Board $board, $id)
    {
        $task = $board->tasks()->findOrFail($id);
        $task->delete();
        return response()->success(null, Response::HTTP_NO_CONTENT);
    }
}
