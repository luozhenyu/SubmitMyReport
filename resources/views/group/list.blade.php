@extends('layouts.normal')

@section('title', '所有小组')

@section('navbar')
    <li>
        <a class="nav-link" href="{{ route('home') }}">主页</a>
    </li>

    <li class="active">
        <a class="nav-link">我的小组</a>
    </li>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('group') }}">小组</a></li>
        <li class="breadcrumb-item active">所有小组</li>
    </ol>
@endsection

@push('js')
    <script>
        $(function () {
            $(".member-join").click(function () {
                $.post("{{ url("group") }}/" + $(this).data("id") + "/join", function () {
                    window.location.reload();
                });
            });
        });
    </script>
@endpush

@section('content')
    <form class="navbar-form" role="search">
        <div class="input-group">
            <input type="text" class="form-control" name="wd" value="{{ $wd }}" placeholder="Search...">
            <button class="btn btn-primary" type="submit">
                <span class="fa fa-search"></span>
            </button>
        </div>
    </form>
    <table class="table table-striped table-hover text-left">
        <caption>
            {{ $groups->links() }}
        </caption>
        <thead>
        <tr>
            <th>名称</th>
            <th>描述</th>
            <th>创建者</th>
            <th>成员</th>
            <th>操作</th>
        </tr>
        </thead>

        <tbody>
        @foreach($groups as $group)
            <tr>
                <td>{{ $group->name }}</td>
                <td>{{ str_limit($group->description, 20) }}</td>
                <td>{{ $group->owner->name }}</td>
                <td>{{ $group->members->count() }}</td>
                <td>
                    @if($group->loginJoined())
                        <button class="btn btn-sm btn-success btn-block" disabled>已加入</button>
                    @else
                        <button class="btn btn-sm btn-primary member-join btn-block" data-id="{{ $group->id }}">
                            加入该组
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection