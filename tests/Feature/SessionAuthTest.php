<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_workspace_redirects_guests_to_login(): void
    {
        $response = $this->get(route('workspace.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_login_open_workspace_and_logout(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('workspace.index'));

        $this->assertAuthenticatedAs($user);

        $this->get(route('workspace.index'))->assertSee('Selamat datang, '.$user->name);

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
