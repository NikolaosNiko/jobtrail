<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobApplicationRequest extends FormRequest
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
			'url' => ['required', 'url', 'max:2048'],
			'location' => ['nullable', 'string', 'max:255'],
			'remote' => ['nullable', 'boolean'],
			'salary_min' => ['nullable', 'integer', 'min:0'],
			'salary_max' => ['nullable', 'integer', 'min:0', Rule::when($this->filled('salary_min'), 'gte:salary_min')],
			'notes' => ['nullable', 'string', 'max:5000'],
		];
	}
}
