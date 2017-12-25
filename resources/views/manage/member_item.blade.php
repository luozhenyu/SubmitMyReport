@if ($member->is_admin)
    <th>
        <span style="line-height:22px; vertical-align: middle; padding-right: 10px;">
            {{$member->name}}
        </span>
        @if ($member->is_creator)
            <span class="badge badge-primary" style="font-weight: bold;">creator</span>
        @else
            <span class="badge badge-info" style="font-weight: bold;">admin</span>
        @endif
    </th>
    <th style="text-align: right">
        @if ($user->id != $member->id and $user->is_creator)
            <a href="/group/fire?user_id={{$member->id}}&group_id={{$group->id}}" role="button" class="btn btn-sm btn-warning">Fire</a>
            <a href="/group/remove?user_id={{$member->id}}&group_id={{$group->id}}" class="btn btn-sm btn-danger">Remove</a>
        @endif
    </th>
@else
    <th><span style="line-height:22px; vertical-align: middle; padding-right: 10px;">
            {{$member->name}}
        </span></th>
    <th style="text-align: right">
        <a href="/group/appoint?user_id={{$member->id}}&group_id={{$group->id}}" role="button" class="btn btn-sm btn-primary">Appoint</a>
        <a href="/group/remove?user_id={{$member->id}}&group_id={{$group->id}}" role="button" class="btn btn-sm btn-danger">Remove</a>
    </th>
@endif