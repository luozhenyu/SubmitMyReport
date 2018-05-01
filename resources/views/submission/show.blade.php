@extends('layouts.normal')

@section('title', "{$submission->owner->name}的提交")

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
        @if($admin)
            <li class="breadcrumb-item"><a href="{{ url("/group/{$group->id}") }}">{{ $group->name }}</a></li>
        @else
            <li class="breadcrumb-item">{{ $group->name }}</li>
        @endif
        <li class="breadcrumb-item">
            <a href="{{ url("/assignment/{$assignment->id}") }}">{{ $assignment->title }}</a>
        </li>
        @if($admin)
            <li class="breadcrumb-item">
                <a href="{{ url("/assignment/{$assignment->id}/submission") }}">提交情况</a>
            </li>
        @endif
        <li class="breadcrumb-item active">{{ "{$submission->owner->name}的提交" }}</li>
    </ol>
@endsection

@php
    use App\Models\File;
    $files = $submission->files->map(function (File $file) {
        return $file->info();
    });
@endphp

@push('js')
    <script>
        $(function () {
            let files = @json($files);

            for (let i = 0; i < files.length; i++) {
                $("#attachmentContainer").append($.parseFile(files[i]));
            }
        });

        function makeCheck(self) {
            let input = self.value.replace(/[^\d]/g, '');
            if (input.length > 0) {
                let integer = parseInt(input);
                if (integer > 100) {
                    integer = 100;
                } else if (integer < 0) {
                    integer = 0;
                }
                self.value = integer;
            } else {
                self.value = '';
            }
        }
    </script>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <section class="col text-center">
                <h3 class="text-dark">{{ $assignment->title }}</h3>

                <h5 class="mt-3">
                    <span class="badge badge-success">作者</span>
                    {{ $assignment->owner->name }}
                    <span class="badge badge-warning">提交人数</span>
                    {{ $assignment->submissions()->count() .'/' .$group->normalMembers()->count() }}
                </h5>

                <h5 class="mt-3">
                    <span class="badge badge-info">截止日期</span>
                    {{ $assignment->human_deadline }}
                </h5>
            </section>

            <section class="col mt-3">
                <h4 class="text-dark">作业全文</h4>
                <hr>
                <div>{!! $assignment->description !!}</div>
            </section>

            <section class="col mt-3">
                <h4 class="text-dark">{{ $submission->owner->name }}的提交</h4>
                <hr>
                <div>{!! $submission->content !!}</div>
            </section>

            <section class="col">
                <hr>
                <div class="card">
                    <h5 class="card-header text-dark">
                        <i class="fa fa-paperclip" aria-hidden="true"></i> 附件
                    </h5>
                    <div class="card-body" id="attachmentContainer">
                    </div>
                </div>
            </section>

            @if($admin)
                <section class="col mt-3">
                    <h4 class="text-dark">评分
                        @if($submission->mark)
                            <span class="text-secondary"
                                  style="font-size: 0.7em">by {{ $submission->mark->owner->name }}</span>
                        @endif
                    </h4>
                    <hr>

                    <form method="post" action="{{ url("/submission/{$submission->id}/mark") }}">
                        @csrf

                        <ul class="nav nav-tabs" role="tablist">
                            @for($index = 0; $index < $assignment->sub_problem; $index++)
                                <li class="nav-item">
                                    <a class="nav-link{{ !$index?' active' :'' }}{{ $errors->has("score.{$index}") || $errors->has("remark.{$index}")? ' text-danger' :'' }}"
                                       data-toggle="tab" href="#problem{{ $index }}">题目 {{ $index + 1 }}</a>
                                </li>
                            @endfor
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            @for($index = 0; $index < $assignment->sub_problem; $index++)
                                @php($problem = $submission->mark? $submission->mark->data[$index] :null)
                                <div id="problem{{ $index }}"
                                     class="container tab-pane{{ !$index?' active':' fade' }} p-3">

                                    <div class="form-group row">
                                        <label for="score{{ $index }}" class="col-md-2 col-form-label">得分</label>

                                        <div class="col-md-10">
                                            <input id="score{{ $index }}" type="text"
                                                   class="form-control{{ $errors->has("score.{$index}")? ' is-invalid' :'' }}"
                                                   name="score[{{ $index }}]"
                                                   value="{{ $problem? $problem->score :old("score.{$index}") }}"
                                                   placeholder="请输入整数分数" onkeyup="makeCheck(this)">
                                            @if ($errors->has("score.{$index}"))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first("score.{$index}") }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="remark{{ $index }}" class="col-md-2 col-form-label">评价</label>

                                        <div class="col-md-10">
                                            <textarea id="remark{{ $index }}" name="remark[{{ $index }}]"
                                                      class="form-control{{ $errors->has("remark.{$index}")? ' is-invalid' :'' }}"
                                                      style="resize: none"
                                                      rows="6">{{ $problem? $problem->remark :old("remark.{$index}") }}</textarea>
                                            @if ($errors->has("remark.{$index}"))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first("remark.{$index}") }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row justify-content-center">
                                        <button type="submit" class="btn btn-primary mt-4">
                                            提交分数
                                        </button>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </form>
                </section>
            @elseif($submission->mark)
                <section class="col mt-3">
                    <h4 class="text-dark">评分
                        <span class="text-secondary"
                              style="font-size: 0.7em">by {{ $submission->mark->owner->name }}</span>
                    </h4>
                    <hr>
                    @for($index = 0; $index < $assignment->sub_problem; $index += 2)
                        @php($data = $submission->mark->data)
                        <div class="row p-3">
                            @if($index < $assignment->sub_problem)
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            题目 {{ $index + 1 }}
                                        </div>

                                        <div class="card-body">
                                            <h5 class="card-title">{{ $data[$index]->score }}分</h5>
                                            <p class="card-text">{{ $data[$index]->remark }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($index + 1 < $assignment->sub_problem)
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            题目 {{ $index + 2 }}
                                        </div>

                                        <div class="card-body">
                                            <h5 class="card-title">{{ $data[$index + 1]->score }}分</h5>
                                            <p class="card-text">{{ $data[$index + 1]->remark }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endfor
                </section>
            @endif
        </div>
    </div>
@endsection