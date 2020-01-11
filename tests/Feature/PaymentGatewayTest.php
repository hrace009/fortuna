<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use App\Models\User;
use App\Models\Payments;
use App\Models\PaymentGateway;
use App\Contracts\PaymentProvider;
use Vinkla\Hashids\Facades\Hashids;
use Facades\App\Services\Payment\PaymentsFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PaymentGatewayTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    private $user;

    /**
     * - User can upload an attachment.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create([
            'status' => 'CONFIRMED',
        ]);
    }

    /**
     * @test
     */
    public function user_can_create_a_transaction_in_paghiper()
    {
        $this->withoutExceptionHandling();

        $gateway = Mockery::mock(PaymentProvider::class);

        factory(PaymentGateway::class)->create();

        $order = $this->user->orders()->create(
            factory(Payments::class)->make([
                'transaction_status' => 3,
                'game_id' =>  Hashids::encode(32),
                'game_login' => 'webmaster',
            ])->toArray()
        );

        PaymentsFactory::shouldReceive('make')
            ->with('paghiper')
            ->andReturn($gateway);

        $gateway->shouldReceive('create')
            ->once()
            ->with($order->order_id)
            ->andReturn(['success' => true]);

        $response = $this->actingAs($this->user, 'api')
        ->json('PATCH', '/api/payments', [
            'order_id' => $order->order_id,
            'gateway' => 'paghiper',
        ]);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function user_can_create_a_transaction_in_paypal()
    {
        $gateway = Mockery::mock(PaymentProvider::class);

        factory(PaymentGateway::class)->create([
            'name' => 'paypal',
            'slug' => 'paypal',
        ]);

        $order = $this->user->orders()->create(
            factory(Payments::class)->make([
                'transaction_status' => 3,
                'game_id' =>  Hashids::encode(32),
                'game_login' => 'webmaster',
            ])->toArray()
        );

        PaymentsFactory::shouldReceive('make')
            ->with('paypal')
            ->andReturn($gateway);

        $gateway->shouldReceive('create')
            ->once()
            ->with($order->order_id)
            ->andReturn(['code', 'token']);

        $response = $this->actingAs($this->user, 'api')
            ->json('PATCH', '/api/payments', [
                'order_id' => $order->order_id,
                'gateway' => 'paypal',
            ]);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function user_can_create_a_transaction_in_mercadopago()
    {
        $gateway = Mockery::mock(PaymentProvider::class);

        factory(PaymentGateway::class)->create([
            'name' => 'Mercado Pago',
            'slug' => 'mercadopago',
        ]);

        $order = $this->user->orders()->create(
            factory(Payments::class)->make([
                'transaction_status' => 3,
                'game_id' =>  Hashids::encode(32),
                'game_login' => 'webmaster',
            ])->toArray()
        );

        PaymentsFactory::shouldReceive('make')
            ->with('mercadopago')
            ->andReturn($gateway);

        $gateway->shouldReceive('create')
            ->once()
            ->with($order->order_id)
            ->andReturn(['success', 'url', 'preference_id']);

        $response = $this->actingAs($this->user, 'api')
            ->json('PATCH', '/api/payments', [
                'order_id' => $order->order_id,
                'gateway' => 'mercadopago',
            ]);

        $response->assertOk();
    }
}
