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
            <th>Submitted at</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($submissions as $submission)
            <tr>
                <td>{{ $submission->user->name }}</td>
                <td>{{ $submission->created_at }}</td>
                <td>
                    @if($submission->corrected())
                        {{ $submission->score }}
                    @else
                        <a class="btn btn-primary btn-sm" href="{{ url("submission/{$submission->id}") }}">Score Now</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $submissions->links() }}
@endsection