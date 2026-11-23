<?php

namespace App\Http\Resources;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin JobApplication
 */
class JobApplicationResource extends JsonResource
{
	/**
	 * Wandelt die Bewerbung in JSON um.
	 *
	 * @param Request $request
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->getId(),
			'company' => $this->getCompanyName(),
			'position' => $this->getJobTitle(),
			'url' => $this->getJobUrl(),
			'location' => $this->getLocation(),
			'remote' => $this->getIsRemote(),
			'salary_min' => $this->getSalaryMin(),
			'salary_max' => $this->getSalaryMax(),
			'status' => $this->getStatus()->value,
			'status_label' => $this->getStatus()->label(),
			'applied_at' => $this->getAppliedAt()?->toDateString(),
			'reminded_at' => $this->getRemindedAt()?->toIso8601String(),
			'edit_url' => config('app.url').route('applications.edit', $this->resource, false),
		];
	}
}
