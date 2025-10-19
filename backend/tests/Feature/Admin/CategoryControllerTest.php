<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Admin;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    #[Test]
    public function index_returns_categories_list()
    {
        Category::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->getJson('/api/admin/categories');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'created_at', 'updated_at']
                ],
                'admin' => ['id', 'name', 'email'],
            ]);
    }

    #[Test]
    public function store_creates_a_new_category()
    {
        $payload = ['name' => 'テストカテゴリ'];

        $response = $this->actingAs($this->admin, 'admin')
            ->postJson('/api/admin/categories', $payload);

        $response->assertCreated()
            ->assertJsonFragment(['name' => 'テストカテゴリ']);

        $this->assertDatabaseHas('categories', ['name' => 'テストカテゴリ']);
    }

    #[Test]
    public function store_validation_fails_if_name_is_missing()
    {
        $payload = []; // nameなし

        $response = $this->actingAs($this->admin, 'admin')
            ->postJson('/api/admin/categories', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    #[Test]
    public function store_validation_fails_if_name_is_too_long()
    {
        $payload = ['name' => str_repeat('あ', 51)];

        $response = $this->actingAs($this->admin, 'admin')
            ->postJson('/api/admin/categories', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    #[Test]
    public function edit_returns_single_category()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->getJson("/api/admin/categories/{$category->id}/edit");

        $response->assertOk()
            ->assertJsonFragment(['id' => $category->id]);
    }

    #[Test]
    public function update_modifies_existing_category()
    {
        $category = Category::factory()->create(['name' => '旧カテゴリ名']);
        $payload = ['name' => '新カテゴリ名'];

        $response = $this->actingAs($this->admin, 'admin')
            ->putJson("/api/admin/categories/{$category->id}", $payload);

        $response->assertOk()
            ->assertJsonFragment(['name' => '新カテゴリ名']);

        $this->assertDatabaseHas('categories', ['name' => '新カテゴリ名']);
    }

    #[Test]
    public function update_validation_fails_if_name_is_empty()
    {
        $category = Category::factory()->create(['name' => '旧カテゴリ']);
        $payload = ['name' => ''];

        $response = $this->actingAs($this->admin, 'admin')
            ->putJson("/api/admin/categories/{$category->id}", $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    #[Test]
    public function update_validation_fails_if_name_is_too_long()
    {
        $category = Category::factory()->create(['name' => '旧カテゴリ']);
        $payload = ['name' => str_repeat('あ', 51)];

        $response = $this->actingAs($this->admin, 'admin')
            ->putJson("/api/admin/categories/{$category->id}", $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    #[Test]
    public function destroy_deletes_category()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->deleteJson("/api/admin/categories/{$category->id}");

        $response->assertOk()
            ->assertJson(['message' => 'カテゴリを削除しました。']);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
