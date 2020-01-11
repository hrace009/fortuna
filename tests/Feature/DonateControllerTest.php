<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Payments;
use App\Models\GoldPackage;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DonateControllerTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase, WithFaker;

    private $user;

    private $goldPackage;

    /**
     * - User can upload an attachment.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create([
            'status' => 'CONFIRMED',
        ]);

        $this->goldPackage = factory(GoldPackage::class)->create();
    }

    /**
     * @test
     */
    public function user_can_view_a_list_of_donations()
    {
        $this->user->orders()->createMany(
            factory(Payments::class, 5)->make([
                'transaction_status' => 3,
            ])->toArray()
        );

        $this->actingAs($this->user, 'api')
            ->json('GET', '/api/donate')
            ->assertJsonCount(5, 'data');
    }

    /**
     * @test
     */
    public function user_can_create_an_order()
    {
        $this->withoutExceptionHandling();

        $game = $this->user->accounts()->create($this->createGameAcccount())->toArray();

        $order = [
            'game_account' => [
                'id' => Hashids::encode($game['ID']),
                'name' => $game['name'],
            ],
            'gold_package' => $this->goldPackage->package_id,
        ];

        $this->actingAs($this->user, 'api')
            ->json('POST', '/api/donate', $order)
            ->assertSuccessful();
    }

    /**
     * @test
     */
    public function user_cannot_create_an_order_using_a_game_account_does_not_exists()
    {
        $order = [
            'game_account' => [
                'id' => Hashids::encode(999),
                'name' => 'webmaster',
            ],
            'gold_package' => $this->goldPackage->package_id,
        ];

        $this->actingAs($this->user, 'api')
            ->json('POST', '/api/donate', $order)
            ->assertStatus(404);
    }

    /**
     * @test
     */
    public function user_cannot_create_an_order_if_game_account_does_not_belongs_to_him()
    {
        $user = factory(User::class)->create([
            'status' => 'CONFIRMED',
        ]);
        $game = $user->accounts()->create($this->createGameAcccount())->toArray();

        $order = [
            'game_account' => [
                'id' => Hashids::encode($game['ID']),
                'name' => 'webmaster',
            ],
            'gold_package' => $this->goldPackage->package_id,
        ];

        $this->actingAs($this->user, 'api')
            ->json('POST', '/api/donate', $order)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['game_account']);
    }

    /**
     * @test
     */
    public function user_can_view_an_order()
    {
        $order = $this->user->orders()->create(
            factory(Payments::class)->make([
                'transaction_status' => 3,
                'game_id' => 32,
                'game_login' => 'webmaster',
            ])->toArray()
        );

        $this->actingAs($this->user, 'api')
            ->json('GET', "/api/donate/{$order['order_id']}")
            ->assertSuccessful()
            ->assertJsonFragment([
                'game_account' => 'webmaster',
            ]);
    }

    /**
     * @test
     */
    public function user_cannot_view_an_order_from_another_user()
    {
        $order = factory(Payments::class)->create([
            'transaction_status' => 3,
            'game_id' => 32,
            'game_login' => 'webmaster',
        ])->toArray();

        $this->actingAs($this->user, 'api')
            ->json('GET', "/api/donate/{$order['order_id']}")
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function user_can_cancel_an_order()
    {
        $order = $this->user->orders()->create(
            factory(Payments::class)->make([
                'game_id' => 32,
                'game_login' => 'webmaster',
            ])->toArray()
        );

        $this->actingAs($this->user, 'api')
            ->json('DELETE', '/api/donate/'.$order->order_id)
            ->assertSuccessful();

        $this->assertSoftDeleted($order);
    }

    /**
     * @return array
     */
    protected function createGameAcccount(): array
    {
        return [
            'ID' => \App\Game\User::count() > 0 ? \App\Game\User::orderBy('ID', 'desc')->first()->ID + 16 : 32,
            'name' => 'phpunit'.Str::random(3),
            'passwd' => 'phpunit1',
        ];
    }
}
