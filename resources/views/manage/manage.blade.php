@extends('layout')
@section('title', 'Manage')
@section('content')
    <div class="container">
        <div class="row" style="height: 20px;"></div>
        <div class="row">
            <div class="col col-sm-12 offset-sm-0 col-md-2 offset-md-1 col-lg-2 offset-lg-1 col-xl-2 offset-xl-1">
                <h4 style="text-align: center; margin-top: 20px; margin-bottom: 20px;">Groups</h4>
                <ul class="list-group">
                    @if($groups)
                        @for ($i = 0; $i < count($groups, 0); $i++)
                            @if ($i == $active_group)
                                <a href="#" class="list-group-item active">{{$groups[$i]}}</a>
                            @else
                                <a href="#" class="list-group-item">{{$groups[$i]}}</a>
                            @endif
                        @endfor
                    @endif
                </ul>
                <p style="padding-top: 10px;"><button class="btn btn-primary btn-md" data-toggle="modal" data-target="#createGroupModal" style="width: 100%;">Create Group</button></p>
                @include('manage.create_group_modal')
            </div>

            <div class="col col-sm-12 offset-sm-0 col-md-8 offset-md-0 col-lg-8 offset-lg-0 col-xl-8 offset-xl-0">
                <div class="container">
                    <ul id="myTab" class="row nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#tab_assignments" data-toggle="tab">Assignments</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_group" data-toggle="tab">Group</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_members" data-toggle="tab">Members</a> </li>
                    </ul>

                    <div class="row tab-content" style="margin-top: 20px; height:85%; border-radius: 5px;">
                        <div role="tabpanel" class="tab-pane fade show active" id="tab_assignments" style="width: 100%;">
                            @include('manage.assignment_tab')
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab_group" style="width: 100%;">
                            @include('manage.group_tab')
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab_members" style="width: 100%;">
                            @include('manage.member_tab')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection