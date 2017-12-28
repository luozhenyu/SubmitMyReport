@extends('layouts.basic')

@section('title', 'New Group')

@section('navbar')
    <li><a href="{{ route('home') }}">Home</a></li>
    <li class="active"><a>Group</a></li>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('group') }}">Group</a></li>
        <li><a href="{{ url("group/{$group->id}") }}">{{ $group->name }}</a></li>
        <li class="active">New Assignment</li>
    </ol>
@endsection

@section('content')
    <form class="form-horizontal" method="POST" action="{{ url("group/{$group->id}/store") }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
            <label for="title" class="col-md-2 control-label">Title</label>

            <div class="col-md-8">
                <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}" required>

                @if ($errors->has('title'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
            <label for="description" class="col-md-2 control-label">Description</label>

            <div class="col-md-8">
                <textarea id="description" class="form-control" name="description" rows="6"
                          required>{{ old('description') }}</textarea>

                @if ($errors->has('description'))
                    <span class="help-block">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-6">
                <button type="submit" class="btn btn-primary">
                    Submit
                </button>
            </div>
        </div>
    </form>
@endsection