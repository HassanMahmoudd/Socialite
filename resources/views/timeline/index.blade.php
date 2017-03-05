@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <form class="post-status-form" role="form" method="post" action="{{ route('status.post') }}" enctype="multipart/form-data">
                <div class="form-group {{$errors->has('status') ? 'has-error':''}}">
                    {!! csrf_field() !!}
                    <textarea id="content" class="form-control" placeholder="What's up {{Auth::user()->getNicknameOrFullnameOrUsername() }} ?" name="status" rows='5'></textarea>
                    @if ($errors->has('status'))
                        <span class="help-block">
                            {{ $errors->first('status') }}
                        </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group {{$errors->has('image') ? 'has-error':''}}">
                            <label class="control-label" for="image">Upload an image</label>
                            <input type="file" class="form-control" name="image">
                            @if ($errors->has('image'))
                                <span class="help-block">
                                            {{ $errors->first('image') }}
                                        </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 col-md-offset-1">
                        <ul class="tg-list list-unstyled">
                            <li class="tg-list-item">
                                <h5>Public</h5>
                                <input class="tgl tgl-light" id="cb1" type="checkbox" name="isPublic"/>
                                <label class="tgl-btn" for="cb1"></label>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <button class="btn btn-default" type="submit"> Update Status</button>
                    </div>
                </div>
            </form>
            <hr>
            <!-- Timeline status and replies -->
            @if (!$statuses->count())
                <p>There is nothing on your Timeline, yet.</p>
            @else
                @foreach ($statuses as $status)
                    <div class="media">
                        <a href="{{route('profile.index', ['username' => $status->user->username])}}" class="pull-left">
                            <img src="{{ asset('images/' . $status->user->profile_picture)}}" width="50" height="50" alt="{{$status->user->getNicknameOrFullnameOrUsername() }}" class="media-object">
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading"><a href="{{route('profile.index', ['username' => $status->user->username])}}">{{$status->user->getNicknameOrFullnameOrUsername() }}</a></h4>
                            <p id="emoji-p" class="profile-status" data-image="{{ asset('images/')}}" data-statusId="{{$status->id}}" data-token="{{Session::token()}}" data-url="{{route('status.edit', ['statusId' => $status->id])}}">{{ $status->body }}</p>
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
                                                Unlike
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

                            @foreach ($status->replies as $reply)
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
                                                            Unlike
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
                            @endforeach

                            <form id="profile-reply-form" role="form" action="{{ route('status.reply',['statusId' => $status->id]) }}" method="post">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    <textarea id="profile-reply-body" class="form-control" name="reply-{{$status->id}}" rows="2" placeholder="Reply to this status."></textarea>

                                </div>
                                <input id="profile-reply-button" type="submit" class="btn btn-default btn-sm" value="Reply">
                            </form>
                        </div>
                    </div>
                @endforeach
                {{$statuses->render()}}
            @endif
        </div>
        <div class="col-md-4 col-md-offset-2">
            <h3>People you may know!</h3>
            <!-- List of friends -->
            @if (!$people->count())
                <p>You have no friends.</p>
            @else
                @foreach($people as $user)
                    @include ('user.partials.userblock', ['delete_friend' => 'false', 'accept_friend' => 'false', 'add_friend' => 'true', 'reject_friend' => 'false'])
                @endforeach
            @endif
        </div>
    </div>
    @include('profile.partials.edit-post-modal')
    @include('profile.partials.edit-reply-modal')
    <script>
        var username = '{{ Auth::user()->username }}';
        {{--var usernameURL = '{{ route('profile.index') }}';--}}
    </script>
@stop