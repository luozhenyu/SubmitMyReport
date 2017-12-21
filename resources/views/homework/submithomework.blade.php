@extends('layouts.app')

@section('title','')

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
        <div class="row">
            <div class="col-md-10 col-md-offset-1 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel panel-heading">Submit Homework</div>
                    <div class="panel panel-body">
                        <h1 class="text-center">Homework Name</h1>
                        <h4 class="text-center text-warning">2000.10.10</h4>
                        <p>As with bootstrap’s own plugins, datepicker provides a data-api that can be used to
                            instantiate datepickers without the need for custom javascript. For most datepickers, simply
                            set data-provide="datepicker" on the element you want to initialize, and it will be
                            intialized lazily, in true bootstrap fashion. For inline datepickers, use
                            data-provide="datepicker-inline"; these will be immediately initialized on page load, and
                            cannot be lazily loaded.</p>
                        <p>As with bootstrap’s own plugins, datepicker provides a data-api that can be used to
                            instantiate datepickers without the need for custom javascript. For most datepickers, simply
                            set data-provide="datepicker" on the element you want to initialize, and it will be
                            intialized lazily, in true bootstrap fashion. For inline datepickers, use
                            data-provide="datepicker-inline"; these will be immediately initialized on page load, and
                            cannot be lazily loaded.</p>
                        <p>As with bootstrap’s own plugins, datepicker provides a data-api that can be used to
                            instantiate datepickers without the need for custom javascript. For most datepickers, simply
                            set data-provide="datepicker" on the element you want to initialize, and it will be
                            intialized lazily, in true bootstrap fashion. For inline datepickers, use
                            data-provide="datepicker-inline"; these will be immediately initialized on page load, and
                            cannot be lazily loaded.</p>
                        <div class="text-center">
                            <img src="#">
                        </div>
                    </div>
                    <div class="panel-footer">
                        <form class="form-horizontal" method="submit">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="col-sm-12 text-left">Add Files</label>
                                    <br/>
                                    <div class="col-sm-12">
                                        <input id="myFile" type="file" name="myFile" class="fileloading">
                                    </div>
                                    <input type="hidden" name="#" id="#">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
