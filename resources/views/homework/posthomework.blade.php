@extends('layouts.app');

@push('css')
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://cdn.bootcss.com/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet">
@endpush


@push('js')
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdn.bootcss.com/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript">
        $('#datepicker').datepicker({
            format: 'mm/dd/yyyy'
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
