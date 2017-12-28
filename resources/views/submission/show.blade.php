@extends('layouts.basic')

@section('title', "{$author->name}'s Submission")

@section('navbar')
    <li><a href="{{ route('home') }}">Home</a></li>
    <li class="active"><a>Group</a></li>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('group') }}">Group</a></li>
        <li><a href="{{ url("group/{$group->id}") }}">{{ $group->name }}</a></li>
        <li><a href="{{ url("assignment/{$assignment->id}") }}">{{ $assignment->title }}</a></li>
        <li class="active">{{ "{$author->name}" }}</li>
    </ol>
@endsection

@section('content')
    <form class="form-horizontal" method="POST" action="{{ url("submission/{$submission->id}") }}">
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

        <div class="form-group">
            <label for="description" class="col-md-2 control-label">Content</label>

            <div class="col-md-8">
                <textarea id="description" class="form-control" rows="12"
                          readonly>{{ $submission->content }}</textarea>
            </div>
        </div>

        <div class="form-group{{ $errors->has('score') ? ' has-error' : '' }}">
            <label for="score" class="col-md-2 control-label">Score</label>

            <div class="col-md-8">
                <input id="score" type="number" class="form-control" name="score"
                       value="{{ $submission->score or old('score') }}" required>

                @if ($errors->has('score'))
                    <span class="help-block">
                        <strong>{{ $errors->first('score') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('remark') ? ' has-error' : '' }}">
            <label for="remark" class="col-md-2 control-label">Remark</label>

            <div class="col-md-8">
                <textarea id="remark" class="form-control" name="remark"
                          rows="3">{{ $submission->remark or old('remark') }}</textarea>

                @if ($errors->has('remark'))
                    <span class="help-block">
                        <strong>{{ $errors->first('remark') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-6">
                <button type="submit" class="btn btn-primary">
                    Mark !
                </button>
            </div>
        </div>
    </form>
@endsection