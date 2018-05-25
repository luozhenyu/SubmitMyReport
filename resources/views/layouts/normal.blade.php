@extends('layouts.basic')

@push('css_import')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.6-rc.1/dist/css/select2.min.css">
@endpush

@push('css')
    <style>
        #messageTabContainer {
            max-height: 300px;
            overflow: auto;
        }

        #messageTabContainer > a > i {
            float: left;
            line-height: 1.5;
        }

        #messageTabContainer > a > i:hover {
            color: red;
        }

        .messageArea {
            overflow: auto;
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

@push('js_import')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.6-rc.1/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.6-rc.1/dist/js/i18n/zh-CN.js"></script>
@endpush

@push('js')
    <script>
        $(function () {
            $("#sendAdvice").click(function () {
                $.post("{{ route('improve') }}", {advice: $("#adviceField").val()}, function (json) {
                    $("#adviceField").val(null);
                    $("#improveModal").modal('hide');
                    swal(json.message, "", "success");
                }).fail(function (xhr) {
                    let json = xhr.responseJSON;
                    if (json.errors !== undefined) {
                        swal(json.errors.advice[0], "", "error");
                    }
                });
            });
        });

        $(function () {
            function scrollToBottom($object) {
                $object.scrollTop($object[0].scrollHeight);
            }

            function updateUnread($object, unread) {
                let $badge = $object.find(".badge");
                if (unread > 0) {
                    if (!$badge.length) {
                        $badge = $("<span>").addClass("badge badge-danger badge-pill");
                        $object.append($badge);
                    }
                    $badge.text(unread);
                } else {
                    $badge.remove();
                }
            }

            function sendMessage($messageContent) {
                let $messageInput = $messageContent.find(".messageInput");
                if ($messageInput.val()) {
                    $.ajax({
                        url: "{{ route('message') }}/" + $messageContent.data('to'),
                        type: "PUT",
                        data: {text: $messageInput.val()},
                        success: function (json) {
                            refreshMessageContent($messageContent, true);
                            $messageInput.val('');
                        },
                        error: function (xhr) {
                            swal(xhr.responseJSON.errors.text[0], "", "error");
                        }
                    });
                }
            }

            function refreshMessageContent($messageContent, forceRefresh) {
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

            function addMessageTab(toWhom, name, count) {
                count = count || 0;
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
                                        .on('keydown', function (evt) {
                                            if (evt.keyCode === 13) {
                                                evt.preventDefault();
                                                sendMessage($messageContent);
                                            }
                                        })
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
                    .append(
                        $("<i>").addClass("fa fa-times")
                            .click(function () {
                                swal({
                                    title: '删除后不可恢复，您要继续吗？',
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: '是的，删除它',
                                    cancelButtonColor: '#d33',
                                    cancelButtonText: "取消操作"
                                }).then(function (result) {
                                    $.ajax({
                                        url: "{{ route('message') }}/" + toWhom,
                                        type: "DELETE",
                                        success: function (json) {
                                            if (result.value) {
                                                $($messageTab.attr("href")).remove();
                                                $messageTab.fadeOut(500, function () {
                                                    $(this).remove();
                                                });
                                            }
                                        }
                                    });
                                });
                            })
                    )
                    .append(name + " ")
                    .on("shown.bs.tab", function () {
                        refreshMessageContent($messageContent);
                        updateUnread($(this), 0);
                    })
                    .click(function (e) {
                        e.preventDefault();
                        $(this).tab('show');
                    });

                if (count > 0) {
                    $messageTab.append(
                        $("<span>").addClass("badge badge-danger").text(count)
                    );
                }
                $("#messageTabContainer").append($messageTab);
                return $messageTab;
            }

            function refreshMessageTab(forceRefresh) {
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
                        if ($.inArray(toWhom, $existUsers) === -1) {
                            addMessageTab(toWhom, item.from.name, item.count);
                        } else {
                            updateUnread($messageTabContainer.find("#list-" + toWhom + "-list"), item.count);
                        }
                    });

                    //Show first panel
                    //$messageTabContainer.find("a:first-child").click();
                });
            }

            $("#siteMessageModal").on('show.bs.modal', function () {
                refreshMessageTab();
                updateUnread($("#siteMessage"), 0);
            }).on('shown.bs.modal', function () {
                //Select new user to chat with.
                let userPicker = $("#userPicker");
                if (userPicker.data('init')) {
                    return;
                }
                userPicker.data('init', true).select2({
                    dropdownParent: $(this),
                    placeholder: '+ 新会话',
                    width: 'resolve',
                    templateResult: function (state) {
                        if (!state.id) {
                            return state.text;
                        }
                        return $("<span>").text(state.text).append(
                            $("<span>").addClass("text-muted font-weight-light float-right")
                                .css("font-size", "0.7rem")
                                .css("line-height", "2")
                                .text(state.id)
                        );
                    },
                    ajax: {
                        url: "{{ route('message') }}/query",
                        type: 'POST',
                        delay: 150,
                        cache: true
                    }
                }).on('select2:select', function (e) {
                    let selected = e.params.data;//TODO: avoid duplicated
                    addMessageTab(selected.id, selected.text).tab('show');
                    $(this).val(null).trigger('change');
                });
            });
            if (Echo !== undefined) {
                Echo.private('user.{{ Auth::user()->id }}')
                    .listen('.message.received', function (evt) {
                        updateUnread($("#siteMessage"), evt.unread);
                        refreshMessageTab(true);
                    });
            }
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
                            <a class="nav-link mr-1" id="siteMessage" href="javascript:void(0);" data-toggle="modal"
                               data-target="#siteMessageModal">
                                站内信&nbsp;
                                @if(($unread = Auth::user()->unreadReceivedSiteMessages()->count()) > 0)
                                    <span class="badge badge-danger badge-pill">{{ $unread }}</span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                <div class="modal-body" style="min-height: 400px">
                    <div class="row">
                        <div class="col-4">
                            <select id="userPicker" style="width: 100%" title="新会话" data-init="false"></select>
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