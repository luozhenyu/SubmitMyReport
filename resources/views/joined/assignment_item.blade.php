<a href="#" class="list-group-item list-group-item-action">
    <div class="d-flex w-100 justify-content-between">
        <h5 class="mb-1">{{$assignment['title']}}️</h5>
        <p>{{$assignment['ddl']}}</p>
    </div>
    <p class="mb-1" style="padding-bottom: 5px">{{$assignment['description']}}</p>
    <div>
        <span class="badge badge-pill badge-secondary" style="line-height: 10px">{{$assignment['group']}}</span>
        @if ($assignment['urgent'])
            <span class="badge badge-pill badge-warning" style="line-height: 10px">🔥Urgent</span>
        @endif
        @if ($assignment['submitted'])
            <span class="badge badge-pill badge-success" style="line-height: 10px">️Done</span>
        @endif
    </div>
</a>