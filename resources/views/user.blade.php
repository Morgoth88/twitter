@extends('layouts.app')

@section('title','user')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">

                <div class="panel-heading">
                    <center><h3>User info</h3></center>
                    <a href="{{route('index')}}">
                        <button class="btn btn-warning">
                            Back
                        </button>
                    </a>
                </div>
                <div class="panel-body">
                        <table class="table"></table>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/user.js') }}"></script>
@endsection