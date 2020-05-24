<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <script defer src="https://use.fontawesome.com/releases/v5.0.4/js/all.js"></script>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
		<x-toolbar :page="$page"/>
		
		<x-nav>
			<a href="/" class="Xh3 navbar-brand">{{ config('app.name', 'Laravel') }}</a>
		</x-nav>

		
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
