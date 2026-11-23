<?php

use App\Enums\ApplicationStatus;

it('has a german label for every status', function (ApplicationStatus $status) {
	expect($status->label())->toBeString()->not->toBeEmpty();
})->with(ApplicationStatus::cases());

it('treats offer and rejection as closed', function () {
	expect(ApplicationStatus::Offer->isClosed())->toBeTrue()
		->and(ApplicationStatus::Rejected->isClosed())->toBeTrue()
		->and(ApplicationStatus::Applied->isClosed())->toBeFalse();
});
