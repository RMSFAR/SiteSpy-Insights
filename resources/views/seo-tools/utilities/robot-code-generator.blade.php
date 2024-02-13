{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Robot code generator'))
@section('content')

<section class="section section_custom">
	<div class="section-header">
		<h1><i class="fas fa-robot"></i> {{ __('Robot code generator')}}</h1>
		<div class="section-header-breadcrumb">

      		<div class="breadcrumb-item"><a href="{{route('utilities') }}">{{ __("Utilities")}}</a></div>
			<div class="breadcrumb-item">{{ __('Robot code generator')}}</div>
		</div>
	</div>

	<div class="section-body">
		<div class="row">
			<div class="col-12">
				<div class="card main_card">

					<div class="card-body">
						<form class="form-horizontal" enctype="multipart/form-data"  method="POST" >
                            @csrf
							<div class="row">
								<div class="form-group col-12 col-md-4" style="padding: 10px;">
									<label> {{ __('Default -  All Robots are')}}</label>
									<select  class="form-control select2 select2-hidden-accessible" id="basic_all_robots" style="width:100%;" tabindex="-1" aria-hidden="true">
										<option value="allowed" selected>{{ __('Allowed')}}</option>
										<option value="refused">{{ __('Refused')}}</option>       
										
									</select>
                                    @if ($errors->has('basic_all_robots'))
									<code class="text-danger">{{ $errors->first('basic_all_robots') }}</code>
									@endif


								</div>  
								<div class="form-group col-12 col-md-4" style="padding: 10px;">
									<label>{{ __('Crawl-Delay')}} </label>
									<select  class="form-control select2 select2-hidden-accessible" id="crawl_delay" style="width:100%;" tabindex="-1" aria-hidden="true">
										<option value="0" selected>{{ __('Default- No delay')}}</option>
										<option value="5">{{ __('5 Seconds')}}</option>                
										<option value="10">{{ __('10 Seconds')}}</option>                
										<option value="20">{{ __('20 Seconds')}}</option>                
										<option value="60">{{ __('60 Seconds')}}</option>                
										<option value="120" >{{ __('120 Seconds')}}</option>     
										
									</select>
                                    @if ($errors->has('crawl_delay'))
									<code class="text-danger">{{ $errors->first('crawl_delay') }}</code>
									@endif                     
								</div>
								<div class="form-group col-12 col-md-4" style="padding: 10px;">
									<label>{{ __('Sitemap')}}</label>
									  <input type="text" name="site_map" id="site_map" class="form-control" placeholder="{{ __('Leave Blank for none')}}">

								</div>
								<div class="form-group col-12 col-md-4">
								  <div class="custom-control custom-checkbox">
								      <input type="checkbox" class="custom-control-input" id="do_u_want_to_more_specific_robot" value="1" name="do_u_want_to_more_specific_robot">
								      <label class="custom-control-label" for="do_u_want_to_more_specific_robot">{{ __('Do You Want To More Specific Search Robots?')}}</label>
								    </div>
								</div>
				
							</div> 

							<div class="row" id="custom_setting" style="display: none;">
								<div class="form-group col-12 col-md-4">
									<label>{{ __('Crawl-Delay')}} </label>
									<select  class="form-control select2 select2-hidden-accessible" id="custom_crawl_delay" style="width:100%;" tabindex="-1" aria-hidden="true">
										<option value="0" selected>{{ __('Default- No delay')}}</option>
										<option value="5">{{ __('5 Seconds')}}</option>                
										<option value="10">{{ __('10 Seconds')}}</option>                
										<option value="20">{{ __('20 Seconds')}}</option>                
										<option value="60">{{ __('60 Seconds')}}</option>                
										<option value="120" >{{ __('120 Seconds')}}</option>     
										
									</select>
                                    @if ($errors->has('custom_crawl_delay'))
									<code class="text-danger">{{ $errors->first('custom_crawl_delay') }}</code>
									@endif                    
								</div>
								<div class="form-group col-12 col-md-4">
									<label>{{ __('Sitemap')}}</label>
									<input type="text" class="form-control" id="custom_site_map" placeholder="{{ __('Leave Blank for none')}}">
                                    @if ($errors->has('custom_site_map'))
									<code class="text-danger">{{ $errors->first('custom_site_map') }}</code>
									@endif
								</div>
								<div class="form-group col-12 col-md-4">
									<label>{{ __('Google')}} </label>
									<select  class="form-control select2 select2-hidden-accessible" id="google" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected>{{ __('Allowed')}}</option>
										<option value="refused">{{ __('Refused')}}</option>      
										
									</select>
                                    @if ($errors->has('google'))
									<code class="text-danger">{{ $errors->first('google') }}</code>
									@endif                    
								</div>
								<div class="form-group col-12 col-md-4">
									<label>{{ __('MSN Search')}} </label>
									<select  class="form-control select2 select2-hidden-accessible" id="msn_search" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected>{{ __('Allowed')}}</option>
										<option value="refused">{{ __('Refused')}}</option>      
										
									</select>
                                    @if ($errors->has('msn_search'))
									<code class="text-danger">{{ $errors->first('msn_search') }}</code>
									@endif                    
								</div>
								<div class="form-group col-12 col-md-4">
									<label>{{ __('Yahoo')}} </label>
									<select  class="form-control select2 select2-hidden-accessible" id="yahoo" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected>{{ __('Allowed')}}</option>
										<option value="refused">{{ __('Refused')}}</option>      
										
									</select>
                                    @if ($errors->has('yahoo'))
									<code class="text-danger">{{ $errors->first('yahoo') }}</code>
									@endif                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label>{{ __('Ask/Teoma')}} </label>
									<select  class="form-control select2 select2-hidden-accessible" id="ask_teoma" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected>{{ __('Allowed')}}</option>
										<option value="refused">{{ __('Refused')}}</option>      
										
									</select>
                                    @if ($errors->has('ask_teoma'))
									<code class="text-danger">{{ $errors->first('ask_teoma') }}</code>
									@endif                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label>{{ __('Cuil')}} </label>
									<select  class="form-control select2 select2-hidden-accessible" id="cuil" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected>{{ __('Allowed')}}</option>
										<option value="refused">{{ __('Refused')}}</option>      
										
									</select>
                                    @if ($errors->has('cuil'))
									<code class="text-danger">{{ $errors->first('cuil') }}</code>
									@endif                     
								</div>
								<div class="form-group col-12 col-md-4">
									<label>{{ __('GigaBlast')}} </label>
									<select  class="form-control select2 select2-hidden-accessible" id="gigablast" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected>{{ __('Allowed')}}</option>
										<option value="refused">{{ __('Refused')}}</option>      
										
									</select>
									@if ($errors->has('gigablast'))
									<code class="text-danger">{{ $errors->first('gigablast') }}</code>
									@endif                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label>{{ __('Scrub The Web')}} </label>
									<select  class="form-control select2 select2-hidden-accessible" id="scrub" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected>{{ __('Allowed')}}</option>
										<option value="refused">{{ __('Refused')}}</option>      
										
									</select>
									@if ($errors->has('scrub'))
									<code class="text-danger">{{ $errors->first('scrub') }}</code>
									@endif                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label>{{ __('DMOZ Checker')}} </label>
									<select  class="form-control select2 select2-hidden-accessible" id="dmoz_checker" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected>{{ __('Allowed')}}</option>
										<option value="refused">{{ __('Refused')}}</option>      
										
									</select>
									@if ($errors->has('dmoz_checker'))
									<code class="text-danger">{{ $errors->first('dmoz_checker') }}</code>
									@endif                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label>{{ __('Nutch')}} </label>
									<select  class="form-control select2 select2-hidden-accessible" id="nutch" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected>{{ __('Allowed')}}</option>
										<option value="refused">{{ __('Refused')}}</option>      
										
									</select>
									@if ($errors->has('nutch'))
									<code class="text-danger">{{ $errors->first('nutch') }}</code>
									@endif                    
								</div>								
								<div class="form-group col-12 col-md-4">
									<label>{{ __('Alexa/Wayback')}} </label>
									<select  class="form-control select2 select2-hidden-accessible" id="alexa_wayback" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected>{{ __('Allowed')}}</option>
										<option value="refused">{{ __('Refused')}}</option>      
										
									</select>
									@if ($errors->has('alexa_wayback'))
									<code class="text-danger">{{ $errors->first('alexa_wayback') }}</code>
									@endif                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label>{{ __('Baidu')}} </label>
									<select  class="form-control select2 select2-hidden-accessible" id="baidu" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected>{{ __('Allowed')}}</option>
										<option value="refused">{{ __('Refused')}}</option>      
										
									</select>
									@if ($errors->has('baidu'))
									<code class="text-danger">{{ $errors->first('baidu') }}</code>
									@endif                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label>{{ __('Naver')}} </label>
									<select  class="form-control select2 select2-hidden-accessible" id="never" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected>{{ __('Allowed')}}</option>
										<option value="refused">{{ __('Refused')}}</option>      
										
									</select>
									@if ($errors->has('never'))
									<code class="text-danger">{{ $errors->first('never') }}</code>
									@endif                     
								</div>
							</div>
							
							<div class="row" id="custom_setting2" style="display: none;">
						
								<div class="card-header">
									<div class="section-title ">{{ __('Specific Special Bots')}}</div>
								</div>
								<div class="form-group col-12 col-md-4">
									<label>{{ __('Google Image')}} </label>
									<select  class="form-control select2 select2-hidden-accessible" id="google_image" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected>{{ __('Allowed')}}</option>
										<option value="refused">{{ __('Refused')}}</option>      
										
									</select>
									@if ($errors->has('google_image'))
									<code class="text-danger">{{ $errors->first('google_image') }}</code>
									@endif                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label>{{ __('Google Mobile')}} </label>
									<select  class="form-control select2 select2-hidden-accessible" id="google_mobile" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected>{{ __('Allowed')}}</option>
										<option value="refused">{{ __('Refused')}}</option>      
										
									</select>
									@if ($errors->has('google_mobile'))
									<code class="text-danger">{{ $errors->first('google_mobile') }}</code>
									@endif                     
								</div>								
								<div class="form-group col-12 col-md-4">
									<label>{{ __('Yahoo MM')}} </label>
									<select  class="form-control select2 select2-hidden-accessible" id="yahoo_mm" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected>{{ __('Allowed')}}</option>
										<option value="refused">{{ __('Refused')}}</option>      
										
									</select>
									@if ($errors->has('yahoo_mm'))
									<code class="text-danger">{{ $errors->first('yahoo_mm') }}</code>
									@endif                    
								</div>								
								<div class="form-group col-12 col-md-4">
									<label>{{ __('MSN Picture Search')}} </label>
									<select  class="form-control select2 select2-hidden-accessible" id="msn_picsearch" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected>{{ __('Allowed')}}</option>
										<option value="refused">{{ __('Refused')}}</option>      
										
									</select>
									@if ($errors->has('msn_picsearch'))
									<code class="text-danger">{{ $errors->first('msn_picsearch') }}</code>
									@endif                      
								</div>								
								<div class="form-group col-12 col-md-4">
									<label>{{ __('Singing Fish')}} </label>
									<select  class="form-control select2 select2-hidden-accessible" id="singing_fish" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected>{{ __('Allowed')}}</option>
										<option value="refused">{{ __('Refused')}}</option>      
										
									</select>
									@if ($errors->has('singing_fish'))
									<code class="text-danger">{{ $errors->first('singing_fish') }}</code>
									@endif                      
								</div>								
								<div class="form-group col-12 col-md-4">
									<label>{{ __('Yahoo Blogs')}} </label>
									<select  class="form-control select2 select2-hidden-accessible" id="yahoo_blogs" style="width:100%;" tabindex="-1" aria-hidden="true">

										<option value="allowed" selected>{{ __('Allowed')}}</option>
										<option value="refused">{{ __('Refused')}}</option>      
										
									</select>
									@if ($errors->has('yahoo_blogs'))
									<code class="text-danger">{{ $errors->first('yahoo_blogs') }}</code>
									@endif                    
								</div>
								

							</div>

							<div class="row" id="custom_setting3" style="display: none;">
								<div class="card-header">
									<div class="section-title">{{ __('Restricted Directories')}}
									</div>
								</div>
								<div class="float-right">
									
									<div class="form-group col-12">
										<button type="button" id="btn2" class="btn btn-primary"><i class='fa fa-plus-circle'></i> {{ __('Add Directories')}}</button>
									</div>
								</div>
								<div class="form-group col-12">
									<label>{{ __('Directory')}} </label>
									<input type="text" class="form-control" id ="restricted_dir0"  placeholder="{{ __('Eg. /temp/img/')}}" name="directory[]">                 
								</div>
								
		
							
							</div>

							<div class="text-center">
								<button class="btn btn-lg btn-primary" id="generate_robot_code" type="button"><i class="fas fa-robot"></i> {{ __('Generate Robot Code')}}</button>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@php
    $id = Auth::user()->id;    
    $time = date("Ymd");  
    $link = asset('download/robot/robot_'.$id.'_'.$time.'.txt');

@endphp

<script>     
    "use strict" 
    var Your_file_is_ready_download = '{{ __('Your file is ready download') }}';
	var download= '{{ __('download') }}';
    var link = '{{$link}}';
    var robot_code_generator_action = '{{route("robot_code_generator_action")}}';

</script>
  
  
<script src="{{asset('assets/custom-js/utilities/robotCode.js')}}"></script>





<div class="modal fade show" id="set_auto_comment_templete_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="background: #fefefe;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-robot"></i> {{ __('Robot Code Generated')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <code aria-hidden="true">×</code>
        </button>
      </div>
      
      <div class="modal-body text-center" id="unique_email_download_div"> 
       
      </div>
      
    </div>
  </div>
</div>




@endsection


