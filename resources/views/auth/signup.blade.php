@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <form class="form-vertical signup-form" role="form" method="post" action="{{ route('auth.signup') }}" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class="form-group {{ $errors->has('email') ? 'has-error':'' }}">
                    <label class="control-label" for="email">Your Email *</label>
                    <input type="text" name="email" class="form-control" id="email" placeholder="Email" value="{{ old('email')?:'' }}">
                    @if ($errors->has('email'))
                        <span class="help-block"> {{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('username') ? 'has-error':'' }}">
                    <label class="control-label" for="username">Your Username *</label>
                    <input type="text" name="username" class="form-control" id="username" placeholder="username" value="{{ old('username')?:'' }}">
                    @if ($errors->has('username'))
                        <span class="help-block"> {{ $errors->first('username') }}</span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('password') ? 'has-error':'' }}">
                    <label class="control-label" for="password">Your Password *</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                    @if ($errors->has('password'))
                        <span class="help-block"> {{ $errors->first('password') }}</span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-6 {{ $errors->has('first_name') ? 'has-error':'' }}">
                        <label class="control-label" for="first_name">First Name *</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" value="{{old('first_name')?:''}}">
                        @if ($errors->has('first_name'))
                            <span class="help-block"> {{ $errors->first('first_name') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 {{ $errors->has('last_name') ? 'has-error':'' }}">
                        <label class="control-label" for="last_name">Last Name *</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" value="{{old('last_name')?:''}}">
                        @if ($errors->has('last_name'))
                            <span class="help-block"> {{ $errors->first('last_name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 {{ $errors->has('nickname') ? 'has-error':'' }}">
                        <label class="control-label" for="nickname">Nickname</label>
                        <input type="text" class="form-control" name="nickname" id="nickname" value="{{old('nickname')?:''}}">
                        @if ($errors->has('nickname'))
                            <span class="help-block"> {{ $errors->first('nickname') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <ul class="tg-list list-unstyled">
                            <li class="tg-list-item">
                                <h5>Male or Female *</h5>
                                <input class="tgl tgl-light" id="cb1" type="checkbox" name="gender" {{old('gender') == 'on'? 'checked':''}}/>
                                <label class="tgl-btn" for="cb1"></label>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6 {{ $errors->has('hometown') ? 'has-error':'' }}">
                        <label class="control-label" for="hometown">Hometown</label>
                        <input type="text" class="form-control" name="hometown" id="hometown" value="{{old('hometown')?:''}}">
                        @if ($errors->has('hometown'))
                            <span class="help-block"> {{ $errors->first('hometown') }}</span>
                        @endif
                    </div>
                    <div class="controls col-md-6">
                        <h5>Marital Status</h5>
                        <ul class="list-unstyled">

                            <li>
                                <input id='radio-1' type="radio" name='marital_status' value="single" {{old('marital_status') == 'single'? 'checked':''}} />
                                <label for="radio-1">Single</label>
                            </li>
                            <li>
                                <input id='radio-2' type="radio" name='marital_status' value="engaged" {{old('marital_status') == 'engaged'? 'checked':''}}/>
                                <label for="radio-2">Engaged</label>
                            </li>
                            <li>
                                <input id='radio-3' type="radio" name='marital_status' value="married" {{old('marital_status') == 'married'? 'checked':''}}/>
                                <label for="radio-3">Married</label>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6 {{ $errors->has('phone_number_1') ? 'has-error':'' }}">
                        <label class="control-label" for="phone_number_1">Phone number 1</label>
                        <input type="text" class="form-control" name="phone_number_1" id="phone_number_1" value="{{old('phone_number_1')?:''}}">
                        @if ($errors->has('phone_number_1'))
                            <span class="help-block"> {{ $errors->first('phone_number_1') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 {{ $errors->has('phone_number_2') ? 'has-error':'' }}">
                        <label class="control-label" for="phone_number_2">Phone number 2</label>
                        <input type="text" class="form-control" name="phone_number_2" id="phone_number_2" value="{{old('phone_number_2')?:''}}">
                        @if ($errors->has('phone_number_2'))
                            <span class="help-block"> {{ $errors->first('phone_number_2') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{$errors->has('profile_picture') ? 'has-error':''}}">
                            <label class="control-label" for="profile_picture">Upload a Profile Picture</label>
                            <input type="file" class="form-control" name="profile_picture" value="{{old('profile_picture')?:''}}">
                            @if ($errors->has('profile_picture'))
                                <span class="help-block">
                                    {{ $errors->first('profile_picture') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 {{ $errors->has('birth_date') ? 'has-error':'' }}">
                        <label class="control-label" for="birth_date">Birth Date *</label>
                        <input id="birthDate" type="text" class="form-control" name="birth_date" id="birth_date" value="{{old('birth_date')?:''}}">
                        @if ($errors->has('birth_date'))
                            <span class="help-block"> {{ $errors->first('birth_date') }}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group {{ $errors->has('about') ? 'has-error':'' }}">
                    <label class="control-label" for="about">About</label>
                    <textarea type="text" class="form-control" name="about" id="about" rows="5">{{old('about') ?:''}}</textarea>
                    @if ($errors->has('about'))
                        <span class="help-block"> {{ $errors->first('about') }}</span>
                    @endif
                </div>
                <input type="hidden" name="_token" value="{{ Session::token() }}">
                <div class="form-group">
                    <button class="btn btn-default" type="submit">Sign up</button>
                </div>
            </form>
        </div>
    </div>
@stop