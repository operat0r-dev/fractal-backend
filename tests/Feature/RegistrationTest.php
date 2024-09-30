<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function testItRegistersAUser(): void
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

    public function testItFailsWithInvalidEmail(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'Jogn Doe',
            'email' => 'invalid-email',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(400);
    }

    public function testItFailsWithDifferentPasswordConfirmation(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'Jogn Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'wrong-password',
        ]);

        $response->assertStatus(400);
    }

    public function testFailsWithExistingEmail(): void
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
