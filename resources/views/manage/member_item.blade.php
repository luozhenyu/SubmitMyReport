@if ($member['is_admin'])
    <th>
        <span style="line-height:22px; vertical-align: middle; padding-right: 10px;">
            {{$member['username']}}
        </span>
        <span class="badge badge-info" style="font-weight: bold;">admin</span>
    </th>
    <th style="text-align: right">
        <button type="button" class="btn btn-sm btn-warning">Cancel</button>
        <button type="button" class="btn btn-sm btn-danger">Remove</button>
    </th>
@else
    <th><span style="line-height:22px; vertical-align: middle; padding-right: 10px;">
            {{$member['username']}}
        </span></th>
    <th style="text-align: right">
        <button type="button" class="btn btn-sm btn-primary">Appoint</button>
        <button type="button" class="btn btn-sm btn-danger">Remove</button>
    </th>
@endif