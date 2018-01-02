@extends('layouts.app')

@section('title','Home')

@section('content')
    <div class="container">
        <!--display flash sessions messages-->
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
    @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
    @endif
    <!--main container-->
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <!--panel header-->
                    <div class="panel-heading">
                        Tweets:
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
                        @if(isset($tweets))
                            @foreach($tweets as $tweet)
                            <!--tweet-->
                                <div class="tweet">
                                    <!--tweet name-->
                                    <div class="tweet-name">{{$tweet->user->name}}
                                    <!--tweet delete and update icons-->
                                        <!--if admin/ ban button-->
                                        @if(Auth::user()->role_id == 1)
                                            <button id="banUserBtn">
                                                <a href="{{route('userBan',['user' => $tweet->user->id])}}"><i class="fa fa-ban" aria-hidden="true"></i></a>
                                            </button>
                                        @endif
                                        <span class="up-del-links">
                                            <!--if admin/ ban button-->
                                            @if(Auth::user()->role_id == 1)
                                                <button id="banMessBtn">
                                                <i class="fa fa-ban" aria-hidden="true"></i>
                                            </button>
                                            @endif
                                            @if(Auth::user()->id===$tweet->user->id && \App\TimeHelper::lessThanTwoMinutes($tweet))
                                            <!--update button-->
                                                <button id="msgUpdtBtn" onclick="updateForm({{$tweet->id}})"><i
                                                            class="fa fa-pencil" aria-hidden="true"></i>
                                                </button>
                                                <!--delete button with form-->
                                                <form method="POST"
                                                      action="{{route('deleteTweet',['message'=> $tweet->id])}}">
                                                    {{method_field('DELETE')}}
                                                    {{csrf_field()}}
                                                    <button id="msgDltBtn" type="submit"><i class='fa fa-times'
                                                                                            aria-hidden='true'></i></button>
                                                </form>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="tweet-time-div">
                                        <!--tweet created at-->
                                        <span class="tweet-time">{{\App\TimeHelper::passedTime($tweet->created_at)}}</span>
                                    </div>
                                    <!--tweet message-->
                                    <div class="tweet-text" data-id="{{$tweet->id}}">{{$tweet->text}}</div>
                                    <!--comment icon-->
                                    <div class="tweet-icons">
                                        <span class="comment-link">
                                            <!--Comment create button-->
                                            <button id="cmntBtn" onclick="commentForm({{$tweet->id}})">
                                            <i class="fa fa-comments" aria-hidden="true"></i>
                                            </button>
                                        </span>
                                    @if(count($tweet->comment) > 0 && count($tweet->comment) < 2)
                                        {{count($tweet->comment)}}
                                        <!--comment link-->
                                            comment
                                        @elseif(count($tweet->comment) >= 2)
                                            {{count($tweet->comment)}}
                                            comments
                                        @endif
                                    </div>
                                    @if(count($tweet->comment) > 0)
                                        <div class="comments-container">
                                        @foreach($tweet->comment as $comment)
                                            @if($comment->old !=1)
                                                <!--if count of comments is greater than 3 display only 3-->
                                                    @if($loop->iteration > 3)
                                                        <a href="{{route('readComment',['message' => $tweet->id])}}">all
                                                            comments</a>
                                                        @break
                                                    @endif
                                                    <div class="comment">
                                                        <!--comment user name-->
                                                        <div class="comment-name">{{$comment->user->name}}
                                                        <!--if admin/ ban button-->
                                                            @if(Auth::user()->role_id == 1)
                                                                <button id="banUserBtn">
                                                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                                                </button>
                                                            @endif
                                                            <span class="up-del-links">
                                                         <!--if admin/ ban button-->
                                                                @if(Auth::user()->role_id == 1)
                                                                    <button id="banCommBtn">
                                                            <i class="fa fa-ban" aria-hidden="true"></i>
                                                        </button>
                                                                @endif
                                                                @if(Auth::user()->id===$comment->user->id && \App\TimeHelper::lessThanTwoMinutes($comment))
                                                                <!--comment update button-->
                                                                    <button id="msgUpdtBtn"
                                                                            onclick="commentUpdateForm({{$comment->id}})"><i
                                                                                class="fa fa-pencil"
                                                                                aria-hidden="true"></i>
                                                            </button>
                                                                    <!--comment delete button with form-->
                                                                    <form method="POST"
                                                                          action="{{route('deleteComment',['message' => $tweet->id, 'comment' => $comment->id])}}">
                                                                {{method_field('DELETE')}}
                                                                        {{csrf_field()}}
                                                                        <button id="msgDltBtn" type="submit"><i
                                                                                    class='fa fa-times'
                                                                                    aria-hidden='true'></i></button>
                                                            </form>
                                                                @endif
                                                    </span>
                                                            <!--comment passed time-->
                                                            <span class="comment-time">{{\App\TimeHelper::passedTime($comment->created_at)}}</span>
                                                        </div>
                                                        <!--comment text-->
                                                        <div class="comment-text"
                                                             data-tweet-id="{{$tweet->id}}"
                                                             data-comment-id="{{$comment->id}}">{{$comment->text}}</div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        <!--tweet pagination-->
                            {{$tweets->links()}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
