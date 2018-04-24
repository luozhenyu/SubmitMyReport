@extends('layouts.normal')

@section('title', "{$assignment->title}的提交")

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
            <a href="{{ url("group/{$group->id}") }}">{{ $group->name }}</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ url("/assignment/{$assignment->id}") }}">{{ $assignment->title }}</a>
        </li>
        <li class="breadcrumb-item active">提交情况</li>
    </ol>
@endsection

@php($submissionTotal = $assignment->submissions()->count())
@php($submissionScored = $assignment->scoredSubmissions()->count())


@section('content')
    <table class="table table-striped table-hover text-left">
        <caption>
            {{ $submissions->links() }}
            <h5>
                共{{ $submissionTotal }}份提交，已评{{ $submissionScored }}份，
                剩余{{ $submissionTotal - $submissionScored }}份,
            </h5>
        </caption>

        <thead>
        <tr>
            <th>学号</th>
            <th>姓名</th>
            <th>内容</th>
            <th>提交时间</th>
            <th>分数</th>
            <th>操作</th>
        </tr>
        </thead>

        <tbody>
        @foreach($submissions as $submission)
            <tr>
                <td>{{ $submission->owner->student_id }}</td>
                <td>{{ $submission->owner->name }}</td>
                <td>{{ str_limit(strip_tags($submission->content), 20) }}</td>
                <td>{{ $submission->created_at }}</td>
                <td>{{ $submission->average_score }}</td>
                <td>
                    @if($submission->corrected())
                        <a class="btn btn-outline-success btn-sm" href="{{ url("submission/{$submission->id}") }}"
                           onmouseover="this.innerHTML='修 改'"
                           onmouseleave="this.innerHTML='已评分'">已评分</a>
                    @else
                        <a class="btn btn-outline-primary btn-sm"
                           href="{{ url("submission/{$submission->id}") }}">去评分</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection