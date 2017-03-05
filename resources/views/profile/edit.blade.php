@extends('layouts.default')

@section('content')
    <h3> Update your Profile </h3>
    <div class="row">
        <div class="col-md-6">
            <form class="form-vertical edit-profile-form" role="form" method="post" action="{{ route('profile.edit') }}" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-md-6 {{ $errors->has('first_name') ? 'has-error':'' }}">
                        <label class="control-label" for="first_name">First Name</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" value="{{old('first_name') ?: Auth::user()->first_name}}">
                        @if ($errors->has('first_name'))
                            <span class="help-block"> {{ $errors->first('first_name') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 {{ $errors->has('last_name') ? 'has-error':'' }}">
                        <label class="control-label" for="last_name">Last Name</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" value="{{old('last_name') ?: Auth::user()->last_name}}">
                        @if ($errors->has('last_name'))
                            <span class="help-block"> {{ $errors->first('last_name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 {{ $errors->has('nickname') ? 'has-error':'' }}">
                        <label class="control-label" for="nickname">Nickname</label>
                        <input type="text" class="form-control" name="nickname" id="nickname" value="{{old('nickname') ?: Auth::user()->nickname}}">
                        @if ($errors->has('nickname'))
                            <span class="help-block"> {{ $errors->first('nickname') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <ul class="tg-list list-unstyled">
                            <li class="tg-list-item">
                                <h5>Male or Female</h5>
                                <input class="tgl tgl-light" id="cb1" type="checkbox" name="gender" {{old('gender') == 'on'? 'checked':Auth::user()->gender? 'checked':''}}/>
                                <label class="tgl-btn" for="cb1"></label>
                            </li>
                        </ul>
                    </div>
                    {{--<div class="col-md-6 {{ $errors->has('gender') ? 'has-error':'' }}">--}}
                        {{--<label class="control-label" for="gender">Gender</label>--}}
                        {{--<input type="text" class="form-control" name="gender" id="gender" value="{{old('gender') ?: Auth::user()->gender}}">--}}
                        {{--@if ($errors->has('gender'))--}}
                            {{--<span class="help-block"> {{ $errors->first('gender') }}</span>--}}
                        {{--@endif--}}
                    {{--</div>--}}
                </div>
                <div class="row">
                    <div class="col-md-6 {{ $errors->has('hometown') ? 'has-error':'' }}">
                        <label class="control-label" for="hometown">Hometown</label>
                        <input type="text" class="form-control" name="hometown" id="hometown" value="{{old('hometown') ?: Auth::user()->hometown }}">
                        @if ($errors->has('hometown'))
                            <span class="help-block"> {{ $errors->first('hometown') }}</span>
                        @endif
                    </div>
                    <div class="controls col-md-6">
                        <h5>Marital Status</h5>
                        <ul class="list-unstyled">

                            <li>
                                <input id='radio-1' type="radio" name='marital_status' {{old('marital_status') == 'single'? 'checked':Auth::user()->marital_status == 'single'? 'checked':''}}  value="single" />
                                <label for="radio-1">Single</label>
                            </li>
                            <li>
                                <input id='radio-2' type="radio" name='marital_status' {{old('marital_status') == 'engaged'? 'checked':Auth::user()->marital_status == 'engaged'? 'checked':''}} value="engaged"/>
                                <label for="radio-2">Engaged</label>
                            </li>
                            <li>
                                <input id='radio-3' type="radio" name='marital_status' {{old('marital_status') == 'married'? 'checked':Auth::user()->marital_status == 'married'? 'checked':''}} value="married"/>
                                <label for="radio-3">Married</label>
                            </li>
                        </ul>
                    </div>
                    {{--<div class="col-md-6 {{ $errors->has('marital_status') ? 'has-error':'' }}">--}}
                        {{--<label class="control-label" for="gender">Marital status</label>--}}
                        {{--<input type="text" class="form-control" name="marital_status" id="marital_status" value="{{old('marital_status') ?: Auth::user()->marital_status}}">--}}
                        {{--@if ($errors->has('marital_status'))--}}
                            {{--<span class="help-block"> {{ $errors->first('marital_status') }}</span>--}}
                        {{--@endif--}}
                    {{--</div>--}}
                </div>
                <div class="row">
                    <div class="col-md-6 {{ $errors->has('phone_number_1') ? 'has-error':'' }}">
                        <label class="control-label" for="phone_number_1">Phone number 1</label>
                        <input type="text" class="form-control" name="phone_number_1" id="phone_number_1" value="{{old('phone_number_1') ?: Auth::user()->phones()->where('is_phone_number_1', '1')->first()->phone_number }}">
                        @if ($errors->has('phone_number_1'))
                            <span class="help-block"> {{ $errors->first('phone_number_1') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 {{ $errors->has('phone_number_2') ? 'has-error':'' }}">
                        <label class="control-label" for="phone_number_2">Phone number 2</label>
                        <input type="text" class="form-control" name="phone_number_2" id="phone_number_2" value="{{old('phone_number_2') ?: Auth::user()->phones()->where('is_phone_number_1', '0')->first()->phone_number}}">
                        @if ($errors->has('phone_number_2'))
                            <span class="help-block"> {{ $errors->first('phone_number_2') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{$errors->has('profile_picture') ? 'has-error':''}}">
                            <label class="control-label" for="profile_picture">Upload a Profile Picture</label>
                            <input type="file" class="form-control" name="profile_picture" value="{{old('profile_picture') ?: Auth::user()->profile_picture}}">
                            @if ($errors->has('profile_picture'))
                                <span class="help-block">
                                    {{ $errors->first('profile_picture') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 {{ $errors->has('birth_date') ? 'has-error':'' }}">
                        <label class="control-label" for="birth_date">Birth Date</label>
                        <input id="birthDate" type="text" class="form-control" name="birth_date" id="birth_date" value="{{old('birth_date') ?: Auth::user()->birth_date}}">
                        @if ($errors->has('birth_date'))
                            <span class="help-block"> {{ $errors->first('birth_date') }}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group {{ $errors->has('about') ? 'has-error':'' }}">
                    <label class="control-label" for="about">About</label>
                    <textarea type="text" class="form-control" name="about" id="about" rows="5">{{old('about') ?: Auth::user()->about }}</textarea>
                    @if ($errors->has('about'))
                        <span class="help-block"> {{ $errors->first('about') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-default"> Update </button>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            @if(Auth::user()->profile_picture)
                <div class="col-md-12">
                    <img class="profile-status-photo img-thumbnail img-rounded" src="{{ asset('images/' . Auth::user()->profile_picture)}}">
                </div>
            @endif
            @if(Auth::user()->profile_picture != 'female_avatar.jpg' && Auth::user()->profile_picture != 'male_avatar.jpg')
            <div class="col-md-6">
                <form action="{{ route('profile.delete-photo') }}" method="post">
                    {!! csrf_field() !!}
                    <input type="submit" value="Delete Photo" class="btn btn-primary">
                </form>
            </div>
            @endif
        </div>
    </div>
    <script>
        var username = '{{ Auth::user()->username }}';
        {{--var usernameURL = '{{ route('profile.index') }}';--}}
    </script>
@stop