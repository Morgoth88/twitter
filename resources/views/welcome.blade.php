@extends('layouts.app')

@section('title','Welcome')

@section('links')
    <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="flex-center position-ref full-height">

    <div class="content">

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="title m-b-md">
            welcome
        </div>
        <div class="links">
            @if (Route::has('login'))
                @auth
                    <a href="{{route('index') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                        @endauth
                    @endif
        </div>
    </div>
</div>
@endsection
