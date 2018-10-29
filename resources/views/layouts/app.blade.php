<html>
    @include('layouts.inc.head')
    <body>
        <div id="app">
            @include('layouts.inc.header')
            @yield('content')
            @include('layouts.inc.footer')
        </div>
        @include('layouts.inc.scripts')
    </body>
</html>