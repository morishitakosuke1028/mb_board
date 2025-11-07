<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Owner;
use App\Models\Course;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class CourseControllerTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;
    private Owner $owner;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
        $this->owner = Owner::factory()->create();
    }

    #[Test]
    public function index_returns_course_list()
    {
        Course::factory()->count(3)->create(['owner_id' => $this->owner->id]);

        $response = $this->actingAs($this->admin, 'admin')
            ->getJson('/api/admin/courses');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'owner_id', 'course_title', 'content', 'instructor',
                        'date_time', 'capacity', 'status', 'created_at', 'updated_at'
                    ],
                ],
            ]);
    }

    #[Test]
    public function store_creates_a_new_course()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.jpg');

        $payload = [
            'owner_id' => $this->owner->id,
            'course_title' => 'Laravel入門講座',
            'content' => 'テストコンテンツ',
            'course_image' => $file,
            'instructor' => '山田太郎',
            'instructor_title' => '講師',
            'date_time' => now()->format('Y-m-d H:i:s'),
            'participation_fee' => '2000円',
            'additional_fee' => '500円',
            'capacity' => 20,
            'venue' => '渋谷ビル',
            'venue_zip' => '150-0002',
            'venue_address' => '東京都渋谷区2-2-2',
            'tel' => '08012345678',
            'email' => 'test@example.com',
            'map' => 'https://maps.example.com',
            'status' => '1',
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->postJson('/api/admin/courses', $payload);

        $response->assertCreated()
            ->assertJsonFragment(['message' => '講座を登録しました。']);

        $this->assertDatabaseHas('courses', ['course_title' => 'Laravel入門講座']);
        Storage::disk('public')->assertExists('courses');
    }

    #[Test]
    public function store_validation_fails_when_required_fields_missing()
    {
        $payload = []; // 空データ

        $response = $this->actingAs($this->admin, 'admin')
            ->postJson('/api/admin/courses', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['owner_id', 'course_title', 'content', 'date_time']);
    }

    #[Test]
    public function edit_returns_course_detail()
    {
        $course = Course::factory()->create(['owner_id' => $this->owner->id]);

        $response = $this->actingAs($this->admin, 'admin')
            ->getJson("/api/admin/courses/{$course->id}/edit");

        $response->assertOk()
            ->assertJsonFragment(['id' => $course->id]);
    }

    #[Test]
    public function update_modifies_existing_course()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('update.jpg');

        $course = Course::factory()->create([
            'owner_id' => $this->owner->id,
            'course_title' => '旧タイトル',
        ]);

        $updateData = [
            '_method' => 'PUT',
            'owner_id' => $this->owner->id,
            'course_title' => '新タイトル',
            'content' => '更新済みコンテンツ',
            'course_image' => $file,
            'instructor' => '田中一郎',
            'instructor_title' => '主任講師',
            'date_time' => now()->addDay()->format('Y-m-d H:i:s'),
            'participation_fee' => '3000円',
            'additional_fee' => '1000円',
            'capacity' => 30,
            'venue' => '大阪ビル',
            'venue_zip' => '530-0001',
            'venue_address' => '大阪府大阪市北区1-2-3',
            'tel' => '09011112222',
            'email' => 'update@example.com',
            'map' => 'https://maps.update.com',
            'status' => '1',
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->postJson("/api/admin/courses/{$course->id}", $updateData);

        $response->assertOk()
            ->assertJsonFragment(['message' => '講座情報を更新しました。']);

        $this->assertDatabaseHas('courses', ['course_title' => '新タイトル']);
    }

    #[Test]
    public function update_validation_fails_if_title_empty()
    {
        $course = Course::factory()->create(['owner_id' => $this->owner->id]);

        $updateData = [
            '_method' => 'PUT',
            'course_title' => '',
            'content' => 'テスト内容',
            'date_time' => now()->format('Y-m-d H:i:s'),
            'owner_id' => $this->owner->id,
            'instructor' => '講師A',
            'participation_fee' => '1000円',
            'capacity' => 10,
            'venue' => 'テスト会場',
            'venue_zip' => '000-0000',
            'venue_address' => '東京都',
            'status' => '1',
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->postJson("/api/admin/courses/{$course->id}", $updateData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['course_title']);
    }

    #[Test]
    public function destroy_deletes_course()
    {
        $course = Course::factory()->create(['owner_id' => $this->owner->id]);

        $response = $this->actingAs($this->admin, 'admin')
            ->deleteJson("/api/admin/courses/{$course->id}");

        $response->assertOk()
            ->assertJson(['message' => '講座情報を削除しました。']);

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }
}
