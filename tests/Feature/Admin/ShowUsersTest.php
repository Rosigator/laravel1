<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Profession;
use Tests\TestCase;
use App\UserProfile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_user_details_with_profession()
    {
        $profession = factory(Profession::class)->create([
            'title' => 'pajillero'
        ]);

        $user = factory(User::class)->create([
            'name' => 'Pedro Pérez',
            'email' => 'pedro@mail.com'
        ]);

        $profile = factory(UserProfile::class)->create([
            'user_id' => $user->id,
            'profession_id' => $profession->id
        ]);

        $this->get('usuarios/' . $user->id)
            ->assertStatus(200)
            ->assertViewIs('users.show')
            ->assertViewHas('user', function ($viewUser) use ($user) {
                return $viewUser->id == $user->id;
            });
    }

    /** @test */
    public function it_displays_user_details_without_profession()
    {
        $user = factory(User::class)->create([
            'name' => 'Pedro Pérez',
            'email' => 'pedro@mail.com'
        ]);

        $profile = factory(UserProfile::class)->create([
            'user_id' => $user->id
        ]);

        $this->get('usuarios/' . $user->id)
            ->assertStatus(200)
            ->assertViewIs('users.show')
            ->assertViewHas('user', function ($viewUser) use ($user) {
                return $viewUser->id == $user->id;
            });
    }

    /** @test */
    public function it_displays_a_404_error_if_user_is_not_found()
    {
        $this->withExceptionHandling();
        $this->get('usuarios/1')
            ->assertStatus(404)
            ->assertSee('Lo sentimos, el usuario no ha sido encontrado');
    }
}
