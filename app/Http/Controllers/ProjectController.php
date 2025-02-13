<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
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

        $project = new Project($request->all());
        $project->save();

        return response()->json($project, 201);
    }

    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Projeto não encontrado'], 404);
        }

        $validator = Validator::make($request->all(), Project::rules());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $project->update($request->all());

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