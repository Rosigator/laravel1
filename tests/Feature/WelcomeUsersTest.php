<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WelcomeUsersTest extends TestCase
{
    /** @test */
    public function it_welcomes_users_without_nickname()
    {
        $this->get('usuarios/manolo')
            ->assertStatus(200)
            ->assertSee('Bienvenido Manolo.');
    }

    /** @test */
    public function it_welcomes_users_with_nickname()
    {
        $this->get('usuarios/manolo/contreras')
            ->assertStatus(200)
            ->assertSee('Bienvenido Manolo. Tu apodo es: contreras');
    }
}
