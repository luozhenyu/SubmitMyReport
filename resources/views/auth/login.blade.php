@extends('auth_layout')

@section('title','Login')

@section('content')
    <div class="col" style="padding-top: 20px; padding-bottom: 15px;"><h3>Login</h3></div>
    <form class="form" style="width: 100%;" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="col control-label">E-Mail Address</label>

            <div class="col">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required
                       autofocus>

                @if ($errors->has('email'))
                    <span class="help-block">
                        <br>
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col control-label">Password</label>

            <div class="col">
                <input id="password" type="password" class="form-control" name="password" required>

                @if ($errors->has('password'))
                    <span class="help-block">
                        <br>
                        <small>{{ $errors->first('password') }}</small>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col text-right">
                <button type="submit" class="btn btn-primary">
                    Login
                </button>

                <a class="btn btn-outline-primary" href="{{ route('register') }}">
                    Register
                </a>

                <a class="btn btn-link" style="padding-right: 0px;" href="{{ route('password.request') }}">
                    Forgot Your Password?
                </a>
            </div>
        </div>
    </form>
    <div style="height: 30px;"></div>
@endsection
