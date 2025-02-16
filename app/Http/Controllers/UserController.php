<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;


class UserController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), User::rules());

        if ($validator->fails()) { 
            return response()->json($validator->errors(), 422);
        }

        // Remove role do request se estiver presente
        $userData = $request->except('role');
        
        // Define role como 'user' por padrão
        $userData['role'] = UserRole::USER;

        $user = User::create($userData);

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuário nao encontrado'], 404);
        }

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
    
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

    
        $validator = Validator::make($request->all(), User::rules(true));
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        if ($request->filled('password')) {
            $request->merge(['password' => Hash::make($request->password)]);
        }
    
        $user->update($request->only('name', 'email', 'password'));
    
        return response()->json($user);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        $user->delete();    

        return response()->json(['message' => 'Usuário excluido com sucesso']);
    }

    public function updateRole(Request $request, $id)
    {
        /** @var \App\Models\User $authenticatedUser */
        $authenticatedUser = Auth::user();

        // Apenas admins podem alterar roles
        if (!$authenticatedUser->isAdmin()) {
            return response()->json([
                'message' => 'Não autorizado. Apenas administradores podem alterar roles.'
            ], Response::HTTP_FORBIDDEN);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Usuário não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'role' => 'required|in:admin,user'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Impedir que o admin remova seu próprio privilégio
        if ($user->id === $authenticatedUser->id && $request->role !== 'admin') {
            return response()->json([
                'message' => 'Não é possível remover seus próprios privilégios de administrador'
            ], Response::HTTP_FORBIDDEN);
        }

        $user->role = $request->role;
        $user->save();

        return response()->json([
            'message' => 'Role atualizada com sucesso',
            'user' => $user
        ]);
    }
}
