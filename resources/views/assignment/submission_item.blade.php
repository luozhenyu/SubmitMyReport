<div id="accordion" role="tablist" aria-multiselectable="true" style="width:100%;">
    <div class="card">
        <div class="card-header" role="tab" id="heading{{$sub['name']}}">
            <h5 class="mb-0">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$sub['name']}}" aria-expanded="true" aria-controls="collapse{{$sub['name']}}" style="color: black; font-size: small">
                    <div class="container">
                        <div class="row">
                            <div style="width: 50%">{{$sub['name']}}</div>
                            <div style="width: 50%; text-align: right">{{$sub['time']}}</div>
                        </div>
                    </div>
                </a>
            </h5>
        </div>

        <div id="collapse{{$sub['name']}}" class="collapse" role="tabpanel" aria-labelledby="heading{{$sub['name']}}">
            <table class="table">
                @foreach($sub['files'] as $f)
                    <tr>
                        <td>{{$f}}</td>
                        <td style="text-align: right">
                            <a href="#">Download</a>
                        </td>
                    </tr>
                    @endforeach
                    </tr>
            </table>
        </div>
    </div>
</div>