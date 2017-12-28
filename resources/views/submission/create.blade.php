@extends('layouts.basic')

@section('title', 'New Submission')

@section('navbar')
    <li><a href="{{ route('home') }}">Home</a></li>
    <li class="active"><a>Group</a></li>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('group') }}">Group</a></li>
        <li><a href="{{ url("group/{$group->id}") }}">{{ $group->name }}</a></li>
        <li><a href="{{ url("assignment/{$assignment->id}") }}">{{ $assignment->title }}</a></li>
        <li class="active">New Submission</li>
    </ol>
@endsection

@section('content')
    <form class="form-horizontal" method="POST" action="{{ url("assignment/{$assignment->id}/store") }}">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="title" class="col-md-2 control-label">Title</label>

            <div class="col-md-8">
                <input id="title" type="text" class="form-control" value="{{ $assignment->title }}" readonly>
            </div>
        </div>

        <div class="form-group">
            <label for="description" class="col-md-2 control-label">Description</label>

            <div class="col-md-8">
                <textarea id="description" class="form-control" rows="6"
                          readonly>{{ $assignment->description }}</textarea>
            </div>
        </div>


        <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
            <label for="content" class="col-md-2 control-label">Content</label>

            <div class="col-md-8">
                <textarea id="content" class="form-control" name="content" rows="12"
                          required>{{ old('content') }}</textarea>

                @if ($errors->has('content'))
                    <span class="help-block">
                        <strong>{{ $errors->first('content') }}</strong>
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