<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'course_id' => Course::factory(),
            'status' => $this->faker->randomElement(['参加', '完了', 'キャンセル']),
        ];
    }
}
