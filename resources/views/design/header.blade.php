@php
    $logo= isset(Auth::user()->brand_logo) ? Auth::user()->brand_logo : ""; 
	if($logo=="") $logo=file_exists(asset("assets/img/avatar/avatar-3.png")) ? asset("assets/img/avatar/avatar-3.png") : asset("assets/img/avatar/avatar-3.png");
	else $logo=$logo;
    $visitor_list = DB::table('visitor_analysis_domain_list')->where("user_id", Auth::user()->id)->where("dashboard", '1')->select("id","domain_name")->get();
    $visitor_domain_name =session("session_visitor_doamin_name");
    $current_visitor_doamin = isset($visitor_domain_name) ? $visitor_domain_name : __("No Website");
@endphp

<?php

?>

<form class="form-inline mr-auto" action="#">
    <ul class="navbar-nav mr-3">
        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
    </ul>
    @if(request()->routeIs('dashboard'))
        <ul class="navbar-nav navbar-right d-none d-md-block ml-2 mr-1 facebook">
            <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <div class="d-inline"><?php echo $current_visitor_doamin; ?></div></a>
            <div class="dropdown-menu dropdown-menu-right acount-switch-lists">
                <div class="dropdown-title"><?php echo __("Select Website"); ?></div>               
                @foreach ($visitor_list as $value)
                    <a class="dropdown-item visitor_doamin_list_item" data-id="{{$value->id}}" href="#">{{$value->domain_name}}</a>
                @endforeach
            </div>
            </li>
        </ul>
    @endif 
</form>

<ul class="navbar-nav navbar-right">
    @include('design.notification')
    @if(\Illuminate\Support\Facades\Auth::user())
        <li class="dropdown">
            
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user" aria-expanded="false">
                <img src="{{$logo}}" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">{{Auth::user()->name}}</div></a>

            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-title">{{config('my_config.product_name')}} - {{Auth::user()->name}}</div>
                <a class="dropdown-item has-icon edit-profile" href="{{route('edit_profile')}}" data-id="{{ \Auth::id() }}">
                    <i class="fa fa-user"></i>{{__('Edit Profile')}}</a>
                {{-- <a class="dropdown-item has-icon" data-toggle="modal" data-target="#changePasswordModal" href="#" data-id="{{ \Auth::id() }}"><i
                            class="fa fa-key"> </i>{{__('Change Password')}}</a> --}}

                <a href="{{ url('logout') }}" class="dropdown-item has-icon text-danger"
                   onclick="event.preventDefault(); localStorage.clear();  document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> {{__('Logout')}}
                </a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" class="d-none">
                    {{ csrf_field() }}
                </form>
            </div>
        </li>
    @else
        <li class="dropdown"><a href="#" data-toggle="dropdown"
                                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                
                <div class="d-sm-none d-lg-inline-block">{{ __('Hello') }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-title">{{ __('Login') }}
                    / {{ __('Register') }}</div>
                <a href="{{ route('login') }}" class="dropdown-item has-icon">
                    <i class="fas fa-sign-in-alt"></i> {{ __('Login') }}
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('register') }}" class="dropdown-item has-icon">
                    <i class="fas fa-user-plus"></i> {{ __('Register') }}
                </a>
            </div>
        </li>
    @endif
</ul>


