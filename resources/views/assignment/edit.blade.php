@extends('layouts.normal')

@section('title', '修改作业信息')

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
        <li class="breadcrumb-item active">修改作业信息</li>
    </ol>
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap-select/1.13.0-beta/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdn.bootcss.com/flatpickr/4.4.4/flatpickr.min.css">
@endpush

@php
    use App\Models\File;
    $files = $assignment->files->map(function (File $file) {
        return $file->info();
    });
@endphp

@push('js')
    <script src="https://cdn.bootcss.com/bootstrap-select/1.13.0-beta/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap-select/1.13.0-beta/js/i18n/defaults-zh_CN.min.js"></script>

    <script src="https://cdn.bootcss.com/flatpickr/4.4.4/flatpickr.min.js"></script>
    <script src="https://cdn.bootcss.com/flatpickr/4.4.4/l10n/zh.js"></script>

    <script src="https://cdn.ckeditor.com/4.9.2/full/ckeditor.js"></script>
    <script src="{{ url('/js/file_upload.js') }}"></script>

    <script>
        $(function () {
            window.onbeforeunload = function () {
                return "您确认要退出此页面?";
            };

            const fp = flatpickr(".flatpick", {
                locale: "zh",
                minDate: "today",
                enableTime: true,
                weekNumbers: true,
                wrap: true,
            });

            $('.selectpicker').selectpicker('val', "{{ $assignment->sub_problem }}");

            const editor = CKEDITOR.replace("description", {
                language: "zh-cn",
                extraPlugins: "uploadimage",
                uploadUrl: "{{ route('file.upload') }}",
                toolbar: [
                    {name: 'document', items: ['Preview', 'Print', '-', 'Templates']},
                    {
                        name: 'clipboard',
                        items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']
                    },
                    {name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt']},
                    {
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat']
                    },
                    {
                        name: 'paragraph',
                        items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']
                    },
                    {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
                    {name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar']},
                    {name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize']},
                    {name: 'colors', items: ['TextColor', 'BGColor']},
                    {name: 'tools', items: ['Maximize', 'ShowBlocks']}
                ]
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
            <form method="post" action="{{ url("/assignment/{$assignment->id}") }}">
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <div class="form-group row">
                    <label for="title" class="col-md-2 col-form-label">标题</label>

                    <div class="col-md-10">
                        <input id="title" type="text"
                               class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                               name="title" value="{{ $assignment->title }}" required autofocus>
                        @if ($errors->has('title'))
                            <span class="invalid-feedback">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="sub_problem" class="col-md-2 col-form-label">题目数量</label>

                    <div class="col-md-10">
                        <select id="sub_problem" class="form-control selectpicker" name="sub_problem" required>
                            @foreach(range(1, 8) as $problemCount)
                                <option>{{ $problemCount }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('sub_problem'))
                            <span class="invalid-feedback">
                        <strong>{{ $errors->first('sub_problem') }}</strong>
                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="deadline" class="col-md-2 col-form-label">截止日期</label>

                    <div class="col-md-10 input-group flatpick">
                        <input id="deadline" type="text"
                               class="form-control{{ $errors->has('deadline') ? ' is-invalid' : '' }}"
                               name="deadline" value="{{ $assignment->deadline }}" placeholder="选择日期..." data-input
                               required>

                        <button type="button" class="btn btn-primary input-button" title="toggle" data-toggle>
                            <i class="fa fa-calendar"></i>
                        </button>

                        <button type="button" class="btn btn-danger input-button" title="clear" data-clear>
                            <i class="fa fa-times"></i>
                        </button>

                        @if ($errors->has('deadline'))
                            <span class="invalid-feedback">
                        <strong>{{ $errors->first('deadline') }}</strong>
                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col">
                    <textarea id="description"
                              class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                              name="description" title="description" rows="6"
                              required>{!! $assignment->description !!}</textarea>
                        @if ($errors->has('description'))
                            <span class="invalid-feedback">
                        <strong>{{ $errors->first('description') }}</strong>
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
                            提交修改
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection