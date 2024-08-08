<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <script src="{{ asset('html5-qrcode.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @stack('prepend-styles')
    @include('includes.styles')
    @stack('addon-styles')
    @yield('style')

</head>

<body>
    <div id="app">
        @include('includes.sidebar')
        <div id="main">
            @yield('content')

            @include('includes.footer')
        </div>
    </div>
    @stack('prepend-scripts')
    @include('includes.scripts')
    @stack('addon-script')
</body>

</html>
