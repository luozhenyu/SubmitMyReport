@extends('layouts.basic')

@section('title', "{$assignment->title}")

@section('navbar')
    <li class="active"><a>Home</a></li>
    <li><a href="{{ route('group') }}">Group</a></li>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li class="active">{{ "{$assignment->title}" }}</li>
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

        <div class="form-group">
            <label for="score" class="col-md-2 control-label">Score</label>

            <div class="col-md-8">
                <input id="score" type="number" class="form-control" value="{{ $submission->score }}" readonly>
            </div>
        </div>

        <div class="form-group">
            <label for="remark" class="col-md-2 control-label">Remark</label>

            <div class="col-md-8">
                <textarea id="remark" class="form-control" rows="3" readonly>{{ $submission->remark }}</textarea>
            </div>
        </div>
    </form>
@endsection