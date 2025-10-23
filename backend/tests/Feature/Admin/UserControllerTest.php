<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    #[Test]
    public function index_returns_users_list(): void
    {
        User::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->getJson('/api/admin/users');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'email', 'created_at', 'updated_at']
                ]
            ]);
    }

    #[Test]
    public function destroy_soft_deletes_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->deleteJson("/api/admin/users/{$user->id}");

        $response->assertOk()
            ->assertJson(['message' => 'ユーザーを削除しました。']);

        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }
}
