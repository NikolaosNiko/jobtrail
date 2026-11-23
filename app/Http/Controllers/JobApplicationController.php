<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Http\Requests\JobApplicationRequest;
use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
	/**
	 * Zeigt das Formular zum Anlegen.
	 *
	 * @return View
	 */
	public function create(): View
	{
		$application = new JobApplication();
		$application->setStatus(ApplicationStatus::Saved);

		return view('applications.create', ['application' => $application]);
	}

	/**
	 * Speichert eine neue Bewerbung.
	 *
	 * @param JobApplicationRequest $request
	 * @return RedirectResponse
	 */
	public function store(JobApplicationRequest $request): RedirectResponse
	{
		$application = new JobApplication();
		$this->fillFromRequest($application, $request);
		$application->save();

		return redirect()
			->route('board')
			->with('status', 'Bewerbung angelegt.');
	}

	/**
	 * Zeigt das Formular zum Bearbeiten.
	 *
	 * @param JobApplication $application
	 * @return View
	 */
	public function edit(JobApplication $application): View
	{
		return view('applications.edit', ['application' => $application]);
	}

	/**
	 * Speichert Änderungen an einer Bewerbung.
	 *
	 * @param JobApplicationRequest $request
	 * @param JobApplication $application
	 * @return RedirectResponse
	 */
	public function update(JobApplicationRequest $request, JobApplication $application): RedirectResponse
	{
		$this->fillFromRequest($application, $request);
		$application->save();

		return redirect()
			->route('board')
			->with('status', 'Bewerbung gespeichert.');
	}

	/**
	 * Löscht eine Bewerbung.
	 *
	 * @param JobApplication $application
	 * @return RedirectResponse
	 */
	public function destroy(JobApplication $application): RedirectResponse
	{
		$application->delete();

		return redirect()
			->route('board')
			->with('status', 'Bewerbung gelöscht.');
	}

	/**
	 * Überträgt Formularwerte ins Model.
	 *
	 * @param JobApplication $application
	 * @param JobApplicationRequest $request
	 * @return void
	 */
	private function fillFromRequest(JobApplication $application, JobApplicationRequest $request): void
	{
		$application->setCompanyName($request->input('company'));
		$application->setJobTitle($request->input('position'));
		$application->setJobUrl($request->input('url'));
		$application->setLocation($request->input('location'));
		$application->setIsRemote($request->boolean('remote'));
		$application->setSalaryMin($request->filled('salary_min') ? $request->integer('salary_min') : null);
		$application->setSalaryMax($request->filled('salary_max') ? $request->integer('salary_max') : null);
		$application->setStatus($request->enum('status', ApplicationStatus::class));
		$application->setAppliedAt($request->input('applied_at'));
		$application->setNotes($request->input('notes'));
	}
}
