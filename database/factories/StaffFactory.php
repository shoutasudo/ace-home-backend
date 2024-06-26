<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Staff>
 */
class StaffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create('ja_JP');

        $roles = [
            '事務員',
            '管理職',
            '社長',
            '副社長',
        ];

        return [
            'name' => $faker->name,
            'comment' => $faker->realText(18),
            'image_path' => 'public\images\518x350.png',
            'role' => $faker->randomElement($roles),
        ];
    }
}
