<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imperium Barber</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-dark text-white min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-darkGray rounded-xl shadow-xl p-8 border border-midGray">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gold">Imperium</h1>
            <p class="text-sm text-gray-400">Barbería profesional</p>
        </div>

        {{ $slot }}
    </div>

</body>
</html>
