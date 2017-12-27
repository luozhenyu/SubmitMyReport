@extends('layouts.basic')

@section('title', "Assignments of {$group->name}")

@section('navbar')
    <li><a href="{{ route('home') }}">Home</a></li>
    <li class="active"><a>Group</a></li>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('group') }}">Group</a></li>
        <li class="active">{{ $group->name }}</li>
    </ol>
@endsection

@section('content')
    <table class="table table-striped table-hover text-left">
        <caption>
            <a class="btn btn-primary" href="{{ url("group/{$group->id}/create") }}">New Assignment</a>
        </caption>
        <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Author</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($assignments as $assignment)
            <tr>
                <td>{{ $assignment->title }}</td>
                <td>{{ $assignment->description }}</td>
                <td>{{ $assignment->user->name }}</td>
                <td>{{ $assignment->created_at }}</td>
                <td>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $assignments->links() }}
@endsection