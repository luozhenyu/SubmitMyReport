@extends('layouts.basic')

@push('js')
    <script>
        $(function () {
            $("#sendAdvice").click(function () {
                $.post("{{ route('improve') }}", {advice: $("#adviceField").val()}, function (json) {
                    $("#adviceField").val('');
                    $("#improveModal").modal('hide');
                    alert(json.message);
                }).fail(function (xhr) {
                    let json = xhr.responseJSON;
                    if (json.errors !== undefined) {
                        console.log(json.errors);
                        alert(json.errors.advice[0]);
                    }
                });
            });
        });
    </script>
@endpush

@section('default_content')
    <nav class="navbar navbar-expand-md navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    @yield('navbar')
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li><a class="nav-link" href="{{ route('login') }}">登录</a></li>
                        <li><a class="nav-link" href="{{ route('register') }}">注册</a></li>
                    @else
                        <li>
                            <a class="nav-link" href="javascript:void(0);" data-toggle="modal"
                               data-target="#improveModal">意见反馈</a>
                        </li>

                        <li>
                            <a class="nav-link mr-1" href="javascript:alert('即将上线');">
                                站内信
                                @if($unreadNotifications = Auth::user()->unreadNotifications)
                                    <span class="badge badge-info badge-pill">{{ $unreadNotifications->count() }}</span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    修改资料
                                </a>

                                <a class="dropdown-item" href="{{ route('profile.password') }}">
                                    修改密码
                                </a>

                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    注销
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="post"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Modal -->
    <div class="modal fade" id="improveModal" tabindex="-1" role="dialog"
         aria-labelledby="improveModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">意见反馈</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>如在使用过程中遇到任何问题，或有更好的建议，请告诉我们</p>
                    <textarea id="adviceField" class="form-control" rows="10" title="description"
                              style="resize: none" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="sendAdvice">发送给管理员</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-10 offset-md-1 bg-white mt-3 mb-5 pt-3 pb-3"
         style="border-radius: 5px; box-shadow: 0 3px 7px #bbbbbb;">
        <div class="row">
            <div class="col-md-5">
                @yield('breadcrumbs')
            </div>

            <div class="col-md-7 text-right">
                @yield('side_header')
            </div>
        </div>

        @yield('content')
    </div>
@endsection