<a href="/assignment" class="list-group-item list-group-item-action">
    <div class="d-flex w-100 justify-content-between">
        <h5 class="mb-1">{{$assignment['title']}}️</h5>
        <p>{{$assignment['ddl']}}</p>
    </div>
    <p class="mb-1" style="padding-bottom: 5px">{{$assignment['description']}}</p>
    @if ($assignment['got']==$assignment['total'])
        <div class="progress" style="height: 18px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;"
                 aria-valuenow="{{$assignment['got']}}" aria-valuemin="0"
                 aria-valuemax="{{$assignment['total']}}">{{$assignment['got']}}/{{$assignment['total']}}</div>
        </div>
    @else
        <div class="progress" style="height: 18px;">
            <div class="progress-bar bg-primary" role="progressbar"
                 style="width: {{100*$assignment['got']/$assignment['total']}}%;" aria-valuenow="{{$assignment['got']}}"
                 aria-valuemin="0" aria-valuemax="{{$assignment['total']}}">{{$assignment['got']}}
                /{{$assignment['total']}}</div>
        </div>
    @endif
    <div style="padding-top: 10px;">
        <span class="badge badge-pill badge-secondary" style="line-height: 10px">{{$assignment['group']}}</span>
    </div>
</a>