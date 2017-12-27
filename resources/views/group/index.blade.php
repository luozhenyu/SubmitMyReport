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
                if (confirm("退出后可重新加入，确定要退出？")) {
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
            <a class="btn btn-primary" href="{{ url('group/create') }}">New Group</a>
            <a class="btn btn-info pull-right" href="{{ url('group/all') }}">Join An Existing Group</a>
        </caption>
        <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Creator</th>
            <th>Number of Members</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($groups as $group)
            <tr>
                <td><a href="{{ url("group/{$group->id}") }}">{{ $group->name }}</a></td>
                <td><a href="{{ url("group/{$group->id}") }}">{{ $group->description }}</a></td>
                <td>{{ $group->user->name }}</td>
                <td>{{ $group->members->count() }}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a class="btn btn-warning" href="{{ url("group/{$group->id}/member") }}">Show Members</a>
                        <button class="btn btn-danger member-quit" data-id="{{ $group->id }}"
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