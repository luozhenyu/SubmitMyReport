<div style="width: 100%;height:90%;overflow: scroll;box-shadow: 0px 2px 7px #bbbbbb; background-color: white; border-radius: 5px;">
    @if($members and count($members, 0) > 0)
        <table class="table" style="width: 100%;">
            @foreach($members as $member)
                <tr style="height: 40px;">@include('manage.member_item')</tr>
            @endforeach
        </table>
    @else
        @include('manage.empty_view')
    @endif
</div>