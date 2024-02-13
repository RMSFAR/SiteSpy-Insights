@extends('design.auth_app')
@section('title')
    {{__('Register')}}
@endsection
@section('content')
    <div class="card card-primary">
        <div class="card-header"><h4><i class="fa fa-user-circle mx-1"></i>{{__('Sign Up')}}</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf
                @if (session('registration_error_message')=='1')
                    <div class="alert alert-danger p-0">
                        <ul>
                            <li>{{ __('Invalid credentials') }}</li>
                        </ul>
                    </div>
                @endif
                @if (session('reg_success')=='1')
                    <div class="alert alert-success text-center">
                        {{ __('An activation code has been sent to your email. please check your inbox to activate your account.') }}   
                    </div>
                @endif
                @if (session('reg_success')=='limit_exceed')
                    <div class="alert alert-danger text-center">
                        {{ __('Signup has been disabled. Please contact system admin.') }}   
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="first_name">{{__('Full Name')}}:</label><span
                                    class="text-danger">*</span>
                            <input id="firstName" type="text"
                                   class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                   name="name"
                                   tabindex="1" placeholder="Enter Full Name" value="{{ old('name') }}"
                                   autofocus required>
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">{{__('Email')}}:</label><span
                                    class="text-danger">*</span>
                            <input id="email" type="email"
                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                   placeholder="Enter Email address" name="email" tabindex="1"
                                   value="{{ old('email') }}"
                                   required autofocus>
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password" class="control-label">{{__('Password')}}
                                :</label><span
                                    class="text-danger">*</span>
                            <input id="password" type="password"
                                   class="form-control{{ $errors->has('password') ? ' is-invalid': '' }}"
                                   placeholder="Set account password" name="password" tabindex="2" required>
                            <div class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation"
                                   class="control-label">{{__('Confirm Password')}}:</label><span
                                    class="text-danger">*</span>
                            <input id="password_confirmation" type="password" placeholder="Confirm account password"
                                   class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid': '' }}"
                                   name="password_confirmation" tabindex="2">
                            <div class="invalid-feedback">
                                {{ $errors->first('password_confirmation') }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12" style="margin-bottom:0">
                          <label><?php echo __("Captcha");?> *</label>
                        </div>
                      </div>                  
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon3"><?php echo $num1. "+". $num2." = ?";?></span>
                        </div>
                        <input type="number" class="form-control" required name="captcha" placeholder="<?php echo __("Put your answer here"); ?>" >
                      </div>      
          
                      <div class="form-group">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" name="agree" required class="custom-control-input" id="agree">
                          <label class="custom-control-label" for="agree"><a target="_BLANK" href=" {{route('policy-terms')}}"><?php echo __("I agree with the terms and conditions");?></a></label>
                        </div>
                      </div>
                    <div class="col-md-12 mt-4">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                <i class="fa fa-user-circle mx-1"></i>{{__('Register')}}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="mt-5 text-muted text-center">
        {{__('Already have an account?')}} <a
                href="{{ route('login') }}">{{__('Sign In')}}</a>
    </div>
@endsection
