<?php

namespace Tests\Unit;

use App\Models\GoldPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class GoldPackageTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    /**
     * @test
     * @group package
     *
     * @return void
     */
    public function can_retrieve_gold_packages()
    {
        $this->withoutExceptionHandling();

        factory(GoldPackage::class, 5)->create();

        $this->actingAs(factory(User::class)->create([
            'status' => 'CONFIRMED'
        ]), 'api');

        $response = $this->json('GET', '/api/gold_packages')
        ->assertSuccessful();

        $response->assertJsonStructure(['data']);
    }
}
