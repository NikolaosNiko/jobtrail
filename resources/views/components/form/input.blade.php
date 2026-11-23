@props(['name', 'label', 'type' => 'text', 'value' => null])

<div>
	<label for="{{ $name }}" class="mb-1 block text-sm font-medium text-gray-700">{{ $label }}</label>
	<input
		id="{{ $name }}"
		name="{{ $name }}"
		type="{{ $type }}"
		value="{{ old($name, $value) }}"
		{{ $attributes->class([
			'w-full rounded-lg border px-3 py-2 text-sm',
			'border-red-500' => $errors->has($name),
			'border-gray-300' => ! $errors->has($name),
		]) }}
	>
	@error($name)
		<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
	@enderror
</div>
