<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- PWA  -->
    <meta name="theme-color" content="#212529" />
    <link rel="apple-touch-icon" href="{{ asset('logoyf.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />


    <title>Yifang Mobile</title>
</head>

<body style="font-family: 'nunito'; background-image: url({{ asset('images/texture.png') }});">
    {{ $slot }}
    <script src="{{ asset('/sw.js') }}"></script>
    <script>
        if ("serviceWorker" in navigator) {
            // Register a service worker hosted at the root of the
            // site using the default scope.
            navigator.serviceWorker.register("/sw.js").then(
                (registration) => {
                    console.log("Service worker registration succeeded:", registration);
                },
                (error) => {
                    console.error(`Service worker registration failed: ${error}`);
                },
            );
        } else {
            console.error("Service workers are not supported.");
        }
    </script>

</body>

</html>
