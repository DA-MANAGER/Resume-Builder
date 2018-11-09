<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>{{ config('app.name', 'Resume Builder') }}</title>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Stylesheet -->
        <link href="{{ mix('css/app.css') }}" rel="stylesheet" type="text/css">
        <script src="https://use.fontawesome.com/8efcdd9cc3.js"></script>

        @stack('app_header')
    </head>

    <body>