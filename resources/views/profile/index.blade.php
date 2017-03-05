@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-md-6">
            @include ('user.partials.userblock', ['delete_friend' => 'false', 'accept_friend' => 'false', 'add_friend' => 'false', 'reject_friend' => 'false'])
            <hr>
            @if(Auth::user() == $user)
            <div class="row">
                <div class="col-md-12">
                    <form class="post-status-form" role="form" method="post" action="{{ route('status.post') }}" enctype="multipart/form-data">
                        <div class="form-group {{$errors->has('status') ? 'has-error':''}}">
                            {!! csrf_field() !!}
                            <textarea class="form-control" placeholder="What's up {{Auth::user()->getNicknameOrFullnameOrUsername() }} ?" name="status" rows='5'></textarea>
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
                </div>
            </div>
            @endif
            @if (!$statuses->count())
                <p>{{$user->getNicknameOrFullnameOrUsername() }} hasn't posted anything, yet.</p>
            @else
                @foreach ($statuses as $status)
                    <div class="media">
                        <a href="{{route('profile.index', ['username' => $status->user->username])}}" class="pull-left">
                            <img src="{{ asset('images/' . $status->user->profile_picture)}}" width="50" height="50" alt="{{$status->user->getNicknameOrFullnameOrUsername() }}" class="media-object">
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading"><a href="{{route('profile.index', ['username' => $status->user->username])}}">{{$status->user->getNicknameOrFullnameOrUsername() }}</a></h4>
                            <p class="profile-status" data-image="{{ asset('images/')}}" data-statusId="{{$status->id}}" data-token="{{Session::token()}}" data-url="{{route('status.edit', ['statusId' => $status->id])}}">{{ $status->body }}</p>
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
                            @if ($authUserIsFriend || Auth::user()->id === $status->user->id)
                                <form id="profile-reply-form" role="form" action="{{ route('status.reply',['statusId' => $status->id]) }}" method="post">
                                    {!! csrf_field() !!}
                                    <div class="form-group">
                                        <textarea id="profile-reply-body" class="form-control" name="reply-{{$status->id}}" rows="2" placeholder="Reply to this status."></textarea>

                                    </div>
                                    <input id="profile-reply-button" type="submit" class="btn btn-default btn-sm" value="Reply">
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
                {{$statuses->render()}}
            @endif
        </div>
        <div class="col-md-4 col-md-offset-2">
            <div class="row">
                @if(Auth::user()->profile_picture)
                    <div class="col-md-12">
                        <img class="profile-status-photo img-thumbnail img-rounded" src="{{ asset('images/' . $user->profile_picture)}}">
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label" for="first_name">First Name</label>
                    <p type="text" class="form-control" name="first_name" id="first_name" value="{{$user->first_name}}">{{$user->first_name}}</p>
                </div>
                <div class="col-md-6">
                    <label class="control-label" for="last_name">Last Name</label>
                    <p type="text" class="form-control" name="last_name" id="last_name" value="{{$user->last_name}}">{{$user->last_name}}</p>

                </div>
            </div>
            <div class="row">
                @if($user->nickname)
                <div class="col-md-6">
                    <label class="control-label" for="nickname">Nickname</label>
                    <p type="text" class="form-control" name="nickname" id="nickname" value="{{$user->nickname}}">{{$user->nickname}}</p>

                </div>
                @endif
                <div class="col-md-6">
                    <label class="control-label" for="gender">Gender</label>
                    <p type="text" class="form-control" name="gender" id="gender">@if($user->gender){{ 'Female' }}@else{{ 'Male' }}@endif</p>
                </div>
            </div>
            <div class="row">
                @if($user->hometown)
                <div class="col-md-6">
                    <label class="control-label" for="hometown">Hometown</label>
                    <p type="text" class="form-control" name="hometown" id="hometown" value="{{$user->hometown }}">{{$user->hometown }}</p>
                </div>
                @endif
                @if($user->marital_status)
                <div class="col-md-6">
                    <label class="control-label" for="gender">Marital Status</label>
                    <p type="text" class="form-control" name="gender" id="gender">{{ucfirst($user->marital_status)}}</p>
                </div>
                @endif
            </div>
            <div class="row">
                @if($user->phones()->where('is_phone_number_1', '1')->first()->phone_number)
                <div class="col-md-6">
                    <label class="control-label" for="phone_number_1">Phone number 1</label>
                    <p type="text" class="form-control" name="phone_number_1" id="phone_number_1" value="">{{$user->phones()->where('is_phone_number_1', '1')->first()->phone_number }}</p>

                </div>
                @endif
                @if($user->phones()->where('is_phone_number_1', '0')->first()->phone_number)
                <div class="col-md-6">
                    <label class="control-label" for="phone_number_2">Phone number 2</label>
                    <p type="text" class="form-control" name="phone_number_2" id="phone_number_2" value="">{{$user->phones()->where('is_phone_number_1', '0')->first()->phone_number }}</p>

                </div>
                @endif
            </div>
            @if(Auth::user()->isFriendsWith($user) || Auth::user() == $user)
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label" for="birth_date">Birth Date</label>
                    <p id="birthDate" type="text" class="form-control" name="birth_date" id="birth_date" value="{{$user->birth_date}}">{{$user->birth_date}}</p>

                </div>
            </div>
            @if($user->about)
            <div class="form-group">
                <label class="control-label" for="about">About</label>
                <p type="text" class="form-control" name="about" id="about" rows="5">{{ $user->about }}</p>
            </div>
            @endif
            @endif
            <hr>
            @if (Auth::user()->hasFriendRequestPending($user))
                <p>Waiting for <a href="{{ route('profile.index', ['username'=>$user->username]) }}"> {{ $user->getNicknameOrFullnameOrUsername() }}</a> to accept your request.</p>
            @elseif (Auth::user()->hasFriendRequestRecieved($user))
                <a href="{{ route('friends.accept',['username'=>$user->username]) }}" class="btn btn-primary">Accept Friend Request</a>
                <a href="{{ route('friends.reject',['username'=>$user->username]) }}" class="btn btn-danger">Reject Friend Request</a>
            @elseif (Auth::user()->isFriendsWith($user))
                <p>You and {{ $user->getNicknameOrFullnameOrUsername() }} are friends.</p>
                <form action="{{ route('friends.delete', ['username'=>$user->username]) }}" method="post">
                    {!! csrf_field() !!}
                    <input type="submit" value="Delete Friend" class="btn btn-primary">
                </form>
            @elseif (Auth::user()->id !== $user->id)

                <a href="{{ route('friends.add',['username'=>$user->username]) }}" class="btn btn-primary">Add Friend</a>
            @endif

            <h4>{{ $user->getNicknameOrFullnameOrUsername() }}'s friends.</h4>
            @if (!$user->friends()->count())
                <p>{{ $user->getNicknameOrFullnameOrUsername() }} has no friends.</p>
            @else
                @foreach($user->friends() as $user)
                    @include ('user.partials.userblock', ['delete_friend' => 'false', 'accept_friend' => 'false', 'add_friend' => 'false', 'reject_friend' => 'false'])
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