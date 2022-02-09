<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User as User;
use App\Profession as Profession;
use App\UserProfile as UserProfile;
use Illuminate\Support\Facades\DB as DB;

class UsersModuleTest extends TestCase
{
    use RefreshDatabase;

    protected $profession;

    public function getValidData(array $custom = [])
    {
        $this->profession = factory(Profession::class)->create();

        return array_filter(array_merge([
            'name' => 'Fernando Contreras',
            'email' => 'fernando@mail.com',
            'profession_id' => $this->profession->id,
            'password' => 'secret',
            'twitter' => 'https://twitter.com/fernando',
            'bio' => 'Soy un tío de puta madre.'
        ], $custom));
    }

    /** @test */
    public function it_shows_the_users_list()
    {
        factory(User::class)->create([
            'name' => 'Ana'
        ]);

        factory(User::class)->create([
            'name' => 'Pedro'
        ]);

        $this->get('usuarios')
            ->assertStatus(200)
            ->assertSee('Lista de Usuarios')
            ->assertSee('Ana')
            ->assertSee('Pedro');
    }

    /** @test */
    public function it_shows_a_default_message_if_list_is_empty()
    {
        $this->get('usuarios')
            ->assertStatus(200)
            ->assertSee('Lista de Usuarios')
            ->assertSee('No hay usuarios registrados');
    }

    /** @test */
    public function it_displays_user_details_with_profession()
    {
        $profession = factory(Profession::class)->create([
            'title' => 'pajillero'
        ]);

        $user = factory(User::class)->create([
            'name' => 'Pedro Pérez',
            'email' => 'pedro@mail.com',
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
            'email' => 'pedro@mail.com',
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
        $this->get('usuarios/1')
            ->assertStatus(404)
            ->assertSee('Lo sentimos, el usuario no ha sido encontrado');
    }

    /** @test */
    public function it_loads_user_creation_page()
    {
        $profession = factory(Profession::class)->create();

        $this->get('usuarios/nuevo')
            ->assertStatus(200)
            ->assertViewIs('users.create')
            ->assertViewHas('professions', function ($professions) use ($profession) {
                return $professions->contains($profession);
            });
    }

    /** @test */
    public function it_creates_a_new_user()
    {
        $this->post('usuarios/crear', $this->getValidData())
            ->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'Fernando Contreras',
            'email' => 'fernando@mail.com',
            'is_admin' => 0,
            'password' => 'secret',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'profession_id' => $this->profession->id,
            'twitter' => 'https://twitter.com/fernando',
            'bio' => 'Soy un tío de puta madre.',
            'user_id' => User::findByEmail('fernando@mail.com')->id
        ]);
    }

