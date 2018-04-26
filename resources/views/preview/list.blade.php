@extends('layouts.basic')

@section('title', $basename)

@push('js')
    <script src="{{ url('/js/file_upload.js') }}"></script>

    <script>
        $(function () {
            let files = @json($files);

            for (let i = 0; i < files.length; i++) {
                $("#fileContainer").append($.parseFile(files[i]));
            }
        });
    </script>
@endpush

@section('content')
    <div class="row mt-5">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-dark">{{ $basename }}</h5>
                </div>
                <div class="card-body">
                    <div id="fileContainer"></div>
                </div>
            </div>
        </div>
    </div>
@endsection