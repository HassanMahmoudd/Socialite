@extends ('layouts.default')

@section ('content')
    <div class="row">
        <div class="col-md-6">
            <h3>Your Friends</h3>
            <!-- List of friends -->
            @if (!$friends->count())
                <p>You have no friends.</p>
            @else
                @foreach($friends as $user)
                    @include ('user.partials.userblock', ['delete_friend' => 'true', 'accept_friend' => 'false', 'add_friend' => 'false', 'reject_friend' => 'false'])
                @endforeach
            @endif
        </div>
        <div class="col-md-6">
            <h3>Friend Requests</h3>
            @if (!$requests->count())
                <p>You have no friends.</p>
            @else
                @foreach($requests as $user)
                    @include('user.partials.userblock', ['delete_friend' => 'false', 'accept_friend' => 'true', 'add_friend' => 'false', 'reject_friend' => 'true'])
                @endforeach
            @endif
        </div>
    </div>
    <script>
        var username = '{{ Auth::user()->username }}';
        {{--var usernameURL = '{{ route('profile.index') }}';--}}
    </script>
@stop