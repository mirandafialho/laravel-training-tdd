<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     *
     * @test
     */
    public function it_should_be_able_to_register_as_a_new_user()
    {
        // Arrange

        // Act
        $return = $this->post(route('register'), [
            'name'               => 'Yuri Miranda Fialho',
            'email'              => 'yuri@email.com',
            'email_confirmation' => 'yuri@email.com',
            'password'           => 'passwordA'
        ]);

        // Assert
        $return->assertRedirect('dashboard');

        $this->assertDatabaseHas('users', [
            'name'  => 'Yuri Miranda Fialho',
            'email' => 'yuri@email.com'
        ]);

        /** @var User $user */
        $user = User::whereEmail('yuri@email.com')->firstOrFail();

        $this->assertTrue(
            Hash::check('passwordA', $user->password),
            'Checking if password was saved and it is encrypted.'
        );
    }

    /** @test */
    public function name_should_be_required()
    {
        $this->post(route('register'), [])
            ->assertSessionHasErrors([
                'name' => __('validation.required', ['attribute' => 'name'])
            ]);
    }

    /** @test */
    public function name_should_have_a_max_of_255_characters()
    {
        $this->post(route('register'), [
            'name' => str_repeat('a', 256)
        ])
            ->assertSessionHasErrors([
                'name' => __('validation.max.string', ['attribute' => 'name', 'max' => 255])
            ]);
    }

    /** @test */
    public function email_should_be_required()
    {
        $this->post(route('register'), [])
            ->assertSessionHasErrors([
                'email' => __('validation.required', ['attribute' => 'email'])
            ]);
    }

    /** @test */
    public function email_should_be_a_valid_email()
    {
        $this->post(route('register'), [
            'email' => 'invalid-email-abcdefghijk'
        ])
            ->assertSessionHasErrors([
                'email' => __('validation.email', ['attribute' => 'email'])
            ]);
    }

    /** @test */
    public function email_should_be_unique()
    {
        // Arrange
        User::factory()->create([
            'email' => 'some@email.com'
        ]);

        // Act
        $this->post(route('register'), [
            'email' => 'some@email.com'
        ])
            ->assertSessionHasErrors([
                'email' => __('validation.unique', ['attribute' => 'email'])
            ]);

        // Assert
    }

    /** @test */
    public function email_should_be_confirmed()
    {
        $this->post(route('register'), [
            'email'              => 'some@email.com',
            'email_confirmation' => ''
        ])
            ->assertSessionHasErrors([
                'email' => __('validation.confirmed', ['attribute' => 'email'])
            ]);
    }

    /** @test */
    public function password_should_be_required()
    {
        $this->post(route('register'), [])
            ->assertSessionHasErrors([
                'password' => __('validation.required', ['attribute' => 'password'])
            ]);
    }

    /** @test */
    public function password_should_have_at_least_1_uppercase()
    {
        $this->post(route('register'), [
            'password' => 'password-uppercase'
        ])
            ->assertSessionHasErrors([
                'password' => 'The password must contain at least one uppercase and one lowercase letter.'
            ]);
    }
}
