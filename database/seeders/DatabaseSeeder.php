<?php

namespace Database\Seeders;

use App\Enums\ApplicationStatus;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	use WithoutModelEvents;

	public function run(): void
	{
		User::factory()->create([
			'name' => 'Test User',
			'email' => 'test@example.com',
		]);

		foreach (ApplicationStatus::cases() as $status) {
			JobApplication::factory()->count(3)->status($status)->create();
		}
	}
}
