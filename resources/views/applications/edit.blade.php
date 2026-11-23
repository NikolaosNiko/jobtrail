<x-layouts.app title="Bewerbung bearbeiten">
	<div class="mx-auto max-w-2xl">
		<h2 class="mb-6 text-xl font-semibold">{{ $application->getCompanyName() }} bearbeiten</h2>

		<form method="POST" action="{{ route('applications.update', $application) }}">
			@method('PUT')
			@include('applications._form')

			<div class="mt-6 flex justify-end gap-3">
				<a href="{{ route('board') }}" class="px-4 py-2 text-sm text-gray-600">Abbrechen</a>
				<button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
					Speichern
				</button>
			</div>
		</form>

		<form method="POST" action="{{ route('applications.destroy', $application) }}"
			  onsubmit="return confirm('Bewerbung wirklich löschen?')" class="mt-10 border-t pt-6">
			@csrf
			@method('DELETE')
			<button type="submit" class="text-sm text-red-600 hover:underline">Bewerbung löschen</button>
		</form>
	</div>
</x-layouts.app>
