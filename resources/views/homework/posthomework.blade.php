@extends('layouts.app');

@push('css')
    <link href="https://cdn.bootcss.com/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/bootstrap-fileinput/4.0.0/css/fileinput.min.css" rel="stylesheet">
@endpush

@push('js')
    <script src="https://cdn.bootcss.com/bootstrap-fileinput/4.0.0/js/fileinput.min.js"></script>
    <script src="https://cdn.bootcss.com/moment.js/2.18.1/moment-with-locales.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('#date1').datetimepicker({
                format: 'YYYY-MM-DD',
                locale: moment.locale('zh-cn')
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
                                <label class="col-md-1 col-md-offset-1 col-sm-12 text-left">Title</label>
                                <div class="col-md-9 col-sm-12">
                                    <input class=" form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-1 col-md-offset-1 col-sm-12 text-left">Deadline</label>
                                <div class="col-md-8 col-sm-12 input-group date " id='date1'>
                                    <input type="text" class="form-control">
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-calendar"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-10 col-md-offset-1 col-sm-12 text-left">Details</label>
                                <br/>
                                <div class="col-md-10 col-md-offset-1 col-sm-12 date">
                                    <textarea class="col-md-12 col-sm-12 form-control" style="height: 250px;resize:none"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-10 col-md-offset-1 col-sm-12 text-left">Add Pictures</label>
                                <br/>
                                <div class="col-md-10 col-md-offset-1 col-sm-12 ">
                                    <input type="file" class="frame-file">
                                </div>
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
