<?php

namespace Database\Factories;

use App\Enums\ApplicationStatus;
use App\Models\JobApplication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobApplication>
 */
class JobApplicationFactory extends Factory
{
	/**
	 * Standardwerte für Testdaten.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		$salary_min = fake()->numberBetween(38, 55) * 1000;

		return [
			JobApplication::$fields['companyName'] => fake()->company(),
			JobApplication::$fields['jobTitle'] => fake()->randomElement([
				'Laravel Entwickler (m/w/d)',
				'Fullstack Developer PHP/Laravel',
				'Backend Engineer Laravel',
			]),
			JobApplication::$fields['jobUrl'] => fake()->url(),
			JobApplication::$fields['location'] => fake()->city(),
			JobApplication::$fields['isRemote'] => fake()->boolean(),
			JobApplication::$fields['salaryMin'] => $salary_min,
			JobApplication::$fields['salaryMax'] => $salary_min + 10000,
			JobApplication::$fields['status'] => ApplicationStatus::Saved,
		];
	}

	/**
	 * Setzt einen bestimmten Status.
	 *
	 * @param ApplicationStatus $status
	 * @return static
	 */
	public function status(ApplicationStatus $status): static
	{
		return $this->state(function () use ($status) {
			return [JobApplication::$fields['status'] => $status];
		});
	}
}
