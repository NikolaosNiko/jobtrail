<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ $title ?? 'JobTrail' }} · {{ config('app.name') }}</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	@livewireStyles
</head>
<body class="min-h-screen bg-white text-gray-900 antialiased">
	<header class="flex items-center justify-between border-b px-6 py-4">
		<a href="{{ route('board') }}" class="text-lg font-semibold">JobTrail</a>
		<a href="{{ route('applications.create') }}"
		   class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
			+ Neue Bewerbung
		</a>
	</header>

	<main class="p-6">
		@if (session('status'))
			<p class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('status') }}</p>
		@endif

		{{ $slot }}
	</main>

	@livewireScripts
</body>
</html>
