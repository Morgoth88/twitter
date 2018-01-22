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

                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        var authUserId = "{{\Illuminate\Support\Facades\Auth::user()->id}}";
        var authUserRole = "{{\Illuminate\Support\Facades\Auth::user()->role_id}}";
    </script>
    <script src="{{ asset('js/ajax.js') }}"></script>
    <script src="{{asset('js/WSscripts/WScomment.js')}}"></script>
    <script src="{{asset('js/WSscripts/WSmessage.js')}}"></script>
    <script src="{{asset('js/WSscripts/WSUserBan.js')}}"></script>

@endsection
