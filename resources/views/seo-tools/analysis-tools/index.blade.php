{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Analysis tools'))
@section('content')

<section class="section">
    <div class="section-header">
      <h1><i class="fa fa-chart-bar"></i> <?php echo __('Analysis tools')?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><?php echo __('Analysis tools')?></div>
      </div>
    </div>
  
    <div class="section-body">
      <div class="row">
        <?php if(Auth::user()->user_type == 'Admin' || in_array(1,$module_access)) : ?>
        <div class="col-lg-6">
          <div class="card card-large-icons">
            <div class="card-icon text-primary">
              <i class="fas fa-users"></i>
            </div>
            <div class="card-body">
              <h4><?php echo __("Visitor Analytics"); ?></h4>
              <p><?php echo __("Visitor analytics is for analyzing own sites."); ?></p>
              <a href="{{route('visitor_analysis')}}" class="card-cta"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
            </div>
          </div>
        </div>
        <?php endif; ?>
  
        <?php if(Auth::user()->user_type == 'Admin' || in_array(2,$module_access)) : ?>
        <div class="col-lg-6">
          <div class="card card-large-icons">
            <div class="card-icon text-primary">
              <i class="fas fa-globe"></i>
            </div>
            <div class="card-body">
              <h4><?php echo __("Website Analysis"); ?></h4>
              <p><?php echo __("Web analysis is for analyzing any website."); ?></p>
              <a href="{{route('website_analysis')}}" class="card-cta"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
            </div>
          </div>
        </div>
        <?php endif; ?>
  
        <?php if(Auth::user()->user_type == 'Admin' || in_array(3,$module_access)) : ?>
        <div class="col-lg-6">
          <div class="card card-large-icons">
            <div class="card-icon text-primary">
              <i class="fas fa-share-square"></i>
            </div>
            <div class="card-body">
              <h4><?php echo __("Social Network Analysis"); ?></h4>
              <p><?php echo __("Social Network Analysis Crawls social activities of a website."); ?></p>
              <a href="{{route('social_network_analysis_index')}}" class="card-cta"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
            </div>
          </div>
        </div>
        <?php endif; ?>
  
        <?php if(Auth::user()->user_type == 'Admin' || in_array(4,$module_access)) : ?>
        <div class="col-lg-6">
            <div class="card card-large-icons">
                <div class="card-icon text-primary"><i class="fas fa-trophy"></i></div>
                <div class="card-body">
                    <h4><?php echo __("Rank & Index Analysis"); ?></h4>
                    <p><?php echo __("Alexa Rank, Alexa Data, Social network analysis, Moz Check"); ?></p>
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" class="no_hover" style="font-weight: 500;"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                        <div class="dropdown-menu">
                            <div class="dropdown-title"><?php echo __("Tools"); ?></div>                        
                            <a class="dropdown-item has-icon" href="{{route('moz_rank_index')}}"><i class="fas fa-plug"></i> <?php echo __("Moz Check"); ?></a>
                            <a class="dropdown-item has-icon" href="{{route('search_engine_index')}}"><i class="fas fa-plug"></i> <?php echo __("Search Engine Index"); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
  
        <?php if(Auth::user()->user_type == 'Admin' || in_array(5,$module_access)) : ?>
        <div class="col-lg-6">
            <div class="card card-large-icons">
                <div class="card-icon text-primary"><i class="fas fa-server"></i></div>
                <div class="card-body">
                    <h4><?php echo __("Domain Analysis"); ?></h4>
                    <p><?php echo __("Whois, Auction Domain, DNS, Server Information."); ?></p>
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" class="no_hover" style="font-weight: 500;"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                        <div class="dropdown-menu">
                            <div class="dropdown-title"><?php echo __("Tools"); ?></div>                        
                            <a class="dropdown-item has-icon" href="{{route('who_is_index')}}"><i class="fas fa-plug"></i> <?php echo __("Whois Search"); ?></a>
                            <a class="dropdown-item has-icon" href="{{route('expired_domain_index')}}"><i class="fas fa-plug"></i> <?php echo __("Auction Domain List"); ?></a>
                            <a class="dropdown-item has-icon" href="{{route('dns_info_index')}}"><i class="fas fa-plug"></i> <?php echo __("DNS Information"); ?></a>
                            <a class="dropdown-item has-icon" href="{{route('server_info_index')}}"><i class="fas fa-plug"></i> <?php echo __("Server Information"); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
  
        <?php if(Auth::user()->user_type == 'Admin' || in_array(7,$module_access)) : ?>
        <div class="col-lg-6">
            <div class="card card-large-icons">
                <div class="card-icon text-primary"><i class="fas fa-anchor"></i></div>
                <div class="card-body">
                    <h4><?php echo __("Link Analysis"); ?></h4>
                    <p><?php echo __("Whois, Auction Domain, DNS, Server Information."); ?></p>
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" class="no_hover" style="font-weight: 500;"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                        <div class="dropdown-menu">
                            <div class="dropdown-title"><?php echo __("Tools"); ?></div>                        
                            <a class="dropdown-item has-icon" href="{{route('link_analysis_index')}}"><i class="fas fa-plug"></i> <?php echo __("Link Analyzer"); ?></a>
                            <a class="dropdown-item has-icon" href="{{route('page_status_index')}}"><i class="fas fa-plug"></i> <?php echo __("Page Status Check"); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
  
        <?php if(Auth::user()->user_type == 'Admin' || in_array(6,$module_access)) : ?>
        <div class="col-lg-6">
            <div class="card card-large-icons">
                <div class="card-icon text-primary"><i class="fas fa-map-marker-alt"></i></div>
                <div class="card-body">
                    <h4><?php echo __("IP Analysis"); ?></h4>
                    <p><?php echo __("IP Address,organization, Region, City, Postal Code, Country."); ?></p>
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" class="no_hover" style="font-weight: 500;"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                        <div class="dropdown-menu">
                            <div class="dropdown-title"><?php echo __("Tools"); ?></div>                        
                            <a class="dropdown-item has-icon" href="{{route('ip_analysis')}}"><i class="fas fa-plug"></i> <?php echo __("My IP Information"); ?></a>
                            <a class="dropdown-item has-icon" href="{{route('domain_info_index')}}"><i class="fas fa-plug"></i> <?php echo __("Domain IP Information"); ?></a>
                            <a class="dropdown-item has-icon" href="{{route('site_this_ip')}}"><i class="fas fa-plug"></i> <?php echo __("Sites in Same IP"); ?></a>
                            <a class="dropdown-item has-icon" href="{{route('ipv6_check')}}"><i class="fas fa-plug"></i> <?php echo __("Ipv6 Compability Check"); ?></a>
                            <a class="dropdown-item has-icon" href="{{route('ip_canonical_check')}}"><i class="fas fa-plug"></i> <?php echo __("IP Canonical Check"); ?></a>
                            <a class="dropdown-item has-icon" href="{{route('ip_traceout')}}"><i class="fas fa-plug"></i> <?php echo __("IP Traceroute"); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
  
        <?php if(Auth::user()->user_type == 'Admin' || in_array(8,$module_access)) : ?>
        <div class="col-lg-6">
            <div class="card card-large-icons">
                <div class="card-icon text-primary"><i class="fas fa-tags"></i></div>
                <div class="card-body">
                    <h4><?php echo __("Keyword Analysis"); ?></h4>
                    <p><?php echo __("analyze h1, h2, h3, h4, h5, h6 content, Single, 2 phrase, 3 phrase, 4 phrase keywords."); ?></p>
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" class="no_hover" style="font-weight: 500;"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                        <div class="dropdown-menu">
                            <div class="dropdown-title"><?php echo __("Tools"); ?></div>                        
                            <a class="dropdown-item has-icon" href="{{route('keyword_analyzer')}}"><i class="fas fa-plug"></i> <?php echo __("Keyword Analyzer"); ?></a>
                            <a class="dropdown-item has-icon" href="{{route('keyword_index')}}"><i class="fas fa-plug"></i> <?php echo __("Position Analysis"); ?></a>
                            <a class="dropdown-item has-icon" href="{{route('keyword_suggestion')}}"><i class="fas fa-plug"></i> <?php echo __("Auto Suggestion"); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
  
        {{-- <?php  if($this->basic->is_exist("modules",array("id"=>84))) {  ?> --}}
        <?php  if(Auth::user()->user_type == 'Admin' || in_array(84,$module_access)) {  ?>
        {{-- <div class="col-lg-6">
            <div class="card card-large-icons">
                <div class="card-icon text-primary"><i class="fas fa-stethoscope"></i></div>
                <div class="card-body">
                    <h4><?php echo __("SiteDoctor"); ?></h4>
                    <p><?php echo __("Website Health Checker"); ?></p>
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" class="no_hover" style="font-weight: 500;"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                        <div class="dropdown-menu">
                            <div class="dropdown-title"><?php echo __("Tools"); ?></div>                        
                            <a class="dropdown-item has-icon" href="{{route('checked_website_lists')}}"><i class="fas fa-stethoscope"></i> <?php echo __("Check Website Health"); ?></a>
                            <a class="dropdown-item has-icon" href="{{route('comparative_check_report')}}"><i class="fas fa-compress-arrows-alt"></i> <?php echo __("Check Comparitive Health"); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <?php } ?>
        {{-- <?php } ?> --}}
      
  
      </div>
    </div>
  </section>

@endsection