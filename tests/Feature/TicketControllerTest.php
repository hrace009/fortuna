<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketCategory;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TicketControllerTest extends TestCase
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
            'status' => 'CONFIRMED',
        ]);

        factory(TicketCategory::class)->create();
    }

    /**
     * @test
     */
    public function user_can_view_a_list_of_tickets()
    {
        $this->user->tickets()->createMany(
            factory(Ticket::class, 10)->make()->toArray()
        );

        $this->actingAs($this->user, 'api')
            ->json('GET', '/api/tickets')
            ->assertJsonCount(10, 'data');
    }

    /**
     * @test
     */
    public function user_can_create_a_ticket()
    {
        Notification::fake();

        $data = $this->formData();

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/tickets', $data);
        $response->assertSuccessful();

        Notification::assertSentTo(
            $this->user,
            \App\Notifications\Ticket\TicketCreated::class,
            1
        );
    }

    /**
     * @test
     */
    public function user_can_view_a_ticket()
    {
        $ticket = $this->user->tickets()->create(
            factory(Ticket::class)->make()->toArray()
        );

        $response = $this->actingAs($this->user, 'api')
                ->json('GET', '/api/tickets/'.$ticket->track_id);
        $response->assertSuccessful();
    }

    /**
     * @test
     */
    public function user_can_add_a_reply_to_a_ticket()
    {
        $ticket = $this->user->tickets()->create(
            factory(Ticket::class)->make()->toArray()
        );

        $reply = [
            'message' => 'This is a reply.',
        ];

        $this->actingAs($this->user, 'api')
            ->json('POST', "/api/tickets/{$ticket->track_id}/reply", $reply)
            ->assertSuccessful();

        $this->assertDatabaseHas('ticket_replies', [
            'track_id' => $ticket->track_id,
            'message' => $reply['message'],
        ]);
    }

    /**
     * @test
     */
    public function user_can_not_see_a_ticket_from_another_user()
    {
        $ticket = $this->user->tickets()->create(
            factory(Ticket::class)->make()->toArray()
        );

        $anotherUser = factory(User::class)->create([
            'status' => 'CONFIRMED',
        ]);

        $response = $this->actingAs($anotherUser, 'api')
            ->json('GET', '/api/tickets/'.$ticket->track_id);
        $response->assertForbidden();
    }

    /**
     * @param array $overrides
     * @return array
     */
    protected function formData($overrides = []): array
    {
        return array_merge(
            [
                'subject' => $this->faker->sentence,
                'category_id' => 1,
                'message' => $this->faker->text,
            ],
            $overrides
        );
    }
}
