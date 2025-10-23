<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Owner;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Owner>
 */
class OwnerFactory extends Factory
{
    protected $model = Owner::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'kana' => $this->faker->name(),
            'company_name' => $this->faker->company(),
            'company_kana' => $this->faker->name(),
            'contact_zip' => $this->faker->postcode(),
            'contact_address' => $this->faker->address(),
            'contact_tel' => $this->faker->unique()->phoneNumber(),
            'secret_zip' => $this->faker->postcode(),
            'secret_address' => $this->faker->address(),
            'secret_tel' => $this->faker->unique()->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
        ];
    }
}
