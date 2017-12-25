@extends('layout')
@section('title', 'Joined')
@section('content')
    <div class="container">
        <div class="row" style="height: 20px;"></div>
        <div class="row">
            <div class="col col-sm-12 offset-sm-0 col-md-2 offset-md-1">
                <h4 style="text-align: center; margin-top: 20px; margin-bottom: 20px;">Groups</h4>
                <div style="background-color: white; border-radius: 5px; box-shadow: 0px 2px 7px #bbbbbb;">
                    @if(count($groups)>0)
                        <ul class="list-group">
                        @for ($i = 0; $i < count($groups, 0); $i++)
                            @if ($groups[$i]->id == $current_group)
                                <a href="/joined?current_group={{$groups[$i]->id}}" class="list-group-item active">{{$groups[$i]->name}}</a>
                            @else
                                <a href="/joined?current_group={{$groups[$i]->id}}" class="list-group-item">{{$groups[$i]->name}}</a>
                            @endif
                        @endfor
                        </ul>
                    @else
                        <div style="height: 100px;">
                            @include('empty_view')
                        </div>
                    @endif
                </div>
                <div style="padding-top: 10px;"><a href="/join_group" role="button" class="btn btn-primary btn-md" style="width: 100%;">Join Group</a></div>
            </div>

            <div class="col col-sm-12 offset-sm-0 col-md-8 offset-md-0 col-lg-8 offset-lg-0 col-xl-8 offset-xl-0">
                <div class="container">
                    <ul id="myTab" class="row nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#tab_assignments" data-toggle="tab">Assignments</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_group" data-toggle="tab">Group</a></li>
                    </ul>

                    <div class="row tab-content" style="margin-top: 20px; height:85%; border-radius: 5px;">
                        <div role="tabpanel" class="tab-pane fade show active" id="tab_assignments" style="width: 100%;">
                            @include('joined.assignment_tab')
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab_group" style="width: 100%;">
                            @include('joined.group_tab')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection