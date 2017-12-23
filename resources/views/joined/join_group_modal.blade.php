<div class="modal fade" id="joinGroupModal" tabindex="-1" role="dialog" aria-labelledby="joinGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Join Group</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <form style="width: 100%;">
                            <div class="container">
                                <div class="row">
                                    <div style="width:80%;">
                                        <input type="text" class="form-control" id="recipient-name">
                                    </div>
                                    <div style="width:20%; text-align: right;">
                                        <button type="button" class="btn btn-outline-primary">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row" style="height: 300px; overflow: scroll;">
                        <table class="table">
                            @if ($search_result && count($search_result, 0) > 0)
                                @foreach($search_result as $result)
                                    <tr>
                                        <td style="line-height: 39px;">
                                            {{$result}}
                                        </td>
                                        <td style="text-align: right">
                                            <button type="button" class="btn btn-primary">Join</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>