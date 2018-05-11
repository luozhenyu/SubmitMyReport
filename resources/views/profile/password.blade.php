@extends('layouts.normal')

@section('title', "修改密码")

@section('navbar')
    <li class="active">
        <a class="nav-link ">主页</a>
    </li>

    <li>
        <a class="nav-link" href="{{ route('group') }}">我的小组</a>
    </li>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">主页</a></li>
        <li class="breadcrumb-item"><a href="{{ route('profile') }}">修改资料</a></li>
        <li class="breadcrumb-item active">修改密码</li>
    </ol>
@endsection

@section('content')
    <div class="col-md-8 offset-md-2">
        <h3 class="col p-3">修改密码</h3>

        <form class="form" method="post" action="{{ route('profile.password') }}">
            @csrf

            {{ method_field('PUT') }}

            @isset($success)
                <div class="form-group">
                    <div class="col">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>修改成功!</strong> 请使用新的密码登录
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endisset

            <div class="form-group">
                <label for="student_id" class="col control-form-label">学号</label>

                <div class="col">
                    <input id="student_id" type="text" class="form-control" value="{{ $user->student_id }}" disabled>
                </div>
            </div>

            <div class="form-group">
                <label for="old_password" class="col control-form-label">当前密码</label>

                <div class="col">
                    <input id="old_password" type="password"
                           class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}"
                           name="old_password" autofocus required>

                    @if ($errors->has('old_password'))
                        <span class="invalid-feedback">
                        <strong>{{ $errors->first('old_password') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="col control-form-label">新密码</label>

                <div class="col">
                    <input id="password" type="password"
                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                           name="password" required>

                    @if ($errors->has('password'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label for="password-confirm" class="col control-form-label">确认新密码</label>

                <div class="col">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                           required>
                </div>
            </div>

            <div class="form-group mt-5 mb-5">
                <div class="col text-right">
                    <button type="submit" class="btn btn-primary">
                        确认修改
                    </button>
                </div>
            </div>
        </form>
    </div>

@endsection