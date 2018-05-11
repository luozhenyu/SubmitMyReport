@extends('layouts.normal')

@section('title', "修改资料")

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
        <li class="breadcrumb-item active">修改资料</li>
    </ol>
@endsection

@section('content')
    <div class="col-md-8 offset-md-2">
        <h3 class="col p-3">个人资料</h3>

        <form class="form" method="post" action="{{ route('profile') }}">
            @csrf

            {{ method_field('PUT') }}

            @isset($success)
                <div class="form-group">
                    <div class="col">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>修改成功!</strong> 您已成功更新资料
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
                <label for="email" class="col control-form-label">邮箱</label>

                <div class="col">
                    <input id="email" type="email"
                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                           name="email" value="{{ $user->email }}" required>

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
                    <input id="name" type="text"
                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                           name="name" value="{{ $user->name }}" required>

                    @if ($errors->has('name'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
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