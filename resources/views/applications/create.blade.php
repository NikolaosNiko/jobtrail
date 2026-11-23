<x-layouts.app title="Neue Bewerbung">
	<div class="mx-auto max-w-2xl">
		<h2 class="mb-6 text-xl font-semibold">Neue Bewerbung</h2>

		<form method="POST" action="{{ route('applications.store') }}">
			@include('applications._form')

			<div class="mt-6 flex justify-end gap-3">
				<a href="{{ route('board') }}" class="px-4 py-2 text-sm text-gray-600">Abbrechen</a>
				<button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
					Speichern
				</button>
			</div>
		</form>
	</div>
</x-layouts.app>
