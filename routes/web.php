<?php

use App\Http\Controllers\JobApplicationController;
use App\Livewire\Board;
use Illuminate\Support\Facades\Route;

Route::get('/', Board::class)
	->name('board');

Route::resource('applications', JobApplicationController::class)
	->except(['index', 'show']);
