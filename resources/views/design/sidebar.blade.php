<div class="main-sidebar">
<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="{{ url('/') }}"><img class="navbar-brand-full app-header-logo mt-4 mb-4" src="{{ config('my_config.logo') }}" width="180"
          alt="Infyom Logo"></a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ url('/') }}" class="small-sidebar-text">
            <img class="navbar-brand-full" src="{{ config('my_config.favicon') }}" width="45px" alt=""/>
        </a>
    </div>

    <ul class="sidebar-menu">
        <li class="menu-header">&nbsp;</li>

        <?php
        $admin_double_level2=array('admin/activity_log','payment/accounts','payment/earning_summary','payment/transaction_log');
        $all_links=array();
        foreach($menus as $single_menu) 
        {              
            $menu_html= '';
            $only_admin = $single_menu->only_admin;
            $only_member = $single_menu->only_member; 
            $module_access = explode(',', $single_menu->module_access);
            $module_access = array_filter($module_access);
            $color = $single_menu->color ?? 'var(--blue)';

            // if($single_menu->url=='social_apps/index' && $single_menu->only_member=='1' && $this->config->item('backup_mode')==='0' && Auth::user()->user_type=='Member') continue; // static condition not to
            if($single_menu->header_text!='') $menu_html .= '<li class="menu-header">'.__($single_menu->header_text).'</li>';

            $extraText='';
            if($single_menu->add_ons_id!='0' && config('app.is_demo')=='1') $extraText=' <label class="label label-warning" style="font-size:9px;padding:4px 3px;">Addon</label>';

            if($single_menu->have_child=='1') 
            {
              $dropdown_class1="nav-item dropdown";
              $dropdown_class2="has-dropdown";
            }
            else 
            {
              $dropdown_class1="";
              $dropdown_class2="";
            }
            if($single_menu->is_external=='1') $site_url1=""; else $site_url1=url('').'/'; // if external link then no need to add site_url()
            if($single_menu->is_external=='1') $parent_newtab=" target='_BLANK'"; else $parent_newtab=''; // if external link then open in new tab
            $color_css = $single_menu->url != 'social_accounts/index' ? "background: -webkit-linear-gradient(270deg,".$color.",".adjustBrightness($color,-0.65).");-webkit-background-clip: text;-webkit-text-fill-color: transparent;" : "color:".$color;
            $menu_html .= "<li class='".$dropdown_class1."'><a {$parent_newtab} href='".$site_url1.$single_menu->url."' class='nav-link ".$dropdown_class2."'><i class= '".$single_menu->icon."' style='".$color_css."'></i> <span>".__($single_menu->name).$extraText."</span></a>"; 

            array_push($all_links, $site_url1.$single_menu->url);  

            if(isset($menu_child_1_map[$single_menu->id]) && count($menu_child_1_map[$single_menu->id]) > 0)
            {
              $menu_html .= '<ul class="dropdown-menu">';
              foreach($menu_child_1_map[$single_menu->id] as $single_child_menu)
              {                  
                  $only_admin2 = $single_child_menu->only_admin;
                  $only_member2 = $single_child_menu->only_member; 
                  $color2 = $single_child_menu->color ?? '';
                  if(empty($color2)) $color2 = $color;

                  // if(Auth::user()->user_type == 'Admin' && session()->get('license_type') != 'double' && in_array($single_child_menu->url, $admin_double_level2)) continue;
                  if(Auth::user()->user_type == 'Admin' && check_build_version() != 'double' && in_array($single_child_menu->url, $admin_double_level2)) continue;

                  if(($only_admin2 == '1' && Auth::user()->user_type == 'Member') || ($only_member2 == '1' && Auth::user()->user_type == 'Admin')) 
                  continue;

                  if($single_child_menu->is_external=='1') $site_url2=""; else $site_url2=url('/').'/'; // if external link then no need to add site_url()
                  if($single_child_menu->is_external=='1') $child_newtab=" target='_BLANK'"; else $child_newtab=''; // if external link then open in new tab

                  if($single_child_menu->have_child=='1') $second_menu_href = '';
                  else $second_menu_href = "href='".$site_url2.$single_child_menu->url."'";

                  $module_access2 = explode(',', $single_child_menu->module_access);
                  $module_access2 = array_filter($module_access2);

                  
                  $hide_second_menu = '';
                  if(Auth::user()->user_type != 'Admin' && !empty($module_access2) && count(array_intersect($module_access, $module_access2))==0) $hide_second_menu = 'hidden';

                  // echo "<pre>"; print_r($module_access2); exit;
                  
                  $menu_html .= "<li class='".$hide_second_menu."'><a {$child_newtab} {$second_menu_href} class='nav-link'><i  style='color:".$color2."' class='".$single_child_menu->icon."'></i>".__($single_child_menu->name)."</a>";

                  array_push($all_links, $site_url2.$single_child_menu->url);

                  if(isset($menu_child_2_map[$single_child_menu->id]) && count($menu_child_2_map[$single_child_menu->id]) > 0)
                  {
                    $menu_html .= "<ul class='dropdown-menu2'>";
                    foreach($menu_child_2_map[$single_child_menu->id] as $single_child_menu_2)
                    { 
                      $only_admin3 = $single_child_menu_2->only_admin;
                      $only_member3 = $single_child_menu_2->only_member;
                      if(($only_admin3 == '1' && Auth::user()->user_type == 'Member') || ($only_member3 == '1' && Auth::user()->user_type == 'Admin'))
                        continue;
                      if($single_child_menu_2->is_external=='1') $site_url3=""; else $site_url3=url('/'); // if external link then no need to add site_url()
                      if($single_child_menu_2->is_external=='1') $child2_newtab=" target='_BLANK'"; else $child2_newtab=''; // if external link then open in new tab   

                      $menu_html .= "<li><a {$child2_newtab} href='".$site_url3.$single_child_menu_2->url."' class='nav-link'><i class='".$single_child_menu_2->icon."'></i> ".__($single_child_menu_2->name)."</a></li>";

                      array_push($all_links, $site_url3.$single_child_menu_2->url);
                    }
                    $menu_html .= "</ul>";
                  }
                  $menu_html .= "</li>";
              }
              $menu_html .= "</ul>";
            }

            $menu_html .= "</li>";
            
            if($only_admin == '1') 
            {
              if(Auth::user()->user_type == 'Admin') 
              echo $menu_html;
            }
            else if($only_member == '1') 
            {
              if(Auth::user()->user_type == 'Member') 
              echo $menu_html;
            }
            else 
            {
              if(Auth::user()->user_type=="Admin" || empty($module_access) || count(array_intersect($module_access, $module_access))>0 ) 
              echo $menu_html;
            }             
        }

        // if(session()->get('license_type') == 'double' && Auth::user()->user_type=='Member')
        if(check_build_version()=='double' && Auth::user()->user_type=='Member')
        {
          echo'
          <li class="menu-header">'.__("Payment").'</li>
          <li class="nav-item dropdown">
            <a href="#" class="nav-link has-dropdown"><i class="fa fa-coins"></i> <span>'.__("Payment").'</span></a>
            <ul class="dropdown-menu">
              <li class=""><a href="'.url("/payment/buy_package").'" class="nav-link"><i class="fa fa-cart-plus"></i>'.__("Renew Package").'</a></li>
              <li class=""><a href="'.url("/payment/transaction_log").'" class="nav-link"><i class="fa fa-history"></i>'.__("Transaction Log").'</a></li>
              <li class=""><a href="'.url("/payment/usage_history").'" class="nav-link"><i class="fa fa-user-clock"></i>'.__("Usage Log").'</a></li>
            </ul>
          </li>
          ';
        }
      ?>
    </ul>

    <?php
      if(check_build_version()=='double'){
        if(config('my_config.enable_support') == '1')
          {
            $support_menu = __("Support Desk");
            $support_icon = "fa fa-headset";
            $support_url = url('/simplesupport/tickets');
            
            echo '
            <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
              <a href="'.$support_url.'" class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="'.$support_icon.'"></i> '.$support_menu.'
              </a>
            </div>';
          }
      }
    ?>

