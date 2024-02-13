{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Keyword position report'))
@section('content')
  
<link rel="stylesheet" href="{{ asset('assets/custom-css/seo-tools/keyword-tracking/position-report.css') }}">


<section class="section">
	<div class="section-header">
	  <h1><i class="fa fa-bars"></i> <?php echo __('Keyword position report');?></h1>
	  <div class="section-header-breadcrumb">
		<div class="breadcrumb-item"><a href="{{route('keyword_tracking')}}"><?php echo __("Keyword Tracking");?></a></div>
		<div class="breadcrumb-item"><?php echo __('Keyword position report');?></div>
	  </div>
	</div>
</section>
  
  <div class="row multi_layout">
  
	  <div class="col-12 col-md-3 collef">
		  <div class="card main_card">
			  <form  method="POST" enctype="multipart/form-data"  id="kewyord_position_report_form">
				@csrf
				  <div class="card-header">
					  <h4><i class="fa fa-info-circle"></i> <?php echo __('Keyword Information'); ?></h4>
				  </div>
  
				  <div class="card-body">
					<div class="form-group">
						<label><?php echo __('Keyword'); ?></label>
						@php 
							$select_note['']= __("Select keyword");
							$keywordss= $select_note+$keywords;
							echo Form::select('keywords',$keywordss,old('keyword'),array('class'=>'form-control select2','id'=>'keywords'));
						@endphp	
					</div>

					  <div class="form-group">
						  <label><?php echo __('From'); ?></label>
						  <input type="text" class="form-control datepicker_x" id="from_date" placeholder="<?php echo __('from date'); ?>" style="width:100%;">
					  </div>
  
					  <div class="form-group">
						  <label><?php echo __('To'); ?></label>
						  <input type="text" class="form-control datepicker_x" id="to_date" placeholder="<?php echo __('to date'); ?>" style="width:100%;">
					  </div>
				  </div>
  
				  <div class="card-footer bg-whitesmoke mt-4">
					  <button type="button" id="start_searching" class="btn btn-primary "><i class="fas fa-search"></i> <?php echo __("Search"); ?></button>
					  <button class="btn btn-secondary btn-md float-right" onclick="goBack('keyword_tracking/keyword_position_report')" type="button"><i class="fa fa-remove"></i> <?php echo __('Cancel'); ?></button>
				  </div>
			  </form>
		  </div>          
	  </div>
  
	  <div class="col-12 col-md-9 colmid">
		  <div id="unique_per"></div>
		  <div class="card shadow-none">
			  <div class="card-header">
				  <h4> <i class="fas fa-bars"></i> <?php echo __('Keyword Position Report'); ?></h4>
			  </div>
  
			  <div class="card-body">
				  <div id="custom_spinner"></div>
				  <div id="middle_column_content" style="background: #ffffff!important;">
					  <div class="col-12 col-sm-6 col-md-6 col-lg-12 bck_clr" id="nodata">
						  <div class="empty-state">
							  <img class="img-fluid" src="{{asset("assets/img/drawkit/revenue-graph-colour.svg")}}" style="height: 300px" alt="image">
						  </div>
  
					  </div>
				  </div>
			  </div>
		  </div>
	  </div>
  </div>


<script>
	"use strict";
	var position_report = '{{route("keyword_position_report_data")}}';
	var Back = '{{route("keyword_position_report")}}';
	var lang1 = '{{__('warning')}}';
	var lang2 = '{{__('Please fill all required fields.')}}';
	var lang3 = '{{__('Please wait for while...')}}';
</script>
  
<script src="{{ asset('assets/custom-js/seo-tools/keyword-tracking/position-report.js') }}"></script>
  



@endsection