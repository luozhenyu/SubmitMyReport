<div style="overflow: scroll;border-radius: 5px;margin: 5px; padding-top:20px;padding-left:30px;padding-right:30px;padding-bottom:30px;box-shadow: 0px 2px 7px #bbbbbb; background-color: white; height: 85%;">
    @if($group)
        <div class="container">
            <div class="row">
                <h3>{{$group->name}}</h3>
            </div>
            <div class="row">
                <div><small>created on {{$group->created_at}}</small></div>
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="jumbotron" style="width: 100%">
                    <p>{{$group->description}}</p>
                </div>
            </div>
            <div class="row">
                <h5>Administrators</h5>
            </div>
            <div class="row" style="margin-top: 10px;">
                @foreach ($admins as $adm)
                    <div style="height:50px; margin-right: 10px;">
                        <span class="badge badge-secondary">{{$adm->name}}</span>
                    </div>
                @endforeach
            </div>
            <div class="row" style="margin-top: 20px;">
                <a href="/group/quit?group_id={{$group->id}}" class="btn btn-danger btn-lg" role="button" style="width:100%;">Quit</a>
            </div>
        </div>
    @else
        @include('layouts.empty_view')
    @endif
</div>