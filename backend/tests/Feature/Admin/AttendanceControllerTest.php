<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\User;
use App\Models\Course;
use App\Models\Attendance;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    #[Test]
    public function index_returns_attendances_with_users_and_courses(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        Attendance::factory()->create([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->getJson('/api/admin/attendances');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'user_id', 'course_id', 'status', 'created_at', 'updated_at', 'user', 'course'],
                ],
                'users',
            ])
            ->assertJsonFragment([
                'id' => $user->id,
                'name' => $user->name,
            ]);
    }

    #[Test]
    public function destroy_deletes_attendance_and_creates_history(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);

        $payload = ['message' => 'テスト削除'];

        $response = $this->actingAs($this->admin, 'admin')
            ->deleteJson("/api/admin/attendances/{$attendance->id}", $payload);

        $response->assertOk()
            ->assertJson(['message' => '受講情報を削除しました。']);

        $this->assertDatabaseMissing('attendances', ['id' => $attendance->id]);

        $this->assertDatabaseHas('attendance_histories', [
            'attendance_id' => $attendance->id,
            'user_id' => $user->id,
            'message' => 'テスト削除',
        ]);
    }
}
