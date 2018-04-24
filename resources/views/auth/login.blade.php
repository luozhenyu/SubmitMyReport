@extends('layouts.auth')

@section('title', '登录')

@section('content')
    <h3 class="col p-3">登录</h3>

    <form class="form" method="post" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="username" class="col control-form-label">学号 / 邮箱</label>

            <div class="col">
                <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                       name="username" value="{{ old('username') }}" required autofocus>

                @if ($errors->has('username'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('username') }}</strong>
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
            <div class="col">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        记住密码
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col text-right">
                <a class="btn btn-outline-primary" href="{{ route('register') }}">
                    注册
                </a>

                <button type="submit" class="btn btn-primary">
                    立即登录
                </button>
            </div>

            <div class="col text-right">
                <a class="btn btn-link pr-0" href="{{ route('password.request') }}">
                    忘记了密码？
                </a>
            </div>
        </div>
    </form>
@endsection
