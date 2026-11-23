<?php

use App\Models\User;

it('creates a user and a token for n8n', function () {
	$this->artisan('app:api-token')
		->expectsOutputToContain('Neuer API-Token')
		->assertSuccessful();

	$user = User::whereEmail('n8n@jobtrail.local')
		->sole();

	expect($user->tokens()->count())->toBe(1);
});

it('replaces the old token when run again', function () {
	$this->artisan('app:api-token');
	$this->artisan('app:api-token');

	$user = User::whereEmail('n8n@jobtrail.local')
		->sole();

	expect(User::count())->toBe(1)
		->and($user->tokens()->count())->toBe(1);
});
