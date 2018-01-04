@extends('layouts.app')

@section('title','user')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">

                <div class="panel-heading">
                    <center><h3>User info</h3></center>
                    <a href="{{route('readTweet')}}">
                        <button class="btn btn-warning">
                            Back
                        </button>
                    </a>
                </div>

                <div class="panel-body">

                    @if(isset($user))
                        <table class="table">
                            <tr><td>User ID: </td><td>{{$user->id}}</td></tr>
                            <tr><td>User role: </td><td>{{\App\Role::where('id',$user->role_id)->first()->name}}</td></tr>
                            <tr><td>User name: </td><td>{{$user->name}}</td></tr>
                            <tr><td>User email: </td><td>{{$user->email}}</td></tr>
                            <tr><td>Registered:</td><td>{{\App\TimeHelper::passedTime($user->created_at)}}</td></tr>
                            <tr><td>Message count: </td><td>{{\App\Message::where([['user_id', $user->id],['old' , 0 ]])->count()}}</td></tr>
                            <tr><td>Comment count: </td><td>{{\App\Comment::where([['user_id', $user->id],['old' , 0 ]])->count()}}</td></tr>
                            <tr><td>Last message created
                                    at: </td><td>{{\App\TimeHelper::passedTime(\App\Message::where([['user_id', $user->id],['old' , 0 ]])->max('created_at'))}}</td></tr>
                            <tr><td>Last comment created
                                    at: </td><td>{{\App\TimeHelper::passedTime(\App\Comment::where([['user_id', $user->id],['old' , 0 ]])->max('created_at'))}}</td></tr>
                        </table>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection