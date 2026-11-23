<?php

use App\Enums\ApplicationStatus;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\Sanctum;

/**
 * Gültige API-Daten.
 *
 * @param array $overrides
 * @return array
 */
function validApiApplication(array $overrides = []): array
{
	return array_merge([
		'company' => 'InnoGE',
		'position' => 'Laravel Entwickler',
		'url' => 'https://karriere.innoge.de/laravel-entwickler',
		'location' => 'Fulda',
		'remote' => true,
		'salary_min' => 48000,
		'salary_max' => 56000,
	], $overrides);
}

beforeEach(function () {
	Sanctum::actingAs(User::factory()->create());
});

it('rejects requests without a token', function () {
	app('auth')->forgetGuards();

	$this->postJson(route('api.applications.store'), validApiApplication())
		->assertUnauthorized();
});

it('stores a job as saved', function () {
	$this->postJson(route('api.applications.store'), validApiApplication())
		->assertCreated()
		->assertJsonPath('data.company', 'InnoGE')
		->assertJsonPath('data.status', ApplicationStatus::Saved->value);

	expect(JobApplication::sole()->getStatus())->toBe(ApplicationStatus::Saved);
});

it('returns the existing application for a known url', function () {
	$this->postJson(route('api.applications.store'), validApiApplication());

	$this->postJson(route('api.applications.store'), validApiApplication(['company' => 'Andere Firma']))
		->assertOk()
		->assertJsonPath('data.company', 'InnoGE');

	expect(JobApplication::count())->toBe(1);
});

it('validates the payload', function () {
	$this->postJson(route('api.applications.store'), validApiApplication(['company' => '', 'url' => 'kein-link']))
		->assertUnprocessable()
		->assertJsonValidationErrors(['company', 'url']);
});

it('lists only due follow-ups', function () {
	$due = JobApplication::factory()
		->status(ApplicationStatus::Applied)
		->create([JobApplication::$fields['appliedAt'] => Carbon::now()->subDays(15)]);

	JobApplication::factory()
		->status(ApplicationStatus::Applied)
		->create([JobApplication::$fields['appliedAt'] => Carbon::now()->subDays(3)]);

	JobApplication::factory()
		->status(ApplicationStatus::Applied)
		->create([
			JobApplication::$fields['appliedAt'] => Carbon::now()->subDays(20),
			JobApplication::$fields['remindedAt'] => Carbon::now(),
		]);

	JobApplication::factory()
		->status(ApplicationStatus::Interview)
		->create([JobApplication::$fields['appliedAt'] => Carbon::now()->subDays(30)]);

	$this->getJson(route('api.applications.follow-ups'))
		->assertOk()
		->assertJsonCount(1, 'data')
		->assertJsonPath('data.0.id', $due->getId());
});

it('marks an application as reminded', function () {
	$application = JobApplication::factory()
		->status(ApplicationStatus::Applied)
		->create([JobApplication::$fields['appliedAt'] => Carbon::now()->subDays(15)]);

	$this->postJson(route('api.applications.reminded', $application))
		->assertOk();

	expect($application->fresh()->getRemindedAt())->not->toBeNull();

	$this->getJson(route('api.applications.follow-ups'))
		->assertJsonCount(0, 'data');
});
