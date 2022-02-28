<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Weather App</title>
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <link href="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css" rel="stylesheet">
        <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.2/mapbox-gl-geocoder.css" type="text/css">
        <style>
            #geocoder {
            z-index: 1;
            }
            .mapboxgl-ctrl-geocoder {
            min-width: 100%;
            }
        </style>
        
        <script src="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js"></script>
        <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.2/mapbox-gl-geocoder.min.js"></script>
        <script src="/js/skycons.js" defer></script>
        <script src="/js/app.js" defer></script>
    </head>
    <body class="bg-blue-200">
        <div class="flex justify-center pt-16">
            <div id="geocoder" class="w-128 max-w-lg"></div>
        </div>
        <div id="app" class="flex justify-center pt-4">
            <weather-component></weather-component>
        </div>
    </body>
</html>
