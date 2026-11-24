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

	/**
	 * Legt Beispieldaten an.
	 *
	 * @return void
	 */
	public function run(): void
	{
		$user = new User();
		$user->setName('Test User');
		$user->setEmail('test@example.com');
		$user->setPassword('password');
		$user->save();

		foreach (ApplicationStatus::cases() as $status) {
			JobApplication::factory()
				->count(3)
				->status($status)
				->create();
		}
	}
}
