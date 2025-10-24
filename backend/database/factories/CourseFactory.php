<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Owner;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'owner_id' => Owner::factory(),
            'course_title' => $this->faker->sentence(3),
            'content' => $this->faker->paragraph(5),
            'course_image' => $this->faker->imageUrl(640, 480, 'education', true, 'Course'),
            'instructor' => $this->faker->name(),
            'instructor_title' => $this->faker->jobTitle(),
            'date_time' => $this->faker->dateTimeBetween('+1 days', '+3 months'),
            'participation_fee' => $this->faker->numberBetween(1000, 10000) . '円',
            'additional_fee' => $this->faker->optional()->numberBetween(500, 2000) . '円',
            'capacity' => $this->faker->numberBetween(5, 50) . '名',
            'venue' => $this->faker->company() . 'ホール',
            'venue_zip' => $this->faker->postcode(),
            'venue_address' => $this->faker->address(),
            'tel' => $this->faker->optional()->phoneNumber(),
            'email' => $this->faker->optional()->safeEmail(),
            'map' => $this->faker->optional()->url(),
            'status' => $this->faker->randomElement(['draft', 'published', 'closed']),
        ];
    }
}
