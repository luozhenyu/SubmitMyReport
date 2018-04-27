@extends('layouts.normal')

@section('title', "{$group->name}的成员")

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
        <li class="breadcrumb-item">
            @if(Auth::user()->managedGroups()->find($group->id))
                <a href="{{ url("group/{$group->id}") }}">{{ $group->name }}</a>
            @else
                {{ $group->name }}
            @endif
        </li>
        <li class="breadcrumb-item active">小组成员</li>
    </ol>
@endsection

@if($group->owner->id === Auth::user()->id)
    @push('js')
        <script>
            $(function () {
                $(".member-toggle").click(function () {
                    $.post('{{ url("/group/{$group->id}/admin") }}', {user_id: $(this).data("id")}, function () {
                        window.location.reload();
                    });
                });
            });
        </script>
    @endpush
@endif

@section('content')
    <div class="table-responsive">
        <table class="table table-striped table-hover text-left">
            <caption>
                {{ $members->links() }}
            </caption>

            <thead>
            <tr>
                <th>序号</th>
                <th>姓名</th>
                <th>类型</th>
                <th>加入日期</th>
                @if($group->owner->id === Auth::user()->id)
                    <th>操作</th>
                @endif
            </tr>
            </thead>
            <tbody>

            @foreach($members as $member)
                <tr>
                    <td>{{ $memberOffset + $loop->iteration }}</td>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->id === $group->owner->id? '创建者' :($member->pivot->is_admin? '管理员' :'普通用户') }}</td>
                    <td>{{ $member->pivot->created_at }}</td>
                    @if($group->owner->id === Auth::user()->id)
                        <td>
                            @if($member->pivot->is_admin)
                                <button class="btn btn-sm btn-danger member-toggle" data-id="{{ $member->id }}">
                                    取消管理员
                                </button>
                            @else
                                <button class="btn btn-sm btn-primary member-toggle" data-id="{{ $member->id }}">
                                    设为管理员
                                </button>
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection