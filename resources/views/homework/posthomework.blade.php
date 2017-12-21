@extends('layouts.app')

@section('title','')

@push('css')
    <link href="{{ url('components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@endpush


@push('js')
    <script src="{{ url('components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ url('components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.zh-CN.min.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            $('#datepicker').datepicker({
                format: 'mm/dd/yyyy'
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
                                <div class="input-group date col-md-9 col-sm-12" id="datepicker">
                                    <input class="form-control" type="text">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
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
