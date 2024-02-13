{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Native API'))
@section('content')

<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-tasks"></i> <?php echo __('Native API'); ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo __("System"); ?></div>      
      <div class="breadcrumb-item"><?php echo __('Native API'); ?></div>
    </div>
  </div>

  {{-- <?php $this->load->view('admin/theme/message'); ?> --}}
	@include('shared.message')

  <div class="section-body">
  	<div class="row">
      <div class="col-12">
      	<div class="card">
	                  
		  	<?php
			$text= __("Generate API Key");
			$get_key_text=__("Get Your API Key");
			if(isset($api_key) && $api_key!="")
			{
				$text=__("Re-generate API Key");
				$get_key_text=__("Your API Key");
			}
			if($is_demo=='1') $api_key='xxxxxxxxxxxxxxxxxxxxxxxxxx'; 
			?>

			<form class="form-horizontal" enctype="multipart/form-data" action="<?php echo url('/').'/native_api/get_api_action';?>" method="GET">
				@csrf
        <div class="card-header">
		            <h4><i class="fas fa-key"></i> <?php echo $get_key_text; ?></h4>
		          </div>
		          <div class="card-body">
		            <h4><?php echo $api_key; ?></h4>
		            <?php if($api_key=="") echo __("Every cron url must contain the API key for authentication purpose. Generate your API key to see the cron job list."); ?>
		          </div>
		          <div class="card-footer bg-whitesmoke">
		          	<button type="submit" name="button" class="btn btn-primary btn-lg btn <?php if($is_demo=='1') echo 'disabled';?>"><i class="fas fa-redo"></i> <?php echo $text; ?></button>
		          </div>
		        </div>
		    </form>
        <?php $call_sync_contact_url=url("/native_api/sync_contact"); ?>


			<?php
			if($api_key!="") 
			{ ?>
				<div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo __("Get Content Overview Data (Your website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo __('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo url('/');?>/native_api/get_content_overview_data?api_key=<?php echo $api_key; ?>&domain_code=XXXXXX</span></code></pre>
                    <br>
                    <?php $example_url=url('/')."/native_api/get_content_overview_data?api_key=".$api_key."&domain_code=2022261578919857-1";?>
                    <?php echo __('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo __('Example Response (JSON):'); ?> <br>
                     {""total_view_for_this_domain":0,"content_overview_data":[]"}
                  </div>
                </div>
          

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> 
                      <?php echo __("Get Overview Data (Your website)");?>
                     </h4>
                  </div>
                  <div class="card-body">
                    <?php echo __('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo url('/');?>/native_api/get_overview_data?api_key=<?php echo $api_key; ?>&domain_code=XXXXXX</span></code></pre>
                    <br>
                    <?php $example_url=url('/')."/native_api/get_overview_data?api_key=".$api_key."&domain_code=36180644";?>
                    <?php echo __('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo __('Example Response (JSON):'); ?> <br>
                     {"total_page_view":"0","total_unique_visitro":"0","average_visit":"0","average_stay_time":"0:0:0","bounce_rate":0}
                  </div>
                </div>

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo __("Facebook Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo __('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo url('/');?>/native_api/facebook_ckeck?api_key=<?php echo $api_key; ?>&domain=EXAMPLE.COM</span></code></pre>
                    <br>
                    <?php $example_url=url('/')."/native_api/facebook_ckeck?api_key=".$api_key."&domain=http://www.xeroneit.net";?>
                    <?php echo __('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo __('Example Response (JSON):'); ?> <br>
                     {"status":"1","details":"Success","total_share":583,"total_reaction":841,"total_comment":96,"total_comment_plugin":0}
                  </div>
                </div> 

                {{-- <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo __("Linkedin Check (any website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo __('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo url('/');?>/native_api/linkedin_check?api_key=<?php echo $api_key; ?>&domain=EXAMPLE.COM</span></code></pre>
                    <br>
                   <?php $example_url=url('/')."/native_api/linkedin_check?api_key=".$api_key."&domain=http://www.xeroneit.net";?>
                    <?php echo __('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo __('Example Response (JSON):'); ?> <br>
                    {"status":"1","details":"Success","total_share":0}
                  </div>
                </div>   --}}

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo __("Xing Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo __('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo url('/');?>/native_api/xing_check?api_key=<?php echo $api_key; ?>&domain=EXAMPLE.COM</span></code></pre>
                    <br>
                   <?php $example_url=url('/')."/native_api/xing_check?api_key=".$api_key."&domain=http://www.facebook.com";?>
                    <?php echo __('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo __('Example Response (JSON):'); ?> <br>
                    {"status":"1","details":"Success","total_share":"0 "}
                  </div>
                </div> 

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo __("Reddit Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo __('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo url('/');?>/native_api/reddit_check?api_key=<?php echo $api_key; ?>&domain=EXAMPLE.COM</span></code></pre>
                    <br>
                   <?php $example_url=url('/')."/native_api/reddit_check?api_key=".$api_key."&domain=http://www.xeroneit.net";?>
                    <?php echo __('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo __('Example Response (JSON):'); ?> <br>
                    {"status":"1","details":"Success","score":0,"downs":0,"ups":0}
                  </div>
                </div>

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo __("Pinterest Check (Any Website))");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo __('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo url('/');?>/native_api/pinterest_check?api_key=<?php echo $api_key; ?>&domain=EXAMPLE.COM</span></code></pre>
                    <br>
                   <?php $example_url=url('/')."/native_api/pinterest_check?api_key=".$api_key."&domain=http://www.facebook.com";?>
                    <?php echo __('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo __('Example Response (JSON):'); ?> <br>
                    {"status":"1","details":"Success","pinterest_pin":0}
                  </div>
                </div> 

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo __("Buffer Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo __('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo url('/');?>/native_api/buffer_check?api_key=<?php echo $api_key; ?>&domain=EXAMPLE.COM</span></code></pre>
                    <br>
                   <?php $example_url=url('/')."/native_api/buffer_check?api_key=".$api_key."&domain=http://www.facebook.com";?>
                    <?php echo __('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo __('Example Response (JSON):'); ?> <br>
                    {"status":"1","details":"Success","buffer_share":0}
                  </div>
                </div> 

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo __("Page Status Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo __('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo url('/');?>/native_api/pagestatus_check?api_key=<?php echo $api_key; ?>&domain=EXAMPLE.COM</span></code></pre>
                    <br>
                   <?php $example_url=url('/')."/native_api/pagestatus_check?api_key=".$api_key."&domain=http://www.xeroneit.net";?>
                    <?php echo __('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo __('Example Response (JSON):'); ?> <br>
                    {"status":"1","details":"Success","http_code":200,"total_time":12.293,"namelookup_time":0.124,"connect_time":0.14,"speed_download":6102}
                  </div>
                </div> 

                {{-- <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo __("Alexa Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo __('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo url('/');?>/native_api/alexa_check?api_key=<?php echo $api_key; ?>&domain=EXAMPLE.COM</span></code></pre>
                    <br>
                   <?php $example_url=url('/')."/native_api/alexa_check?api_key=".$api_key."&domain=http://www.xeroneit.net";?>
                    <?php echo __('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo __('Example Response (JSON):'); ?> <br>
                    {"reach_rank":"515095","country":"Egypt","country_rank":"16776","traffic_rank":"429248"}
                  </div>
                </div> --}}

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo __("SimilarWeb Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo __('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo url('/');?>/native_api/similar_web_check?api_key=<?php echo $api_key; ?>&domain=EXAMPLE.COM</span></code></pre>
                    <br>
                   <?php $example_url=url('/')."/native_api/similar_web_check?api_key=".$api_key."&domain=http://www.facebook.com";?>
                    <?php echo __('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                  </div>
                </div> 

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo __("Bing Index Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo __('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo url('/');?>/native_api/bing_index_check?api_key=<?php echo $api_key; ?>&domain=EXAMPLE.COM</span></code></pre>
                    <br>
                   <?php $example_url=url('/')."/native_api/bing_index_check?api_key=".$api_key."&domain=http://www.xeroneit.net";?>
                    <?php echo __('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo __('Example Response:'); ?> <br>
                    {9}
                  </div>
                </div>

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo __("Yahoo Index Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo __('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo url('/');?>/native_api/yahoo_index_check?api_key=<?php echo $api_key; ?>&domain=EXAMPLE.COM</span></code></pre>
                    <br>
                   <?php $example_url=url('/')."/native_api/yahoo_index_check?api_key=".$api_key."&domain=http://www.xeroneit.net";?>
                    <?php echo __('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo __('Example Response:'); ?> <br>
                    {9}
                  </div>
                </div> 

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo __("Link Analysis Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo __('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo url('/');?>/native_api/link_analysis_check?api_key=<?php echo $api_key; ?>&domain=EXAMPLE.COM</span></code></pre>
                    <br>
                    <?php $example_url=url('/')."/native_api/link_analysis_check?api_key=".$api_key."&domain=http://www.xeroneit.net";?>
                    <?php echo __('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                  </div>
                </div> 

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo __("Backlink Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo __('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo url('/');?>/native_api/backlink_check?api_key=<?php echo $api_key; ?>&domain=EXAMPLE.COM</span></code></pre>
                    <br>
                   <?php $example_url=url('/')."/native_api/backlink_check?api_key=".$api_key."&domain=http://www.xeroneit.net";?>
                    <?php echo __('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo __('Example Response:'); ?> <br>
                    {9}
                  </div>
                </div>

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo __("Google Safe Browser Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo __('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo url('/');?>/native_api/google_malware_check?api_key=<?php echo $api_key; ?>&domain=EXAMPLE.COM</span></code></pre>
                    <br>
                   <?php $example_url=url('/')."/native_api/google_malware_check?api_key=".$api_key."&domain=http://www.facebook.com";?>
                    <?php echo __('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo __('Example Response:'); ?> <br>
                    {"safe"}
                  </div>
                </div>      
                
                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo __("McAfee Malware Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo __('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"> <?php echo url('/');?>/native_api/macafee_malware_check?api_key=<?php echo $api_key; ?>&domain=EXAMPLE.COM</span></code></pre>
                    <br>
                   <?php $example_url=url('/')."/native_api/macafee_malware_check?api_key=".$api_key."&domain=http://www.facebook.com";?>
                    <?php echo __('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo __('Example Response:'); ?> <br>
                    {"safe"}
                  </div>
                </div>

               {{-- <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo __("AVG Malware Check (any website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo __('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"> <?php echo url('/');?>/native_api/avg_malware_check?api_key=<?php echo $api_key; ?>&domain=EXAMPLE.COM</span></code></pre>
                    <br>
                   <?php $example_url=url('/')."/native_api/avg_malware_check?api_key=".$api_key."&domain=http://www.facebook.com";?>
                    <?php echo __('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo __('Example Response:'); ?> <br>
                    {"safe"}
                  </div>
                </div>  --}}

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo __("Norton Malware Check (Any Website)");?></h4>
                  </div>
                  <div class="card-body">
                    <?php echo __('API HTTP URL:'); ?>
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"> <?php echo url('/');?>/native_api/norton_malware_check?api_key=<?php echo $api_key; ?>&domain=EXAMPLE.COM</span></code></pre>
                    <br>
                   <?php $example_url=url('/')."/native_api/norton_malware_check?api_key=".$api_key."&domain=http://www.facebook.com";?>
                    <?php echo __('Example API HTTP URL:'); ?> <br>
                    <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                    <?php echo __('Example Response:'); ?> <br>
                    {"safe"}
                  </div>
                </div>

               <div class="card">
                 <div class="card-header">
                   <h4><i class="fas fa-circle"></i> <?php echo __("Domain IP Check (Any Website)");?></h4>
                 </div>
                 <div class="card-body">
                   <?php echo __('API HTTP URL:'); ?>
                   <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"> <?php echo url('/');?>/native_api/domain_ip_check?api_key=<?php echo $api_key; ?>&domain=EXAMPLE.COM</span></code></pre>
                   <br>
                  <?php $example_url=url('/')."/native_api/domain_ip_check?api_key=".$api_key."&domain=http://www.xeroneit.net";?>
                   <?php echo __('Example API HTTP URL:'); ?> <br>
                   <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                   <?php echo __('Example Response (JSON):'); ?> <br>
                   {"isp":"GoDaddy.com, LLC","ip":"166.62.28.90","city":"Scottsdale","region":"Arizona","country":" United States","time_zone":"America\/Phoenix","longitude":"-111.890600","latitude":"33.611900"}
                 </div>
               </div> 
               <div class="card">
                 <div class="card-header">
                   <h4><i class="fas fa-circle"></i> <?php echo __("Sites in Same IP Check (Any Website)");?></h4>
                 </div>
                 <div class="card-body">
                   <?php echo __('API HTTP URL:'); ?>
                   <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"> <?php echo url('/');?>/native_api/sites_in_same_ip_check?api_key=<?php echo $api_key; ?>&ip=XXX.XXX.XXX.XXX</span></code></pre>
                   <br>
                  <?php $example_url=url('/')."/native_api/sites_in_same_ip_check?api_key=".$api_key."&ip=192.64.112.13";?>
                   <?php echo __('Example API HTTP URL:'); ?> <br>
                   <a target="_BLANK" href="<?php echo $example_url;?>"><?php echo $example_url;?></a> <br>
                   <?php echo __('Example Response (JSON):'); ?> <br>
                   {"twitter.com"}
                 </div>
               </div> 

			<?php }?>
	  </div>
	</div>
  </div>
</section>

@endsection