<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/tasks",
     *     summary="List all tasks",
     *     tags={"Tasks"},
     *     @OA\Response(
     *         response=200,
     *         description="List of tasks",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Task"))
     *     )
     * )
     */
    public function index()
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    public function show($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Tarefa não encontrada'], 404);
        }

        return response()->json($task);
    }

    /**
     * @OA\Post(
     *     path="/tasks",
     *     summary="Create a new task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "project_id", "creation_date", "completion_date", "status"},
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="creation_date", type="string", format="date"),
     *             @OA\Property(property="completion_date", type="string", format="date"),
     *             @OA\Property(property="status", type="string", enum={"pending", "in_progress", "completed"}),
     *             @OA\Property(property="project_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Task::rules());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $task = new Task($request->all());
        $task->save();

        return response()->json($task, 201);
    }

    /**
     * @OA\Put(
     *     path="/tasks/{id}",
     *     summary="Update task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="creation_date", type="string", format="date"),
     *             @OA\Property(property="completion_date", type="string", format="date"),
     *             @OA\Property(property="status", type="string", enum={"pending", "in_progress", "completed"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task updated",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Tarefa não encontrada'], 404);
        }

        $validator = Validator::make($request->all(), Task::rules(true));

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $task->update($request->only(['title', 'description', 'creation_date', 'completion_date', 'status']));
        
        return response()->json($task);
    }

    /**
     * @OA\Delete(
     *     path="/tasks/{id}",
     *     summary="Delete task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task deleted"
     *     )
     * )
     */
    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Tarefa não encontrada'], 404);
        }

        $task->delete();

        return response()->json(['message' => 'Tarefa excluída com sucesso']);
    }

    /**
     * @OA\Get(
     *     path="/projects/{id}/tasks",
     *     summary="Get tasks by project",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of tasks for project",
     *         @OA\JsonContent(
     *             @OA\Property(property="project", type="string"),
     *             @OA\Property(
     *                 property="tasks",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Task")
     *             )
     *         )
     *     )
     * )
     */
    public function getTasksByProject($projectId)
    {
        // Verifica se o projeto existe
        $project = Project::find($projectId);
        
        if (!$project) {
            return response()->json([
                'message' => 'Projeto não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }

        $tasks = Task::where('project_id', $projectId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'project' => $project->name,
            'tasks' => $tasks
        ]);
    }
}