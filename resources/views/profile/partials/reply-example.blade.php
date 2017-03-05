<div class="media">
    <a href="{{ route('profile.index',['username'=> $reply->user->username]) }}" class="pull-left">
        <img src="{{ asset('images/' . $reply->user->profile_picture)}}" width="50" height="50" alt="{{ $reply->user->getNicknameOrFullnameOrUsername() }}" class="media-object">
    </a>
    <div class="media-body">
        <h4 class="media-heading"><a href="{{ route('profile.index',['username'=>$reply->user->username]) }}">
                {{$reply->user->getNicknameOrFullnameOrUsername() }}</a></h4>
        <p class="profile-status" data-statusId="{{$reply->id}}" data-token="{{Session::token()}}" data-url="{{route('status.edit', ['statusId' => $reply->id])}}">{{$reply->body}}</p>
        <ul class="list-inline">
            <li>{{ $reply->created_at->diffForHumans() }}</li>
            @if ($reply->user->id !== Auth::user()->id)
                <li><a class="like-status" data-url="{{route('status.like', ['statusId' => $reply->id])}}" href="#">
                        @if(Auth::user()->hasLikedStatus($reply))
                            You liked this reply
                        @else
                            Like
                        @endif
                    </a></li>
            @else
                <li><a class="edit-reply" href="#">Edit</a></li>
                <li><a href="{{route('status.delete', ['statusId' => $reply->id])}}">Delete</a></li>
            @endif
            <li><a class="like-count" data-url="{{route('status.likers', ['statusId' => $reply->id])}}" href="#">{{ $reply->likes->count() }} Likes</a></li>

        </ul>
    </div>
</div>