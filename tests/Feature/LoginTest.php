<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testItFailsWithInvalidEmail(): void
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

    public function testItFailsWithInvalidPassword(): void
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
