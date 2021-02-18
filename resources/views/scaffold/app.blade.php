<!DOCTYPE html>
<html>
<head>
    <title>@yield('page', app_name())</title>    
    <link rel="icon" type="image/png" href="/tg/images/favicon.svg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no" />

    @include('snippets.fonts')
    
    <link type="text/css" rel="stylesheet" href="{{ asset('css/app.css') }}">

</head>
<body class="min-vh-100 d-flex flex-column">
    <header id="vue-header">
        @include('layout.nav')
    </header>
    
	<main id="vue-main">
		<div class="main-inner container my-4">
            @yield('content')
		</div>
    </main>
    
    <footer id="vue-footer">
        @include('layout.footer')
    </footer>
    
    <script src="{{ asset('js/app.js') }}"></script>

    @yield('after-script')
</body>
</html>
