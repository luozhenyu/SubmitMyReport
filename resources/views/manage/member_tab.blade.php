<div style="width: 100%;height:85%;overflow: scroll;box-shadow: 0px 2px 7px #bbbbbb; background-color: white; border-radius: 5px;">
    @if(count($members, 0) > 0)
        <table class="table" style="width: 100%;">
            @foreach($members as $member)
                <tr style="height: 40px;">@include('manage.member_item')</tr>
            @endforeach
        </table>
    @else
        @include('layouts.empty_view')
    @endif
</div>