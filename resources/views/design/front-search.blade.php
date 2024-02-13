<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{config('my_config.product_name')}} | @yield('title')</title>
        @include('design.css')
        @include('design.js')
        <link rel="shortcut icon" href="{{ config('my_config.favicon') }}" type="image/x-icon">
   
    </head>


    <body class="bg-light">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 header-sticky">
                    <div class="container" >
                        <a href="<?php echo url('/'); ?>">
                            <img src="{{ config('my_config.logo') }}" style="height:60%;margin-top:12px;" alt="{{ config('my_config.product_name') }}" class="img-responsive">
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container body-div bg-light">
            <!-- page content -->
            @yield('content')
            <!-- page content --> 
        </div>

        <footer id="footer" class='sticky_bottom' style="padding-top: 30px; padding-bottom: 30px; color: #fff; background: #002240;">
            <div class="container-fluid text-center">
                <div class="row">
                    <div class="col-12">             
                        <?php echo  config('my_config.product_name') ." ".config("product_version").' - <a target="_BLANK" href="'.url('/').'"><b>'.config("my_config.institute_address").'</b></a>'; ?> 
                    </div>
                </div>
            </div>
        </footer>

    </body>
</html>
<style>
    .body-div {
        padding-top: 80px;
    }
    .header-sticky {
        height: 80px !important;
        background: #fff !important;
        position: fixed !important;
        top: 0px !important;
        bottom: 0 !important;
        z-index: 1000 !important;
        box-shadow: 0px 2px 3px #d2d2d2;
    }
    .card.card-statistic-1 .card-icon i { line-height:80px !important; }
</style>