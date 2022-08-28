<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'nickname' => "닉" . (fake()->name()),
            'email' => fake()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'type' => 'U',
            'active_status' => 'inactive',
            'login_platform' => '',
            'sns_id' => '',
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function superadmin()
    {
        return $this->state([
            'type' => 'S'
        ]);
    }

    public function admin()
    {
        return $this->state([
            'type' => 'A'
        ]);
    }

    public function active_status($status = 'active')
    {
        return $this->state([
            'active_status' => $status
        ]);
    }

    public function has_sns($platform = 'facebook')
    {
        return $this->state([
            'login_platform' => $platform,
            'sns_id' => mt_rand(100000000000, 999999999999)
        ]);
    }
}
