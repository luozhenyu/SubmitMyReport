@extends('layouts.layout')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row" style="height: 20px;"></div>
        <div class="row">
            <div class="col col-sm-12 offset-sm-0 col-md-8 offset-md-2" style="overflow: scroll;border-radius: 5px;box-shadow: 0px 2px 7px #bbbbbb; background-color: white; height: 85%; padding-left: 30px; padding-right: 30px;">
                <div class="container">
                    <div class="row" style="padding-top: 20px;">
                        <a href="/joined" role="button" class="btn btn-outline-primary">Back</a>
                        &nbsp&nbsp
                    </div>
                    <div class="row" style="padding-top: 20px;">
                        <h4>Join Group</h4>
                    </div>
                    <div class="row" style="padding-top: 20px;">
                        <form style="width: 100%;" method="POST" action="/join_group">
                            {{ csrf_field() }}
                            <div class="container">
                                <div class="row">
                                    <div style="width:80%;">
                                        <input type="text" class="form-control" id="group-name" name="group_name" required>
                                    </div>
                                    <div style="width:20%; text-align: right;">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row align-items-center">
                        <table class="table">
                            @if ($search_result)
                                @foreach($search_result as $result)
                                    <tr>
                                        <td style="line-height: 39px;">
                                            {{$result->name}}
                                        </td>
                                        <td style="text-align: right">
                                            @if ($result->joined)
                                                <button type="button" class="btn btn-outline-primary disabled">Joined</button>
                                            @else
                                                <a href="/group/join?group_id={{$result->id}}" role="button" class="btn btn-outline-primary">Join</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <div style="height: 200px;">
                                </div>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection