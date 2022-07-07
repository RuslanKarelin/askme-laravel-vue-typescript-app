<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <script defer src="{{ mix('js/app-client.js') }}"></script>
        <title>Laravel</title>
    </head>
    <body>
        <div id="app">
            {!! ssr('js/app-server.js')->render() !!}
        </div>
        
    </body>
</html>