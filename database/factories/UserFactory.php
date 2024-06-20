<?php

namespace Database\Factories;

use App\Services\SplitNameIntoNamePartsService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();
        $service = new SplitNameIntoNamePartsService($name);
        $parts = $service->getNameParts();
        return [
            'name' => $name,
            'prefix_name' => $parts['prefix_name'],
            'first_name' => $parts['first_name'],
            'middle_name' => $parts['middle_name'],
            'last_name' => $parts['last_name'],
            'suffix_name' => $parts['suffix_name'],
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'pronoun_id' => fake()->numberBetween(1, 9),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
