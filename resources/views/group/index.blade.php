@extends('layouts.basic')

@section('title', 'Joined Group')

@section('navbar')
    <li><a href="{{ route('home') }}">Home</a></li>
    <li class="active"><a>Group</a></li>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('group') }}">Group</a></li>
        <li class="active">Joined Groups</li>
    </ol>
@endsection

@push('js')
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(".member-quit").click(function () {
                if (confirm("Are you sure you want to quit?")) {
                    $.post("{{ url("group") }}/" + $(this).data("id") + "/quit", function () {
                        window.location.reload();
                    });
                }
            });
        });
    </script>
@endpush

@section('content')
    <table class="table table-striped table-hover text-left">
        <caption>
            <a class="btn btn-primary" href="{{ url('group/all') }}">Join An Existing Group</a>
            <a class="btn btn btn-default" href="{{ url('group/create') }}">Create Group</a>

        </caption>
        <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Creator</th>
            <th>Members</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($groups as $group)
            <tr>
                <td>
                    {{ $group->name }}
                </td>
                <td class="col-md-5">
                    @if($group->pivot->is_admin)
                        <p>
                            {{ $group->description }}
                        </p>
                    @else
                        {{ $group->description }}
                    @endif
                </td>
                <td>{{ $group->user->name }}</td>
                <td><a role="button" class="btn btn-default" href="{{ url("group/{$group->id}/member") }}">{{ $group->members->count() }}</a></td>
                <td>
                    <div>
                        @if($group->pivot->is_admin)
                            <a role="button" class="btn btn-primary btn-block btn-sm" href="{{ url("group/{$group->id}") }}">
                                Manage
                            </a>
                        @endif

                        <button class="btn btn-danger member-quit btn-block btn-sm" data-id="{{ $group->id }}"
                                {{ $group->user->id === Auth::user()->id? 'disabled' :'' }}>
                            Quit
                        </button>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $groups->links() }}
@endsection