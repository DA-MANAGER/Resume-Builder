    @include('partials._header')
        <div id="app">
            @include('partials._topnavigation')

            <div id="page" class="site-main">
                @include('partials._errors')

                @yield('app_content')
            </div>

            @include('partials._footer')
        </div>

        @stack('app_footer')

        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
