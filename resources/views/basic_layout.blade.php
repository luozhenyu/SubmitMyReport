<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ config('app.name') }}</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootswatch/4.0.0-beta.2/litera/bootstrap.min.css" rel="stylesheet">
    @stack('css')

    <script src="https://cdn.bootcss.com/jquery/1.12.3/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/4.0.0-beta.2/js/bootstrap.bundle.min.js"></script>
    @stack('js')

    <style>
        body {
            background-color: #f8f8fb;
        }
    </style>
</head>

<body>


@yield('content')


</body>

</html>