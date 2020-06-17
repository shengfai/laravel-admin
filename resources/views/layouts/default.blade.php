<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="renderer" content="webkit">
        <link rel="shortcut icon" href="{{ settings('site_icon') }}" type="image/x-icon" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>控制台 · {{ settings('site_name') }}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <!-- Styles -->
        <link href="{{ asset('admin/plugs/awesome/css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('admin/plugs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('admin/plugs/layui/css/layui.css') }}" rel="stylesheet">
        <link href="{{ asset('admin/theme/css/console.css') }}" rel="stylesheet">
        @yield('style')
        <script>
            window.ROOT_URL ="{{$baseUrl}}";
        </script>
        <script src="{{ asset('admin/plugs/layui/layui.all.js') }}"></script>
        <script src="{{ asset('admin/js/admin.js') }}?{{ time() }}"></script>
    </head>
    <body class="framework mini">
        @yield('body')
        <script src="{{ asset('admin/plugs/require/require.js') }}"></script>
        <script src="{{ asset('admin/js/app.js') }}"></script>
        @yield('script')
    </body>
</html>
