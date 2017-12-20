@extends('layouts.app')

@section('title','Home')

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Tweets:
                        <button id="tweetBtn" class="btn btn-primary" onclick="createForm()">
                            <i class="fa fa-commenting-o fa-2x" aria-hidden="true"></i>

                        </button>
                    </div>
                    <div class="panel-body">
                        @if (count($errors) > 0)

                            <div class="alert alert-danger">
                                <strong>Whoops! Something went wrong!</strong>
                                <br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div id="tweet-form"></div>

                        @if(isset($tweets))
                            @foreach($tweets as $tweet)
                                <div class="tweet">
                                    <div class="tweet-name">{{$tweet->user->name}}
                                        <span class="tweet-time">{{\App\TimeHelper::passedTime($tweet->created_at)}}</span>
                                        @if(\App\TimeHelper::updated($tweet))
                                        <span class="tweet-updtTime">updated</span>
                                        @endif
                                    </div>
                                    <div class="tweet-text" data-id="{{$tweet->id}}">{{$tweet->text}}</div>

                                    <div class="tweet-icons">
                                        <span class="comment-link">
                                            <i class="fa fa-comments" aria-hidden="true"></i>
                                        </span>

                                        @if(Auth::user()->id===$tweet->user->id && \App\TimeHelper::lessThanTwoMinutes($tweet))
                                            <span class="up-del-links">

                                                    <button id="msgUpdtBtn" onclick="updateForm({{$tweet->id}})"><i class="fa fa-pencil" aria-hidden="true"></i></button>

                                                <form method="POST" action="tweet/{{$tweet->id}}">
                                                    {{method_field('DELETE')}}
                                                    {{csrf_field()}}
                                                    <button id="msgDltBtn" type="submit"><i class='fa fa-times' aria-hidden='true'></i></button>
                                                </form>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
