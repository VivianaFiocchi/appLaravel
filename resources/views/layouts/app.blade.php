<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .btn {
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-800">
    <!-- Barra de navegación -->
    <nav class="bg-blue-500 text-white py-4">
        <div class="container mx-auto px-4">
            <span class="text-lg font-bold">XY</span> <!-- Nombre de la empresa -->
        </div>
    </nav>

        @yield('content')


    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-4">
        <div class="container mx-auto px-4">
            <p class="text-center">Contacto: example@example.com | Teléfono: +123456789</p>
        </div>
    </footer>
</body>

</html>
