@extends('layouts.basic')

@section('default_content')
    <div class="row m-3">
        <div class="col-md-4 offset-md-4 p-0 bg-white"
             style="border-radius: 5px; box-shadow: 0 2px 7px #bbbbbb;">
            <div class="bg-primary text-center p-2"
                 style="border-radius: 5px; box-shadow: 0 2px 7px #bbbbbb;">
                <h3 class="text-white font-weight-normal">{{ config('app.name') }}</h3>
            </div>
            <div class="p-2">
                @yield('content')
            </div>
        </div>
    </div>
@endsection
