<!DOCTYPE html>
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>{{config('my_config.product_name')}} | @yield('title')</title>
	<meta name="description" content="">
	<meta name="author" content="<?php echo config('my_config.institute_address1');?>">

	<!-- Mobile Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Favicon -->
	<link rel="shortcut icon" href="{{ config('my_config.favicon') }}">

    <!--====== STYLESHEETS ======-->
    <link rel="stylesheet" href="{{asset('assets/site_new/css/normalize.css')}}">
    <link rel="stylesheet" href="{{asset('assets/site_new/css/animate.css')}}">
    <link rel="stylesheet" href="{{asset('assets/site_new/css/modal-video.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/site_new/css/stellarnav.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/site_new/css/owl.carousel.css')}}">
    <link rel="stylesheet" href="{{asset('assets/site_new/css/slick.css')}}">
    <link href="{{asset('assets/site_new/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/site_new/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/site_new/css/material-icons.css')}}" rel="stylesheet">

    <!--====== MAIN STYLESHEETS ======-->
    {{-- <!-- <link href="{{asset('assets/site_new/style.css')}}" rel="stylesheet"> --> --}}
    @include("shared.style")
    <link href="{{asset('assets/site_new/css/responsive.css')}}" rel="stylesheet">

    <script src="{{asset('assets/site_new/js/vendor/modernizr-2.8.3.min.js')}}"></script>

</head>

<body class="home-two" data-spy="scroll" data-target=".mainmenu-area" data-offset="90">

    {{-- <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]--> --}}

    <!--- PRELOADER -->
    <div class="preeloader">
        <div class="preloader-spinner"></div>
    </div>

    <!--SCROLL TO TOP-->
    <a href="#home" class="scrolltotop"><i class="fa fa-long-arrow-up"></i></a>

    <!--START TOP AREA-->
    <header>
        <div class="header-top-area">
            <!--MAINMENU AREA-->
            <div class="mainmenu-area" id="mainmenu-area">
                <!-- <div class="mainmenu-area-bg"></div> -->
                <nav class="navbar">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <a href="#home" class="navbar-brand"><img style="max-height:45px !important" src="{{ asset('assets/img/logo.png') }}" alt="<?php echo config('my_config.product_name');?>"></a>
                        </div>
                        <div id="main-nav" class="stellarnav">
                            <div class="search-and-signup-button white pull-right hidden-sm hidden-xs">
                                <a href="{{route('login')}}" class="sign-up"><?php echo __('Login'); ?></a>
                            </div>
                            <ul id="nav" class="nav">
                                <li class="active">
                                    <a href="<?php echo url('#home');?>"><?php echo __('home'); ?></a>
                                </li>
                                <li>
                                    <a href="<?php echo url('#features');?>"><?php echo __('Features');?></a>
                                </li>
                                <li>
                                    <a href="<?php echo url('#download');?>"><?php echo __('Pricing'); ?></a>
                                </li>
                                <li <?php if(config('my_config.display_video_block') == '0') echo "class='hidden'"; ?>>
                                    <a href="<?php echo url('#tutorial');?>"><?php echo __('Tutorial');?></a>
                                </li>
                                <li>
                                    <a href="<?php echo url('#contact');?>"><?php echo __('Contact'); ?></a>
                                </li>
                                <li class="hidden-md hidden-lg">
                                    <a href="{{route('login')}}"><?php echo __('Login'); ?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
            <!--END MAINMENU AREA END-->
        </div>
        
    </header>
    <!--END TOP AREA-->




    <!--ABOUT AREA-->
    <section class="about-area section-padding" id="app">
        <div class="container">
            <div class="row flex-v-center">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                    <div class="about-content sm-mb50 sm-center text-justify" style="padding-top: 80px;">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--ABOUT AREA END-->

  

  
    <!--FOOER AREA-->
    <footer class="footer-area white relative">
        <div class="area-bg"></div>
        <div class="footer-bottom-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <div class="footer-copyright text-center wow fadeIn">
                            <p>
                            	<?php echo config("my_config.product_short_name"); ?> &copy; <a target="_blank" href="<?php echo url('/'); ?>"><?php echo config("my_config.institute_address1"); ?></a></p>
                        	<p class="text-center" style="font-size: 10px;">
								<a href="{{route('policy-privacy')}}" target="_blank"><?php echo __("Privacy Policy"); ?></a> | <a href="{{route('policy-terms')}}" target="_blank"><?php echo __("Terms of Service"); ?></a> | <a href="{{route('policy-gdpr')}}" target="_blank"><?php echo __("GDPR Compliant"); ?></a>
							</p>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </footer>



    <!--====== SCRIPTS JS ======-->
    <script src="{{asset('assets/site_new/js/vendor/jquery-1.12.4.min.js')}}"></script>
    <script src="{{asset('assets/site_new/js/vendor/bootstrap.min.js')}}"></script>

    <!--====== PLUGINS JS ======-->
    <script src="{{asset('assets/site_new/js/vendor/jquery.easing.1.3.js')}}"></script>
    <script src="{{asset('assets/site_new/js/vendor/jquery-migrate-1.2.1.min.js')}}"></script>
    <script src="{{asset('assets/site_new/js/vendor/jquery.appear.js')}}"></script>
    <script src="{{asset('assets/site_new/js/owl.carousel.min.js')}}"></script>
    <script src="{{asset('assets/site_new/js/slick.min.js')}}"></script>
    <script src="{{asset('assets/site_new/js/stellar.js')}}"></script>
    <script src="{{asset('assets/site_new/js/wow.min.js')}}"></script>
    <script src="{{asset('assets/site_new/js/jquery-modal-video.min.js')}}"></script>
    <script src="{{asset('assets/site_new/js/stellarnav.min.js')}}"></script>
    <script src="{{asset('assets/site_new/js/contact-form.js')}}"></script>
    <script src="{{asset('assets/site_new/js/jquery.ajaxchimp.js')}}"></script>
    <script src="{{asset('assets/site_new/js/jquery.sticky.js')}}"></script>

    <!--===== ACTIVE JS=====-->
    <script src="{{asset('assets/site_new/js/main.js')}}"></script>

    <!-- cookiealert section -->

    @include('shared/fb-px')
    @include('shared/google-code')

</body>
</html>

<style type="text/css" media="screen">
    .red{color:red;}
</style>


<style>
    .exe { font-weight: bold; } 
    .exe:hover  { cursor: pointer; text-decoration: underline;  }    
    h4{margin: 30px 0 20px;}
</style>
