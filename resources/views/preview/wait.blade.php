@extends('layouts.basic')

@section('title', '请稍候')

@push('js')
    <script>
        Echo.private('user.{{ Auth::user()->id }}')
            .listen('.conversion.finished', (evt) => {
                if (evt.success) {
                    window.location.reload();
                } else {
                    $("#hit").text("转换失败，请直接下载。");
                }
            });

        setInterval("window.location.reload();", 5000);
    </script>
@endpush

@section('content')
    <div class="row">
        <div class="col text-center">
            <h1 class="mt-5" id="hit">正在加载中...</h1>
        </div>
    </div>
@endsection