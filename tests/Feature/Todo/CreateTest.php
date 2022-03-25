<?php

namespace Tests\Feature\Todo;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /** @test */
    public function it_should_be_able_to_create_a_todo_item()
    {
        Storage::fake('s3');

        // Arrange
        /** @var User $user */
        $user = User::factory()->createOne();

        /** @var User $assignedTo */
        $assignedTo = User::factory()->createOne();

        $this->actingAs($user);

        // Act
        $request = $this->post(route('todo.store'), [
            'title' => 'Todo Item',
            'description' => 'Todo Item Description',
            'assigned_to_id' => $assignedTo->id
        ]);

        // Assert

        // 1. Garantir que o usuário foi redirecionado para a página de todos com a lista de todos
        $request->assertRedirect(route('todo.index'));

        // 2. Garantir que o todo foi criado no banco de dados corretamente
        $this->assertDatabaseHas('todos', [
            'title' => 'Todo Item',
            'description' => 'Todo Item Description',
            'assigned_to_id' => $assignedTo->id
        ]);
    }

    /** @test */
    public function it_should_be_able_add_a_file_to_the_todo_item()
    {
        Storage::fake('s3');

        // Arrange
        /** @var User $user */
        $user = User::factory()->createOne();
        $this->actingAs($user);

        // Act
        $request = $this->post(route('todo.store'), [
            'title' => 'Todo Item',
            'description' => 'Todo Item Description',
            'assigned_to_id' => $user->id,
            'file' => UploadedFile::fake()->image('todo/avatar.jpg')
        ]);

        // Assert
        Storage::disk('s3')->assertExists('todo/avatar.jpg');
    }
}
