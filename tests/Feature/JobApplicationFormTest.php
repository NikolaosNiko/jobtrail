<?php

use App\Enums\ApplicationStatus;
use App\Models\JobApplication;

/**
 * Gültige Formulardaten.
 *
 * @param array $overrides
 * @return array
 */
function validApplication(array $overrides = []): array
{
	return array_merge([
		'company' => 'InnoGE',
		'position' => 'Laravel Entwickler',
		'url' => 'https://example.com/job',
		'location' => 'Fulda',
		'remote' => '1',
		'salary_min' => 48000,
		'salary_max' => 56000,
		'status' => ApplicationStatus::Saved->value,
	], $overrides);
}

it('shows the create form', function () {
	$this->get(route('applications.create'))
		->assertOk()
		->assertSee('Neue Bewerbung');
});

it('stores a new application', function () {
	$this->post(route('applications.store'), validApplication())
		->assertRedirect(route('board'))
		->assertSessionHas('status');

	$application = JobApplication::sole();

	expect($application->getCompanyName())->toBe('InnoGE')
		->and($application->getIsRemote())->toBeTrue()
		->and($application->getSalaryMin())->toBe(48000)
		->and($application->getStatus())->toBe(ApplicationStatus::Saved);
});

it('stores remote as false when the checkbox is not sent', function () {
	$data = validApplication();
	unset($data['remote']);

	$this->post(route('applications.store'), $data);

	expect(JobApplication::sole()->getIsRemote())->toBeFalse();
});

it('stores an empty salary as null', function () {
	$this->post(route('applications.store'), validApplication(['salary_min' => null, 'salary_max' => null]));

	expect(JobApplication::sole()->getSalaryMin())->toBeNull();
});

it('requires company and position', function () {
	$this->post(route('applications.store'), validApplication(['company' => '', 'position' => '']))
		->assertSessionHasErrors(['company', 'position']);

	expect(JobApplication::count())->toBe(0);
});

it('rejects a maximum salary below the minimum', function () {
	$this->post(route('applications.store'), validApplication(['salary_min' => 50000, 'salary_max' => 40000]))
		->assertSessionHasErrors('salary_max');
});

it('accepts a maximum salary without a minimum', function () {
	$this->post(route('applications.store'), validApplication(['salary_min' => null, 'salary_max' => 50000]))
		->assertSessionHasNoErrors();
});

it('rejects an unknown status', function () {
	$this->post(route('applications.store'), validApplication(['status' => 'hired']))
		->assertSessionHasErrors('status');
});

it('shows the edit form with existing values', function () {
	$application = JobApplication::factory()->create([JobApplication::$fields['companyName'] => 'Händlerbund']);

	$this->get(route('applications.edit', $application))
		->assertOk()
		->assertSee('Händlerbund');
});

it('updates an application', function () {
	$application = JobApplication::factory()->create();

	$this->put(route('applications.update', $application), validApplication(['status' => ApplicationStatus::Interview->value]))
		->assertRedirect(route('board'));

	$application = $application->fresh();

	expect($application->getCompanyName())->toBe('InnoGE')
		->and($application->getStatus())->toBe(ApplicationStatus::Interview);
});

it('deletes an application', function () {
	$application = JobApplication::factory()->create();

	$this->delete(route('applications.destroy', $application))
		->assertRedirect(route('board'));

	expect(JobApplication::count())->toBe(0);
});

it('sets the applied date when stored as applied without a date', function () {
	$this->post(route('applications.store'), validApplication(['status' => ApplicationStatus::Applied->value]));

	expect(JobApplication::sole()->getAppliedAt()->isToday())->toBeTrue();
});

it('keeps an entered applied date', function () {
	$this->post(route('applications.store'), validApplication([
		'status' => ApplicationStatus::Applied->value,
		'applied_at' => '2026-11-01',
	]));

	expect(JobApplication::sole()->getAppliedAt()->toDateString())->toBe('2026-11-01');
});
