@extends('layouts.auth')

@section('title','Reset Password')

@section('content')
    <h3 class="col p-3">重设密码</h3>

    <form class="form" method="post" action="{{ route('password.request') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label for="email" class="col control-form-label">邮箱</label>

            <div class="col">
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                       name="email" value="{{ $email ?? old('email') }}" required autofocus>

                @if ($errors->has('email'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="password" class="col control-form-label">密码</label>

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
            <label for="password-confirm" class="col control-form-label">确认密码</label>

            <div class="col">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
        </div>

        <div class="form-group">
            <div class="col text-center">
                <button type="submit" class="btn btn-primary btn-block">
                    重置密码
                </button>
            </div>

            <div class="col text-right mt-4">
                <a class="btn btn-outline-primary" href="{{ route('register') }}">
                    注册
                </a>

                <a class="btn btn-outline-primary" href="{{ route('login') }}">
                    登录
                </a>
            </div>
        </div>
    </form>
@endsection
