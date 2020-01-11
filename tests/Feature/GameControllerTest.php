<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GameControllerTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase, WithFaker;

    /**
     * - User can upload an attachment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create([
            'status' => 'CONFIRMED',
        ]);

        Notification::fake();
    }

    /**
     * @test
     */
    public function user_can_view_a_list_of_game_accounts()
    {
        $this->user->accounts()->createMany(
            factory(\App\Game\User::class, 5)->make()->toArray()
        );

        $this->actingAs($this->user, 'api')
            ->json('GET', '/api/game_accounts/all')
            ->assertJsonCount(5, 'data');
    }

    /**
     * @test
     */
    public function user_can_create_a_game_account()
    {
        $this->actingAs($this->user, 'api')
            ->json('POST', '/api/game_accounts/create_game_account',
                [
                    'name' => 'webmaster_'.Str::random(3),
                    'password' => 'secret',
                    'password_confirmation' => 'secret',
                ]
            )->assertSuccessful();
    }

    /**
     * @test
     */
    public function user_cannot_create_a_game_account_if_already_exists()
    {
        $this->actingAs($this->user, 'api')
            ->json('POST', '/api/game_accounts/create_game_account',
                [
                    'name' => 'webmaster',
                    'password' => 'secret',
                    'password_confirmation' => 'secret',
                ]
            )
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function user_can_change_game_account_password()
    {
        $this->withoutExceptionHandling();

        Event::fake();

        $game = $this->user->accounts()->create($this->createGameAcccount())->toArray();

        $id = Hashids::encode($game['ID']);

        $this->actingAs($this->user, 'api')
            ->json('PATCH', "/api/game_accounts/account/$id", [
                'password' => 'secret',
                'password_confirmation' => 'secret',
            ])
            ->assertSuccessful();

        Event::assertDispatched(
            \App\Events\GamePasswordChanged::class,
            1
        );
    }

    /**
     * @return array
     */
    protected function createGameAcccount(): array
    {
        return [
            'ID' => \App\Game\User::count() > 0 ? \App\Game\User::orderBy('ID', 'desc')->first()->ID + 16 : 32,
            'email' => $this->user->email,
            'name' => 'phpunit'.Str::random(3),
            'passwd' => 'phpunit1',
        ];
    }
}
