<div class="main-footer">
 
   <div class="footer-left">
      &copy; <?php  echo config("my_config.product_short_name")." ";?> <div class="bullet"></div>  <?php echo '<a  href="'.url('/').'">'.config("my_config.product_name").'</a>'; ?>
    </div>
    <div class="footer-right">
  
        {{-- <?php $current_language = isset($language_info->language) ? $language_info->language : __("Language"); ?>
      <a href="#" data-toggle="dropdown" class="dropdown-toggle dropdown-item has-icon d-inline">  <?php echo $current_language; ?></a>
      <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
        <li class="dropdown-title"><?php echo __("Switch Language"); ?></li>
        <?php 
        foreach ($language_info as $key => $value) 
        {
          $selected='';
          // if($key==$this->session->userdata("facebook_rx_fb_user_info")) $selected='active';
          echo '<li><a href="" data-id="'.$key.'" class="dropdown-item language_switch '.$selected.'">'.$value.'</a></li>';
        } 
        ?>
      </ul> --}}
  
      v<?php echo $APP_VERSION;?>
    </div>
