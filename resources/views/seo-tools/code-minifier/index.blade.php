{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Code minifier'))

@section('content')
    
    <section class="section">
        <div class="section-header">
            <h1><i class="fa fa-cogs"></i><?php echo __('Code minifier')?></h1>
            <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><?php echo __('Code minifier')?></div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
            <div class="col-lg-6">
                <div class="card card-large-icons">
                    <div class="card-icon text-primary">
                        <i class="fab fa-html5"></i>
                    </div>
                    <div class="card-body">
                        <h4><?php echo __("Html minifier"); ?></h4>
                        <p><?php echo __("Minified HTML Files."); ?></p>
                        <a href="{{route('html_minifier')}}" class="card-cta"><?php echo __("Actions"); ?><i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card card-large-icons">
                    <div class="card-icon text-primary">
                        <i class="fab fa-css3-alt"></i>
                    </div>
                    <div class="card-body">
                        <h4><?php echo __("Css minifier"); ?></h4>
                        <p><?php echo __("Minified CSS Files."); ?></p>
                        <a href="{{route('css_minifier')}}" class="card-cta"><?php echo __("Actions"); ?><i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card card-large-icons">
                    <div class="card-icon text-primary">
                        <i class="fab fa-js"></i>
                    </div>
                    <div class="card-body">
                        <h4><?php echo __("Js minifier"); ?></h4>
                        <p><?php echo __("Minified JS Files."); ?></p>
                        <a href="{{route('js_minifier')}}" class="card-cta"><?php echo __("Actions"); ?><i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>


            </div>
        </div>
    </section>
    
@endsection