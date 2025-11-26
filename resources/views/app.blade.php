 <!DOCTYPE html>
 <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>C B T</title>
     <link rel="icon" href="{{ url('/favicon1.ico') }}">
     <link class="js-stylesheet" href="{{ secure_asset('css/light.css') }}" rel="stylesheet">
     </link>
     <!-- Styles / Scripts -->
     @vite(['resources/css/app.css', 'resources/js/app.js'])
 </head>

 <body>
     <div id="app"></div>
     <script src="{{ secure_asset('js/app.js') }}"></script>
 </body>

 </html>
