@extends('layouts.basic')

@section('title', '请稍候')

@push('js')
    <script>
        function checkStatus() {
            $.post('', function (json) {
                let code = json.code, msg = json.msg;
                $("#hit").text(msg);
                if (code === 0) {
                    setTimeout("checkStatus()", 1000);
                } else if (code === 1) {
                    setTimeout("window.location.reload()", 500);
                }
            });
        }

        $(function () {
            checkStatus();
        });
    </script>
@endpush

@section('content')
    <div class="row">
        <div class="col text-center">
            <h1 class="mt-5" id="hit">正在加载中</h1>
        </div>
    </div>
@endsection