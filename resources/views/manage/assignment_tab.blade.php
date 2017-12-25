<div class="container"
     style="height: 48px; padding-top: 10px; padding-bottom: 10px; padding-left: 20px; background-color: #4884E9; border-radius: 5px; box-shadow: 0px 2px 7px #bbbbbb;">
    <div class="row">
        <div style="width: 50%; padding-left: 20px;">
            <small style="color:white">Filter:&nbsp&nbsp</small>
            <span class="dropdown">
                <a class="btn btn-sm dropdown-toggle" style="background-color: white;" href="#" role="button"
                   id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    On Due
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="#"><small>Outdated</small></a>
                </div>
            </span>
        </div>
        <div class="text-right" style="width: 50%; padding-right: 20px;">
            @if($group)
                <a class="btn btn-sm btn-light" href="/post?group_id={{$group->id}}" role="button" style="color:#396be4;">&nbspPost&nbsp</a>
            @else
                <a class="btn btn-sm btn-light disabled" href="#" role="button" style="color:#396be4;">&nbspPost&nbsp</a>
            @endif
        </div>
    </div>
</div>
<div style="height:80%;overflow: scroll;box-shadow: 0px 2px 7px #bbbbbb;border-radius: 5px;background-color: white">
    @if($assignments and count($assignments, 0) > 0)
        <div class="list-group">
            @foreach($assignments as $assignment)
                @include('manage.assignment_item')
            @endforeach
        </div>
    @else
        @include('layouts.empty_view')
    @endif
</div>