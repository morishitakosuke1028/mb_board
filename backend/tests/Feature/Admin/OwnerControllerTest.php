<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Owner;
use App\Models\Admin;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OwnerControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    #[Test]
    public function index_returns_owners_list(): void
    {
        Owner::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->getJson('/api/admin/owners');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'email', 'created_at', 'updated_at']
                ]
            ]);
    }

    #[Test]
    public function destroy_soft_deletes_owner(): void
    {
        $owner = Owner::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->deleteJson("/api/admin/owners/{$owner->id}");

        $response->assertOk()
            ->assertJson(['message' => 'オーナーを削除しました。']);

        $this->assertSoftDeleted('owners', ['id' => $owner->id]);
    }
}
