<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_users_can_access_admin_routes(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertOk();
    }

    public function test_staff_users_can_access_scoped_admin_routes(): void
    {
        $user = User::factory()->staff()->create();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertOk();
    }

    public function test_staff_users_cannot_access_admin_only_routes(): void
    {
        $user = User::factory()->staff()->create();

        $this->actingAs($user)->get('/admin/users')->assertForbidden();
        $this->actingAs($user)->get('/admin/reports')->assertForbidden();
    }
}
