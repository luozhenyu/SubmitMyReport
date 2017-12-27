@extends('layouts.default')

@section('default_content')
    <div class="container">
        <div class="col-md-4 col-md-offset-4"
             style="border-radius: 5px; box-shadow: 0 2px 7px #bbbbbb; background-color: white; padding: 0;">
            <div class="bg-primary text-center"
                 style="border-radius: 5px; padding-top: 20px; padding-bottom: 15px; box-shadow: 0 2px 7px #bbbbbb;">
                <h3 style="color: white; font-weight: normal;">{{ config('app.name') }}</h3>
            </div>
            <div style="padding: 10px">
                @yield('content')
            </div>
        </div>
    </div>
@endsection
