<div class="flex gap-4 overflow-x-auto pb-4">
	@foreach ($columns as $column)
		<section
			wire:key="column-{{ $column->value }}"
			x-data="{ over: false }"
			x-on:dragover.prevent="over = true"
			x-on:dragleave="over = false"
			x-on:drop.prevent="over = false; $wire.move($event.dataTransfer.getData('id'), '{{ $column->value }}')"
			:class="over ? 'bg-indigo-50 ring-2 ring-indigo-300' : 'bg-gray-100'"
			class="flex w-72 shrink-0 flex-col gap-3 rounded-xl p-3"
		>
			<h2 class="flex items-center justify-between text-sm font-semibold text-gray-700">
				{{ $column->label() }}
				<span class="rounded-full bg-white px-2 text-xs text-gray-500">
					{{ $applications->get($column->value)?->count() ?? 0 }}
				</span>
			</h2>

			@foreach ($applications->get($column->value, collect()) as $application)
				<article
					wire:key="application-{{ $application->getId() }}"
					draggable="true"
					x-on:dragstart="$event.dataTransfer.setData('id', {{ $application->getId() }})"
					class="cursor-grab rounded-lg bg-white p-3 shadow-sm"
				>
					<a href="{{ route('applications.edit', $application) }}" class="font-medium text-gray-900 hover:underline">{{ $application->getCompanyName() }}</a>
					<p class="text-sm text-gray-600">{{ $application->getJobTitle() }}</p>
					<p class="mt-2 flex gap-2 text-xs text-gray-500">
						@if ($application->getIsRemote())
							<span class="rounded bg-green-100 px-1.5 text-green-700">Remote</span>
						@endif
						@if ($application->getSalaryMin())
							<span>{{ number_format($application->getSalaryMin() / 1000) }}{{ $application->getSalaryMax() ? '–' . number_format($application->getSalaryMax() / 1000) : '' }}k €</span>
						@endif
					</p>
				</article>
			@endforeach
		</section>
	@endforeach
</div>
