@extends('layouts.app')

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
                    <div class="panel panel-heading">Submit Homework</div>
                    <div class="panel panel-body">
                        <h1 class="text-center">Homework Name</h1>
                        <h4 class="text-center text-warning">2000.10.10</h4>
                        <p>As with bootstrap’s own plugins, datepicker provides a data-api that can be used to instantiate datepickers without the need for custom javascript. For most datepickers, simply set data-provide="datepicker" on the element you want to initialize, and it will be intialized lazily, in true bootstrap fashion. For inline datepickers, use data-provide="datepicker-inline"; these will be immediately initialized on page load, and cannot be lazily loaded.</p>
                        <p>As with bootstrap’s own plugins, datepicker provides a data-api that can be used to instantiate datepickers without the need for custom javascript. For most datepickers, simply set data-provide="datepicker" on the element you want to initialize, and it will be intialized lazily, in true bootstrap fashion. For inline datepickers, use data-provide="datepicker-inline"; these will be immediately initialized on page load, and cannot be lazily loaded.</p>
                        <p>As with bootstrap’s own plugins, datepicker provides a data-api that can be used to instantiate datepickers without the need for custom javascript. For most datepickers, simply set data-provide="datepicker" on the element you want to initialize, and it will be intialized lazily, in true bootstrap fashion. For inline datepickers, use data-provide="datepicker-inline"; these will be immediately initialized on page load, and cannot be lazily loaded.</p>
                        <div class="text-center">
                            <img src="#">
                        </div>
                    </div>
                    <div class="panel-footer">
                        <form class="form-horizontal" method="submit">
                            <div class="form-group" >
                                <div class="col-md-offset-1 col-md-10 col-sm-12">
                                    <input type="file" class="frame-file">
                                </div>
                            </div>

                            <div class="form-group" >
                                <div class="col-md-offset-1 col-md-10 col-sm-12 text-right">
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
