@csrf

<div class="grid gap-4 sm:grid-cols-2">
	<x-form.input name="company" label="Firma *" :value="$application->getCompanyName()" required />
	<x-form.input name="position" label="Stelle *" :value="$application->getJobTitle()" required />
	<x-form.input name="url" label="Link zur Anzeige" type="url" :value="$application->getJobUrl()" />
	<x-form.input name="location" label="Ort" :value="$application->getLocation()" />
	<x-form.input name="salary_min" label="Gehalt von (€ / Jahr)" type="number" :value="$application->getSalaryMin()" min="0" step="1000" />
	<x-form.input name="salary_max" label="Gehalt bis (€ / Jahr)" type="number" :value="$application->getSalaryMax()" min="0" step="1000" />
	<x-form.select name="status" label="Status" :value="$application->getStatus()?->value" :options="\App\Enums\ApplicationStatus::options()" />
	<x-form.input name="applied_at" label="Beworben am" type="date" :value="$application->getAppliedAt()?->format('Y-m-d')" />
</div>

<label class="mt-4 flex items-center gap-2 text-sm">
	<input type="checkbox" name="remote" value="1" @checked(old('remote', $application->getIsRemote())) class="rounded border-gray-300">
	Remote möglich
</label>

<div class="mt-4">
	<label for="notes" class="mb-1 block text-sm font-medium text-gray-700">Notizen</label>
	<textarea id="notes" name="notes" rows="4" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">{{ old('notes', $application->getNotes()) }}</textarea>
	@error('notes')
		<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
	@enderror
</div>
