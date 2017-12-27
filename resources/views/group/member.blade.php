@extends('layouts.basic')

@section('title', "Members of {$group->name}")

@section('navbar')
    <li><a href="{{ route('home') }}">Home</a></li>
    <li class="active"><a>Group</a></li>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('group') }}">Group</a></li>
        <li><a href="{{ url("group/{$group->id}") }}">{{ $group->name }}</a></li>
        <li class="active">Members</li>
    </ol>
@endsection

@if($group->user->id === Auth::user()->id)
    @push('js')
        <script>
            $(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $(".member-toggle").click(function () {
                    $.post("", {user_id: $(this).data("id")}, function () {
                        window.location.reload();
                    });
                });
            });
        </script>
    @endpush
@endif

@section('content')
    <table class="table table-striped table-hover text-left">
        <thead>
        <tr>
            <th></th>
            <th>Member Name</th>
            <th>Type</th>
            <th>Joining Date</th>
            @if($group->user->id === Auth::user()->id)
                <th>Manage</th>
            @endif
        </tr>
        </thead>
        <tbody>

        @foreach($members as $index => $member)
            <tr>
                <td>{{ $offset + $index + 1 }}</td>
                <td>{{ $member->name }}</td>
                <td>{{ $member->pivot->is_admin? 'Admin' :'Normal' }}</td>
                <td>{{ $member->pivot->created_at }}</td>
                @if($group->user->id === Auth::user()->id)
                    <td>
                        @if($member->pivot->is_admin)
                            <button class="btn btn-danger btn-sm member-toggle" data-id="{{ $member->id }}">
                                Revoke Admin
                            </button>
                        @else
                            <button class="btn btn-info btn-sm member-toggle" data-id="{{ $member->id }}">
                                Set Admin
                            </button>
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $members->links() }}
@endsection