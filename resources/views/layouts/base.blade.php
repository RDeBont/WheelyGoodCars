<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wheely Good Cars</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

        @include('layouts.header')
        
    @include('components.alert')


    <main>
        {{ $slot }}
    </main>

    <footer>
    </footer>
    @livewireScripts
    <script>

    setTimeout(function () {
        const bubble = document.getElementById('alert-bubble');
        if (bubble) {
            bubble.style.opacity = '0';
            setTimeout(() => bubble.remove(), 500);
        }
    }, 5000);
    </script>
</body>


</html>