    /** @test */
    public function the_name_field_is_required()
    {
        $this->from('usuarios/nuevo')->post('usuarios/crear', $this->getValidData([
            'name' => '',
        ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);
        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    public function the_email_field_is_required()
    {
        $this->from('usuarios/nuevo')->post('usuarios/crear', $this->getValidData([
            'email' => null
        ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors([
                'email' => 'The email field is required.'
            ]);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    public function the_password_field_is_required()
    {
        $this->from('usuarios/nuevo')->post('usuarios/crear', $this->getValidData([
            'password' => null
        ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors([
                'password' => 'The password field is required.'
            ]);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    public function the_email_must_be_valid()
    {
        $this->from('usuarios/nuevo')->post('usuarios/crear', $this->getValidData([
            'email' => 'asdf'
        ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors([
                'email' => 'The email must be a valid email address.'
            ]);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    public function the_email_must_be_unique()
    {
        factory(User::class)->create([
            'email' => 'oldemail@mail.com'
        ]);

        $this->from('usuarios/nuevo')->post('usuarios/crear', $this->getValidData([
            'email' => 'oldemail@mail.com'
        ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors([
                'email' => 'The email has already been taken.'
            ]);

        $this->assertEquals(1, User::count());
    }

    /** @test */
    public function the_password_field_must_be_at_least_6_chars()
    {
        $this->from('usuarios/nuevo')->post('usuarios/crear', $this->getValidData([
            'password' => '123'
        ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors([
                'password' => 'The password must be at least 6 characters.'
            ]);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    public function the_profession_id_field_is_optional()
    {
        $this->from('/usuarios/nuevo')->post('usuarios/crear', $this->getValidData([
            'profession_id' => null
        ]))->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'Fernando Contreras',
            'email' => 'fernando@mail.com',
            'password' => 'secret',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'profession_id' => null,
            'twitter' => 'https://twitter.com/fernando',
            'bio' => 'Soy un tío de puta madre.'
        ]);
    }

    /** @test */
    public function the_profession_id_field_must_be_valid()
    {
        $this->from('usuarios/nuevo')->post('usuarios/crear', $this->getValidData([
            'profession_id' => 300
        ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors([
                'profession_id' => 'The chosen profession is not valid.'
            ]);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    public function only_selectable_professions_are_valid()
    {
        $deletedProfession = factory(Profession::class)->create([
            'deleted_at' => now()->format('Y-m-d')
        ]);

        $this->from('usuarios/nuevo')->post('usuarios/crear', $this->getValidData([
            'profession_id' => $deletedProfession->id
        ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['profession_id']);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    public function it_loads_user_edit_page()
    {
        $user = factory(User::class)->create();
        $user->profile()->create(['bio' => 'asdf', 'profession_id' => null, 'twitter' => null]);

        $this->get("usuarios/{$user->id}/editar")
            ->assertStatus(200)
            ->assertViewIs('users.edit')
            ->assertSee("Editando información del usuario #{$user->id}")
            ->assertViewHas('user', function ($viewUser) use ($user) {
                return $viewUser->id === $user->id;
            });
    }

    /** @test */
    public function it_updates_a_user()
    {
        $user = factory(User::class)->create();

        $this->put("usuarios/{$user->id}", [
            'name' => 'Fernando Contreras',
            'email' => 'fernando@mail.com',
            'password' => 'secret',
        ])->assertRedirect("usuarios/{$user->id}");

        $this->assertCredentials([
            'name' => 'Fernando Contreras',
            'email' => 'fernando@mail.com',
            'password' => 'secret',
        ]);
    }

    /** @test */
    public function the_name_field_is_required_in_updates()
    {
        $user = factory(User::class)->create();

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => '',
                'email' => 'floripondio@mail.com',
                'password' => 'secret'
            ])
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);

        $this->assertDatabaseMissing('users', ['email' => 'floripondio@mail.com']);
    }

    /** @test */
    public function the_email_field_is_required_in_updates()
    {
        $user = factory(User::class)->create();

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => 'Tropofosio Filibusteo',
                'email' => '',
                'password' => 'secret'
            ])
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors([
                'email' => 'The email field is required.'
            ]);

        $this->assertDatabaseMissing('users', ['name' => 'Tropofosio Filibusteo']);
    }

    /** @test */
    public function the_password_field_is_optional_in_updates()
    {
        $old_password = 'clave_vieja';

        $user = factory(User::class)->create([
            'password' => bcrypt($old_password)
        ]);

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => 'Tropofosio Filibusteo',
                'email' => 'tropofosio@mail.com',
                'password' => ''
            ])
            ->assertRedirect("usuarios/{$user->id}");

        $this->assertCredentials([
            'name' => 'Tropofosio Filibusteo',
            'email' => 'tropofosio@mail.com',
            'password' => $old_password
        ]);
    }

    /** @test */
    public function the_email_must_be_valid_in_updates()
    {
        $user = factory(User::class)->create();

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => 'Tropofosio Filibusteo',
                'email' => 'asdf',
                'password' => 'secret'
            ])
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors([
                'email' => 'The email must be a valid email address.'
            ]);

        $this->assertDatabaseMissing('users', ['email' => 'asdf']);
    }

    /** @test */
    public function the_email_must_be_unique_in_updates()
    {
        factory(User::class)->create([
            'email' => 'existe@mail.com'
        ]);

        $user = factory(User::class)->create([
            'email' => 'noexiste@mail.com'
        ]);

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => 'Tropofosio Filibusteo',
                'email' => 'existe@mail.com',
                'password' => 'secret'
            ])
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors([
                'email' => 'The email has already been taken.'
            ]);

        $this->assertDatabaseMissing('users', ['name' => 'Tropofosio Filibusteo']);
    }

    /** @test */
    public function the_profession_field_value_exists_in_updates()
    {
        $user = factory(User::class)->create();

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => 'Tropofosio Filibusteo',
                'email' => 'tropofosio@mail.com',
                'password' => '123456',
                'profession' => 'asdf'
            ])
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors([
                'profession' => 'The selected profession is invalid.'
            ]);

        $this->assertDatabaseMissing('users', ['email' => 'tropofosio@mail.com']);
    }

    /** @test */
    public function the_user_email_can_stay_the_same_in_updates()
    {
        $oldemail = 'oldemail@mail.com';

        $user = factory(User::class)->create([
            'email' => $oldemail
        ]);

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => 'Cocoloco Pérez',
                'email' => $oldemail,
                'password' => ''
            ])->assertRedirect("usuarios/{$user->id}");

        $this->assertDatabaseHas('users', [
            'name' => 'Cocoloco Pérez',
            'email' => $oldemail
        ]);
    }

    /** @test */
    public function it_deletes_a_user()
    {
        $user = factory(User::class)->create([
            'email' => 'miemail@mail.com'
        ]);

        $user->profile()->create([
            'bio' => 'asdf'
        ]);

        $this->delete("usuarios/{$user->id}")
            ->assertRedirect('usuarios');

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'email' => 'miemail@mail.com'
        ]);
    }

    /** @test */
    public function the_twitter_field_is_optional()
    {
        $this->from('usuarios/nuevo')->post('usuarios/crear', $this->getValidData([
            'twitter' => ''
        ]))->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'Fernando Contreras',
            'email' => 'fernando@mail.com',
            'is_admin' => 0,
            'password' => 'secret',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'twitter' => null,
            'bio' => 'Soy un tío de puta madre.',
            'user_id' => User::findByEmail('fernando@mail.com')->id
        ]);
    }
}
