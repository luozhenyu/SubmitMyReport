<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name') }}</title>

    <link href="{{ url('components/bootswatch/cerulean/bootstrap.min.css') }}" rel="stylesheet">
    @stack('css')

    <script src="{{ url('components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ url('components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    @stack('js')

    <style>
        body {
            background-color: #f8f8fb;
        }
    </style>
</head>

<body>
@yield('default_content')
</body>

</html>