@extends('layouts.basic')

@section('title', 'Home')

@section('navbar')
    <li class="active"><a>Home</a></li>
    <li><a href="{{ route('group') }}">Group</a></li>
@endsection

@section('content')
    @if($current_group)
        <div class="col-md-3">
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

        <div class="col-md-9">
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
                        <td>{{ $assignment->description }}</td>
                        <td>{{ $assignment->user->name }}</td>
                        <td>
                            @if(!$current_group->pivot->is_admin)
                                @if(!$submission = $assignment->submissions()->where('user_id', Auth::user()->id)->first())
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
                            @else
                                <a class="btn btn-primary btn-sm"
                                   href="{{ url("assignment/{$assignment->id}") }}">
                                    Watch Submissions
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