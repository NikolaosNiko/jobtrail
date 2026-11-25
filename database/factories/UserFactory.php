<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
	protected static ?string $password;

	/**
	 * Standardwerte für Testdaten.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			User::$fields['name'] => fake()->name(),
			User::$fields['email'] => fake()->unique()->safeEmail(),
			User::$fields['emailVerifiedAt'] => now(),
			User::$fields['password'] => static::$password ??= Hash::make('password'),
			'remember_token' => Str::random(10),
		];
	}
}
