<div style="overflow: scroll;border-radius: 5px;margin: 5px; padding-top:20px;padding-left:30px;padding-right:30px;padding-bottom:30px;box-shadow: 0px 2px 7px #bbbbbb;">
    <div class="container">
        <div class="row">
            <h3>{{$group_name}}</h3>
        </div>
        <div class="row">
            <div>
                <small>created by {{$creator}} on {{$created_on}}</small>
            </div>
        </div>
        <div class="row" style="margin-top: 10px;">
            <div class="jumbotron">
                <p>{{$description}}</p>
            </div>
        </div>
        <div class="row">
            <h5>Administrators</h5>
        </div>
        <div class="row" style="margin-top: 10px;">
            @foreach ($administrators as $adm)
                <div style="height:50px; margin-right: 10px;">
                    <span class="badge badge-secondary">{{$adm}}</span>
                </div>
            @endforeach
        </div>
        <div class="row" style="margin-top: 20px;">
            <a href="#" class="btn btn-danger btn-lg" role="button" style="width:100%;">Quit</a>
        </div>
    </div>
</div>