<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ url('/css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css">
    @stack('css_import')
    <style>
        body {
            background-color: #f8f8fb;
        }
    </style>
    @stack('css')

    <script src="https://{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
    <script src="{{ url('/js/app.js') }}"></script>
    @stack('js_import')
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
    @stack('js')
</head>

<body>
@hasSection('default_content')
    @yield('default_content')
@else
    <div class="container">
        @yield('content')
    </div>
@endif
</body>

</html>