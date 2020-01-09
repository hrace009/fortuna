<?php

namespace Tests\Unit;

use App\Models\Payments;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Vinkla\Hashids\Facades\Hashids;

class UserTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    /**
     * @test
     */
    public function user_can_see_his_last_approved_donation()
    {
        $user = factory(User::class)->create([
            'status' => 'CONFIRMED',
        ]);

        $order = $user->orders()->create(
            factory(Payments::class)->make([
                'game_id' => Hashids::encode(32),
                'game_login' => 'webmaster',
                'amount' => 50,
                'transaction_status' => 'approved',
            ])->toArray()
        );

        $this->assertDatabaseHas('payments', $order->toArray());

        $this->actingAs($user, 'api')->json('GET', '/api/user/latest_order')->assertSuccessful();
    }

    /**
     * @test
     */
    public function user_can_not_see_because_is_not_approved()
    {
        $user = factory(User::class)->create([
            'status' => 'CONFIRMED',
        ]);

        $order = $user->orders()->create(
            factory(Payments::class)->make([
                'game_id' => Hashids::encode(32),
                'game_login' => 'webmaster',
                'amount' => 50,
                'transaction_status' => 'pending',
            ])->toArray()
        );

        $this->actingAs($user, 'api')->json('GET', '/api/user/latest_order')
            ->assertStatus(204);
    }
}
