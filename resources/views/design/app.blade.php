<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>{{config('my_config.product_name')}} | @yield('title')</title>
    <link rel="shortcut icon" href="{{ config('my_config.favicon') }}" type="image/x-icon">

    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    @include('design.css')
    @yield('page_css')
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('web/css/components.css')}}"> --}}
    @yield('page_css')


    @yield('css')

   
    @include('design.js')

    @yield('page_js')
    @yield('scripts')

</head>

<body>

    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                @include('design.header')

            </nav>
            <div class="main-sidebar main-sidebar-postion">
                @include('design.sidebar')
            </div>
            <!-- Main Content -->
            <div class="main-content">
                @yield('content')
            </div>
            <footer class="main-footer">
                @include('design.footer')
            </footer>
        </div>
    </div>
    
    @include('profile.edit_profile')
    @include('profile.change_password')

    

</body>


</html>
