@extends ('layouts.default')

@section ('content')
    <h3>Your search for "{{ Request::input('query') }}"</h3>
    @if (!$users->count() && !$statuses->count())
        No Result Found.
    @else
        <div class="row">
            <div class="col-md-12">
                @foreach ($users as $user)
                    @include ('user.partials.userblock', ['delete_friend' => 'false', 'accept_friend' => 'false', 'add_friend' => 'false', 'reject_friend' => 'false'])
                @endforeach

                @foreach ($statuses as $status)
                        <div class="media">
                            <a href="{{route('profile.index', ['username' => $status->user->username])}}" class="pull-left">
                                <img src="{{ asset('images/' . $status->user->profile_picture)}}" width="50" height="50" alt="{{$status->user->getNicknameOrFullnameOrUsername() }}" class="media-object">
                            </a>
                            <div class="media-body">
                                <h4 class="media-heading"><a href="{{route('profile.index', ['username' => $status->user->username])}}">{{$status->user->getNicknameOrFullnameOrUsername() }}</a></h4>
                                <p class="profile-status" data-statusId="{{$status->id}}" data-token="{{Session::token()}}" data-url="{{route('status.edit', ['statusId' => $status->id])}}">{{ $status->body }}</p>
                                @if($status->image)
                                    <div>
                                        <img class="profile-status-photo" src="{{ asset('images/' . $status->image)}}" data-image="{{ asset('images/')}}">
                                    </div>
                                @endif
                                <ul class="list-inline profile-status-photo">
                                    <li>{{ $status->created_at->diffForHumans() }}</li>
                                    @if ($status->user->id !== Auth::user()->id)
                                        <li><a class="like-status" data-url="{{route('status.like', ['statusId' => $status->id])}}" href="#">
                                                @if(Auth::user()->hasLikedStatus($status))
                                                    You liked this status
                                                @else
                                                    Like
                                                @endif
                                            </a></li>
                                    @else
                                        <li><a class="edit-status" href="#" data-ispublic="{{ $status->is_public }}">Edit</a></li>
                                        <li><a href="{{route('status.delete', ['statusId' => $status->id])}}">Delete</a></li>
                                    @endif
                                    <li><a class="like-count" data-url="{{route('status.likers', ['statusId' => $status->id])}}" href="#">{{ $status->likes->count() }} Likes</a></li>

                                </ul>
                            </div>
                        </div>
                    @endforeach
            </div>
        </div>
    @endif
    @include('profile.partials.edit-post-modal')
    <script>
        var username = '{{ Auth::user()->username }}';
        {{--var usernameURL = '{{ route('profile.index') }}';--}}
    </script>
@stop