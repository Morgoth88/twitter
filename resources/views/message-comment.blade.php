@extends('layouts.app')

@section('title','message')

@section('content')
    <div class="container">
        <!--display flash sessions messages-->
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
    @endif

    <!--main container-->
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <!--panel header-->
                    <div class="panel-heading">
                        <a href="{{route('readTweet')}}">
                        <button class="btn btn-warning">
                            Back
                        </button>
                        </a>

                        <!--new tweet button-->
                        <button id="tweetBtn" class="btn btn-primary" onclick="createForm()">
                            <i class="fa fa-commenting-o fa-2x" aria-hidden="true"></i>
                        </button>

                    </div>

                    <!--panel body-->
                    <div class="panel-body">
                        <!--display errors-->
                        @if (count($errors) > 0)

                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                    @endif

                    <!--tweet form-->
                        <div id="tweet-form"></div>

                        <!--tweets display-->
                    @if(isset($tweet))

                        <!--tweet-->
                            <div class="tweet">

                                <!--tweet name-->
                                <div class="tweet-name">
                                    @if(Auth::user()->role_id == 1)
                                        <a href="{{route('showUser',['user' => $tweet->user->id])}}">{{$tweet->user->name}}</a>
                                    @else
                                        {{$tweet->user->name}}
                                    @endif

                                <!--if admin/ ban button-->
                                    @if(Auth::user()->role_id == 1)
                                        <button id="banUserBtn">
                                            <a href="{{route('userBan',['user' => $tweet->user->id])}}"><i
                                                        class="fa fa-ban" aria-hidden="true"></i></a>
                                        </button>
                                    @endif

                                <!--tweet delete and update icons-->
                                    @if(Auth::user()->id===$tweet->user->id && \App\TimeHelper::lessThanTwoMinutes($tweet))
                                        <span class="up-del-links">

                                            <!--if admin/ ban button-->
                                            @if(Auth::user()->role_id == 1)
                                                <button id="banMessBtn">
                                                    <a href="{{route('messageBan',['message' => $tweet->id])}}">
                                                        <i class="fa fa-ban" aria-hidden="true"></i>
                                                    </a>
                                                </button>
                                            @endif

                                                <!--update button-->
                                                <button id="msgUpdtBtn" onclick="updateForm({{$tweet->id}})">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                </button>

                                            <!--delete button with form-->
                                                <form method="POST"
                                                      action="{{route('deleteTweet',['message'=> $tweet->id])}}">
                                                    {{method_field('DELETE')}}
                                                    {{csrf_field()}}
                                                    <button id="msgDltBtn" type="submit">
                                                        <i class='fa fa-times' aria-hidden='true'></i>
                                                    </button>
                                                </form>
                                            </span>
                                    @endif
                                </div>

                                <!--tweet created at-->
                                <div class="tweet-time-div">
                                    <span class="tweet-time">{{\App\TimeHelper::passedTime($tweet->created_at)}}</span>
                                </div>

                                <!--tweet message-->
                                <div class="tweet-text" data-id="{{$tweet->id}}">{{$tweet->text}}</div>

                                <!--comment icon-->
                                <div class="tweet-icons">
                                        <span class="comment-link">
                                            <button id="cmntBtn" onclick="commentForm({{$tweet->id}})">
                                                <i class="fa fa-comments" aria-hidden="true"></i>
                                            </button>
                                        </span>

                                    <!--comment-->
                                    @if(count($tweet->comment) > 0 && count($tweet->comment) < 2)
                                        {{count($tweet->comment)}}
                                            comment
                                    @elseif(count($tweet->comment) >= 2)
                                        {{count($tweet->comment)}}
                                        comments
                                    @endif
                                </div>
                                        <div class="comments-container">
                                            @foreach($tweet->comment as $comment)
                                                @if($comment->old !=1)

                                                    <div class="comment">
                                                        <!--comment user name-->
                                                        <div class="comment-name">
                                                            @if(Auth::user()->role_id == 1)
                                                                <a href="{{route('showUser',['user' => $comment->user_id])}}">{{$comment->user->name}}</a>
                                                            @else
                                                                {{$comment->user->name}}
                                                            @endif

                                                        <!--if admin/ ban button-->
                                                            @if(Auth::user()->role_id == 1)
                                                                <button id="banUserBtn">
                                                                    <a href="{{route('userBan',['user' => $comment->user_id])}}">
                                                                        <i class="fa fa-ban" aria-hidden="true"></i>
                                                                    </a>
                                                                </button>
                                                            @endif

                                                            <span class="up-del-links">
                                                         <!--if admin/ ban button-->
                                                                @if(Auth::user()->role_id == 1)
                                                                    <button id="banCommBtn">
                                                                        <a href="{{route('commentBan',['message' => $tweet->id, 'comment' => $comment->id])}}">
                                                                            <i class="fa fa-ban" aria-hidden="true"></i>
                                                                        </a>
                                                                     </button>
                                                                @endif

                                                                @if(Auth::user()->id===$comment->user->id && \App\TimeHelper::lessThanTwoMinutes($comment))
                                                                <!--comment update button-->
                                                                    <button id="msgUpdtBtn"
                                                                            onclick="commentUpdateForm({{$comment->id}})">
                                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                                    </button>

                                                                    <!--comment delete button with form-->
                                                                    <form method="POST"
                                                                          action="{{route('deleteComment',['message' => $tweet->id, 'comment' => $comment->id])}}">
                                                                        {{method_field('DELETE')}}
                                                                        {{csrf_field()}}
                                                                        <button id="msgDltBtn" type="submit">
                                                                            <i class='fa fa-times'
                                                                               aria-hidden='true'></i>
                                                                        </button>
                                                                     </form>
                                                                @endif
                                                            </span>
                                                            <!--comment passed time-->
                                                            <span class="comment-time">{{\App\TimeHelper::passedTime($comment->created_at)}}</span>
                                                        </div>

                                                    <!--comment text-->
                                                    <div class="comment-text"
                                                         data-tweet-id ="{{$tweet->id}}" data-comment-id="{{$comment->id}}">{{$comment->text}}</div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
