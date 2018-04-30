@extends('layouts.basic')

@push('css')
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap-select/1.13.0-beta/css/bootstrap-select.min.css">
    <style>
        .messageArea {
            overflow: scroll;
        }

        .sent, .received {
            position: relative;
            padding: .75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: .25rem;

            display: inline-block;
            clear: both;
        }

        .sent > .date, .received > .date {
            font-size: 0.7rem;
        }

        .sent {
            float: right;
            color: #004085;
            background-color: #cce5ff;
            border-color: #b8daff;
        }

        .received {
            float: left;
            color: #383d41;
            background-color: #e2e3e5;
            border-color: #d6d8db;
        }
    </style>
@endpush

@push('js')
    <script src="https://cdn.bootcss.com/bootstrap-select/1.13.0-beta/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap-select/1.13.0-beta/js/i18n/defaults-zh_CN.min.js"></script>

    <script src="https://{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
    <script src="{{ url('/js/app.js') }}"></script>

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
                        alert(json.errors.advice[0]);
                    }
                });
            });
        });


        @if(Auth::user()->id ===1)
        $(function () {

            function scrollToBottom($object) {
                $object.scrollTop($object[0].scrollHeight);
            }

            function sendMessage($messageContent) {
                let text = $messageContent.find(".messageInput").val();

                $.ajax({
                    url: "{{ route('message') }}/" + $messageContent.data('to'),
                    type: "PUT",
                    data: {text: text},
                    success: function (json) {
                        refreshMessageContent($messageContent, true);
                    },
                    error: function (xhr) {
                        alert("发送失败");
                    }
                });
            }

            function refreshMessageContent($messageContent, forceRefresh = false) {
                if ($messageContent.data("init") && !forceRefresh) {
                    return;
                }
                $messageContent.data("init", true);

                let $messageArea = $messageContent.find(".messageArea");
                let $existMessages = $messageArea.children().map(function () {
                    return $(this).data("id")
                });

                $.get("{{ route('message') }}/" + $messageContent.data('to'), function (json) {
                    json.forEach(function (item) {
                        if ($.inArray(item.id, $existMessages) !== -1) {
                            return;
                        }

                        $messageArea.append(
                            $("<div>").data("id", item.id)
                                .addClass(item.data.type === 1 ? 'sent' : 'received')
                                .append(
                                    $("<div>").addClass("date").text(item.created_at)
                                )
                                .append(item.data.text)
                        );
                    });
                    scrollToBottom($messageArea);
                });
            }

            function refreshMessageTab(forceRefresh = false) {
                let $messageTabContainer = $("#messageTabContainer");
                if ($messageTabContainer.data('init') && !forceRefresh) {
                    return;
                }
                $messageTabContainer.data('init', true);

                let $existUsers = $messageTabContainer.children().map(function () {
                    return $(this).data("to")
                });
                $.get("{{ route('message') }}", function (json) {
                    json.forEach(function (item) {
                        let toWhom = item.from.student_id;
                        if ($.inArray(toWhom, $existUsers) !== -1) {
                            return;
                        }

                        let dataTarget = "list-" + toWhom;

                        //Add tab contents
                        let $messageContent = $("<div>").addClass("tab-pane fade")
                            .attr("id", dataTarget)
                            .data("to", toWhom)
                            .data("init", false)
                            .append(
                                $("<div>").addClass("bg-light messageArea").css("height", "250px")
                            )
                            .append(
                                $("<div>").addClass("row")
                                    .append(
                                        $("<div>").addClass("col-9 pr-0").append(
                                            $("<textarea>").addClass("form-control messageInput")
                                                .attr("rows", 3)
                                                .css("resize", "none")
                                        )
                                    )
                                    .append(
                                        $("<div>").addClass("col-3 pl-0").append(
                                            $("<button>").attr("type", "button")
                                                .addClass("btn btn-outline-success btn-lg btn-block px-0 h-100")
                                                .text("发送")
                                                .click(function () {
                                                    sendMessage($messageContent);
                                                })
                                        )
                                    )
                            );

                        $("#messageContentContainer").append($messageContent);

                        //Add tabs
                        let $messageTab = $("<a>").addClass("list-group-item list-group-item-action")
                            .attr("id", dataTarget + "-list")
                            .attr("href", "#" + dataTarget)
                            .data("toggle", "list")
                            .data("to", toWhom)
                            .text(item.from.name + " ")
                            .on("shown.bs.tab", function () {
                                refreshMessageContent($messageContent);
                            })
                            .click(function (e) {
                                e.preventDefault();
                                $(this).tab('show');
                            });

                        if (item.count > 0) {
                            $messageTab.append(
                                $("<span>").addClass("badge badge-danger").text(item.count)
                            );
                        }

                        $messageTabContainer.append($messageTab);
                    });

                    //Show first panel
                    $messageTabContainer.find("a:first-child").click();
                });
            }

            $("#userPicker").selectpicker();

            $("#siteMessageModal").on('show.bs.modal', function () {
                refreshMessageTab();
            });


            //send message button
            // $("#sendMessage").click(function () {
            //
            // });

            Echo.private('user.{{ Auth::user()->id }}')
                .listen('message.received', function (evt) {
                    console.log(evt);
                });
        });
        @endif

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
                            <a class="nav-link mr-1" id="siteMessage" href="javascript:void(0);" data-toggle="modal"
                               data-target="#siteMessageModal">
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

    <!-- Advice Modal -->
    <div class="modal fade" id="improveModal" tabindex="-1" role="dialog"
         aria-labelledby="improveModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="improveModalTitle">意见反馈</h5>
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

    <!-- Site Message Modal -->
    <div class="modal fade" id="siteMessageModal" tabindex="-1" role="dialog"
         aria-labelledby="siteMessageModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="siteMessageModalTitle">站内信</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <div class="row">
                        <div class="col-4">
                            <select class="form-control" id="userPicker" data-live-search="true" title="+ 新会话">
                            </select>

                            <div class="list-group text-center mt-1" id="messageTabContainer"
                                 role="tablist" data-init="false"></div>
                        </div>

                        <div class="col-8">
                            <div class="tab-content" id="messageContentContainer"></div>
                        </div>
                    </div>


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