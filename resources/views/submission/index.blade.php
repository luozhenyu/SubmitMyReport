@extends('layouts.basic')

@section('title', "Submissions of {$assignment->title}")

@section('navbar')
    <li><a href="{{ route('home') }}">Home</a></li>
    <li class="active"><a>Group</a></li>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('group') }}">Group</a></li>
        <li><a href="{{ url("group/{$group->id}") }}">{{ $group->name }}</a></li>
        <li class="active">{{ $assignment->title }}</li>
    </ol>
@endsection

@section('content')
    <table class="table table-striped table-hover text-left">
        <thead>
        <tr>
            <th>Author</th>
            <th>Content</th>
            <th>Created At</th>
            <th>Score</th>
        </tr>
        </thead>
        <tbody>
        @foreach($submissions as $submission)
            <tr>
                <td><a href="{{ url("submission/{$submission->id}") }}">{{ $submission->user->name }}</a></td>
                <td><a href="{{ url("submission/{$submission->id}") }}">{{ $submission->content }}</a></td>
                <td>{{ $submission->created_at }}</td>
                <td>
                    @if($submission->corrected())
                        {{ $submission->score }}
                    @else
                        <a class="btn btn-primary btn-xs" href="{{ url("submission/{$submission->id}") }}">Mark Now</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $submissions->links() }}
@endsection