<?php

namespace App\Http\Controllers\Api;

use App\Enums\ApplicationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreJobApplicationRequest;
use App\Http\Resources\JobApplicationResource;
use App\Models\JobApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobApplicationController extends Controller
{
	/**
	 * Legt eine Stelle als gemerkt an.
	 *
	 * @param StoreJobApplicationRequest $request
	 * @return JsonResponse
	 */
	public function store(StoreJobApplicationRequest $request): JsonResponse
	{
		$existing_application = JobApplication::whereJobUrl($request->input('url'))
			->first();

		if ($existing_application != null) {
			return (new JobApplicationResource($existing_application))
				->response()
				->setStatusCode(200);
		}

		$application = new JobApplication();
		$application->setCompanyName($request->input('company'));
		$application->setJobTitle($request->input('position'));
		$application->setJobUrl($request->input('url'));
		$application->setLocation($request->input('location'));
		$application->setIsRemote($request->boolean('remote'));
		$application->setSalaryMin($request->filled('salary_min') ? $request->integer('salary_min') : null);
		$application->setSalaryMax($request->filled('salary_max') ? $request->integer('salary_max') : null);
		$application->setNotes($request->input('notes'));
		$application->setStatus(ApplicationStatus::Saved);
		$application->save();

		return (new JobApplicationResource($application))
			->response()
			->setStatusCode(201);
	}

	/**
	 * Liefert fällige Follow-ups.
	 *
	 * @return AnonymousResourceCollection
	 */
	public function followUps(): AnonymousResourceCollection
	{
		$applications = JobApplication::followUpDue()
			->orderBy(JobApplication::$fields['appliedAt'])
			->get();

		return JobApplicationResource::collection($applications);
	}

	/**
	 * Markiert eine Bewerbung als erinnert.
	 *
	 * @param JobApplication $application
	 * @return JobApplicationResource
	 */
	public function reminded(JobApplication $application): JobApplicationResource
	{
		$application->markReminded();

		return new JobApplicationResource($application);
	}
}
