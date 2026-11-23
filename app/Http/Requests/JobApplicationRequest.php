<?php

namespace App\Http\Requests;

use App\Enums\ApplicationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobApplicationRequest extends FormRequest
{
	/**
	 * Validierungsregeln.
	 *
	 * @return array<string, mixed>
	 */
	public function rules(): array
	{
		return [
			'company' => ['required', 'string', 'max:255'],
			'position' => ['required', 'string', 'max:255'],
			'url' => ['nullable', 'url', 'max:2048'],
			'location' => ['nullable', 'string', 'max:255'],
			'remote' => ['boolean'],
			'salary_min' => ['nullable', 'integer', 'min:0'],
			'salary_max' => ['nullable', 'integer', 'min:0', Rule::when($this->filled('salary_min'), 'gte:salary_min')],
			'status' => ['required', Rule::enum(ApplicationStatus::class)],
			'applied_at' => ['nullable', 'date'],
			'notes' => ['nullable', 'string', 'max:5000'],
		];
	}

	/**
	 * Bereitet die Checkbox vor.
	 *
	 * @return void
	 */
	protected function prepareForValidation(): void
	{
		$this->merge(['remote' => $this->boolean('remote')]);
	}

	/**
	 * Deutsche Fehlermeldungen.
	 *
	 * @return array<string, string>
	 */
	public function messages(): array
	{
		return [
			'required' => ':attribute ist ein Pflichtfeld.',
			'url' => ':attribute muss eine gültige URL sein.',
			'integer' => ':attribute muss eine ganze Zahl sein.',
			'min' => ':attribute darf nicht negativ sein.',
			'salary_max.gte' => 'Gehalt bis muss mindestens so hoch sein wie Gehalt von.',
			'status' => 'Bitte einen gültigen Status wählen.',
		];
	}

	/**
	 * Deutsche Feldnamen.
	 *
	 * @return array<string, string>
	 */
	public function attributes(): array
	{
		return [
			'company' => 'Firma',
			'position' => 'Stelle',
			'url' => 'Link zur Anzeige',
			'location' => 'Ort',
			'salary_min' => 'Gehalt von',
			'salary_max' => 'Gehalt bis',
			'applied_at' => 'Beworben am',
			'notes' => 'Notizen',
		];
	}
}
