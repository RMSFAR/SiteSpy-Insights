<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>{{config('my_config.product_name')}} | @yield('title')</title>
  <link rel="shortcut icon" href="{{ config('my_config.favicon') }}">
  <link rel="stylesheet" href="{{asset('assets/modules/bootstrap/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/modules/bootstrap-social/bootstrap-social.css')}}">
  <link rel="stylesheet" href="{{asset('assets/modules/fontawesome/css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/modules/fontawesome/css/v4-shims.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/components.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
  @include('shared.guest-variables')
  <script src="{{asset('assets/modules/jquery.min.js')}}"></script>
  <script src="{{asset('assets/modules/sweetalert/sweetalert.min.js')}}"></script>
</head>

<body>
  <div id="app">
    <section class="section">
        @yield('content')
    </section>
  </div>
</body>

@include('shared/fb-px')
@include('shared/google-code')

</html>