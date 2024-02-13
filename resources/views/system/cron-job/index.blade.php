{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Cron Job'))
@section('content')

<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-tasks"></i> <?php echo __('Cron Job'); ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo __("System"); ?></div>      
      <div class="breadcrumb-item"><?php echo __('Cron Job'); ?></div>
    </div>
  </div>

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

			<form class="form-horizontal" enctype="multipart/form-data" action="<?php echo url('/').'/cron_job/get_api_action';?>" method="GET">
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


			<?php
			if($api_key!="") 
			{ ?>
				<div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo __("Send Notification");?> <code><?php echo __("Once/Day"); ?></code></h4>
                  </div>
                  <div class="card-body">
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo "curl ".url("/cron_job/send_notification")."/".$api_key." >/dev/null 2>&1"; ?></span></code></pre>
                  </div>
                </div>
          

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> 
                      <?php echo __("Auction Domain");?>
                      <code><?php echo __("Once/Day"); ?></code></h4>
                  </div>
                  <div class="card-body">
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo "curl ".url("/cron_job/auction_domain")."/".$api_key." >/dev/null 2>&1"; ?></span></code></pre>
                  </div>
                </div>

                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo __("Keyword Rank Tracking");?> <code><?php echo __("Once/15 minutes"); ?></code></h4>
                  </div>
                  <div class="card-body">
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo "curl ".url("/cron_job/get_keyword_position_data")."/".$api_key." >/dev/null 2>&1"; ?></span></code></pre>
                  </div>
                </div>                
                
                <div class="card">
                  <div class="card-header">
                    <h4><i class="fas fa-circle"></i> <?php echo __("Junk Data Delete");?> <code><?php echo __("Once/Day"); ?></code></h4>
                  </div>
                  <div class="card-body">
                    <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo "curl ".url("/cron_job/delete_junk_files")."/".$api_key." >/dev/null 2>&1"; ?></span></code></pre>
                  </div>
                </div> 

			<?php }?>
	  </div>
	</div>
  </div>
</section>

@endsection