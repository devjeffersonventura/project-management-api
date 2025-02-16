<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProjectResource;

class ProjectController extends Controller
{
    /**
     * @OA\Get(
     *     path="/projects",
     *     summary="List all projects",
     *     tags={"Projects"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of projects",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Project"))
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/projects/{id}",
     *     summary="Get project details",
     *     tags={"Projects"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Project details",
     *         @OA\JsonContent(ref="#/components/schemas/Project")
     *     ),
     *     @OA\Response(response=404, description="Project not found")
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/projects",
     *     summary="Create a new project",
     *     tags={"Projects"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProjectRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Project created",
     *         @OA\JsonContent(ref="#/components/schemas/Project")
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/projects/{id}",
     *     summary="Update project",
     *     tags={"Projects"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProjectRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Project updated",
     *         @OA\JsonContent(ref="#/components/schemas/Project")
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/projects/{id}",
     *     summary="Delete project",
     *     tags={"Projects"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Project deleted"
     *     )
     * )
     */
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