<!DOCTYPE html>
<html>
    <head>
        <title>Hey - @yield('title', 'Hey App')</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body>
        @include('layouts._header')
        <div class="container">
            <div class="col-md-offset-1 col-md-10">
                @include('shared._messages')
                @yield('content')
                @include('layouts._footer')
            </div>
        </div>
        <script src="{{ asset('js/app.js') }}" ></script>
    </body>
</html>


