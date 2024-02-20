<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel + Vue SPA</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link rel="stylesheet" href="node_modules/@fortawesome/fontawesome-free/css/all.css">
        
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">

    </head>
    <body class="bg-dark"> 
        
        <div id="app">

            <vue-progress-bar></vue-progress-bar>

            <product-component></product-component>

        </div>


        <script src="node_modules/@fortawesome/fontawesome-free/js/all.js"></script>

        <script src="{{ mix('/js/app.js') }}"></script>

    </body>
</html>
