<?php

namespace App\Livewire;

use App\Enums\ApplicationStatus;
use App\Models\JobApplication;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Board')]
class Board extends Component
{
	/**
	 * Verschiebt eine Karte in eine Spalte.
	 *
	 * @param int $id
	 * @param string $status
	 * @return void
	 */
	public function move(int $id, string $status): void
	{
		$application = JobApplication::findOrFail($id);
		$application->moveTo(ApplicationStatus::from($status));
	}

	/**
	 * Rendert das Board.
	 *
	 * @return View
	 */
	public function render(): View
	{
		$applications = JobApplication::newestFirst()
			->get()
			->groupBy(function (JobApplication $application) {
				return $application->getStatus()->value;
			});

		return view('livewire.board', [
			'columns' => ApplicationStatus::cases(),
			'applications' => $applications,
		]);
	}
}