</aside>

</div>

<?php 
$all_links=array_unique($all_links);
$unsetkey = array_search (url('/').'#', $all_links); 
if($unsetkey!=FALSE)
unset($all_links[$unsetkey]); // removing links without a real url

/* 
links that are not in database [custom link = sibebar parent]
No need to add a custom link if it's parent is controller/index
*/
$custom_links=array
(
  url("admin/general_settings")=>url("/admin/settings"),
  url("admin/frontend_settings")=>url("/admin/settings"),
  url("admin/smtp_settings")=>url("/admin/settings"),
  url("admin/email_template_settings")=>url("/admin/settings"),
  url("admin/analytics_settings")=>url("/admin/settings"),
  url("admin/advertisement_settings")=>url("/admin/settings"),
  url("social_apps/google_settings")=>url("/social_apps/settings"),
  url("social_apps/facebook_settings")=>url("/social_apps/settings"),
  url("admin/add_user")=>url("/admin/user_manager"),
  url("admin/edit_user")=>url("/admin/user_manager"),
  url("admin/login_log")=>url("/admin/user_manager"),
  url("payment/add_package")=>url("/payment/package_manager"),
  url("payment/update_package")=>url("/payment/package_manager"),
  url("payment/details_package")=>url("/payment/package_manager"),
  url("announcement/add")=>url("/announcement/full_list"),
  url("announcement/edit")=>url("/announcement/full_list"),
  url("announcement/details")=>url("/announcement/full_list"),
  url("addons/upload")=>url("/addons/lists"),
  url("themes/upload") => url("/themes/lists"),

  url("visitor_analysis/index") => url("/menu_loader/analysis_tools"),
  url("visitor_analysis/domain_details") => url("/menu_loader/analysis_tools"),
  url("website_analysis") => url("/menu_loader/analysis_tools"),
  url("website_analysis/index") => url("/menu_loader/analysis_tools"),
  url("website_analysis/analysis_report") => url("/menu_loader/analysis_tools"),
  url("social/social_list") => url("/menu_loader/analysis_tools"),
  url("social/analysis_new") => url("/menu_loader/analysis_tools"),
  url("rank/alexa_rank") => url("/menu_loader/analysis_tools"),
  url("rank/rank_alexa") => url("/menu_loader/analysis_tools"),
  url("rank/alexa_rank_full") => url("/menu_loader/analysis_tools"),
  url("rank/alexa_data") => url("/menu_loader/analysis_tools"),
  url("rank/moz_rank") => url("/menu_loader/analysis_tools"),
  url("rank/moz_rank_analysis") => url("/menu_loader/analysis_tools"),
  url("search_engine_index/index") => url("/menu_loader/analysis_tools"),
  url("search_engine_index/search_engine") => url("/menu_loader/analysis_tools"),
  url("who_is/index") => url("/menu_loader/analysis_tools"),
  url("who_is/who_is") => url("/menu_loader/analysis_tools"),
  url("expired_domain/index") => url("/menu_loader/analysis_tools"),
  url("dns_info/index") => url("/menu_loader/analysis_tools"),
  url("server_info/index") => url("/menu_loader/analysis_tools"),
  url("link_analysis/index") => url("/menu_loader/analysis_tools"),
  url("link_analysis/analysis_new") => url("/menu_loader/analysis_tools"),
  url("page_status/index") => url("/menu_loader/analysis_tools"),
  url("page_status/analysis_new") => url("/menu_loader/analysis_tools"),
  url("ip/index") => url("/menu_loader/analysis_tools"),
  url("ip/domain_info") => url("/menu_loader/analysis_tools"),
  url("ip/analysis_new") => url("/menu_loader/analysis_tools"),
  url("ip/site_this_ip") => url("/menu_loader/analysis_tools"),
  url("ip/sites_same_ip") => url("/menu_loader/analysis_tools"),
  url("ip/ipv6_check") => url("/menu_loader/analysis_tools"),
  url("ip/check_ipv6") => url("/menu_loader/analysis_tools"),
  url("ip/ip_canonical_check") => url("/menu_loader/analysis_tools"),
  url("ip/ip_traceout") => url("/menu_loader/analysis_tools"),
  url("keyword/keyword_analyzer") => url("/menu_loader/analysis_tools"),
  url("keyword/index") => url("/menu_loader/analysis_tools"),
  url("keyword/position_keyword") => url("/menu_loader/analysis_tools"),
  url("keyword/keyword_suggestion") => url("/menu_loader/analysis_tools"),
  url("keyword/auto_keyword") => url("/menu_loader/analysis_tools"),


  url("tools/email_encoder_decoder") => route("email_encoder_decoder"),
  url("tools/meta_tag_list") => route("meta_tag_list"),
  url("tools/plagarism_check_list") => route("plagarism_check_list"),
  url("tools/index") => route("utilities"),
  url("tools/duplicate_email_filter_list") => route("duplicate_email_filter_list"),
  url("tools/url_encode_list") => route("url_encode_list"),
  url("tools/url_canonical_check") => route("url_canonical_check"),
  url("tools/gzip_check") => route("gzip_check"),
  url("tools/base64_encode_list") => route("base64_encode_list"),
  url("tools/robot_code_generator") => route("robot_code_generator"),


  url("url_shortener/index") => route("url_shortner"),

  url("keyword_position_tracking/index") => route("keyword_tracking"),

  url("antivirus/scan") => url("/menu_loader/security_tools"),
  url("antivirus/malware") => url("/menu_loader/security_tools"),
  url("antivirus/virus_total") => url("/menu_loader/security_tools"),
  url("antivirus/new_scan") => url("/menu_loader/security_tools"),

  url("code_minifier/html_minifier") => url("/menu_loader/code_minifier"),
  url("code_minifier/css_minifier") => url("/menu_loader/code_minifier"),
  url("code_minifier/js_minifier") => url("/menu_loader/code_minifier"),

  url("backlink/backlink_search") => url("/menu_loader/backlink_ping"),
  url("backlink/backlink_new") => url("/menu_loader/backlink_ping"),
  
  url("native_widgets/get_widget") => url("/native_widgets/get_widget"),



);

