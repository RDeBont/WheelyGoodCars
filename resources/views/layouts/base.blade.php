<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wheely Good Cars</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>

<body>

        @include('layouts.header')
        
    @include('components.alert')


    <main>
        {{ $slot }}
    </main>

    <footer>
    </footer>
</body>
<script>

    setTimeout(function () {
        const bubble = document.getElementById('alert-bubble');
        if (bubble) {
            bubble.style.opacity = '0';
            setTimeout(() => bubble.remove(), 500);
        }
    }, 5000);
</script>


</html>