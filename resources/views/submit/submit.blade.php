@extends('layout')

@section('title', 'Submit')

@push('css')
    <link href="{{ url('components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ url('components/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
@endpush

@push('js')
    <script src="{{ url('components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ url('components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ url('components/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
    <script src="{{ url('components/bootstrap-fileinput/js/locales/zh.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            $("#myFile").fileinput({
                language : 'en',
                uploadUrl : "${ctx}/admin/uplode/photo",
                autoReplace : true,
                maxFileCount : 5,
                allowedFileExtensions : [ "zip", "rar", "7z" ],
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
        <div class="row" style="height: 30px;"></div>
        <div class="col col-sm-12 offset-sm-0 col-md-8 offset-md-3">
            <div class="container" style="height:85%; overflow: scroll; border-radius: 5px; box-shadow: 0px 2px 7px #bbbbbb;">
                <div class="row" style="padding-top: 20px; padding-left: 30px; padding-right: 30px;">
                    <a href="/joined" role="button" class="btn btn-outline-primary">Back</a>
                    &nbsp&nbsp
                    <button type="button" class="btn btn-outline-danger">Delete</button>
                </div>
                <div class="row" style="padding-top: 20px; padding-left: 30px; padding-right: 30px;">
                    <h4>{{$assignment_title}}</h4>
                </div>
                <div class="row" style="padding-left: 30px; padding-right: 30px;">
                    <span class="badge badge-pill badge-primary">{{$group}}</span> &nbsp
                    <span class="badge badge-pill badge-warning">{{$ddl}}</span>
                </div>
                <div class="jumbotron" style="margin-top: 20px; margin-left: 17px; margin-right: 17px;">
                    {{$description}}
                </div>
                <div class="row" style="padding-left: 30px; padding-right: 30px;">
                    <h5>Submit:</h5>
                </div>
                <div class="row" style="padding-left: 30px; padding-right: 30px;">
                    <form class="form-horizontal" method="submit" style="width: 100%; height: 230px;">
                        <div class="form-group">
                            <input id="myFile" type="file" name="myFile" class="fileloading">
                            <input type="hidden" name="#" id="#">
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12 text-center">
                                <button class="btn btn-primary btn-lg btn-block" style="font-weight: bold">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection