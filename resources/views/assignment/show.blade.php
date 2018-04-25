@extends('layouts.normal')

@section('title', $assignment->title)

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
            <a href="{{ url("/group/{$group->id}") }}">{{ $group->name }}</a>
        </li>
        <li class="breadcrumb-item active">{{ $assignment->title }}</li>
    </ol>
@endsection

@php
    use App\Models\File;
    $files = $assignment->files->map(function (File $file) {
        return $file->info();
    });
@endphp

@push('js')
    <script src="{{ url('/js/file_upload.js') }}"></script>

    <script>
        $(function () {
            let files = @json($files);

            for (let i = 0; i < files.length; i++) {
                $("#attachmentContainer").append($.parseFile(files[i]));
            }
        });
    </script>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <header class="text-center">
                <h3 class="text-dark">{{ $assignment->title }}</h3>

                <h5 class="mt-3">
                    <span class="badge badge-secondary">作者</span>
                    {{ $assignment->owner->name }}
                    <span class="badge badge-secondary">提交人数</span>
                    {{ $assignment->submissions->count() .'/' .$group->normalMembers->count() }}
                </h5>

                <h5 class="mt-3">
                    <span class="badge badge-secondary">截止日期</span>
                    {{ $assignment->human_deadline }}
                </h5>
            </header>

            <hr>
            <section>
                <div>{!! $assignment->description !!}</div>
            </section>

            <hr>
            <section class="card">
                <div>
                    <h5 class="card-header text-dark">
                        <i class="fa fa-paperclip" aria-hidden="true"></i> 附件
                    </h5>
                    <div class="card-body" id="attachmentContainer">
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection