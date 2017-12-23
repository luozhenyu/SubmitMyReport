@extends('auth_layout')

@section('title','Register')

@section('content')
    <div class="col" style="padding-top: 20px; padding-bottom: 15px;"><h3>Register</h3></div>
    <form class="form" method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="col control-label">Name</label>

            <div class="col">
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                @if ($errors->has('name'))
                    <span class="help-block">
                        <br>
                        <small>{{ $errors->first('name') }}</small>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="col">E-Mail Address</label>

            <div class="col">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                    <span class="help-block">
                        <br>
                        <small>{{ $errors->first('email') }}</small>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col">Password</label>

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
            <label for="password-confirm" class="col control-label">Confirm Password</label>

            <div class="col">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
        </div>

        <div class="form-group">
            <div class="col text-right">
                <button type="submit" class="btn btn-primary">
                    Register
                </button>
                <a class="btn btn-outline-primary" href="/login">
                    Login
                </a>
            </div>
        </div>
    </form>
@endsection
