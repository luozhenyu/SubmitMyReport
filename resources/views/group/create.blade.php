@extends('layouts.normal')

@section('title', '创建小组')

@section('navbar')
    <li>
        <a class="nav-link" href="{{ route('home') }}">主页</a>
    </li>

    <li class="active">
        <a class="nav-link">我的小组</a>
    </li>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('group') }}">小组</a></li>
        <li class="breadcrumb-item active">创建小组</li>
    </ol>
@endsection

@section('content')
    <div class="col-md-8 offset-md-2">
        <form method="post" action="{{ route('group') }}">
            @csrf

            <div class="form-group row">
                <label for="name" class="col-md-2 col-form-label">名称</label>

                <div class="col-md-10">
                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                           name="name" value="{{ old('name') }}" required autofocus>
                    @if ($errors->has('name'))
                        <span class="invalid-feedback">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="description" class="col-md-2 col-form-label">描述</label>

                <div class="col-md-10">
                <textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                          name="description" rows="6" required>{{ old('description') }}</textarea>
                    @if ($errors->has('description'))
                        <span class="invalid-feedback">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row justify-content-center">
                <button type="submit" class="btn btn-primary mt-4">
                    立即创建
                </button>
            </div>
        </form>
    </div>
@endsection