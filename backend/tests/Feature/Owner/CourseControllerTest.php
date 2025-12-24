<?php

namespace Tests\Feature\Owner;

use Tests\TestCase;
use App\Models\Owner;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class CourseControllerTest extends TestCase
{
    use RefreshDatabase;

    private Owner $owner;
    private Owner $otherOwner;

    protected function setUp(): void
    {
        parent::setUp();
        $this->owner = Owner::factory()->create();
        $this->otherOwner = Owner::factory()->create();
    }

    #[Test]
    public function index_returns_only_authenticated_owner_courses(): void
    {
        Course::factory()->count(2)->create(['owner_id' => $this->owner->id]);
        Course::factory()->count(1)->create(['owner_id' => $this->otherOwner->id]);

        $response = $this->actingAs($this->owner, 'owner')
            ->getJson('/api/owner/courses');

        $response->assertOk()
            ->assertJsonCount(2, 'data');

        $courseOwnerIds = collect($response->json('data'))->pluck('owner_id')->unique()->all();
        $this->assertSame([$this->owner->id], $courseOwnerIds);
    }

    #[Test]
    public function store_creates_course_for_authenticated_owner(): void
    {
        $payload = [
            'owner_id' => $this->otherOwner->id,
            'course_title' => 'Owner Course',
            'content' => 'Owner content',
            'instructor' => 'Owner Instructor',
            'instructor_title' => '講師',
            'date_time' => now()->format('Y-m-d H:i:s'),
            'participation_fee' => '1000円',
            'capacity' => '10',
            'venue' => 'Test Venue',
            'venue_zip' => '123-4567',
            'venue_address' => '東京都テスト区1-2-3',
            'status' => '1',
        ];

        $response = $this->actingAs($this->owner, 'owner')
            ->postJson('/api/owner/courses', $payload);

        $response->assertCreated()
            ->assertJsonFragment(['message' => '講座を登録しました。']);

        $this->assertDatabaseHas('courses', [
            'course_title' => 'Owner Course',
            'owner_id' => $this->owner->id,
        ]);
    }

    #[Test]
    public function edit_returns_course_for_owner(): void
    {
        $course = Course::factory()->create(['owner_id' => $this->owner->id]);

        $response = $this->actingAs($this->owner, 'owner')
            ->getJson("/api/owner/courses/{$course->id}/edit");

        $response->assertOk()
            ->assertJsonFragment(['id' => $course->id]);
    }

    #[Test]
    public function edit_forbidden_for_other_owner_course(): void
    {
        $course = Course::factory()->create(['owner_id' => $this->otherOwner->id]);

        $response = $this->actingAs($this->owner, 'owner')
            ->getJson("/api/owner/courses/{$course->id}/edit");

        $response->assertStatus(403);
    }

    #[Test]
    public function update_modifies_course_for_owner(): void
    {
        $course = Course::factory()->create([
            'owner_id' => $this->owner->id,
            'course_title' => 'Old Title',
        ]);

        $payload = [
            'course_title' => 'New Title',
            'content' => 'Updated content',
            'date_time' => now()->addDay()->format('Y-m-d H:i:s'),
            'participation_fee' => '2000円',
            'capacity' => '15',
            'venue' => 'Updated Venue',
            'venue_zip' => '987-6543',
            'venue_address' => '大阪府テスト市4-5-6',
            'status' => '1',
        ];

        $response = $this->actingAs($this->owner, 'owner')
            ->putJson("/api/owner/courses/{$course->id}", $payload);

        $response->assertOk()
            ->assertJsonFragment(['message' => '講座を更新しました。']);

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'course_title' => 'New Title',
        ]);
    }

    #[Test]
    public function update_forbidden_for_other_owner_course(): void
    {
        $course = Course::factory()->create(['owner_id' => $this->otherOwner->id]);

        $payload = [
            'course_title' => 'New Title',
            'content' => 'Updated content',
            'date_time' => now()->addDay()->format('Y-m-d H:i:s'),
        ];

        $response = $this->actingAs($this->owner, 'owner')
            ->putJson("/api/owner/courses/{$course->id}", $payload);

        $response->assertStatus(403);
    }

    #[Test]
    public function destroy_deletes_course_for_owner(): void
    {
        $course = Course::factory()->create(['owner_id' => $this->owner->id]);

        $response = $this->actingAs($this->owner, 'owner')
            ->deleteJson("/api/owner/courses/{$course->id}");

        $response->assertOk()
            ->assertJson(['message' => '講座を削除しました。']);

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }
}
