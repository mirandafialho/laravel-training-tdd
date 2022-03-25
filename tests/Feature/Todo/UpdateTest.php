<?php

namespace Tests\Feature\Todo;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    /** @test */
    public function it_should_be_able_to_update_a_todo()
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->create();

        $this->actingAs($user);

        $this->put(route('todo.update', $todo), [
            'title' => 'Updated Todo',
            'description' => 'Updated Todo Description',
            'assigned_to_id' => $user->id
        ])->assertRedirect();
    }
}
