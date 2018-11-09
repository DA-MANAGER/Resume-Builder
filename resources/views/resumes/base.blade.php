<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Resume</title>
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        @stack('app_header')
    </head>

    <body>
        <div id="app">
            <div id="page" class="site-main">
                @yield('app_content')
            </div>
        </div>

        @stack('app_footer')

        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
