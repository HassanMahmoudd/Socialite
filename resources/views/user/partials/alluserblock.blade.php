@foreach($users as $user)
    <div class="media">
        <a href="{{ route('profile.index', ['username'=>$user->username]) }}" class="pull-left">
            <img src="{{ asset('images/' . $user->profile_picture)}}" width="50" height="50" class="media-object" alt="{{ $user->getNicknameOrFullnameOrUsername() }}">
        </a>
        <div class="media-body">
            <h4 class="media-heading"><a href="{{ route('profile.index', ['username'=>$user->username]) }}">{{ $user->getNicknameOrFullnameOrUsername() }}</a></h4>
            @if ($user->hometown)
                <p>{{ $user->hometown }}</p>
            @endif
        </div>
    </div>
@endforeach