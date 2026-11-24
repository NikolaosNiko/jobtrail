<?php

use App\Enums\ApplicationStatus;
use App\Livewire\Board;
use App\Models\JobApplication;
use Livewire\Livewire;

it('shows all status columns', function () {
	$this->get('/')
		->assertOk()
		->assertSee(['Gemerkt', 'Beworben', 'Gespräch', 'Angebot', 'Absage']);
});

it('lists applications on the board', function () {
	JobApplication::factory()->create([JobApplication::$fields['companyName'] => 'Acme GmbH']);

	Livewire::test(Board::class)
		->assertSee('Acme GmbH');
});

it('moves an application to another column', function () {
	$application = JobApplication::factory()->create();

	Livewire::test(Board::class)
		->call('move', $application->getId(), ApplicationStatus::Interview->value);

	expect($application->fresh()->getStatus())->toBe(ApplicationStatus::Interview);
});

it('sets the applied date when moved to applied', function () {
	$application = JobApplication::factory()->create([JobApplication::$fields['appliedAt'] => null]);

	Livewire::test(Board::class)
		->call('move', $application->getId(), ApplicationStatus::Applied->value);

	expect($application->fresh()->getAppliedAt())->not->toBeNull();
});

it('shows only the minimum salary when no maximum is set', function () {
	JobApplication::factory()->create([
		JobApplication::$fields['salaryMin'] => 45000,
		JobApplication::$fields['salaryMax'] => null,
	]);

	Livewire::test(Board::class)
		->assertSee('45k €')
		->assertDontSee('45–0k €');
});
