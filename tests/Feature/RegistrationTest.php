<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_registers_a_user(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'Jogn Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertCount(1, User::all());
        $this->assertEquals('john@example.com', User::first()->email);

        $response->assertStatus(201);
    }

    public function test_it_fails_registration_with_invalid_email(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'Jogn Doe',
            'email' => 'invalid-email',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(400);
    }

    public function test_it_fails_registration_with_different_password_confirmation(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'Jogn Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'wrong-password',
        ]);

        $response->assertStatus(400);
    }

    public function test_user_cannot_register_with_existing_mail(): void
    {
        $user = User::factory()->create([
            'email' => 'existinguser@example.com',
        ]);

        $response = $this->post('/api/register', [
            'name' => 'Jogn Doe',
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(409);
    }
}
