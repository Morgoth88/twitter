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
                    <button id="tweetBtn" class="btn btn-primary">
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
                                        <span class="tweet-time">{{\App\Message::passedTime($tweet->created_at)}}</span>
                                    </div>
                                    <div class="tweet-text">{{$tweet->text}}</div>
                                </div>
                            @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
