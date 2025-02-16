<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProjectResource;

class ProjectController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado'], 401);
        }

        if ($user->isAdmin()) {
            $projects = Project::with('user')->get();
        } else {
            $projects = Project::with('user')
                ->where('user_id', $user->id)
                ->get();
        }

        return ProjectResource::collection($projects);
    }

    public function show($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();       
        $project = Project::with('user')->find($id);

        if (!$project) {
            return response()->json(['message' => 'Projeto não encontrado'], 404);
        }

        if (!$user->isAdmin() && $project->user_id !== $user->id) {
            return response()->json(['error' => 'Acesso não autorizado'], 403);
        }

        return new ProjectResource($project);
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado'], 401);
        }

        $validator = Validator::make($request->all(), Project::rules());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $project = new Project($request->all());
        $project->user_id = $user->id;
        $project->save();

        return new ProjectResource($project);
    }

    public function update(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $project = Project::with('user')->find($id);

        if (!$project) {
            return response()->json(['message' => 'Projeto não encontrado'], 404);
        }

        if (!$user->isAdmin() && $project->user_id !== $user->id) {
            return response()->json(['error' => 'Acesso não autorizado'], 403);
        }

        $validator = Validator::make($request->all(), Project::rules(true));

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $project->update($request->only([
            'name', 
            'description', 
            'start_date', 
            'end_date', 
            'status',
            'cep',
            'location'
        ]));
        
        return new ProjectResource($project);
    }

    public function destroy($id)
    {
        /** @var User $user */
        $user = Auth::user();
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Projeto não encontrado'], 404);
        }

        if (!$user->isAdmin() && $project->user_id !== $user->id) {
            return response()->json(['error' => 'Acesso não autorizado'], 403);
        }

        $project->delete();

        return response()->json(['message' => 'Projeto excluído com sucesso']);
    }
}