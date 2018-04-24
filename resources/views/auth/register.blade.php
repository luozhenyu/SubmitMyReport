@extends('layouts.auth')

@section('title', '注册')

@section('content')
    <h3 class="col p-3">注册</h3>

    <form class="form" method="post" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="student_id" class="col control-form-label">学号</label>

            <div class="col">
                <input id="student_id" type="text"
                       class="form-control{{ $errors->has('student_id') ? ' is-invalid' : '' }}"
                       name="student_id" value="{{ old('student_id') }}" required autofocus>

                @if ($errors->has('student_id'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('student_id') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="col control-form-label">邮箱</label>

            <div class="col">
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                       name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="name" class="col control-form-label">姓名</label>

            <div class="col">
                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                       name="name" value="{{ old('name') }}" required>

                @if ($errors->has('name'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('name') }}</strong>
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
            <div class="col text-right">
                <a class="btn btn-outline-primary" href="{{ route('login') }}">
                    登录
                </a>

                <button type="submit" class="btn btn-primary">
                    立即注册
                </button>
            </div>
        </div>
    </form>
@endsection
