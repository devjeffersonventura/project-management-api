<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
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

    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Tarefa não encontrada'], 404);
        }

        $task->delete();

        return response()->json(['message' => 'Tarefa excluída com sucesso']);
    }

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