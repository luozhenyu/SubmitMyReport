@extends('layouts.app')

@section('title','')

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
                language : 'en',
                uploadUrl : "${ctx}/admin/uplode/photo",
                autoReplace : true,
                maxFileCount : 5,
                allowedFileExtensions : [ "jpg", "png", "gif" ],
                browseClass : "btn btn-primary", //按钮样式
                previewFileIcon : "<i class='glyphicon glyphicon-king'></i>"
            }).on("fileuploaded", function(e, data) {
                var res = data.response;
                alert(res.success);
                $("#logo").attr("value", res.success);
            });
        });
    </script>
@endpush

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel panel-heading">Post Homework</div>
                    <div class="panel panel-body">
                        <form class="form-horizontal" method="post">
                            <div class="form-group">
                                <div class="col-md-1 col-md-offset-1 col-sm-12 ">
                                    <label class=" text-left">Title</label>
                                </div>

                                <div class="col-md-9 col-sm-12">
                                    <input class=" form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-1 col-md-offset-1 col-sm-12 ">
                                    <label class="text-left">Deadline</label>
                                </div>
                                <div class="col-md-5 col-sm-12 ">
                                    <div class="input-group date" id="datepicker">
                                        <input class="form-control">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-10 col-md-offset-1 col-sm-12 text-left">Details</label>
                                <br/>
                                <div class="col-md-10 col-md-offset-1 col-sm-12 date">
                                    <textarea class="col-md-12 col-sm-12 form-control"
                                              style="height: 250px;resize:none"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-10 col-md-offset-1 col-sm-12 text-left">Add Pictures</label>
                                <br/>
                                <div class="col-md-10 col-md-offset-1 col-sm-12">
                                    <input id="myFile" type="file" name="myFile" class="fileloading">
                                </div>
                                <input type="hidden" name="#" id="#">
                            </div>

                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-1 col-sm-12 text-right">
                                    <button class="btn btn-primary" type="submit">Post Homework</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
