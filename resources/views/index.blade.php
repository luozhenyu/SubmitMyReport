@extends('layouts.basic')

@section('title', 'Home')

@section('navbar')
    <li class="active"><a>Home</a></li>
    <li><a href="{{ route('group') }}">Group</a></li>
@endsection

@section('content')
    @if($current_group)
        <div class="col-md-4">
            <h3>Groups</h3>
            <div class="list-group">
                @foreach($groups as $group)
                    <a class="list-group-item{{ $group->id === $current_group->id? ' active' :'' }}"
                       href="{{ url('')."?group={$group->id}" }}">
                        <h4 class="list-group-item-heading">
                            {{ $group->name }}
                        </h4>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="col-md-8">
            <table class="table table-striped table-hover text-left">
                <caption><h4>Assignments</h4></caption>
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Author</th>
                    <th>Created At</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($current_group->assignments as $assignment)
                    <tr>
                        <td>{{ $assignment->title }}</td>
                        <td>{{ $assignment->description }}</td>
                        <td>{{ $assignment->user->name }}</td>
                        <td>{{ $assignment->created_at }}</td>
                        <td>
                            @if(!$submission = $assignment->submissions()->where('user_id',Auth::user()->id)->first())
                                <a class="btn btn-danger btn-xs"
                                   href="{{ url("assignment/{$assignment->id}/create") }}">
                                    To do
                                </a>
                            @elseif(!$submission->corrected())
                                <span class="btn btn-success btn-xs" disabled>
                                    Submitted
                                </span>
                            @else
                                <a class="btn btn-primary btn-xs"
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
@endsection