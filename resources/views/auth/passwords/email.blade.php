@extends('auth_layout')

@section('title','Reset Password')

@section('content')
    <div style="padding-left: 20px; padding-right: 20px;">
        <div style="padding-top: 20px; padding-bottom: 15px;">
            <h5>Reset Password</h5>
        </div>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form class="form-horizontal" method="POST" action="{{ route('password.email') }}" style="width: 100%;">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="control-label">E-Mail Address</label>

                <div>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                    @if ($errors->has('email'))
                        <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div>
                    <button type="submit" class="btn btn-primary btn-block">
                        Send Password Reset Link
                    </button>
                </div>
                <div class="text-right" style="padding-top: 15px;">
                    <a href="/login" class="btn btn-outline-primary">
                        Login
                    </a>
                    <a class="btn btn-outline-primary" href="/register">
                        Register
                    </a>
                </div>
            </div>
        </form>
        <div style="height: 30px;"></div>
    </div>
@endsection

