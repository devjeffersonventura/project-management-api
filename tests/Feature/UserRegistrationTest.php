<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class UserRegistrationTest extends TestCase
{
    #[Test]
    public function user_can_be_registered_successfully()
    {
        $userData = [
            'name' => 'Usuário Teste',
            'email' => 'user@user.com',
            'password' => 'user1234'
        ];

        $response = $this->postJson('/v1/users', $userData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'id',
                    'name',
                    'email',
                    'role',
                    'created_at',
                    'updated_at'
                ]);

        // Verifica se o usuário foi realmente criado no banco
        $this->assertDatabaseHas('users', [
            'email' => 'user@user.com',
            'name' => 'Usuário Teste',
            'role' => 'user'
        ]);

        // Verifica valores específicos na resposta
        $response->assertJson([
            'name' => 'Usuário Teste',
            'email' => 'user@user.com',
            'role' => 'user'
        ]);
    }
} 