@extends('layouts.normal')

@section('title', '主页')

@section('navbar')
    <li class="active">
        <a class="nav-link">主页</a>
    </li>

    <li>
        <a class="nav-link" href="{{ route('group') }}">我的小组</a>
    </li>
@endsection

@section('content')
    <div class="container p-4">
        <div class="row">
            <div class="col-md-2">
                <h5 class="text-center">我加入的</h5>
                <div class="list-group">
                    @foreach($groups as $group)
                        <a href="{{ $group->id === $selectedGroup->id? '#' :route('home', ['group' => $group->id]) }}"
                           class="list-group-item{{ $group->id === $selectedGroup->id? ' active' :'' }}">
                            {{ $group->name }}
                            @if(!$group->loginAdmin() && $count = $group->assignments->count())
                                <span class="badge badge-primary badge-pill">{{ $count }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="col-md-10 p-4"
                 style="min-height: 500px; border-radius:5px; box-shadow: 0 3px 7px #bbbbbb;">
                @if($selectedGroup)
                    @php($loginNotSubmit = $selectedGroup->loginNotSubmit)
                    @php($orderedAssignments = $selectedGroup->orderedAssignments()->paginate(6))
                    <div class="row">
                        <div class="col-md-4">
                            <h4>我的作业</h4>
                        </div>
                        <div class="col-md-8">
                            <p class="text-justify">{{ $selectedGroup->description }}</p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-left">
                            <caption>
                                {{ $orderedAssignments->links() }}
                                @if($selectedGroup->loginAdmin())
                                @else
                                    @if($loginNotSubmit->count() > 0)
                                        <h5>还有{{ $loginNotSubmit->count() }}项未完成作业</h5>
                                    @else
                                        <h5>暂时没有未完成作业</h5>
                                    @endif
                                @endif
                            </caption>

                            <thead>
                            <tr>
                                <th scope="col">标题</th>
                                <th scope="col">描述</th>
                                <th scope="col">作者</th>
                                <th scope="col">截止日期</th>
                                <th scope="col">提交情况</th>
                                @if($selectedGroup->loginAdmin())
                                    <th scope="col">评分情况</th>
                                @endif
                                <th scope="col">状态</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($orderedAssignments as $assignment)
                                <tr>
                                    <td>{{ $assignment->title }}</td>
                                    <td>{{ str_limit(html_entity_decode(strip_tags($assignment->description)), 20) }}</td>
                                    <td>{{ $assignment->owner->name }}</td>
                                    <td>{{ $assignment->human_deadline }}</td>
                                    <td>{{ $assignment->submissions()->count() .'/' .$group->normalMembers()->count() }}</td>
                                    @if($selectedGroup->loginAdmin())
                                        <td>{{ $assignment->scoredSubmissions()->count() .'/' .$assignment->submissions()->count() }}</td>
                                    @endif
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a class="btn btn-outline-primary"
                                               href="{{ url("/assignment/{$assignment->id}") }}">
                                                题目
                                            </a>
                                            @if($selectedGroup->loginAdmin())
                                                <a href="{{ url("/assignment/{$assignment->id}/submission") }}"
                                                   class="btn btn-outline-info">
                                                    提交情况
                                                </a>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                                                        更多操作
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{ action('AssignmentController@exportGrade', $assignment->id) }}">导出成绩</a>
                                                        <a class="dropdown-item" href="{{ action('AssignmentController@exportFile', $assignment->id) }}">导出提交文件</a>
                                                    </div>
                                                </div>
                                            @else
                                                @if(!$submission = $assignment->loginSubmissions->first())
                                                    <a href="{{ url("/assignment/{$assignment->id}/submit") }}"
                                                       class="btn btn-outline-danger">
                                                        待完成
                                                    </a>
                                                @elseif($submission->mark)
                                                    <a class="btn btn-outline-danger"
                                                       href="{{ url("submission/{$submission->id}") }}"
                                                       onmouseover="innerHTML='查 看'"
                                                       onmouseleave="innerHTML='{{ $submission->mark->average_score }}分'">
                                                        {{ $submission->mark->average_score }}分
                                                    </a>
                                                @else
                                                    <a class="btn btn-outline-info"
                                                       href="{{ url("submission/{$submission->id}") }}">查看提交
                                                    </a>
                                                    <a class="btn btn-outline-success btn-sm submitted"
                                                       href="{{ url("submission/{$submission->id}/edit") }}"
                                                       onmouseover="innerHTML='修改提交'"
                                                       onmouseleave="innerHTML='提交成功'">
                                                        提交成功
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <h3>第一步，选择一个小组并加入。</h3>
                @endif
            </div>
        </div>
    </div>
@endsection