$custom_links[url("payment/transaction_log_manual")]=url("/payment/transaction_log");

$custom_links_assoc_str="{";
$loop=0;
foreach ($custom_links as $key => $value) 
{
  $loop++;
  array_push($all_links, $key); // adding custom urls in all urls array

  /* making associative link -> parent array for js, js dont support special chars */
  $custom_links_assoc_str.=str_replace(array('/',':','-','.'), array('FORWARDSLASHES','COLONS','DASHES','DOTS'), $key).":'".$value."'";
  if($loop!=count($custom_links)) $custom_links_assoc_str.=',';
}
$custom_links_assoc_str.="}";
 
?>



<script type="text/javascript">

var all_links_JS = [{!! '"'.implode('","', $all_links).'"' !!}]; // all urls includes database & custom urls
  var custom_links_JS= [{!! '"'.implode('","', array_keys($custom_links)).'"'  !!}]; // only custom urls
  var custom_links_assoc_JS = {!! $custom_links_assoc_str !!}; // custom urls associative array link -> parent
  var sideBarURL = window.location;
  sideBarURL=String(sideBarURL).trim();
  sideBarURL=sideBarURL.replace('#_=_',''); // redirct from facebook login return extra chars with url

  function removeUrlLastPart(the_url)   // function that remove last segment of a url
  {
      var theurl = String(the_url).split('/');
      theurl.pop();      
      var answer=theurl.join('/');
      return answer;
  }

  // get parent url of a custom url
  function matchCustomUrl(find)
  {
    var parentUrl='';
    var tempu1=find.replace(/\//g, 'FORWARDSLASHES'); // decoding special chars that was encoded to make js array
    tempu1=tempu1.replace(/:/g, 'COLONS');
    tempu1=tempu1.replace(/-/g, 'DASHES');
    tempu1=tempu1.replace(/\./g, 'DOTS');

    if(typeof(custom_links_assoc_JS[tempu1])!=='undefined')
    parentUrl=custom_links_assoc_JS[tempu1]; // getting parent value of custom link

    return parentUrl;
  }

  if(jQuery.inArray(sideBarURL, custom_links_JS) !== -1) // if the current link match custom urls
  {    
    sideBarURL=matchCustomUrl(sideBarURL);
  } 
  else if(jQuery.inArray(sideBarURL, all_links_JS) !== -1) // if the current link match known urls, this check is done later becuase all_links_JS also contains custom urls
  {
     sideBarURL=sideBarURL;
  }
  else // url does not match any of known urls
  {  
    var remove_times=1;
    var temp_URL=sideBarURL;
    var temp_URL2="";
    var tempu2="";
    while(true) // trying to match known urls by remove last part of url or adding /index at the last
    {
      temp_URL=removeUrlLastPart(temp_URL); // url may match after removing last
      temp_URL2=temp_URL+'/index'; // url may match after removing last part and adding /index

      if(jQuery.inArray(temp_URL, custom_links_JS) !== -1) // trimmed url match custom urls
      {
        sideBarURL=matchCustomUrl(temp_URL);
        break;
      }
      else if(jQuery.inArray(temp_URL, all_links_JS) !== -1) //trimmed url match known links
      {
        sideBarURL=temp_URL;
        break;
      }
      else // trimmed url does not match known urls, lets try extending url by adding /index
      {
        if(jQuery.inArray(temp_URL2, custom_links_JS) !== -1) // extended url match custom urls
        {
          sideBarURL=matchCustomUrl(temp_URL2);
          break;
        }
        else if(jQuery.inArray(temp_URL2, all_links_JS) !== -1)  // extended url match known urls
        {
          sideBarURL=temp_URL2;
          break;
        }
      }
      remove_times++;
      if(temp_URL.trim()=="") break;
    }    
  }

  $('ul.sidebar-menu a').filter(function() {
     return this.href == sideBarURL;
  }).parent().addClass('active');
  $('ul.dropdown-menu a').filter(function() {
     return this.href == sideBarURL;
  }).parentsUntil(".sidebar-menu > .dropdown-menu").addClass('active');
</script>







