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

@section('side_header')
    <div class="col-md-8 offset-md-4">
        <form class="navbar-form" role="search">
            <div class="input-group">
                <input type="text" class="form-control" name="wd" value="{{ $wd }}" placeholder="支持 学号/姓名 筛选">
                <button class="btn btn-primary" type="submit">
                    <span class="fa fa-search"></span>
                </button>
            </div>
        </form>
    </div>
@endsection

@php
    $submissionTotal = $assignment->submissions()->count();
    $submissionScored = $assignment->scoredSubmissions()->count();
    $SubmissionNotScored = $submissionTotal - $submissionScored;

    $yourSubmissionScored = $assignment->myScoredSubmissions()->count();
@endphp

@section('content')
    <div class="table-responsive">
        <table class="table table-striped table-hover text-left">
            <caption>
                {{ $submissions->links() }}
                <h5>您已评价{{ $yourSubmissionScored }}份</h5>
                <h5>
                    共{{ $submissionTotal }}份提交，已评{{ $submissionScored }}份，剩余{{ $SubmissionNotScored }}份
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
                    <td>{{ str_limit(html_entity_decode(strip_tags($submission->content)), 20) }}</td>
                    <td>{{ $submission->created_at }}</td>
                    <td>{{ $submission->mark? $submission->mark->average_score :null }}</td>
                    <td>
                        @if($submission->mark)
                            <a class="btn btn-outline-success btn-sm" href="{{ url("submission/{$submission->id}") }}"
                               onmouseover="innerHTML='修 改'"
                               onmouseleave="innerHTML='已评分'">已评分</a>
                        @else
                            <a class="btn btn-outline-primary btn-sm"
                               href="{{ url("submission/{$submission->id}") }}">去评分</a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection