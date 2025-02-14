<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            $projects = Project::all();
        } else {
            $projects = $user->projects;
        }

        return response()->json($projects);
    }

    public function show($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Projeto não encontrado'], 404);
        }

        return response()->json($project);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Project::rules());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = Auth::user();
        $project = new Project($request->all());
        $project->user_id = $user->id;
        $project->save();

        return response()->json($project, 201);
    }

    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Projeto não encontrado'], 404);
        }

        $validator = Validator::make($request->all(), Project::rules(true));

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $project->update($request->only(['name', 'description', 'start_date', 'end_date', 'status']));

        return response()->json($project);
    }

    public function destroy($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Projeto não encontrado'], 404);
        }


        $project->delete();

        return response()->json(['message' => 'Projeto excluído com sucesso']);
    }
}