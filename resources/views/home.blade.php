@extends('layouts.cover')

@section('content')
    <div class="home-header" style="background-image: url('{{ asset('images/Background.jpeg') }}')">
        <h2>Welcome to Socialite</h2>
        <p>We bring the whole world near to you</p>
        <div class="buttons text-center">
            <div>
                <button class="signup-button text-uppercase" data-href="{{ route('auth.signup') }}">Sign Up</button>
            </div>
            <div>
                <button class="signin-button text-uppercase" data-href="{{ route('auth.signin') }}">Sign In</button>
            </div>
        </div>
    </div>


@stop