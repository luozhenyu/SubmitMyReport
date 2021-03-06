@extends('layouts.normal')

@section('title', '修改作业')

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
        <li class="breadcrumb-item"><a href="{{ url("group/{$group->id}") }}">{{ $group->name }}</a></li>
        <li class="breadcrumb-item">
            <a href="{{ url("/assignment/{$assignment->id}") }}">{{ $assignment->title }}</a>
        </li>
        <li class="breadcrumb-item active">修改作业</li>
    </ol>
@endsection

@php
    use App\Models\File;
     $files = $submission->files->map(function (File $file) {
         return $file->info();
     });
@endphp

@push('js_import')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.5.0/dist/flatpickr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.5.0/dist/l10n/zh.js"></script>
    <script src="{{ url('/ckeditor/ckeditor.js') }}"></script>
@endpush

@push('js')
    <script>
        $(function () {
            window.onbeforeunload = function () {
                return "您确认要退出此页面?";
            };

            const editor = CKEDITOR.replace("content", {
                extraPlugins: "uploadimage",
                uploadUrl: "{{ route('file.upload') }}"
            });
            editor.on("fileUploadRequest", function (evt) {
                let xhr = evt.data.fileLoader.xhr;
                xhr.setRequestHeader("X-CSRF-TOKEN", $("meta[name='csrf-token']").attr("content"));
            });

            $("#attachmentBtn").click(function () {
                $(this).upload({
                    url: "{{ route('file.upload') }}",
                    maxsize: {{ \App\Http\Controllers\FileController::UPLOAD_MAX_SIZE }},
                    success: function (json) {
                        if (json.uploaded) {
                            $("#attachmentContainer").append($.parseFile(json, true));
                        } else if (json.error) {
                            alert(json.error.message);
                        }
                    }
                });
            });

            let files = @json($files);

            for (let i = 0; i < files.length; i++) {
                $("#attachmentContainer").append($.parseFile(files[i], true));
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
                    <span class="badge badge-info">截止日期</span>
                    {{ $assignment->human_deadline }}
                </h5>
                <p>{{ str_limit(html_entity_decode(strip_tags($assignment->description)), 100) }}</p>
            </header>

            <hr>
            <section>
                <form method="post" action="{{ url("/submission/{$submission->id}") }}">
                    @csrf
                    {{ method_field('PUT') }}

                    <div class="form-group row">
                        <div class="col">
                        <textarea id="content"
                                  class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}"
                                  name="content" title="content" rows="6"
                                  required>{!! $submission->content !!}</textarea>
                            @if ($errors->has('content'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('content') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title text-dark">
                                        附件列表&nbsp;
                                        <a class="btn btn-sm btn-outline-primary" id="attachmentBtn">
                                            <span class="fa fa-file"></span>
                                            添加附件 {{ \App\Http\Controllers\FileController::uploadLimitHit() }}
                                        </a>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div id="attachmentContainer"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col text-right">
                            <button type="submit" class="btn btn-primary mt-4" onclick="window.onbeforeunload=null;">
                                确认修改
                            </button>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection