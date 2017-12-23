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
        body{
            background-color: #f8f8fb;
        }
    </style>
</head>

<body>

<div class="container" style="height: 100%;">
    <div class="row align-items-center" style="height: 100%">
        <div class="col col-sm-12 offset-sm-0 col-md-4 offset-md-4" style="border-radius: 5px; box-shadow: 0px 2px 7px #bbbbbb; background-color: white; padding: 0px;">
            <div class="bg-primary text-center" style="border-radius: 5px; padding-top: 20px; padding-bottom: 15px; box-shadow: 0px 2px 7px #bbbbbb;">
                <h5 style="color: white; font-weight: normal;">{{ config('app.name') }}</h5>
            </div>
            <div style="padding: 10px">
                @yield('content')
            </div>
        </div>
    </div>
</div>

</body>

</html>