<?php

namespace Tests\Feature;

use App\Events\PasswordChanged;
use App\Events\UpdateProfileDetails;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase, WithFaker;

    private $user;

    /**
     * - User can upload an attachment.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create([
            'name' => 'John Doe',
            'status' => 'CONFIRMED',
        ]);
    }

    public function test_user_can_update_profile()
    {
        Event::fake();

        $this->actingAs($this->user, 'api')
            ->patchJson('/api/settings/profile', [
                'name' => 'Jane Doe',
                'gender' => 'male',
                'birthday' => '01/01/1969',
            ])
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Jane Doe',
        ]);

        Event::assertDispatched(
            UpdateProfileDetails::class,
            1
        );
    }

    public function test_user_can_update_password()
    {
        Event::fake();

        $this->actingAs($this->user, 'api')
        ->patchJson('/api/settings/password', [
            'current_password' => 'password',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ])
            ->assertSuccessful();

        $this->assertTrue(Hash::check('secret123', $this->user->password));

        Event::assertDispatched(
            PasswordChanged::class,
            1
        );
    }
}
