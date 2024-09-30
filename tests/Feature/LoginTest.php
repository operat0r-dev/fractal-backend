<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_log_in_with_correct_credentials(): void
    {
        $password = 'password';
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200);
    }

    public function test_user_cannot_login_with_invalid_email(): void
    {
        $password = 'password';
        User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make($password)
        ]);

        $response = $this->post('/api/login', [
            'email' => 'daniel@example.com',
            'password' => $password,
        ]);

        $response->assertStatus(404);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(404);
    }
}
