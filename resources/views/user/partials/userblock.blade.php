<div class="media">
    <div class="row">
        <div class="col-md-8 media">
            <a href="{{ route('profile.index', ['username'=>$user->username]) }}" class="pull-left">
                <img src="{{ asset('images/' . $user->profile_picture)}}" width="50" height="50" class="media-object" alt="{{ $user->getNicknameOrFullnameOrUsername() }}">
            </a>
            <div class="media-body">
                <h4 class="media-heading"><a href="{{ route('profile.index', ['username'=>$user->username]) }}">{{ $user->getNicknameOrFullnameOrUsername() }}</a></h4>
                @if ($user->hometown)
                    <p>{{ $user->hometown}}</p>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            @if($delete_friend == 'true')
                <form action="{{ route('friends.delete', ['username'=>$user->username]) }}" method="post">
                    {!! csrf_field() !!}
                    <input type="submit" value="Delete Friend" class="btn btn-primary">
                </form>
            @endif
            @if($accept_friend == 'true')
                <a href="{{ route('friends.accept',['username'=>$user->username]) }}" class="btn btn-primary">Accept</a>
            @endif
            @if($add_friend == 'true')
                <a href="{{ route('friends.add',['username'=>$user->username]) }}" class="btn btn-primary">Add Friend</a>
            @endif
            @if($reject_friend == 'true')
                <a href="{{ route('friends.reject',['username'=>$user->username]) }}" class="btn btn-danger">Reject</a>
            @endif
        </div>
    </div>


</div>
