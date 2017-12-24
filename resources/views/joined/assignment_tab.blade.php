<div style="height: 48px; padding-top: 10px; padding-bottom: 10px; padding-left: 20px; background-color: #4884E9; border-radius: 5px; box-shadow: 0px 2px 7px #bbbbbb;">
    <span><small style="color:white">Filter:&nbsp&nbsp</small></span>
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
<div style="height:80%;overflow: scroll;border-radius: 5px; box-shadow: 0px 2px 7px #bbbbbb;">
    @if($assignments && count($assignments, 0) > 0)
        <div class="list-group">
            @foreach($assignments as $assignment)
                @include('joined.assignment_item')
            @endforeach
        </div>
    @else
        @include('assignment_empty_view')
    @endif
</div>