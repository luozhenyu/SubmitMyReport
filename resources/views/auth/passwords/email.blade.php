@extends('layouts.auth')

@section('title','Reset Password')

@section('content')
    <h3 class="col p-3">重设密码</h3>

    <form class="form" method="post" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group">
            <label for="email" class="col control-form-label">邮箱</label>

            <div class="col">
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                       name="email" value="{{ old('email') }}" required autofocus>

                @if ($errors->has('email'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col text-center">
                <button type="submit" class="btn btn-primary btn-block">
                    发送密码重置邮件
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

