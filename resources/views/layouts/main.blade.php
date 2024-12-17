<!-- Este es tu layout principal, normalmente llamado layout.blade.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard CORONA</title>
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container">
        @include('layouts.partials.siderbar') <!-- Este incluye el sidebar -->
        <main class="main-content">
            @include('layouts.partials.header') <!-- Este incluye el header -->
            @yield('content') <!-- Aquí se va a mostrar el contenido -->
        </main>
    </div>
    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>

</html>
