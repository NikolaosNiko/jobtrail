@props(['name', 'label', 'options' => [], 'value' => null])

<div>
	<label for="{{ $name }}" class="mb-1 block text-sm font-medium text-gray-700">{{ $label }}</label>
	<select id="{{ $name }}" name="{{ $name }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
		@foreach ($options as $option_value => $option_label)
			<option value="{{ $option_value }}" @selected(old($name, $value) == $option_value)>{{ $option_label }}</option>
		@endforeach
	</select>
	@error($name)
		<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
	@enderror
</div>
