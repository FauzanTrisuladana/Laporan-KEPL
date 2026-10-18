<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'bendahara@example.com',
            'password' => 'password',
            'status' => 'Aktif',
            'role' => 'bendahara',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'bendahara@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('message', 'Login berhasil')
            ->assertJsonPath('data.email', $user->email)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => ['id', 'name', 'email', 'role', 'status'],
                'auth' => ['token', 'token_type'],
            ]);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create([
            'email' => 'bendahara@example.com',
            'password' => 'password',
            'status' => 'Aktif',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'bendahara@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('status', 'unauthenticated')
            ->assertJsonPath('message', 'Email atau password salah');
    }

    public function test_login_validation_error(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'not-an-email',
            'password' => 'short',
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('status', 'error')
            ->assertJsonStructure(['errors' => ['email', 'password']]);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create(['status' => 'Aktif']);
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('message', 'Berhasil logout');

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_unauthenticated_user_cannot_logout(): void
    {
        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(401)
            ->assertJsonPath('status', 'unauthenticated');
    }
}
