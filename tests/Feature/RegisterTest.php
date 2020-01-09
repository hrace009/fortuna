<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use WithFaker, DatabaseMigrations, RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_register()
    {
        $this->postJson('/api/register', [
            'name' => $this->faker()->name,
            'email' => 'pedro@hotmail.com',
            'password' => 'password',
        ])
            ->assertSuccessful()
            ->assertJsonStructure(['data' => ['id', 'name', 'email']]);
    }

    public function test_user_cannot_register_using_a_fake_email_provider()
    {
        $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@cuvox.de',
            'password' => 'secret',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
