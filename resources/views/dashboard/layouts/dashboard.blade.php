<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Barbería</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>

<body class="bg-zinc-950 text-white min-h-screen" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

    {{-- CAMBIO AQUÍ: Eliminamos overflow-x-hidden del contenedor raíz --}}
    <div class="flex min-h-screen w-full">
        {{-- Sidebar --}}
        @include('dashboard.includes.sidebar')

        <div 
            x-show="sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-30 bg-black/50 lg:hidden"
            x-transition
        ></div>

        {{-- CAMBIO CLAVE AQUÍ: w-px y min-w-0 controlan el comportamiento de Flexbox --}}
        <div class="flex-1 min-w-0 w-px flex flex-col">
            {{-- Navbar --}}
            @include('dashboard.includes.navbar')

            {{-- CAMBIO AQUÍ: Eliminamos overflow-x-hidden para que el scroll hijo funcione --}}
            <main class="flex-1 p-4 sm:p-6 bg-zinc-900">
                <x-flash-message />
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
