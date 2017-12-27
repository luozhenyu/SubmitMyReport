@extends('layouts.basic')

@section('title', 'All Group')

@section('navbar')
    <li><a href="{{ route('home') }}">Home</a></li>
    <li class="active"><a>Group</a></li>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('group') }}">Group</a></li>
        <li class="active">All Groups</li>
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

            $(".member-join").click(function () {
                $.post("{{ url("group") }}/" + $(this).data("id") + "/join", function () {
                    window.location.reload();
                });
            });
        });
    </script>
@endpush

@section('content')
    <table class="table table-striped table-hover text-left">
        <caption class="text-right">
            <form class="navbar-form" role="search">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search" name="wd" value="{{ $wd }}">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="submit">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>
                    </div>
                </div>
            </form>
        </caption>
        <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Creator</th>
            <th>Number of Members</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($groups as $group)
            <tr>
                <td>{{ $group->name }}</td>
                <td>{{ $group->description }}</td>
                <td>{{ $group->user->name }}</td>
                <td>{{ $group->members->count() }}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        @if(Auth::user()->joinedGroups()->find($group->id))
                            <button class="btn btn-default" disabled>Joined</button>
                        @else
                            <button class="btn btn-primary member-join" data-id="{{ $group->id }}">Join
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $groups->links() }}
@endsection