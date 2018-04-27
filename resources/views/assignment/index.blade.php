@extends('layouts.normal')

@section('title', "{$group->name}的作业")

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
        <li class="breadcrumb-item active">{{ $group->name }}的作业</li>
    </ol>
@endsection

@push('js')
    <script>
        $(function () {
            $("#updateDescription").click(function () {
                $.ajax({
                    url: "{{ url()->current() }}",
                    type: "PUT",
                    data: {description: $("#descriptionField").val()},
                    success: function () {
                        window.location.reload();
                    },
                    error: function (xhr) {
                        let json = xhr.responseJSON;
                        $("#descriptionField").addClass("is-invalid");
                        $("#descriptionFeedback").text(json.errors.description[0]);
                    }
                });
            });
        });
    </script>
@endpush

@section('side_header')
    <p class="text-justify">
        {{ $group->description }}&nbsp;
        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editDescriptionModal">
            修改
        </button>
    </p>

    <!-- Modal -->
    <div class="modal fade" id="editDescriptionModal" tabindex="-1" role="dialog"
         aria-labelledby="editDescriptionModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">修改{{ $group->name }}的描述</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <textarea id="descriptionField" class="form-control" rows="8" title="description"
                              style="resize: none" required>{{ $group->description }}</textarea>
                    <span class="invalid-feedback">
                        <strong id="descriptionFeedback"></strong>
                    </span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" id="updateDescription">更新小组描述</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="table-responsive">
        <table class="table table-striped table-hover text-left">
            <caption>
                {{ $assignments->links() }}
                <a class="btn btn-primary" href="{{ url("group/{$group->id}/create") }}">创建新作业</a>
            </caption>

            <thead>
            <tr>
                <th>标题</th>
                <th>描述</th>
                <th>作者</th>
                <th>修改日期</th>
                <th>提交数</th>
                <th>操作</th>
            </tr>
            </thead>

            <tbody>
            @foreach($assignments as $assignment)
                <tr>
                    <td>
                        <a href="{{ url("/assignment/{$assignment->id}") }}">{{ $assignment->title }}</a>
                    </td>
                    <td>
                        <a href="{{ url("/assignment/{$assignment->id}") }}">
                            {{ str_limit(strip_tags($assignment->description), 20) }}
                        </a>
                    </td>
                    <td>{{ $assignment->owner->name }}</td>
                    <td>{{ $assignment->updated_at }}</td>
                    <td>{{ $assignment->submissions()->count() .'/' .$group->normalMembers()->count() }}</td>
                    <td>
                        <a href="{{ url("/assignment/{$assignment->id}/edit") }}"
                           class="btn btn-sm btn-outline-primary">
                            修改信息
                        </a>
                        <a href="{{ url("/assignment/{$assignment->id}/submission") }}"
                           class="btn btn-outline-info btn-sm">
                            提交情况
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection