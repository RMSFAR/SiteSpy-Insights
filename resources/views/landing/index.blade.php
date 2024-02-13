<?php
/*
Theme Name: Default 
Unique Name: default
Theme URI: https://xeroneit.net/
Author: Xerone IT
Author URI: https://xeroneit.net
Version: 6.0
Description: This is a default theme provided by the Author of SiteSpy. We highly recommend not to change core files for your customization needs. For your own customization, create your own theme as per our <a href="https://xeroneit.net/blog/xerochat-front-end-theme-development-manual" target="_BLANK">documentation</a>. 
*/
?>
<!DOCTYPE html>
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title><?php echo config('my_config.product_name'); if(config('my_config.slogan')!='') echo " | ".config('my_config.slogan')?></title>
	<meta name="description" content="<?php echo config('my_config.slogan'); ?>">
	<meta name="author" content="<?php echo config('my_config.institute_address1');?>">

	<!-- Mobile Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Favicon -->
    <link rel="shortcut icon" href="{{ config('my_config.favicon') }}" type="image/x-icon">

    <!--====== STYLESHEETS ======-->
    <link rel="stylesheet" href="{{asset('assets/mainlanding/site_new/css/normalize.css')}}">
    <link rel="stylesheet" href="{{asset('assets/mainlanding/site_new/css/animate.css')}}">
    <link rel="stylesheet" href="{{asset('assets/mainlanding/site_new/css/modal-video.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/mainlanding/site_new/css/stellarnav.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/mainlanding/site_new/css/owl.carousel.css')}}">
    <link rel="stylesheet" href="{{asset('assets/mainlanding/site_new/css/slick.css')}}">
    <link href="{{asset('assets/mainlanding/site_new/css/bootstrap.min.css')}}" rel="stylesheet">
     {{-- <link href="{{asset('assets/mainlanding/site_new/css/font-awesome.min.css')}}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{asset('assets/modules/fontawesome/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/modules/fontawesome/css/v4-shims.min.css')}}">
    <link href="{{asset('assets/mainlanding/site_new/css/material-icons.css')}}" rel="stylesheet">


    <!--====== MAIN STYLESHEETS ======-->
    {{-- <!-- <link rel="stylesheet" href="{{asset('assets/css/site/style.css')}}"> --> --}}
    @include("shared.style")
    <link rel="stylesheet" href="{{asset('assets/mainlanding/site_new/css/custom.css')}}">
    <link href="{{asset('assets/mainlanding/site_new/css/responsive.css')}}" rel="stylesheet">

    <script src="{{asset('assets/mainlanding/site_new/js/vendor/modernizr-2.8.3.min.js')}}"></script>
    <!-- sweetalert -->
    <script src="{{asset('assets/modules/sweetalert/sweetalert.min.js')}}"></script>
</head>

<body class="home-two" data-spy="scroll" data-target=".mainmenu-area" data-offset="90">
    <!--- PRELOADER -->
    <div class="preeloader">
        <div class="preloader-spinner"></div>
    </div>

    <!--SCROLL TO TOP-->
    <a href="#home" class="scrolltotop"><i class="fas fa-arrow-circle-up"></i></a>

    <!--START TOP AREA-->
    <header class="top-area" id="home">
        <div class="header-top-area">
            <!--MAINMENU AREA-->
            <div class="mainmenu-area" id="mainmenu-area">
                <div class="mainmenu-area-bg"></div>
                <nav class="navbar">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <a href="#home" class="navbar-brand"><img style="max-height:45px !important" src="{{ config('my_config.logo')}}" alt="<?php echo config('my_config.product_name');?>"></a>
                        </div>
                        <div id="main-nav" class="stellarnav">
                                <div class="search-and-signup-button white pull-right hidden-sm hidden-xs">
                                    <a href="{{route('login')}}" class="sign-up">
                                        <?php if(Auth::user()) echo __('Dashboard');
                                        else echo __('Login'); ?>
                                    </a>
                                </div>
                            <ul id="nav" class="nav">
                                <li class="active">
                                    <a href="#home"><?php echo __('home'); ?></a>
                                </li>
                                <li>
                                    <a href="#features"><?php echo __('Features');?></a>
                                </li>
                                <li>
                                    <a href="#download"><?php echo __('Pricing'); ?></a>
                                </li>
                                <li <?php if(config('frontend.display_video_block') == '0') echo "class='hidden'"; ?>>
                                    <a href="#tutorial"><?php echo __('Tutorial');?></a>
                                </li>
                                <li>
                                    <a href="#contact"><?php echo __('Contact'); ?></a>
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
        <div class="welcome-text-area white">
            <div class="area-bg"></div>
            <div class="welcome-area">
                <div class="container">
                    <div class="row flex-v-center">
                        <div class="col-md-7 col-lg-7 col-sm-12 col-xs-12">
                            <div class="welcome-mockup center">
                                <img src="{{asset('assets/mainlanding/site_new/img/home/watch-mockup.png')}}" alt="">
                            </div>
                        </div>
                        <div class="col-md-7 col-lg-7 col-sm-12 col-xs-12">
                            <div class="welcome-text">
                                <h1><span><?php echo config('my_config.product_name'); ?></span></h1>
                                <span class="em"><?php echo __(config('my_config.slogan')); ?></span>

                                <!-- frontend website analysis -->
                                <?php if(config('frontend.front_end_search_display')=='1') : ?>
                                    <div class="lead text-center form_holder">  
                                      <input  class="center-block" type="text" name="website_name" id="website_name" placeholder="<?php echo __('type domain name and hit search button'); ?>">
                                      <button class="center-block"  id="submit" type="submit"> <i class="fa fa-search fa-2x"></i> </button>
                                    </div>
                                    <br/>
                                <?php endif; ?>
                                <!-- frontend website analysis -->

                                <div class="home-button" <?php if(config('frontend.front_end_search_display')=='0' || config('frontend.front_end_search_display')=='') echo "style='margin-top: 30px;'" ?>>
                                    <a href="#features"><?php echo __("detailed features"); ?></a>
                                    <a <?php if(config('frontend.enable_signup_form') =='0' || Auth::user()) echo "class='hidden'"; ?> href="{{route('register')}}"><?php echo __("Sign up now"); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!--END TOP AREA-->

    <!--FEATURES TOP AREA-->
    <section class="features-top-area padding-100-50" id="features">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2 col-sm-12 col-xs-12">
                    <div class="area-title text-center wow fadeIn">
                        <h2><?php echo __("Key Features").' : '.config('my_config.product_name'); ?></h2>
                        <p><?php echo __("The Most Complete Visitor Analytics & SEO Tools"); ?></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                    <div class="qs-box relative mb50 center wow fadeInUp" data-wow-delay="0.2s">
                        <div class="qs-box-icon">
                            <i class="fa fa-line-chart"></i>
                        </div>
                        <h3><?php echo __("Visitor Analytics"); ?></h3>
                        <p><?php echo __("It Has the ability to analyze your own website's informations"); ?></p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                    <div class="qs-box relative mb50 center  wow fadeInUp" data-wow-delay="0.3s">
                        <div class="qs-box-icon">
                            <i class="fa fa-globe"></i>
                        </div>
                        <h3><?php echo __("Website Analytics"); ?></h3>
                        <p><?php echo __("It Has the ability to analyze any other website's informations"); ?></p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                    <div class="qs-box relative mb50 center wow fadeInUp" data-wow-delay="0.4s">
                        <div class="qs-box-icon">
                            <i class="fa fa-map-marker"></i>
                        </div>
                        <h3><?php echo __("Native API"); ?></h3>
                        <p><?php echo __("It has native API by which developers can integrate it’s facilities with another app"); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--FEATURES TOP AREA END-->
 

    <!--FEATURES AREA-->
    <section class="features-area relative padding-100-50 gray-bg">
        <div class="container">
            <div class="row">
                <?php if($is_ad_enabled2 || $is_ad_enabled3)
                {
                    if($is_ad_enabled2) 
                    echo '<div class="col-xs-12 col-md-3 add-300-250">'.$ad_content2.'</div>';
                    else 
                    echo '<div class="col-xs-12 col-md-3 add-300-250">'.$ad_content3.'</div>';
                } ?>
                <div class="col-md-6 col-lg-6 <?php if(!$is_ad_enabled2 && !$is_ad_enabled3) echo "col-md-offset-3 col-lg-offset-3";?> col-sm-12 col-xs-12">
                    <div class="area-title text-center wow fadeIn">                        
                        <?php if($is_ad_enabled2 || $is_ad_enabled3) echo '<div class="hidden-xs hidden-sm" style="margin-top:50px"></div>';?>
                        <h2><?php echo __("detailed features"); ?></h2>
                        <p><?php echo config('my_config.product_name').' '.__("is an app to analyze your site visitors and analyze any site's information such as alexa data,similarWeb data, whois data, social media data, moz check, search engine index, google page rank, IP analysis, malware check etc"); ?></p>
                    </div>
                </div>
                <?php if($is_ad_enabled2 && $is_ad_enabled3)
                {
                    if($is_ad_enabled3) 
                    echo '<div class="col-xs-12 col-md-3 add-300-250">'.$ad_content3.'</div>';
                } ?>
            </div>
            <?php if($is_ad_enabled2 || $is_ad_enabled3) echo '<div style="margin-top:50px"></div>';?>
            <br><br>
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                            <div class="qs-box relative mb50 pos-icon-left  wow fadeInUp" data-wow-delay="0.2s">
                                <div class="qs-box-icon">
                                    <i class="fa fa-line-chart"></i>
                                </div>
                                <h4><?php echo __("Visitor Analytics"); ?></h4>
                                <p>{{__("Unique Visitor,Page View,Bounce Rate,Average Stay Time,Average Visit,Traffic Analysis,Top Refferer,New & Returning Visitor,Content Overview,Country & Browser Report,OS & Device Report")}}</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                            <div class="qs-box relative mb50 pos-icon-left  wow fadeInUp" data-wow-delay="0.3s">
                                <div class="qs-box-icon">
                                    <i class="fa fa-globe"></i>
                                </div>
                                <h4><?php echo __("Website Analytics"); ?></h4>
                                <p>{{__("Alexa data,SimilarWeb data,Whois data,Social media data,moz check,dmoz check,search engine index, google page rank, IP analysis, malware check")}}</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                            <div class="qs-box relative mb50 pos-icon-left  wow fadeInUp" data-wow-delay="0.2s">
                                <div class="qs-box-icon">
                                    <i class="fa fa-share-alt"></i>
                                </div>
                                <h4><?php echo __("Social Network Analysis"); ?></h4>
                                <p>{{__("Facebook Share,Xing Share,Reddit Score Up & Down,Pinterest Pin,Buffer Share,StumbleUpon View")}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                            <div class="qs-box relative mb50 pos-icon-left  wow fadeInUp" data-wow-delay="0.3s">
                                <div class="qs-box-icon">
                                    <i class="fa fa-trophy"></i>
                                </div>
                                <h4><?php echo __("Rank & Index Analysis"); ?></h4>
                                <p>{{__("Alexa Rank,Alexa Data,MOZ Check,Google Index,Yahoo Index,Bing Index")}}</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                            <div class="qs-box relative mb50 pos-icon-left  wow fadeInUp" data-wow-delay="0.2s">
                                <div class="qs-box-icon">
                                    <i class="fa fa-server"></i>
                                </div>
                                <h4><?php echo __("Domain Analysis"); ?></h4> 
                                <p>{{__("Whois Search,Auction Domain List,DNS Information,Server Information")}}</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                            <div class="qs-box relative mb50 pos-icon-left  wow fadeInUp" data-wow-delay="0.2s">
                                <div class="qs-box-icon">
                                    <i class="fa fa-map-marker"></i>
                                </div>
                                <h4><?php echo __("IP Analysis"); ?></h4> 
                                <p>{{__("What is my IP,Domain IP Information,Sites in Same IP,Ipv6 Compability Check,IP Canonical Check,IP Traceout")}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                            <div class="qs-box relative mb50 pos-icon-left wow fadeInUp" data-wow-delay="0.3s">
                                <div class="qs-box-icon">
                                    <i class="fa fa-tags"></i>
                                </div>
                                <h4><?php echo __("Keyword Analysis"); ?></h4>
                                <p>{{__("Keyword Analyzer,Keyword Position,Keyword Position Tracking (Daily),Correlared Trending Keywords,Keyword Auto Suggestion")}}</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                            <div class="qs-box relative mb50 pos-icon-left wow fadeInUp" data-wow-delay="0.3s">
                                <div class="qs-box-icon">
                                    <i class="fa fa-anchor"></i>
                                </div>
                                <h4><?php echo __("Link Analysis"); ?></h4>
                                <p>{{__("Link Analyzer (internal, external, doFollow, noFollow),Page Status Check")}}</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                            <div class="qs-box relative mb50 pos-icon-left wow fadeInUp" data-wow-delay="0.3s">
                                <div class="qs-box-icon">
                                    <i class="fa fa-link"></i>
                                </div>
                                <h4><?php echo __("Backlink & Ping"); ?></h4>
                                <p>{{__("Google Backlink Search,Backlink Generator,Website/Blog Ping")}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                            <div class="qs-box relative mb50 pos-icon-left wow fadeInUp" data-wow-delay="0.3s">
                                <div class="qs-box-icon">
                                    <i class="fa fa-shield"></i>
                                </div>
                                <h4><?php echo __("Malware Scan"); ?></h4>
                                <p>{{__("Google Safe Browser,Norton,VirusTotal (67 different scans)")}}</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                            <div class="qs-box relative mb50 pos-icon-left wow fadeInUp" data-wow-delay="0.3s">
                                <div class="qs-box-icon">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                                <h4><?php echo __("Google Tools & Utilities"); ?></h4>
                                <p>{{__("Google URL Shortener + Analytics,Email Encoder/ Decoder,URL Encoder/ Decoder,Base64 Encoder/Decoder,Meta Tag Generator,Robot Code Generator,Plagiarism Check,Valid Email Check,Duplicate Email Filter,URL Canonical Check,Gzip Check")}}</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                            <div class="qs-box relative mb50 pos-icon-left wow fadeInUp" data-wow-delay="0.3s">
                                <div class="qs-box-icon">
                                    <i class="fa fa-code"></i>
                                </div>
                                <h4><?php echo __("Code minifier"); ?></h4>
                                <p>{{__("HTML code minifier,CSS code minifier,JS code minifier")}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--FEATURES AREA END-->

    <?php if($is_ad_enabled && $is_ad_enabled1) : ?>    
        <div class="add-970-90 hidden-xs hidden-sm text-center" style="background: #F5F4F4;"><?php echo $ad_content1; ?></div> 
        <div class="add-320-100 hidden-md hidden-lg text-center" style="background: #F5F4F4;"><?php echo $ad_content1_mobile; ?></div> 
    <?php endif; ?> 

    <!--INTRO AREA-->
    <section class="intro-area section-padding relative">
        <div class="area-bg"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                    <div class="intro-image wow fadeIn text-center">
                        <h3 class="hidden">{{__("Just Used For Validation")}}</h3>
                        <img src="{{asset('assets/mainlanding/site_new/img/mockups/home-two-promo-mockup.png')}}" alt="" style="max-width: 82%;margin:0 auto;">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--INTRO AREA END-->

    <!--WORK AREA-->
    <section class="work-area section-padding" id="work">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3 col-sm-12 col-xs-12">
                    <div class="area-title text-center wow fadeIn">
                        <h2><?php echo __("Visitor Analytics"); ?></h2>
                        <span class="icon-and-border"><i class="material-icons">{{__("phone_android")}}</i></span>
                        <p><?php echo __("Visitor Analytics is one the key features of").' '.config('my_config.product_name').'.'.' '.__("You can analyze your own website's informations."); ?></p>
                    </div>
                </div>
            </div>
            <div class="row flex-v-center">
                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                    <div class="qs-box pos-icon-right mb100 wow fadeIn">
                        <div class="qs-box-icon">
                            <img src="{{asset('assets/mainlanding/site_new/img/icon/icon-1.png')}}" alt="">
                        </div>
                        <h4><?php echo __("Input Domain"); ?></h4>
                        <p><?php echo __("Input a domain name and click on save button"); ?></p>
                    </div>
                    <div class="qs-box  pos-icon-right wow fadeIn xs-mb50">
                        <div class="qs-box-icon">
                            <img src="{{asset('assets/mainlanding/site_new/img/icon/icon-2.png')}}" alt="">
                        </div>
                        <h4><?php echo __("Get js embed code"); ?></h4>
                        <p><?php echo __("You will get a js code by clicking on save button"); ?></p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-4 col-xs-12 hidden-xs hidden-sm">
                    <div class="service-image text-center wow fadeIn xs-mb50">
                        <img src="{{asset('assets/mainlanding/site_new/img/mockups/home-two-work-mockup.png')}}" alt="">
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 pull-left">
                    <div class="qs-box  pos-icon-left mb100 wow fadeIn">
                        <div class="qs-box-icon">
                            <img src="{{asset('assets/mainlanding/site_new/img/icon/icon-3.png')}}" alt="">
                        </div>
                        <h4><?php echo __("Put js Code"); ?></h4>
                        <p><?php echo __("Copy the embedded js code and paste it into your web page"); ?></p>
                    </div>
                    <div class="qs-box pos-icon-left wow fadeIn">
                        <div class="qs-box-icon">
                            <img src="{{asset('assets/mainlanding/site_new/img/icon/icon-4.png')}}" alt="">
                        </div>
                        <h4><?php echo __("Get Report Everyday"); ?></h4>
                        <p><?php echo __("You will get daily report about your website"); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--WORK AREA END-->

    <!--ABOUT AREA-->
    <section class="about-area gray-bg section-padding" id="app">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3 col-sm-12 col-xs-12">
                    <div class="area-title text-center wow fadeIn">
                        <h2><?php echo __("About Our App"); ?></h2>
                        <span class="icon-and-border"><i class="material-icons">phone_android</i></span>
                        <p><?php echo __("World’s very first, most powerful and Complete Visitor Analytics & SEO Tools"); ?></p>
                    </div>
                </div>
            </div>
            <div class="row flex-v-center">
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <div class="about-content sm-mb50 sm-center">
                        <h4 class="mb30"><?php echo config('my_config.product_name').' '."-". __("The Most Complete Visitor Analytics & SEO Tools"); ?></h4>
                        <p class="description"><?php echo __('It`s a app to analyze your site visitors and analyze ay site`s information such as alexa data,similarWeb data, whois data, social media data, Moz check, DMOZ check, search engine index, google page rank, IP analysis, malware check etc. combined with some other great SEO tools such as link analysis, keyword position analysis, auto keyword suggestion,page status check, backlink creation/search, website ping, google adword scraper etc.');?></p>
                        <p class="description"><?php echo __('You will get some bonus utility tools such as email encoder/decoder, metatag generator, ogtag generator, plgiarism check, valid email check, duplicate email filter, url encode/decode, robot code generator etc.');?></p>
                        <p class="description"><?php echo __('It has native APIs by which developers can integrate it`s facilities with another app.');?></p>
                        <p class="description"><?php echo __('Nice colorful widgets are available. You can simply copy & paste some line of codes to any page you want and can display site information.');?></p>
                        @if(config('frontend.display_video_block') == '1')
                        <a href="#video" class="video-button mt30 inline-block"><i class="fa fa-play"></i> <?php echo __("Watch Promo Video"); ?></a>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <div class="about-mockup center wow fadeIn xs-mt50">
                        <img src="{{asset('assets/mainlanding/site_new/img/mockups/home-two-about-mockup.png')}}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--ABOUT AREA END-->

    <!--PROMO AREA-->
    <section class="<?php if(config('frontend.display_video_block') == '0' || config('frontend.promo_video') == '') echo 'hidden';?> promo-area relative section-padding" id="video">
        <div class="area-bg"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2 col-sm-12 col-xs-12">
                    <div class="area-title center white wow fadeIn">
                        <h2><?php echo __("Explore The Best Promo Video"); ?></h2>
                        <p><?php echo __("See the super promo video"); ?></p>
                    </div>
                </div>
            </div>
            <?php 
                $link = config('frontend.promo_video');
                $final = trim(str_replace('https://www.youtube.com/watch?v=','',$link));
                $final = trim(str_replace('https://youtube.com/watch?v=','',$link));
             ?>
            <div class="row">
                <div class="col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2 col-sm-12 col-xs-12">
                    <div class="promo-area-content center white wow fadeIn">
                        <div class="video-promo-slider">
                            <div class="single-video-promo-slide">
                                <img src="{{asset('assets/mainlanding/site_new/img/promo/video-promo-slide-1.png')}}" alt="">
                                <div class="video-play-button">
                                    {{-- <button data-video-id="<?php echo $final; ?>" class="video-area-popup"><i class="fas fa-play-circle"></i></button> --}}
                                    <a class="popup-youtube play-button video-play-button" data-url="{{$link ?? ''}}" data-toggle="modal" data-target="#myMpromovideoModalodal" title="XJj2PbenIsU">
                                        <i class="fas fa-play-circle"></i>
                                    </a>
                                    
                                </div>
                            </div>
                            <div class="single-video-promo-slide">
                                <img src="{{asset('assets/mainlanding/site_new/img/promo/video-promo-slide-1.png')}}" alt="">
                                <div class="video-play-button">
                                    {{-- <button data-video-id="<?php echo $final; ?>" class="video-area-popup"><i class="fas fa-play-circle"></i></button> --}}
                                    <a class="popup-youtube play-button video-play-button" data-url="{{$link ?? ''}}" data-toggle="modal" data-target="#myMpromovideoModalodal" title="XJj2PbenIsU">
                                        <i class="fas fa-play-circle"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--PROMO AREA END-->

    <!--SCREENSHOT AREA-->
    <section class="screenshot-area section-padding" id="screenshot">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3 col-sm-12 col-xs-12">
                    <div class="area-title text-center wow fadeIn">
                        <h2><?php echo __("App Screenshots"); ?></h2>
                        <span class="icon-and-border"><i class="material-icons">phone_android</i></span>
                        <p><?php echo __("Here are some screenshots of").' '.config('my_config.product_name').' '.__("See the amazing shots and enjoy."); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row flex-v-center">
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <div class="screenshot-slider-area wow fadeIn xs-mb50">
                        <div class="screenshot-slider-2">
                            <div class="single-screenshot">
                                <img src="{{asset('assets/mainlanding/site_new/img/screenshot/screenshot-1.jpg')}}" alt="">
                            </div>
                            <div class="single-screenshot">
                                <img src="{{asset('assets/mainlanding/site_new/img/screenshot/screenshot-2.jpg')}}" alt="">
                            </div>
                            <div class="single-screenshot">
                                <img src="{{asset('assets/mainlanding/site_new/img/screenshot/screenshot-3.jpg')}}" alt="">
                            </div>
                            <div class="single-screenshot">
                                <img src="{{asset('assets/mainlanding/site_new/img/screenshot/screenshot-4.jpg')}}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="<?php if($is_ad_enabled4) echo 'col-md-3 col-lg-3'; else echo 'col-md-4 col-lg-4';?> col-sm-12 col-xs-12">
                    <div class="screenshot-content xs-center sm-center xs-mt50 sm-mt50">
                        <h2><?php echo __("Awesome App"); ?></h2>
                        <p><?php echo config('my_config.product_name').' '.__("- The Most Complete Visitor Analytics & SEO Tools. It's a app to analyze your site visitors and analyze ay site's information."); ?></p>
                    </div>
                </div>
                <?php 
                if($is_ad_enabled4) echo '<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 add-300-600">'.$ad_content4.'</div>';
                ?>
            </div>
        </div>
    </section>
    <!--SCREENSHOT AREA END-->

    <!--DOWNLOAD AREA-->
    <section class="download-area section-padding relative white" id="download">
        <div class="area-bg" data-stellar-background-ratio="0.6"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                    <div class="download-content sm-center xs-center xs-mb50 xs-font wow fadeIn">
                        <h2><?php echo __("Get the greatest app !"); ?></h2>
                        <p><?php echo config('my_config.product_name').' '.__("provides you trial package. So Click on the button and explore it."); ?></p>
                        <?php if(isset($default_package[0])) : ?>
                            <a href="{{route('register')}}" class="download-button wow shake"><i class="fa fa-shopping-cart"></i><?php echo __("Free Trial"); ?> <span><?php echo $default_package[0]->validity ?> <?php echo __("Days"); ?></span></a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4 col-sm-6 col-xs-12">
                    <div class="download-content sm-center xs-center wow fadeIn">
                        <h2><?php echo __("Amazing Prices"); ?></h2>
                        <p><?php echo __("Greatest Visitor Analytics & SEO Tools Software with very reasonable prices. So explore the plans and get the best software on the earth"); ?></p>
                        <a href="#pricing" class="download-button wow shake"><i class="fa fa-dollar"></i><?php echo __("Get the app"); ?> <span><?php echo __("Price Plans"); ?></span></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--DOWNLOAD AREA END-->

	<!--PRICING AREA-->
    <?php
    if(!empty($pricing_table_data)) : 
    ?>
	<section class="price-area padding-100-70 sky-gray-bg" id="pricing">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3 col-sm-12 col-xs-12">
                    <div class="area-title text-center wow fadeIn">
                        <h2><?php echo __("Pricing"); ?> <span><?php echo __("Table"); ?></span></h2>
                        <span class="icon-and-border"><i class="material-icons">phone_android</i></span>
                        <p><?php echo __("Get the Complete visitor and SEO analytical software with very reasonable price."); ?></p>
                    </div>
                </div>
            </div>

		<!-- starting of table row -->
        <div class="row">
            <?php 
                $i=0;
                $classes=array(1=>"tiny",2=>"small",3=>"medium",4=>"pro");
                foreach($pricing_table_data as $pack) :    
                $i++;   
            ?>

            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
                <div class="single-price center wow fadeInUp" data-wow-delay="0.2s">
                    <div class="price-hidding">
                        <h4><?php echo $pack->package_name; ?></h4>
                    </div>
                    <div class="price-rate" <?php if($pack->highlight=='1') echo 'style="background:'.$THEMECOLORCODE.'"'; ?>>
                        <h3 <?php if($pack->highlight=='1') echo 'style="color:#FFF"'; ?>>
                            <br>
                            <sup><?php echo $curency_icon; ?></sup><?php echo $pack->price?>
                            <sub><?php echo $pack->validity?> <?php echo __("days"); ?></sub>
                            <br><br>
                        </h3>
                        
                    </div>
                    <div class="price-details scrollit text-left" style="height: 300px;overflow-y: auto;">
                        <ul>
                            <?php 
                                $module_ids=$pack->module_ids;
                                $monthly_limit=json_decode($pack->monthly_limit,true);
                                // $module_names_array=$this->basic->execute_query('SELECT module_name,id FROM modules WHERE FIND_IN_SET(id,"'.$module_ids.'") > 0  ORDER BY module_name ASC');
                                $module_names_array = DB::table('modules')->select('module_name', 'id')->whereIn('id', explode(',', $module_ids))->orderBy('module_name', 'ASC')->get();
                                $module_names_array = json_decode(json_encode($module_names_array));
                                foreach ($module_names_array as $row) : ?>
                                <li>
                                    <i class="fas fa-circle"></i>&nbsp;
                                    <?php 
                                        $limit=0;
                                        $limit=$monthly_limit[$row->id];

                                        if($limit=="0") 
                                            $limit2="<b>".__("unlimited")."</b>";
                                        else 
                                            $limit2=$limit;

                                        if($row->id!="1" && $limit!="0") 
                                            
                                            $limit2="<b>".$limit2."/".__("month")."</b>";
                                            echo __($row->module_name);

                                        if($row->id!="13" && $row->id!="14" && $row->id!="16") 
                                            echo " : <b>". $limit2."</b>"."<br>";
                                        else 
                                            echo "<br>";
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="buy-now-button <?php if(config('my_config.enable_signup_form') == '0') echo "hidden"; ?>">
                        <a href="{{route('register')}}" class="read-more"><?php echo __('sign up'); ?></a>
                    </div>
                </div>
            </div>
    
            <?php
                if($i%4==0) break;
                endforeach;
            ?>
        </div> <!-- end of table row -->
        </div>
    </section>
     <?php endif; ?>
    <!--PRICING AREA END-->

    <!--Review AREA-->
    <section class="<?php if(config('frontend.display_review_block') == '0') echo 'hidden';?> video-area section-padding style-two" id="team">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3 col-sm-12 col-xs-12">
                    <div class="area-title text-center wow fadeIn" style="visibility: visible; animation-name: fadeIn;">
                        <h2><span><?php echo __("Reviews"); ?></span></h2>
                        <span class="icon-and-border"><i class="material-icons">phone_android</i></span>
                    </div>
                </div>
            </div>
            <div class="row flex-v-center">
                <!-- Demo video section -->
                <?php 
                    $demo = config('my_config.customer_review_video');
                    $customer_review_video = trim(str_replace('https://www.youtube.com/watch?v=','',$demo));
                ?>
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 <?php if(config('my_config.customer_review_video') == '') echo 'hidden';?>">
                    <div class="video-area-content wow fadeIn sm-mb50 xs-mb50">
                        <img src="{{asset('assets/mainlanding/site_new/img/video/review-bg.jpg')}}" alt="">
                        <button data-video-id="<?php echo $customer_review_video; ?>" class="video-area-popup"><i class="fas fa-play"></i></button>
                        <h4 class="demo-title-area" style="text-align: center; font-weight: bold;border-radius: 60px;width: 40%;box-shadow: 2px 2px 2px #aaa, -1px 0 1px #aaa;position: relative;left: 170px;margin: 5px 0px; padding: 0 1px;"><?php echo __('Customer review Video'); ?></h4>
                    </div>
                </div>
                <!-- End of demo video section -->

                <div class="<?php if(config('frontend.customer_review_video') == '') echo 'col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2 col-sm-12 col-xs-12'; else echo 'col-md-6 col-lg-6 col-sm-12 col-xs-12';?>">
                    <div class="team-member-content wow fadeIn">
                        <div class="team-member-list team-slider">
	                        <?php 
                                $customerReview = config('frontend.customer_review');
                                $ct=0;
							    foreach($customerReview as $singleReview) : 
                                if(isset($singleReview[3]) && empty($singleReview[3])) continue;
                                $ct++;
                                $original = $singleReview[2];
                                $base     = url('/');

                                if (substr($original, 0, 4) != 'http') {
                                    $img = $base.$original;
                                } else {
                                   $img = $original;
                                }

                            ?>
                                <div class="single-team" style="height: 200px;">
                                    <div class="member-image">
                                        <img src="<?php echo $img; ?>" alt="reviewer">
                                    </div>
                                    <div class="name-and-designation">
                                        <h4><?php echo $singleReview[0]; ?></h4>
                                        <p><?php echo $singleReview[1]; ?></p>
                                        <p style="text-align: justify; font-weight: normal;">
                                            <?php 
                                                if(strlen($singleReview[3]) > 200 ) {
                                                    $str = substr($singleReview[3],0,180);
                                                    echo $str.". . ."."<a class='exe' type='button' data-toggle='modal' data-target=#myModal".$ct.">see more</a>";
                                                
                                                } else {
                                                    echo $str = $singleReview[3];
                                                }
                                                
                                            ?>
                                        </p>
                                    </div>
                                    <div class="member-details">
                                    </div>
                                </div>
	                        <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Review AREA END-->

    <!--Tutorial AREA-->
    <section class="<?php if(config('frontend.display_video_block') == '0') echo 'hidden';?> blog-feed-area padding-100-70 gray-bg" id="tutorial" style="background: <?php if(config('frontend.display_review_block') == '0') echo "#FFFFFF"; ?>">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3 col-sm-12 col-xs-12">
                    <div class="area-title text-center wow fadeIn">
                        <h2><?php echo __("Video Walkthrough"); ?></h2>
                        <span class="icon-and-border"><i class="material-icons">phone_android</i></span>
                        <p><?php echo __('Get the latest videos of our app which may help you to make you comfortable with the app.'); ?></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php 
                $custom_videos = config('frontend.custom_video'); 
                foreach($custom_videos as $customVideo) : 
                    if(isset($customVideo[2]) && empty($customVideo[2])) continue;
                    $original_video = $customVideo[0];
                    $baseurl        = url('/');

                    if (substr($original_video,0,4) != 'http') {
                        $thumb = $baseurl.$original_video;
                    } else {
                        $thumb = $original_video;
                    }
                ?>
                <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
                    <div class="single-blog mb30 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="blog-thumb width100">
                            <a target="_blank" href="<?php echo $customVideo[2]; ?>"><img style="height: 150px;" src="<?php echo $thumb;?>" alt="">
                                <button class="video-area-popup-new"><i class="fa fa-play-circle"></i></button></a>

                        </div>
                        <div class="blog-details padding-30 border" style="height: 100px !important;">
                            <h4  class="text-center" title="<?php echo $customVideo[1]; ?>">
                                <a target="_blank" href="<?php echo $customVideo[2]; ?>">
                                    <?php 
                                        $videotitle = $customVideo[1];
                                        if(strlen($videotitle) > 50) {
                                            $substring = substr($videotitle,0,48);
                                            echo $substring."...";
                                        } else {
                                            echo $videotitle;
                                        }
                                    ?>
                                    
                                </a>
                            </h4>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!--Tutorial AREA END-->

    <!--CONTACT US AREA-->
    <section style="<?php if(config('my_config.display_video_block') == '0' && config('my_config.display_review_block') == '0' ) echo 'background-color: #fff'; elseif(config('my_config.display_video_block') == '0') echo 'background-color: #f5f4f4'; else echo 'background-color: #fff'; ?>" class="contact-area relative padding-100-50" id="contact">
        <div class="contact-form-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3 col-sm-12 col-xs-12">
                        <div class="area-title text-center wow fadeIn">
                            <h2><?php echo __('Contact Us');?></h2>
                            <span class="icon-and-border"><i class="material-icons">phone_android</i></span>
                            <p><?php echo __('Feel free to contact with us.'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                    	<div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                <div class="form-group" id="name-field">
                                    <?php 
										if(session()->get('mail_sent') == 1) {
										echo "<div class='alert alert-success text-center'>".__("we have received your email. we will contact you through email as soon as possible")."</div>";
										session()->forget('mail_sent');
										}
									?>
                                </div>
                            </div>
                    	</div>
                        <div class="contact-form mb50 wow fadeIn">
                            <form action="<?php echo url("/home/email_contact"); ?>" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-5 col-lg-5 col-sm-12 col-xs-12">
                                        <div class="form-group" id="email-field">
                                            <div class="form-input">
                                                <input type="email" class="form-control" required id="email" <?php echo set_value("email"); ?> placeholder="<?php echo __("email");?>" name="email">
                                            </div>
                                            @if ($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-12 col-xs-12">
                                        <div class="form-group" id="phone-field">
                                            <div class="form-input">
                                                <input type="text" class="form-control" required id="subject" <?php echo set_value("subject"); ?> placeholder="<?php echo __("message subject");?>" name="subject">
                                            </div>
                                            @if ($errors->has('subject'))
                                            <span class="text-danger">{{ $errors->first('subject') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-12 col-xs-12">
                                        <div class="form-group" id="message-field">
                                            <div class="form-input">
                                                <input type="number" class="form-control" step="1" required id="captcha" <?php echo set_value("captcha"); ?> placeholder="<?php echo $contact_num1. "+". $contact_num2." = ?"; ?>" name="captcha">
													{{-- <span class="red">
														<?php 
														if(form_error('captcha')) 
															echo form_error('captcha'); 
														else  
														{ 
															echo session()->get("contact_captcha_error"); 
															session()->forget("contact_captcha_error"); 
														} 
														?>
													</span> --}}
                                                    @if ($errors->has('captcha'))
                                                    <span class="text-danger">{{ $errors->first('captcha') }}</span>														
                                                    @endif
                                            	</div>
                                            @if ($errors->has('message'))
                                            <span class="text-danger">{{ $errors->first('message') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                        <div class="form-group" id="message-field">
                                            <div class="form-input">
                                                <textarea class="form-control" rows="3" required id="message" <?php echo set_value("message"); ?> placeholder="<?php echo __("message");?>" name="message"></textarea>
                                            </div>
                                            @if ($errors->has('message'))
                                            <span class="text-danger">{{ $errors->first('message') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                        <div class="form-group center">
                                            <button type="submit"><?php echo __("Send Message");?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--CONTACT US AREA END-->

    <!--FOOER AREA-->
    <footer class="footer-area white relative">
        <div class="area-bg"></div>
        <div class="footer-bottom-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3 col-sm-12 col-xs-12">
                        <div class="footer-social-bookmark text-center section-padding wow fadeIn">
                            <div class="footer-logo mb50 hidden-xs">
                                <a href="#"><img src="{{config('my_config.logo')}}" alt="logo" style="max-height: 70px;"></a>
                            </div>
                            <p style=""><?php echo __("World’s very first, most powerful and Complete Visitor Analytics & SEO Tools"); ?></p>
                            <?php 
                                $facebook = config('frontend.facebook');
                                $twitter  = config('frontend.twitter');
                                $linkedin = config('frontend.linkedin');
                                $reddit   = config('frontend.reddit');
                                $pinterest = config('frontend.pinterest');
                                $youtube  = config('frontend.youtube');

                                if($facebook=='' && $twitter=='' && $linkedin=='' && $youtube=='') $cls='hidden';
                            ?>
                            <ul class="social-bookmark mt50 <?php if(isset($cls)) echo $cls; ?>">
                                <li <?php if($facebook=='') echo "class='hidden'"; ?>>
                                    <a title="Facebook" target="_blank" class="facebook" href="<?php echo $facebook; ?>"><i class="fa fa-facebook"></i>
                                    </a>
                                </li>
                                <li <?php if($twitter=='') echo "class='hidden'"; ?>>
                                    <a title="Twitter" target="_blank" class="twitter" href="<?php echo $twitter; ?>"><i class="fa fa-twitter"></i>
                                    </a>
                                </li>
                                <li <?php if($linkedin=='') echo "class='hidden'"; ?>>
                                    <a title="Linkedin" target="_blank" class="linkedin" href="<?php echo $linkedin; ?>"><i class="fa fa-linkedin"></i>
                                    </a>
                                </li>
                                <li <?php if($youtube=='') echo "class='hidden'"; ?>>
                                    <a title="Youtube" target="_blank" class="youtube" href="<?php echo $youtube; ?>"><i class="fa fa-youtube-play"></i>
                                    </a>
                                </li>
                                <li <?php if($reddit=='') echo "class='hidden'"; ?>>
                                    <a title="Reddit" target="_blank" class="reddit" href="<?php echo $reddit; ?>"><i class="fa fa-reddit"></i>
                                    </a>
                                </li>
                                <li <?php if($pinterest=='') echo "class='hidden'"; ?>>
                                    <a title="Pinterest" target="_blank" class="pinterest" href="<?php echo $pinterest; ?>"><i class="fa fa-pinterest"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <div class="footer-copyright text-center wow fadeIn" style="padding-bottom: 55px;">
                            <p>
                                <?php echo config("my_config.product_short_name")." ".$APP_VERSION; ?> | <?php echo __("Copyright"); ?> &copy; <a target="_blank" href="<?php echo url('/'); ?>"><?php echo config("my_config.institute_address"); ?></a></p>
                            <p class="text-center" style="font-size: 10px;">
                                <a href="{{route('policy-privacy')}}" target="_blank"><?php echo __("Privacy Policy"); ?></a> | <a href="{{route('policy-terms')}}" target="_blank"><?php echo __("Terms of Service"); ?></a> | <a href="{{route('policy-gdpr')}}" target="_blank"><?php echo __("GDPR Compliant"); ?></a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </footer>
    <!-- COOKIES -->
    <?php if(session('allow_cookie') !='yes') : ?>
        <div class="text-center cookiealert">
            <div class="cookiealert-container">
                <a style="font-size: 16px; color:#fff;text-decoration: none;" href="<?php echo url('home/privacy_policy#cookie_policy');?>">
                    <?php echo __("This site requires cookies in order for us to provide proper service to you.");?>
                </a>
                <a type="button" href="{{route('accept-cookie')}}" style="color:#000;" class="btn btn-warning btn-sm acceptcookies" aria-label="Close">
                    <?php echo __("Got it !"); ?>
                </a>

            </div>
        </div>
    <?php endif; ?>
    <!-- /COOKIES -->
    <!--FOOER AREA END-->


    <!--====== SCRIPTS JS ======-->
    <script src="{{asset('assets/mainlanding/site_new/js/vendor/jquery-1.12.4.min.js')}}"></script>
    <script src="{{asset('assets/mainlanding/site_new/js/vendor/bootstrap.min.js')}}"></script>

    <!--====== PLUGINS JS ======-->
    <script src="{{asset('assets/mainlanding/site_new/js/vendor/jquery.easing.1.3.js')}}"></script>
    <script src="{{asset('assets/mainlanding/site_new/js/vendor/jquery-migrate-1.2.1.min.js')}}"></script>
    <script src="{{asset('assets/mainlanding/site_new/js/vendor/jquery.appear.js')}}"></script>
    <script src="{{asset('assets/mainlanding/site_new/js/owl.carousel.min.js')}}"></script>
    <script src="{{asset('assets/mainlanding/site_new/js/slick.min.js')}}"></script>
    <script src="{{asset('assets/mainlanding/site_new/js/stellar.js')}}"></script>
    <script src="{{asset('assets/mainlanding/site_new/js/wow.min.js')}}"></script>
    <script src="{{asset('assets/mainlanding/site_new/js/jquery-modal-video.min.js')}}"></script>
    <script src="{{asset('assets/mainlanding/site_new/js/stellarnav.min.js')}}"></script>
    <script src="{{asset('assets/mainlanding/site_new/js/contact-form.js')}}"></script>
    <script src="{{asset('assets/mainlanding/site_new/js/jquery.ajaxchimp.js')}}"></script>
    <script src="{{asset('assets/mainlanding/site_new/js/jquery.sticky.js')}}"></script>
    <script src="{{asset('assets/modules/nicescroll/jquery.nicescroll.min.js')}}"></script>

    <!--===== ACTIVE JS=====-->
    <script src="{{asset('assets/mainlanding/site_new/js/main.js')}}"></script>

    <!-- cookiealert section -->
@include('shared/fb-px')
@include('shared/google-code')

@include('shared.landing')

</body>
</html>

<style type="text/css" media="screen">
    .red{color:red;}
</style>

<script type="text/javascript">
	$(document).ready(function() {
        $(document.body).on('click', '.acceptcookies', function(event) {
            event.preventDefault();
            var base_url = '<?php echo url('/'); ?>';
            $('.cookiealert').hide();
            $.ajax({
                url: base_url+'/home/allow_cookie',
                type: 'POST',
                beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
                },
            })
        });
        $(".scrollit").niceScroll();
	});
</script>


<style>
    .exe { font-weight: bold; } 
    .exe:hover  { cursor: pointer; text-decoration: underline;  }
</style>

    <!-- Modal -->
    <?php   
    $ct=0;
    foreach($customerReview as $singleReview) : 
        $ct++;
        $original = $singleReview[2];
        $base     = url('/');

        if (substr($original, 0, 4) != 'http') {
            $img = $base.$original;
        } else {
           $img = $original;
        }
    ?>

    <div id="myModal<?php echo $ct; ?>" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="font-weight: bold;"><?php echo __('Full Review'); ?></h4>
            </div>
            <div class="single-item" style="text-align: center; margin-top: 10px;">
                <div class="member-image">
                    <img class="img-circle img-thumbnail" src="<?php echo $img; ?>" alt="reviewer">
                </div>
                <div class="modal-body name-and-designation" style="margin-top: 10px;">
                    <h4><?php echo $singleReview[0]; ?></h4>
                    <p><?php echo $singleReview[1]; ?></p>
                    <p style="text-align: justify; font-style: normal; color: #000;padding:10px 20px;"><?php echo $singleReview[3]; ?></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo __('Close'); ?></button>
            </div>
        </div>

      </div>
    </div>
    <?php endforeach; ?>
    <!-- End of Modal -->





<!-- ================================== Frontend website analysis section ======================================= -->

<div class="modal fade" id="modal_add_domain" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="min-width:50%;">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bbw">
                <h4 class="modal-title blue"><i class="fa fa-hourglass-half"></i> <?php echo __('Analyze Website'); ?>
                    <button type="button" class="close pull-right" data-dismiss="modal" aria-hidden="true">&times;</button>
                </h4>
                <!--  -->
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card" style="box-shadow:none;margin-bottom:0;">
                            <div class="card-body">
                                <div class="text-center">
                                    <h3><span id="domain_name_show"></span></h3>
                                </div>
                                <div class="text-center" id="domain_success_msg"></div>    

                                <div class="text-center" id="progress_msg">
                                    <span id="domain_progress_msg_text"></span>
                                    <div class="progress" style="display: none;height: 20px;" id="domain_progress_bar_con"> 
                                        <div style="width:3%" class="progress-bar progress-bar-primary progress-bar-striped"><span>1%</span></div>
                                    </div>
                                </div>
                                <div class="text-center"><h2 id="completed_result_link"></h2></div>
                                <div class="row"><div class="col-xs-12" id="completed_function_str"></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer bg-whitesmoke">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo __('close'); ?></button>

            </div>

        </div>
    </div>
</div>

  <!-- PROMO VIDEO MODAL -->
  {{-- <div class="modal fade youtube-video" id="promovideoModal" tabindex="-1" role="dialog" aria-labelledby="promovideoModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-video">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" id="youtubevideo" allowfullscreen></iframe>
                </div>       
            </div>
            <div class="modal-footer">
            </div>
        </div> 
    </div>
  </div> --}}

  <div class="modal fade youtube-video" id="promovideoModal" tabindex="-1" role="dialog" aria-labelledby="promovideoModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="padding: 0 !important">
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" id="youtubevideo" style="width: 100% !important; height:100% !important;" allowfullscreen></iframe>
            </div> 
        </div>
        <div class="modal-footer">
        </div>
      </div>
    </div>
  </div>

<script type="text/javascript">

    var interval="";

    function get_bulk_progress()
    {
        var domain_name1 = $('#website_name').val();
        $.ajax({
            url:base_url+'/home/front_end_bulk_scan_progress_count',
            type:'POST',
            dataType:'json',
            data:{domain_name:domain_name1},
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
            success:function(response){
                var search_complete=response.search_complete;
                var search_total=response.search_total;
                var latest_record=response.latest_record;

                var view_details_button = response.view_details_button;

                $("#domain_progress_msg_text").html(search_complete +" / "+ search_total +" <?php echo __('step completed') ?>");
                $("#completed_function_str").html(response.completed_function_str);
                var width=(search_complete*100)/search_total;
                width=Math.round(width);                    
                var width_per=width+"%";
                if(width<3)
                {
                    $("#domain_progress_bar_con div").css("width","3%");
                    $("#domain_progress_bar_con div span").html("1%");
                }
                else
                {
                    $("#domain_progress_bar_con div").css("width",width_per);
                    $("#domain_progress_bar_con div span").html(width_per);
                }

                if(width==100) 
                {
                    $("#domain_progress_msg_text").html("<?php echo __('completed') ?>");
                    $("#domain_success_msg").html('');
                    $("#completed_result_link").html(response.view_details_button);         
                    clearInterval(interval);
                }         
                
            }
        });
        
    }
    
    
    $(document).ready(function() {
        $('#submit').click(function(e){
            e.preventDefault();
                   
            var domain_name = $('#website_name').val().trim();
            $("#domain_name_show").html(domain_name);

            var reg = /^((?:(?:(?:\w[\.\-\+]?)*)\w)+)((?:(?:(?:\w[\.\-\+]?){0,62})\w)+)\.(\w{2,6})$/i;
            var output = reg.test(domain_name);
            if(output === false)
            {
              swal(global_lang_warning, '<?php echo __('Please provide a domain name in valid format.')?>', global_lang_warning);
              return;
            }

            if(domain_name == '') {
                swal(global_lang_warning, '<?php echo __("you have not entered any domain name") ?>', global_lang_warning);
                return false;
            } else {
                $('#modal_add_domain').modal();             

                $("#domain_progress_bar_con div").css("width","3%");
                // $("#domain_progress_bar_con div").attr("aria-valuenow","3");
                $("#domain_progress_bar_con div span").html("1%");
                $("#domain_progress_msg_text").html("");                
                $("#domain_progress_bar_con").show();               
                interval=setInterval(get_bulk_progress, 10000);
                
                $("#domain_success_msg").html('<img  class="center-block" src="'+base_url+'/assets/pre-loader/loading-animations.gif" width="150px" height="150px" alt="<?php echo __('please wait'); ?>"><br/>');
                $("#completed_result_link").html('');
                
                $.ajax({
                    type:'POST' ,
                    url: base_url+"/home/front_end_website_analysis",
                    data:{domain_name:domain_name},
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
                    },
                    success:function(response){

                        $("#domain_progress_bar_con div").css("width","100%");
                        $("#domain_progress_bar_con div span").html("100%");
                        $("#domain_progress_msg_text").html("<?php echo __('completed') ?>");
                        $("#domain_success_msg").html('');
                        $("#completed_result_link").html(response);
                    }
                }); 
            }
        });
    });

</script>

<script>
    $('.play-button').click(function (e) {
        var videoUrl = $(this).data('url');
        $('#youtubevideo').attr('src', videoUrl);
        $('#promovideoModal').modal('show');
    });

    $('#close-video').click(function (e) {
        $('#youtubevideo').attr('src', '');
    });

    $(document).on('hidden.bs.modal', '#myModal', function () {
        $('#youtubevideo').attr('src', '');
    });
</script>
<!-- ================================== End of Frontend website analysis section ======================================= -->

