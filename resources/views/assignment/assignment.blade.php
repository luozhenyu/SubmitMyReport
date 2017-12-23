@extends('layout')

@section('title', 'Assignment')

@section('content')
    <div class="container">
        <div class="row" style="height: 30px;"></div>
        <div class="col col-sm-12 offset-sm-0 col-md-10 offset-md-1">
            <div class="container" style="height:85%; overflow: scroll; border-radius: 5px; box-shadow: 0px 2px 7px #bbbbbb;">
                <div class="row" style="padding-top: 20px; padding-left: 30px; padding-right: 30px;">
                    <a href="manage" role="button" class="btn btn-outline-primary">Back</a>
                    &nbsp&nbsp
                    <button type="button" class="btn btn-outline-danger">Delete</button>
                </div>
                <div class="row" style="padding-top: 20px; padding-left: 30px; padding-right: 30px;">
                    <h4>{{$assignment_title}}</h4>
                </div>
                <div class="row" style="padding-left: 30px; padding-right: 30px;">
                    Deadline: {{$ddl}}
                </div>
                <div class="jumbotron" style="margin-top: 20px; margin-left: 17px; margin-right: 17px;">
                    {{$description}}
                </div>
                <div class="row" style="padding-left: 30px; padding-right: 30px;">
                    <h5>Submissions:</h5>
                </div>
                <div class="row" style="padding-left: 30px; padding-right: 30px;">
                    @if($submissions && count($submissions, 0) > 0)
                        @foreach($submissions as $sub)
                            @include('assignment.submission_item')
                        @endforeach
                    @else
                        @include('empty_view');
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection