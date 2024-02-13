{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('My ip information'))
@section('content')

@php 
if($ip_info["status"]!="success") 
{
  $ip_info['city']="";
  $ip_info['country']="";
  $ip_info['postal']="";
  $ip_info['org']="";
  $ip_info['hostname']="";
  $ip_info['region']="";
  $ip_info['latitude']="";
  $ip_info['longitude']="";
}
@endphp

<link rel="stylesheet" href="{{ asset('assets/custom-css/analysis-tools/myipinfo.css') }}">



<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-map-marker-alt"></i> {{ __('My ip information')}}</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="{{route('analysis_tools') }}">{{ __("Analysis")}}</a></div>
      <div class="breadcrumb-item">{{ __('My ip information')}}</div>
    </div>
  </div>
</section>
  

<div class="row">

  <div class="col-12 ">
    <div class="card main_card">
        <div class="card-body">
            <div class="row">
               <div class="col-12 col-md-12">
                

                    
                      <div class="row">
                        <!-- domain name -->
                        <div class="col-12 col-md-4">
                          <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                              <i class="fas fa-bullseye"></i>
                            </div>
                            <div class="card-wrap">
                              <div class="card-header">
                                <h4>{{ __('IP Address')}}</h4>
                              </div>
                              <div class="card-body"><h6 class="mt-2">{{ $my_ip }}</h6></div>
                            </div>
                          </div>
                        </div>

                        <!-- global rank -->
                        <div class="col-12 col-md-4">
                          <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                              <i class="far fa-map"></i>
                            </div>
                            <div class="card-wrap">
                              <div class="card-header">
                                <h4>{{ __('Latitude')}}</h4>
                              </div>
                              <div class="card-body"><h6 class="mt-2">{{ $ip_info["latitude"]}}</h6></div>
                            </div>
                          </div>
                        </div>                        
                        <div class="col-12 col-md-4">
                          <div class="card card-statistic-1">
                            <div class="card-icon bg-info">
                              <i class="far fa-map"></i>
                            </div>
                            <div class="card-wrap">
                              <div class="card-header">
                                <h4>{{ __('Longitude')}}</h4>
                              </div>
                              <div class="card-body"><h6 class="mt-2">{{ $ip_info["longitude"]}}</h6></div>
                            </div>
                          </div>
                        </div>

                        <!-- time on site -->
                        <div class="col-12 col-md-4">
                          <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                              <i class="far fa-building"></i>
                            </div>
                            <div class="card-wrap">
                              <div class="card-header">
                                <h4>{{ __('Organization')}}</h4>
                              </div>
                              <div class="card-body"><h6 class="mt-2">{{ $ip_info["org"]}}</h6></div>
                            </div>
                          </div>
                        </div>
                        
        

                        <!-- bounce rate  -->
                        <div class="col-12 col-md-4">
                          <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                              <i class="fas fa-server"></i>
                            </div>
                            <div class="card-wrap">
                              <div class="card-header">
                                <h4>{{ __('Hostname')}}</h4>
                              </div>
                              <div class="card-body">{{ $ip_info["hostname"]}}</div>
                            </div>
                          </div>
                        </div>

                        <!-- total sites linking -->
                        <div class="col-12 col-md-4">
                          <div class="card card-statistic-1">
                            <div class="card-icon bg-direction">
                              <i class="fas fa-flag"></i>
                            </div>
                            <div class="card-wrap">
                              <div class="card-header">
                                <h4>{{ __('Country')}}</h4>
                              </div>
                              <div class="card-body">{{ $ip_info["country"]}} </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-12 col-md-4">
                          <div class="card card-statistic-1">
                            <div class="card-icon bg-region">
                              <i class="fas fa-chart-area"></i>
                            </div>
                            <div class="card-wrap">
                              <div class="card-header">
                                <h4>{{ __('Region')}}</h4>
                              </div>
                              <div class="card-body">{{ $ip_info["region"]}} </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-12 col-md-4">
                          <div class="card card-statistic-1">
                            <div class="card-icon bg-city">
                             <i class="fas fa-city"></i>
                            </div>
                            <div class="card-wrap">
                              <div class="card-header">
                                <h4>{{ __('City')}}</h4>
                              </div>
                              <div class="card-body">{{ $ip_info["city"]}} </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-12 col-md-4">
                          <div class="card card-statistic-1">
                            <div class="card-icon bg-post">
                              <i class="fas fa-address-card"></i>
                            </div>
                            <div class="card-wrap">
                              <div class="card-header">
                                <h4>{{ __('Postal Code')}}</h4>
                              </div>
                              <div class="card-body">{{ $ip_info["postal"]}} </div>
                            </div>
                          </div>
                        </div>

                      </div>
                    
                 
                </div>
            </div>
        </div>
    </div>          
  </div>


</div>


@endsection