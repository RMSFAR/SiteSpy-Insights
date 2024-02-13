@extends('design.auth_app')
@section('title')
    {{__("Login")}}
@endsection
@section('content')
    <div class="card card-primary">
        <div class="card-header"><h4><i class="fas fa-sign-in-alt mx-1"></i>{{__("Login")}}</h4></div>

        <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                @if ($errors->any())
                    <div class="alert alert-danger p-0">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('login_error_message')=='1')
                    <div class="alert alert-danger p-0">
                        <ul>
                            <li>{{ __('Invalid password or email') }}</li>
                        </ul>
                    </div>
                @endif
                <div class="form-group">
                    <label for="email">{{__('Email')}}</label>
                    <input aria-describedby="emailHelpBlock" id="email" type="email"
                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                           placeholder="Enter Email" tabindex="1"
                           value="<?php echo (config('app.is_demo')== '1') ? 'admin@gmail.com' : old('email') 
                           ?>" autofocus
                           required>
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                </div>

                <div class="form-group">
                    <div class="d-block">
                        <label for="password" class="control-label">{{__('Password')}}</label>
                        <div class="float-right">
                            <a href="{{ route('password.request') }}" class="text-small">
                                {{__('Forgot Password')}}?
                            </a>
                        </div>
                    </div>
                    <input aria-describedby="passwordHelpBlock" id="password" type="password"
                            value="<?php echo (config('app.is_demo')== '1') ? '12345678' : old('password') 
                            ?>" 
                           placeholder="Enter Password"
                           class="form-control{{ $errors->has('password') ? ' is-invalid': '' }}" name="password"
                           tabindex="2" required>
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                        <i class="fas fa-sign-in-alt mx-1"></i>{{__('Login')}}
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-5 text-muted text-center">
            {{__('Do not have an account?')}} <a
                    href="{{ route('register') }}">{{__('Create One')}}</a>
        </div>
    </div>
@endsection
