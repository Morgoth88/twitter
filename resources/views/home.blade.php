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
                                        @if(Auth::user()->id===$tweet->user->id && \App\TimeHelper::lessThanTwoMinutes($tweet))
                                            <span class="up-del-links">
                                                <!--update button-->
                                                <button id="msgUpdtBtn" onclick="updateForm({{$tweet->id}})"><i
                                                            class="fa fa-pencil" aria-hidden="true"></i>
                                                </button>
                                                <!--delete button with form-->
                                                <form method="POST" action="tweet/{{$tweet->id}}">
                                                    {{method_field('DELETE')}}
                                                    {{csrf_field()}}
                                                    <button id="msgDltBtn" type="submit"><i class='fa fa-times' aria-hidden='true'></i></button>
                                                </form>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="tweet-time-div">
                                        <!--tweet created at-->
                                        <span class="tweet-time">{{\App\TimeHelper::passedTime($tweet->created_at)}}</span>
                                        <!--if tweet was updated-->
                                        @if(\App\TimeHelper::updated($tweet))
                                            <span class="tweet-updtTime">updated</span>
                                        @endif
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
                                        @if(count($tweet->comment) > 0)
                                            {{count($tweet->comment)}}
                                            <!--comment link-->
                                            <a id="cmntShowBtn" href="tweet/{{$tweet->id}}/comment">comments</a>
                                        @endif
                                    </div>
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
