@extends('layout')

@section('title', $title)

@push('css')
    <link href="{{ url('components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ url('components/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
@endpush

@push('js')
    <script src="{{ url('components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ url('components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.zh-CN.min.js') }}"></script>
    <script src="{{ url('components/bootstrap-fileinput/js/fileinput.min.js') }}"></script>

    <script type="text/javascript">
        $(function () {
            $('#datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        });

        $(function () {
            $("#myFile").fileinput({
                language: 'en',
                uploadUrl: "${ctx}/admin/uplode/photo",
                autoReplace: true,
                maxFileCount: 5,
                allowedFileExtensions: ["jpg", "png", "gif"],
                browseClass: "btn btn-primary", //按钮样式
                previewFileIcon: "<i class='glyphicon glyphicon-king'></i>"
            }).on("fileuploaded", function (e, data) {
                var res = data.response;
                alert(res.success);
                $("#logo").attr("value", res.success);
            });
        });
    </script>
@endpush

@section('content')
    <div class="container">
        <div class="row" style="height: 30px;"></div>
        <div class="row">
            <div class="col col-sm-12 offset-sm-0 col-md-8 offset-md-3">
                <div class="container"
                     style="height:85%; overflow: scroll; border-radius: 5px; box-shadow: 0px 2px 7px #bbbbbb; background-color: white;">
                    <div class="row" style="padding-top: 20px; padding-left: 30px; padding-right: 30px;">
                        <a href="/manage" role="button" class="btn btn-outline-primary">Back</a>
                        &nbsp&nbsp
                    </div>
                    <div class="row" style="padding-top: 20px; padding-left: 30px; padding-right: 30px;">
                        <h4>Post Assignment</h4>
                    </div>
                    <div class="row align-items-center" style="padding-top: 20px; padding-left: 30px; padding-right: 30px;">
                        <small>under</small>
                        &nbsp
                        <span class="badge badge-pill badge-secondary">{{$group->name}}</span>
                    </div>
                    <div class="row" style="padding-left: 30px; padding-right: 30px; padding-top: 20px;">
                        <form class="form-horizontal" method="post" style="width: 100%; height: 230px;">
                            <div class="form-group">
                                <div>
                                    <label class=" text-left">Title</label>
                                </div>

                                <div>
                                    <input class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <div>
                                    <label class="text-left">Deadline</label>
                                </div>
                                <div>
                                    <div class="input-group date" id="datepicker">
                                        <input class="form-control">
                                        <div class="input-group-addon">
                                            📅
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="text-left">Details</label>
                                <br/>
                                <div class="date">
                                    <textarea class="form-control"
                                              style="height: 200px;resize:none"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="text-left">Add Pictures</label>
                                <br/>
                                <div>
                                    <input id="myFile" type="file" name="myFile" class="fileloading">
                                </div>
                                <input type="hidden" name="#" id="#">
                            </div>

                            <div class="form-group">
                                <div>
                                    <button class="btn btn-primary btn-block" type="submit">Post Homework</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
