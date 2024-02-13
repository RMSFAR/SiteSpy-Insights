{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Utilities'))
@section('content')

    <section class="section">
        <div class="section-header">
        <h1><i class="fa fa-ellipsis-h"></i> <?php echo __("Utilities"); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><?php echo __("Utilities"); ?></div>
        </div>
        </div>
    
        <div class="section-body">
        <div class="row">
            <?php if(Auth::user()->user_type == 'Admin' || in_array(13,$module_access)) : ?>
            <div class="col-lg-6">
            <div class="card card-large-icons">
                <div class="card-icon text-primary">
                <i class="fas fa-at"></i>
                </div>
                <div class="card-body">
                <h4><?php echo __("Email Encoder/Decoder"); ?></h4>
                <p><?php echo __("Email Encode, Decode, Csv Download"); ?></p>
                <a href="{{route('email_encoder_decoder')}}" class="card-cta"><?php echo __("Actions") ?><i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
            </div>
    
            <div class="col-lg-6">
            <div class="card card-large-icons">
                <div class="card-icon text-primary">
                <i class="fas fa-tags"></i>
                </div>
                <div class="card-body">
                <h4><?php echo __("Metatag Generator"); ?></h4>
                <p><?php echo __("Metatag Generator Facebook, Google, Twitter"); ?></p>
                <a href="{{route("meta_tag_list")}}" class="card-cta"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
            </div>
            <?php endif; ?>
            <?php if(Auth::user()->user_type == 'Admin' || in_array(12,$module_access)) : ?>
            <div class="col-lg-6">
            <div class="card card-large-icons">
                <div class="card-icon text-primary">
                <i class="fas fa-language"></i>
                </div>
                <div class="card-body">
                <h4><?php echo __("Plagiarism Check"); ?></h4>
                <p><?php echo __("Plagiarism Checker, files"); ?></p>
                <a href="{{route("plagarism_check_list")}}" class="card-cta"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
            </div> 
            <div class="col-lg-6">
                <div class="card card-large-icons">
                    <div class="card-icon text-primary">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="card-body">
                        <h4><?php echo __("Word Count"); ?></h4>
                        <p><?php echo __("Count no. words and characters"); ?></p>
                        <a href="{{route("word_count")}}" class="card-cta"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div> 
            <?php endif; ?>
            <?php if(Auth::user()->user_type == 'Admin' || in_array(13,$module_access)) : ?>
            <div class="col-lg-6">
            <div class="card card-large-icons">
                <div class="card-icon text-primary">
                <i class="fas fa-envelope"></i>
                </div>
                <div class="card-body">
                <h4><?php echo __("Valid Email Check"); ?></h4>
                <p><?php echo __("Email Checker, Files"); ?></p>
                <a href="{{route("valid_email_check")}}" class="card-cta"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
            </div>
    
            <div class="col-lg-6">
            <div class="card card-large-icons">
                <div class="card-icon text-primary">
                <i class="fas fa-envelope-square"></i>
                </div>
                <div class="card-body">
                <h4><?php echo __("Duplicate Email Filter"); ?></h4>
                <p><?php echo __("Email Filter, Files"); ?></p>
                <a href="{{route("duplicate_email_filter_list")}}" class="card-cta"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
            </div>
    
    
            <div class="col-lg-6">
            <div class="card card-large-icons">
                <div class="card-icon text-primary">
                <i class="fas fa-link"></i>
                </div>
                <div class="card-body">
                <h4><?php echo __("URL Encoder/Decoder"); ?></h4>
                <p><?php echo __("Link Encode, Decode"); ?></p>
                <a href="{{route("url_encode_list")}}" class="card-cta"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
            </div>      
            <div class="col-lg-6">
            <div class="card card-large-icons">
                <div class="card-icon text-primary">
                <i class="fas fa-external-link-square-alt"></i>
                </div>
                <div class="card-body">
                <h4><?php echo __("URL Canonical Check"); ?></h4>
                <p><?php echo __("URL Canonical Checker, Files"); ?></p>
                <a href="{{route("url_canonical_check")}}" class="card-cta"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
            </div>      
            <div class="col-lg-6">
            <div class="card card-large-icons">
                <div class="card-icon text-primary">
                <i class="fas fa fa-file-archive"></i>
                </div>
                <div class="card-body">
                <h4><?php echo __("Gzip Check"); ?></h4>
                <p><?php echo __("Gzip Checker, files"); ?></p>
                <a href="{{route("gzip_check")}}" class="card-cta"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
            </div>      
            <div class="col-lg-6">
            <div class="card card-large-icons">
                <div class="card-icon text-primary">
                <i class="fab fa-centercode"></i>
                </div>
                <div class="card-body">
                <h4><?php echo __("Base64 Encoder/Decoder"); ?></h4>
                <p><?php echo __("Base64 Encode, Decode, files"); ?></p>
                <a href="{{route("base64_encode_list")}}" class="card-cta"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
            </div>      
            <div class="col-lg-6">
            <div class="card card-large-icons">
                <div class="card-icon text-primary">
                <i class="fas fa-file-code"></i>
                </div>
                <div class="card-body">
                <h4><?php echo __("Robot Code Generator"); ?></h4>
                <p><?php echo __("Code Generator, files"); ?></p>
                <a href="{{route("robot_code_generator")}}" class="card-cta"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
            </div>

            <div class="col-lg-6">
                <div class="card card-large-icons">
                    <div class="card-icon text-primary">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <div class="card-body">
                        <h4><?php echo __("Sitemap Generator"); ?></h4>
                        <p><?php echo __("Sitemap Generator, Xml Download"); ?></p>
                        <a href="{{route("sitemap_generator")}}" class="card-cta"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-large-icons">
                    <div class="card-icon text-primary">
                        <i class="fas fa-adjust"></i>
                    </div>
                    <div class="card-body">
                        <h4><?php echo __("Website comparison"); ?></h4>
                        <p><?php echo __("Social existency (share, like, comment...)"); ?></p>
                        <a href="{{route("comparision")}}" class="card-cta"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
    
    
        </div>
        </div>
    </section>
  

@endsection