<?php

use App\Http\Controllers\Api\JobApplicationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
	->group(function () {
		Route::post('/applications', [JobApplicationController::class, 'store'])
			->name('api.applications.store');

		Route::get('/applications/follow-ups', [JobApplicationController::class, 'followUps'])
			->name('api.applications.follow-ups');

		Route::post('/applications/{application}/reminded', [JobApplicationController::class, 'reminded'])
			->name('api.applications.reminded');
	});
