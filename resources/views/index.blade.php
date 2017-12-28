@extends('layouts.default')

@section('title', 'Home')

@section('default_content')
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse" aria-expanded="false">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a>Home</a></li>
                    <li><a href="{{ route('group') }}">Group</a></li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    @guest
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false" aria-haspopup="true">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row" style="height: 30px"></div>
        <div class="row">
            @if($current_group)
                <div class="col-md-2 col-md-offset-1 text-center">
                    <h4>Groups</h4>
                    <div class="list-group">
                        @foreach($groups as $group)
                            <a class="list-group-item{{ $group->id === $current_group->id? ' active' :'' }}"
                               href="{{ url('')."?group={$group->id}" }}">
                                {{ $group->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-8" style="background-color: white;padding-top: 15px;margin-bottom:30px; border-radius:5px; box-shadow: 0px 3px 7px #bbbbbb; min-height: 500px;">
                    <table class="table table-striped table-hover text-left">
                        <caption><h4>Assignments</h4></caption>
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Author</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($current_group->assignments as $assignment)
                            <tr>
                                <td>{{ $assignment->title }}</td>
                                <td class="col-md-5">{{ $assignment->description }}</td>
                                <td>{{ $assignment->user->name }}</td>
                                <td>
                                    @if(!$submission = $assignment->submissions()->where('user_id',Auth::user()->id)->first())
                                        <a class="btn btn-danger btn-sm"
                                           href="{{ url("assignment/{$assignment->id}/create") }}">
                                            To do
                                        </a>
                                    @elseif(!$submission->corrected())
                                        <span class="btn btn-success btn-sm" disabled>
                                    Submitted
                                </span>
                                    @else
                                        <a class="btn btn-primary btn-sm"
                                           href="{{ url("submission/{$submission->id}/score") }}">
                                            {{ "Score {$submission->score}" }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <h3>First, join a group.</h3>
            @endif
        </div>
    </div>
@endsection