<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PokéGallery - Area Ospiti</title>

    {{-- "viteReactRefresh" è importante, fondamentale, per far funzionare React --}}
    @viteReactRefresh
    @vite(['resources/scss/app.scss', 'resources/js/app.jsx'])
</head>

<body class="bg-light">

    {{-- React "Guest" --}}
    <div id="react-root"></div>

</body>

</html>
