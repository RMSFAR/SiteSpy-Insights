{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Bitly URL shortener'))
@section('content')

<link rel="stylesheet" href="{{asset('assets/custom-css/url-shortner/bitly-index.css')}}">

<section class="section section_custom">
	<div class="section-header">
		<h1><i class="fa fa-cut"></i> {{ __('Bitly URL shortener')}}</h1>
		<div class="section-header-button">
			<a class="btn btn-primary" href="{{route("bitly")}}">
				<i class="fas fa-plus-circle"></i> {{ __("New Shortener")}}
			</a> 
		</div>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><a href="{{route('url_shortner')}}">{{ __('URL Shortner')}}</a></div>
			<div class="breadcrumb-item">{{ __('Bitly URL shortener')}}</div>
		</div>
	</div>

	<div class="section-body">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body data-card">
						<div class="row">
							<div class="col-md-9 col-12">
								<div class="input-group mb-3 float-left" id="searchbox">

									<input type="text" class="form-control" id="searching" name="searching"  autocomplete="false" placeholder="{{ __('Search...')}}" aria-label="" aria-describedby="basic-addon2">
									<div class="input-group-append">
										<button class="btn btn-primary" id="search_submit" title="{{ __('Search')}}" type="button"><i class="fas fa-search"></i> <span class="d-none d-sm-inline">{{ __('Search')}}</span></button>


									</div>
									<div class="btn-group dropright float-right ml-10">
										<button type="button" class="btn btn-primary btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  {{ __('Options')}}  </button>  
										<div class="dropdown-menu dropright" x-placement="left-start" style="position: absolute; transform: translate3d(-202px, 5px, 0px); top: 0px; left: 0px; will-change: transform;"> 
											<a class="dropdown-item has-icon download pointer" id="download_btn"><i class="fa fa-cloud-download-alt"></i> {{ __('Download Selected')}}</a> 
											<a class="dropdown-item has-icon downlaod" id="download_btn_all"><i class="fa fa-cloud-download-alt"></i> {{ __('Download All')}}</a>
											<a target="_BLANK" class="dropdown-item has-icon delete" id="delete_btn"><i class="fa fa-trash"></i> {{ __('Delete Selected')}}</a>
											<a target="_BLANK" class="dropdown-item has-icon delete" id="delete_btn_all"><i class="fa fa-trash"></i>{{ __('Delete All')}}</a>
										</div> 
									</div>

								</div>

							</div>
							<div class="col-md-3 col-12">
								<a href="javascript:;" id="post_date_range" class="btn btn-primary btn-lg float-right icon-left btn-icon"><i class="fas fa-calendar"></i> {{ __("Choose Date")}}</a><input type="hidden" id="post_date_range_val">
							</div>
						</div>
						<div class="table-responsive2">
							<table class="table table-bordered" id="mytable">
								<thead>
									<tr>
										<th>#</th> 
										<th style="vertical-align:middle;width:20px">
											<input class="regular-checkbox" id="datatableSelectAllRows" type="checkbox"/><label for="datatableSelectAllRows"></label>        
										</th> 
										<th>{{ __("ID")}}</th>            
										<th>{{ __("Long URL")}}</th>         
										<th>{{ __("Short URL")}}</th>         
										<th>{{ __("Short URL ID")}}</th>                 
										<th>{{ __("Analytics")}}</th>                 
										<th>{{ __("Created At")}}</th>

									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>             
					</div>
				</div>
			</div>
		</div>

	</div>
</section> 

<script>
	"use strict"
	var url_shortener_data = '{{route("url_shortener_data")}}';
	var short_url_download = '{{route("short_url_download")}}';
	var short_url_delete = '{{route("short_url_delete")}}';
	var Your_Bitly_URL_shortener_data_has_been_deleted_successfully = '{{__("Your Bitly URL shortener data has been deleted successfully.")}}';
	var Your_All_Bitly_URL_shortener_data_has_been_deleted_successfully = '{{__("Your All Bitly URL shortener data has been deleted successfully.")}}';
	
</script>

<script src="{{asset('assets/custom-js/url-shortner/bitly-index.js')}}"></script>




<div class="modal fade show" id="bitly_url_shortener_download_selected">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="background: #fefefe;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-cut"></i> {{ __('Bitly URL Shortener')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div id="custom_spinner"></div>
      <div class="modal-body text-center" id="total_download_selected"> 
       
      </div>
      
    </div>
  </div>
</div>


@endsection