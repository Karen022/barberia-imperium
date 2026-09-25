<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Barbería Imperium')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-neutral-900 text-white antialiased">

    @include('landing.includes.navbar')

    <main>

        <x-flash-message />
        
        @yield('content')
    </main>

    @include('landing.includes.footer')



</body>

</html>