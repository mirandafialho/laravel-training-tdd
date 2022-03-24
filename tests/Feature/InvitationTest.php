<?php

namespace Tests\Feature;

use App\Mail\Invitation;
use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;

use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_should_be_able_to_invite_someone_to_the_plataform()
    {
        // Arrange
        Mail::fake();

        // Preciso um usuário que vá convidar
        $user = User::factory()->create();

        // Preciso estar logado
        $this->actingAs($user);

        // Act
        $this->post('invite', [
            'email' => 'new@email.com'
        ]);

        // Assert
        // Email foi enviado
        Mail::assertSent(Invitation::class, function ($mail) {
            return $mail->hasTo('new@email.com');
        });

        // Criou um convite no banco de dados
        $this->assertDatabaseHas('invites', [
            'email' => 'new@email.com'
        ]);
    }
}
