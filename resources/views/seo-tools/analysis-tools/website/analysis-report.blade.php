{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Website analysis'))

@section('content')


<style>
	.box-card .card-statistic-1{border:.5px solid #dee2e6;border-radius: 4px;}
	.box-card .card-icon{ border: .5px solid #dee2e6; }
	.bg-body {background: #FAFDFB !important;}
	.social_shared_icon{ width: 40px;height: 10px; }
	.color_codes_div .media { border-bottom: 0; }
	.bg-direction { background-color: #a45fff !important; }
	.font-12 { font-size:12px !important; }
</style>

<input type="hidden" id="domain_id" value="{{ $id }}"/>

<section class="section">
	<div class="section-header">
		<h1><i class="fas fa-globe"></i> {{ __('Website analysis')}}</h1>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><a href="{{ route("analysis_tools")}}">{{ __("Analysis Tools")}}</a></div>
			<div class="breadcrumb-item active"><a href="{{ route('website_analysis')}}">{{ __("Website Analysis")}}</a></div>
			<div class="breadcrumb-item">{{ __('Report')}}</div>
		</div>
	</div>

	<div class="section-body">
		<div class="card">

			<div class="card-header">
				<h4><i class="fas fa-globe"></i> @php echo "Domain Name - <span class='red'>".$domain_info[0]->domain_name."</span>"@endphp</h4>
				<div class="card-header-action">
					<a href="{{ url('website_analysis/download_analysis_report/'.$id)}}" class="btn btn-lg btn-primary" title="{{ __('Download Report as Pdf')}}"><i class="fas fa-cloud-download-alt"></i> {{ __('Download Report')}}</a>
				</div>
			</div>
			
			<div class="card-body">
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">{{ __('General')}}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="social_network-tab" data-toggle="tab" href="#social_network" role="tab" aria-controls="social_network" aria-selected="false">{{ __('Social Network Information')}}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="meta_tag_info-tab" data-toggle="tab" href="#meta_tag_info" role="tab" aria-controls="meta_tag_info" aria-selected="false">{{ __('Keyword & Meta Information')}}</a>
					</li>
					{{-- <li class="nav-item">
						<a class="nav-link" id="alexa_info-tab" data-toggle="tab" href="#alexa_info" role="tab" aria-controls="alexa_info" aria-selected="false">{{ __('Alexa Information')}}</a>
					</li> --}}
				</ul>
				<div class="tab-content tab-bordered" id="myTab3Content">
					<div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
						@include('seo-tools.analysis-tools.website.general') 
					</div>
					<div class="tab-pane fade" id="social_network" role="tabpanel" aria-labelledby="social_network-tab">
						@include("seo-tools.analysis-tools.website.social-network") 
					</div>
					<div class="tab-pane fade" id="meta_tag_info" role="tabpanel" aria-labelledby="meta_tag_info-tab">
						@include("seo-tools.analysis-tools.website.meta-tag-info") 
					</div>
					{{-- <div class="tab-pane fade" id="alexa_info" role="tabpanel" aria-labelledby="alexa_info-tab">
						@include('seo-tools.analysis-tools.website.alexa-info') 	
					</div> --}}

				</div>
			</div>

		</div>
	</div>
</section>


<script>
	"use strict";
	
	var pre_loader = '{{ asset('assets/img/pre-loader/full-screenshots.gif') }}';


</script>

<script src="{{asset('assets/custom-js/analysis-tools/website-analysis-report.js')}}"></script>




@endsection




