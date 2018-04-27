@extends('layouts.normal')

@section('title', '我加入的小组')

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
        <li class="breadcrumb-item">小组</li>
        <li class="breadcrumb-item active">我加入的小组</li>
    </ol>
@endsection

@push('js')
    <script>
        $(function () {
            $(".member-quit").click(function () {
                if (confirm("你确定要退出该小组吗?")) {
                    $.post("{{ url("group") }}/" + $(this).data("id") + "/quit", function () {
                        window.location.reload();
                    });
                }
            });
        });
    </script>
@endpush

@section('content')
    <div class="table-responsive">
        <table class="table table-striped table-hover text-left">
            <caption>
                {{ $groups->links() }}
                <a class="btn btn-primary" href="{{ url('group/list') }}">查找并加入小组</a>
                <a class="btn btn btn-default" href="{{ url('group/create') }}">创建小组</a>
            </caption>

            <thead>
            <tr>
                <th scope="col">名称</th>
                <th scope="col">描述</th>
                <th scope="col">创建者</th>
                <th scope="col">成员数</th>
                <th scope="col">操作</th>
            </tr>
            </thead>

            <tbody>
            @foreach($groups as $group)
                <tr>
                    <td>{{ $group->name }}</td>
                    <td>{{ str_limit($group->description, 20) }}</td>
                    <td>{{ $group->owner->name }}</td>
                    <td>
                        <a class="btn btn-default" href="{{ url("group/{$group->id}/members") }}" role="button">
                            {{ $group->members()->count() }}
                        </a>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            @if($group->loginAdmin())
                                <a class="btn btn-sm btn-primary" href="{{ url("group/{$group->id}") }}"
                                   role="button">
                                    管理小组
                                </a>
                            @endif

                            @if($group->owner->id === Auth::user()->id)
                                <button class="btn btn-sm btn-danger" disabled>
                                    创建者不可退出
                                </button>
                            @else
                                <button class="btn btn-sm btn-danger member-quit" data-id="{{ $group->id }}">
                                    退出小组
                                </button>
                            @endif
                        </div>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection