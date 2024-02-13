@extends('design.app')
@section('title', __('Sitemap generator'))
@section('content')

    <section class="section">
        <div class="section-header">
            <h1><i class="fas fa-sitemap"></i></i> {{ __('Sitemap generator') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('utilities') }}">{{ __('Utilities') }}</a></div>
                <div class="breadcrumb-item">{{ __('Sitemap generator') }}</div>
            </div>
        </div>
    </section>


    <div class="row multi_layout">

        <div class="col-12 col-md-5 col-lg-5 collef">
            <div class="card main_card">
                <div class="card-header">
                    <h4><i class="fas fa-info-circle"></i> {{ __('Info') }}</h4>
                </div>
                <form method="POST" enctype="multipart/form-data" id="new_search_form">
                    @csrf


                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label"> {{ __('Domain') }} <code>*</code> <a href="#"
                                    data-placement="top" data-toggle="popover" data-trigger="focus"
                                    title="{{ __('Domain') }}"
                                    data-content='{{ __('Put your domain name with https:// Example: https://example.com') }}'><i
                                        class='fa fa-info-circle'></i> </a></label>

                            <input id="domain_name" name="domain_name" class="form-control" style="width:100%">
                        </div>
                        <div class="form-group">
                            <label class="form-label"> {{ __('Search Depth') }} <code>*</code> <a href="#"
                                    data-placement="top" data-toggle="popover" data-trigger="focus"
                                    title="{{ __('Search Depth') }}"
                                    data-content='{{ __('Enter How deep you want to generate Sitemap. Example: 0 for level 1 , 1 for level 2.') }}'>
                                    <i class='fa fa-info-circle'></i> </a>
                            </label>

                            <input type="number" id="depth_size" name="depth_size" class="form-control" style="width:100%">
                        </div>


                    </div>

                    <div class="card-footer bg-whitesmoke mt-42">

                        <button type="button" id="new_search_button" class="btn btn-primary ">{{ __('Analysis') }}</button>
                        <button class="btn btn-secondary btn-md float-right" onclick="goBack('menu_loader/utlities')"
                            type="button"><i class="fa fa-remove"></i> {{ __('Cancel') }}</button>



                    </div>

                </form>
            </div>
        </div>

        <div class="col-12 col-md-7 col-lg-7 colmid">
            <div id="custom_spinner"></div>
            <div id="unique_per">

            </div>
            <div id="middle_column_content" style="background: #ffffff!important;">

                <div class="card">
                    <div class="card-header">
                        <h4> <i class="fas fa-sitemap"></i> {{ __('SiteMap') }}</h4>

                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-12 bck_clr" id="nodata">

                    <div class="empty-state">
                        <img class="img-fluid" src="{{ asset('assets/img/drawkit/revenue-graph-colour.svg') }}"
                            style="height: 300px" alt="image">


                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        "use strict";

        var Please_Enter_Domain_Name = '{{ __('Please Enter Domain Name') }}';

        var sitemap_action = '{{ route('sitemap_generator_action') }}';
    </script>

    <script>

    </script>
    <script src="{{asset('assets/custom-js/utilities/sitemap.js')}}"></script>

@endsection
