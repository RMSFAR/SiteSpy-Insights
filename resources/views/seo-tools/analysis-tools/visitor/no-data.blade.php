{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Visitor analysis report'))
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


<section class="section">
	<div class="section-header">
		<h1><i class="fas fa-chart-line"></i> {{ __('Visitor analysis report')}}</h1>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><a href="{{route("analysis_tools")}}">{{ __("Analysis Tools")}}</a></div>
			<div class="breadcrumb-item active"><a href="{{route('visitor_analysis')}}">{{ __("Visitor Analytics")}}</a></div>
			<div class="breadcrumb-item">{{ __('Report')}}</div>
		</div>
	</div>

	<div class="section-body">
		<div class="card">
			<div class="card-header">
				<h4><i class="fas fa-ban"></i> {{ __('Not found')}}</h4>
			</div>
			<div class="card-body">
				<div class="empty-state" data-height="600" style="height: 600px;">
					<img class="img-fluid" src="{{ asset('assets/img/drawkit/drawkit-nature-man-colour.svg') }}" alt="image">
					<h2 class="mt-0">{{ __('Looks like you got lost')}}</h2>
					<p class="lead">
						{{ __('We could not find any data associated with this account.')}}
					</p>
					<a href="{{route('visitor_analysis')}}" class="btn btn-warning mt-4">{{ __('Back')}}</a>
				</div>
			</div>
		</div>
	</div>
</section>


@endsection