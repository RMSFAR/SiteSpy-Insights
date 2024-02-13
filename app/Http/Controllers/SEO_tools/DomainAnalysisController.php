<?php

namespace App\Http\Controllers\SEO_tools;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Services\Custom\WebCommonReportServiceInterface;


class DomainAnalysisController extends HomeController
{


    public function __construct(WebCommonReportServiceInterface $web_common_repport)
    {
        $this->set_global_userdata(true,[],[],17);    
        $this->web_repport= $web_common_repport;  

    }

    public function who_is_index()
    {
        
        $data['body'] = "seo-tools.analysis-tools.domain-analysis.whois-index";
        return $this->_viewcontroller($data);        
    }
    public function who_is()
    {
        
        $data['body'] = "seo-tools.analysis-tools.domain-analysis.who-is";
        return $this->_viewcontroller($data);      
    }

    public function who_is_list_data(Request $request)
    {

        $searching       = trim($request->input("searching"));
        $post_date_range = $request->input("post_date_range");
        $display_columns = array("#",'CHECKBOX','id','domain_name','admin_name','admin_email','admin_country','admin_phone','admin_street','admin_city','admin_postal_code','tech_email','registrant_email','registrant_name','registrant_organization','registrant_street','registrant_city','registrant_state','registrant_postal_code','registrant_country','registrant_phone','registrar_url','is_registered','namve_servers','created_at','changed_at','expire_at','scraped_time');
        $search_columns = array('domain_name','scraped_time');

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 2;
        $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'id';
        $order = isset($_POST['order'][0]['dir']) ? strval($_POST['order'][0]['dir']) : 'desc';
        $order_by=$sort." ".$order;


        $from_date = $to_date = "";
        if($post_date_range!="")
        {
            $exp = explode('|', $post_date_range);
            $from_date = isset($exp[0])?$exp[0]:"";
            $to_date   = isset($exp[1])?$exp[1]:"";

        }

        $user_id = Auth::user()->id;
        $table = "whois_search";
        $query = DB::table($table);
        if($from_date!='') $query->where("scraped_time", ">=", $from_date);
        if($to_date!='') $query->where("scraped_time", "<=", $to_date);

        if ($searching != '')
        {
            $query->where(function($query) use ($search_columns,$searching){
                foreach ($search_columns as $key => $value) $query->orWhere($value, 'like',  "%$searching%");
            });
        }
        $query->where(function($query) use ($user_id){
            $query->orWhere('whois_search.user_id', '=', $user_id);
        });

        $info = $query->orderByRaw($order_by)->offset($start)->limit($limit)->get();

        $query = DB::table($table);
        $query->where(function($query) use ($user_id){
            $query->Where('whois_search.user_id', '=', $user_id);
        });

        $total_result=$query->count();

        for($i=0;$i<count($info);$i++)
        {  
         $info[$i]->scraped_time = date("jS M y",strtotime($info[$i]->scraped_time));
        }

        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }


    public function who_is_action(Request $request)
    {

        $urls=strip_tags($request->input('domain_name'));        
       
        $urls=str_replace("\n", ",", $urls);
        $url_array=explode(",", $urls);
        $url_array=array_filter($url_array);
        $url_array=array_unique($url_array);
        $bulk_tracking_code=time();


        //************************************************//
        $status=$this->_check_usage($module_id=5,$req=count($url_array));
        if($status=="2") 
        {
            echo "<div class='card-body'>
                    <div class='alert alert-warning alert-has-icon'>
                     <div class='alert-icon'><i class='far fa-lightbulb'></i></div>
                     <div class='alert-body'>
                        <div class='alert-title'>".__('warning')."</div>
                       ".__("Sorry, your bulk limit is exceeded for this module.")."
                        <a target='_BLANK' href='".url("/payment/usage_history")."'>".__("click here to see usage log.")."</a>
                     </div>
                    </div>
                </div>";
            exit();
        }
        else if($status=="3") 
        {
            echo "<div class='card-body'>
                    <div class='alert alert-warning alert-has-icon'>
                     <div class='alert-icon'><i class='far fa-lightbulb'></i></div>
                     <div class='alert-body'>
                        <div class='alert-title'>".__('warning')."</div>
                       ".__("Sorry, your monthly limit is exceeded for this module.")."
                        <a target='_BLANK' href='".url("/payment/usage_history")."'>".__("click here to see usage log.")."</a>
                     </div>
                    </div>
                </div>";
            exit();
        }
        //************************************************//
        
      
        session(['who_is_search_bulk_total_search'=>count($url_array)]);
        session(['who_is_search_complete_search'=>0]);

        $download_id= time();
        if (!file_exists(storage_path("app/public/download/who_is"))) {
            mkdir(storage_path("app/public/download/who_is"), 0777, true);
        }
        $download_path=fopen(storage_path("app/public/download/who_is/who_is_{$download_id}.csv"), "w");

        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
        $total_count=0;
        
        /**Write header in csv file***/
        $write_domain[]="Domain";
        $write_domain[]="Is Registered";
        $write_domain[]="Registrant Email"; 
        $write_domain[]="Tech Email";
        $write_domain[]="Admin Email";
    
        $write_domain[]="Name Servers";
        $write_domain[]="Created At";
        $write_domain[]="Changed At";
        $write_domain[]="Expires At";
        
        
        $write_domain[]="Registrat URL";
        
        $write_domain[]="Registrant Name";
        $write_domain[]="Registrant Organization";
        $write_domain[]="Registrant Street";
        $write_domain[]="Registrant City";
        $write_domain[]="Registrant State";
        $write_domain[]="Registrant Postal Code";
        $write_domain[]="Registrant Country";
        $write_domain[]="Registrant Phone";
        
        $write_domain[]="Admin Name";
        $write_domain[]="Admin Street";
        $write_domain[]="Admin City";
        $write_domain[]="Admin Postal Code";
        $write_domain[]="Admin Country";
        $write_domain[]="Admin Phone";            
        
        fputcsv($download_path, $write_domain);
        
        $str = "<div class='card'>
                    <div class='card-header'>
                      <h4><i class='fas fa-server'></i> ".__("Whois Search")."</h4>
                        <div class='card-header-action'>
                          <div class='badges'>
                            <a  class='btn btn-primary float-right' href='".url('/')."/storage/download/who_is/who_is_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
                          </div>                    
                        </div>
                    </div>
                    <div class='card-body'>";

        $str .="<div class='row'>";
        $str .="<div class='col-12 col-sm-12 col-md-4'>
                  <ul class='nav nav-pills flex-column' id='myTab4' role='tablist'>";
        $tab = 0;
        foreach ($url_array as $key => $value) {
             $tab++;
             if ($tab == 1) {
                $str.="<li class='nav-item'>
                              <a class='nav-link active p-3' id='home-tab".$tab."' data-toggle='tab' href='#home".$tab."' role='tab' aria-controls='home' aria-selected='true'>".$value."</a>
                            </li>";
             }
             else{
                $str.="<li class='nav-item'>
                             <a class='nav-link p-3' id='home-tab".$tab."' data-toggle='tab' href='#home".$tab."' role='tab' aria-controls='home' aria-selected='true'>".$value."</a>
                           </li>";
             }

        }

        $str.="</ul>
                </div>";
        //col end
        $str.="<div class='col-12 col-sm-12 col-md-8'>
                  <div class='tab-content no-padding' id='myTab2Content'>";
        $tab = 0;
        foreach ($url_array as $domain) {
            $tab++;
             /***Remove all www. http:// and https:// ****/            
             $domain=str_replace("www.","",$domain);
             $domain=str_replace("http://","",$domain);
             $domain=str_replace("https://","",$domain);
            
            /* $who_is_report =$this->web_common_report->whois_email($domain);*/
             $domain_info=$this->web_repport->whois_email2($domain);


             $write_domain=array();
             $write_domain[]=$domain;
             $write_domain[]=$domain_info['is_registered'];
             $write_domain[]=$domain_info['registrant_email'];
             $write_domain[]=$domain_info['tech_email'];
             $write_domain[]=$domain_info['admin_email'];
             $write_domain[]=$domain_info['name_servers'];
             $write_domain[]=$domain_info['created_at'];
             $write_domain[]=$domain_info['changed_at'];
             $write_domain[]=$domain_info['expire_at'];
             
             $write_domain[]=$domain_info['registrar_url'];
             
             $write_domain[]=$domain_info['registrant_name'];
             $write_domain[]=$domain_info['registrant_organization'];
             $write_domain[]=$domain_info['registrant_street'];
             $write_domain[]=$domain_info['registrant_city'];
             $write_domain[]=$domain_info['registrant_state'];
             $write_domain[]=$domain_info['registrant_postal_code'];
             $write_domain[]=$domain_info['registrant_country'];
             $write_domain[]=$domain_info['registrant_phone'];
             
             $write_domain[]=$domain_info['admin_name'];
             $write_domain[]=$domain_info['admin_street'];
             $write_domain[]=$domain_info['admin_city'];
             $write_domain[]=$domain_info['admin_postal_code'];
             $write_domain[]=$domain_info['admin_country'];
             $write_domain[]=$domain_info['admin_phone'];
             // $write_domain[]=$domain_info[''];
            
            
             fputcsv($download_path, $write_domain);
             
             /** Insert into database ***/
             
             $time=date("Y-m-d H:i:s");
             $insert_data=array(
                                 'user_id'           => Auth::user()->id,
                                 'domain_name'       => $domain,
                                 'tech_email'        => $domain_info['tech_email'],
                                 'admin_email'       => $domain_info['admin_email'],
                                 'is_registered'     =>$domain_info['is_registered'],
                                 'namve_servers'     =>$domain_info['name_servers'],
                                 'created_at'        =>$domain_info['created_at'],
                                 'changed_at'        =>$domain_info['changed_at'],
                                 'expire_at'         =>$domain_info['expire_at'],
                                 'scraped_time'      =>$time,
                                 'registrant_email'  =>$domain_info['registrant_email'],
                                 'registrant_name'   => $domain_info['registrant_name'],
                                 'registrant_organization'=>$domain_info['registrant_organization'],
                                 'registrant_street' =>$domain_info['registrant_street'],
                                 'registrant_city'   =>$domain_info['registrant_city'],
                                 'registrant_state'  =>$domain_info['registrant_state'],
                                 'registrant_postal_code'=> $domain_info['registrant_postal_code'],
                                 'registrant_country'=>$domain_info['registrant_country'],
                                 'registrant_phone'  =>$domain_info['registrant_phone'],
                                 'registrar_url'     =>$domain_info['registrar_url'],
                                 'admin_name'        =>$domain_info['admin_name'],
                                 'admin_street'      =>$domain_info['admin_street'],
                                 'admin_city'        =>$domain_info['admin_city'],
                                 'admin_postal_code'=> $domain_info['admin_postal_code'],
                                 'admin_country'     =>$domain_info['admin_country'],
                                 'admin_phone'       =>$domain_info['admin_phone']
                                 );
             

            if ($tab == 1) {
                $str.="<div class='tab-pane fade active show' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";

 
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Is Registered')."<span class='badge badge-primary badge-pill'>".$domain_info['is_registered']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Registrant Email')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_email']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Tech Email')."<span class='badge badge-primary badge-pill'>".$domain_info['tech_email']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Admin Email')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_email']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Name Servers')."<span class='badge badge-primary badge-pill'>".$domain_info['name_servers']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Created At')."<span class='badge badge-primary badge-pill'>".$domain_info['created_at']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Changed At')."<span class='badge badge-primary badge-pill'>".$domain_info['changed_at']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Expires At')."<span class='badge badge-primary badge-pill'>".$domain_info['expire_at']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Registrat URL')."<span class='badge badge-primary badge-pill'>".$domain_info['registrar_url']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Registrant Name')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_name']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Registrant Organization')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_organization']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Registrant Street')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_street']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Registrant City')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_city']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Registrant State')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_state']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Registrant Postal Code')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_postal_code']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Registrant Country')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_country']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Registrant Phone')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_phone']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Admin Name')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_name']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Admin Street')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_street']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Admin City')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_city']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Admin Postal Code')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_postal_code']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Admin Country')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_country']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Admin Phone')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_phone']."</span></li>";



                $str.= "</ul></div>";
            }
            else{
                $str.="<div class='tab-pane fade' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";

                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Is Registered')."<span class='badge badge-primary badge-pill'>".$domain_info['is_registered']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Registrant Email')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_email']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Tech Email')."<span class='badge badge-primary badge-pill'>".$domain_info['tech_email']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Admin Email')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_email']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Name Servers')."<span class='badge badge-primary badge-pill'>".$domain_info['name_servers']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Created At')."<span class='badge badge-primary badge-pill'>".$domain_info['created_at']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Changed At')."<span class='badge badge-primary badge-pill'>".$domain_info['changed_at']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Expires At')."<span class='badge badge-primary badge-pill'>".$domain_info['expire_at']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Registrat URL')."<span class='badge badge-primary badge-pill'>".$domain_info['registrar_url']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Registrant Name')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_name']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Registrant Organization')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_organization']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Registrant Street')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_street']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Registrant City')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_city']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Registrant State')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_state']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Registrant Postal Code')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_postal_code']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Registrant Country')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_country']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Registrant Phone')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_phone']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Admin Name')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_name']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Admin Street')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_street']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Admin City')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_city']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Admin Postal Code')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_postal_code']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Admin Country')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_country']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Admin Phone')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_phone']."</span></li>";
                
                $str.= "</ul></div>";
            }

            // $this->basic->insert_data('whois_search', $insert_data);
            DB::table('whois_search')->insert($insert_data);
        }
        $str.="</div>
                </div>";

        $this->_insert_usage_log($module_id=5,$req=count($url_array)); 
        echo $str.="</div></div></div>";

    }

  

    public function who_is_download(Request $request)
    {
        $all=$request->input("ids");
        $table = 'whois_search';
        $query=DB::table($table);
        if($all !=0)
        {
            $id_array = [];
            foreach ($all as  $value) 
            {
                $id_array[] = $value;
            }
            
            $query->whereIn('id',$id_array);
        }

        // $info = $this->basic->get_data($table, $where, $select ='', $join='', $limit='', $start=null, $order_by='id asc');
        $info = $query->where('user_id',Auth::user()->id)->orderBy('id')->get(); 
        $info=json_decode(json_encode($info));

        $download_id=time();
        if (!file_exists(storage_path("app/public/download/who_is"))) {
            mkdir(storage_path("app/public/download/who_is"), 0777, true);
        }
        $fp=fopen(storage_path("app/public/download/who_is/who_is_{$download_id}.csv"), "w");

        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));

        $write_domain[]="Domain";
        $write_domain[]="Is Registered";
        $write_domain[]="Registrant Email"; 
        $write_domain[]="Tech Email";
        $write_domain[]="Admin Email";
    
        $write_domain[]="Name Servers";
        $write_domain[]="Created At";
        $write_domain[]="Changed At";
        // $write_domain[]="Sponsor";
        $write_domain[]="Expires At";
        
        
        $write_domain[]="Registrat URL";
        
        $write_domain[]="Registrant Name";
        $write_domain[]="Registrant Organization";
        $write_domain[]="Registrant Street";
        $write_domain[]="Registrant City";
        $write_domain[]="Registrant State";
        $write_domain[]="Registrant Postal Code";
        $write_domain[]="Registrant Country";
        $write_domain[]="Registrant Phone";
        
        $write_domain[]="Admin Name";
        $write_domain[]="Admin Street";
        $write_domain[]="Admin City";
        $write_domain[]="Admin State";
        $write_domain[]="Admin Postal Code";
        $write_domain[]="Admin Country";
        $write_domain[]="Admin Phone";
                    
        fputcsv($fp, $write_domain);

        $write_info = array();

        foreach ($info as  $domain_info) 
        {
			$write_info = array();
            $write_info[]=$domain_info->domain_name;
            $write_info[]=$domain_info->is_registered;
            $write_info[]=$domain_info->registrant_email;
            
            $write_info[]=$domain_info->tech_email;
            $write_info[]=$domain_info->admin_email;
            $write_info[]=$domain_info->namve_servers;
            $write_info[]=$domain_info->created_at;
            $write_info[]=$domain_info->changed_at;
            // $write_info[]=$domain_info->sponsor;
            $write_info[]=$domain_info->expire_at;
            
            $write_info[]=$domain_info->registrar_url;
            
            $write_info[]=$domain_info->registrant_name;
            $write_info[]=$domain_info->registrant_organization;
            $write_info[]=$domain_info->registrant_street;
            $write_info[]=$domain_info->registrant_city;
            $write_info[]=$domain_info->registrant_state;
            $write_info[]=$domain_info->registrant_postal_code;
            $write_info[]=$domain_info->registrant_country;
            $write_info[]=$domain_info->registrant_phone;
            
            $write_info[]=$domain_info->admin_name;
            $write_info[]=$domain_info->admin_street;
            $write_info[]=$domain_info->admin_city;
            // $write_info[]=$domain_info->admin_state;
            $write_info[]=$domain_info->admin_postal_code;
            $write_info[]=$domain_info->admin_country;
            $write_info[]=$domain_info->admin_phone;
            
            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "/storage/download/who_is/who_is_{$download_id}.csv";
        echo "<p>".__("Your file is ready to download")."</p> <a href=".url('/').$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".__("Download")."</a>";
    }


    

    public function who_is_delete(Request $request)
    {
        $all=$request->input("ids");

        $query=DB::table('whois_search');
        if($all !=0)
        {
            $id_array = [];
            foreach ($all as  $value) 
            {
                $id_array[] = $value;
            }     
            $query->whereIn('id', $id_array);
        }
        $query->where('user_id', Auth::user()->id)->delete();
    }
    


    
    public function bulk_scan_progress_count()
    {
        $bulk_tracking_total_search=session('who_is_search_bulk_total_search'); 
        $bulk_complete_search=session('who_is_search_complete_search'); 
        
        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        
        echo json_encode($response);
        
    }

    public function read_text_csv_file_backlink(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }


        $ret=array();
        if (!file_exists(storage_path("app/public/upload/tmp/"))) {
            mkdir(storage_path("app/public/upload/tmp/"), 0777, true);
        }
        $output_dir = storage_path("app/public/upload/tmp");
        if (isset($_FILES["myfile"])) {
            $error =$_FILES["myfile"]["error"];
            $post_fileName =$_FILES["myfile"]["name"];
            $post_fileName_array=explode(".", $post_fileName);
            $ext=array_pop($post_fileName_array);
            $filename=implode('.', $post_fileName_array);
            $filename="domain_ip_analysis_".Auth::user()->id."_".time().substr(uniqid(mt_rand(), true), 0, 6).".".$ext;

            $allow=".csv,.txt";
            $allow=str_replace('.', '', $allow);
            $allow=explode(',', $allow);

            if(!in_array(strtolower($ext), $allow)) 
            {
                echo json_encode(array("are_u_kidding_me" => "yarki"));
                exit();
            }

            
            move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir.'/'.$filename);

            $path = storage_path("app/public/upload/tmp/".$filename);
            $read_handle=fopen($path, "r");
            $context_array =array('file_name'=>$filename);
            $context ="";
            while (!feof($read_handle)) 
            {
                $information = fgetcsv($read_handle);
                if (!empty($information)) 
                {
                    foreach ($information as $info) 
                    {
                        if (!is_numeric($info)) 
                        $context.=$info."\n";                       
                    }
                }
            }

            $context_array['content'] = trim($context, "\n");
            echo json_encode($context_array);
            
        }
    }

    public function read_after_delete_csv_txt(Request $request) // deletes the uploaded video to upload another one
    {
        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }
         
        $output_dir = storage_path("app/public/upload/tmp/");
        if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
        {
             $fileName =$_POST['name'];
             $fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files
             $filePath = $output_dir. $fileName;
             if (file_exists($filePath))
             {
                unlink($filePath);
             }
        }
    }

    public function expired_domain_index()
    {
        
        $data['body'] = "seo-tools.analysis-tools.domain-analysis.auction-domain-list";
        return $this->_viewcontroller($data);      
    }

    public function expired_domain_data(Request $request)
    {

        $searching       = trim($request->input("searching"));
        $post_date_range = $request->input("post_date_range");
        $display_columns = array("#",'CHECKBOX','id','domain_name','auction_type','auction_end_date','sync_at');
        $search_columns = array('domain_name','sync_at');

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 2;
        $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'id';
        $order = isset($_POST['order'][0]['dir']) ? strval($_POST['order'][0]['dir']) : 'desc';
        $order_by=$sort." ".$order;

        $from_date = $to_date = "";
        if($post_date_range!="")
        {
            $exp = explode('|', $post_date_range);
            $from_date = isset($exp[0])?$exp[0]:"";
            $to_date   = isset($exp[1])?$exp[1]:"";

        }

        $user_id = Auth::user()->id;
        $table = "expired_domain_list";
        $query = DB::table($table);
        if($from_date!='') $query->where("sync_at", ">=", $from_date);
        if($to_date!='') $query->where("sync_at", "<=", $to_date);


        if ($searching != '')
        {
            $query->where(function($query) use ($search_columns,$searching){
                foreach ($search_columns as $key => $value) $query->orWhere($value, 'like',  "%$searching%");
            });
        }



        // $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        $info = $query->orderByRaw($order_by)->offset($start)->limit($limit)->get();

        $query = DB::table($table);
        $total_result=$query->count();
        // for($i=0;$i<count($info);$i++)
        // {  
        //  $info[$i]->scraped_time = date("Y-m-d H:i:s",strtotime($info[$i]->scraped_time));
        //  $info[$i]->owner_email = "<div style='min-width:100px !important;' class='text-muted text-center'>".$info[$i]->owner_email."</div>";
        // }

        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }



    public function expired_domain_download(Request $request)
    {
        $all=$request->input("ids");
        $table = 'expired_domain_list';
        $query=DB::table($table);
        if($all !=0)
        {
            $id_array = [];
            foreach ($all as  $value) 
            {
                $id_array[] = $value;
            }
            
            $query->whereIn('id',$id_array);
        }


        // $info = $this->basic->get_data($table, $where, $select ='', $join='', $limit='', $start=null, $order_by='id asc');
        $info = $query->orderBy('id')->get(); 
        $info=json_decode(json_encode($info));

        $download_id=time();
        if (!file_exists(storage_path("app/public/download/expired_domain"))) {
            mkdir(storage_path("app/public/download/expired_domain"), 0777, true);
        }
        $fp=fopen(storage_path("app/public/download/expired_domain/expired_{$download_id}.csv"), "w");

        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));

        $head=array("Domain", "Auction Type", "Auction End Date", "Sync At");
                    
        fputcsv($fp, $head);

        foreach ($info as  $value) 
        {
        	$write_info = array();
            $write_info[] = $value->domain_name;
            $write_info[] = $value->auction_type;
            $write_info[] = $value->auction_end_date;
            $write_info[] = $value->sync_at;
            // $write_info[] = $value['page_rank'];
            // $write_info[] = $value['google_index'];
            // $write_info[] = $value['yahoo_index'];
            // $write_info[] = $value['bing_index'];
            
            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "/storage/download/expired_domain/expired_{$download_id}.csv";
       
        echo "<p>".__("Your file is ready to download")."</p> <a href=".url('/').$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".__("Download")."</a>";
    }

    public function dns_info_index()
    {
        
        $data['body'] = "seo-tools.analysis-tools.domain-analysis.dns-info";
        return $this->_viewcontroller($data);      
    }

    public function dns_info_action(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }       


        $url_lists = array();
        $url_values = explode(',',strip_tags($request->input('domain_name')));
        $str = "<div class='card'>
                    <div class='card-header'>
                      <h4><i class='fas fa-globe'></i> ".__("DNS Information")."</h4>
                    </div>
                    <div class='card-body'>";

        $str .="<div class='row'>";
        $str .="<div class='col-12 col-sm-12 col-md-4'>
                  <ul class='nav nav-pills flex-column' id='myTab4' role='tablist'>";
        if (count($url_values) <= 50) {
          $tab = 0;
          foreach ($url_values as $key => $value) {
               $tab++;
               if ($tab == 1) {
                  $str.="<li class='nav-item'>
                                <a class='nav-link active p-3' id='home-tab".$tab."' data-toggle='tab' href='#home".$tab."' role='tab' aria-controls='home' aria-selected='true'>".$value."</a>
                              </li>";
               }
               else{
                  $str.="<li class='nav-item'>
                               <a class='nav-link p-3' id='home-tab".$tab."' data-toggle='tab' href='#home".$tab."' role='tab' aria-controls='home' aria-selected='true'>".$value."</a>
                             </li>";
               }

          }
        }


        $str.="</ul>
                </div>";
        //col end
        $str.="<div class='col-12 col-sm-12 col-md-8'>
                  <div class='tab-content no-padding' id='myTab2Content'>";
        if (count($url_values) <= 50) {
          $tab = 0;
         foreach ($url_values as $url_value) {
           $tab++;
           $url_value = trim($url_value);
           if (is_valid_url($url_value) === TRUE || is_valid_domain_name($url_value) === TRUE) {
              $check_data =$this->web_repport->dns_information($url_value);
              
              $first_element = $check_data[0];
              if ($tab == 1) {

               $str.="<div class='tab-pane fade active show' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                       <ul class='list-group'>";

               $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Host')."<span class='badge badge-primary badge-pill'>".$first_element['host']."</span></li>";
               $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Type')."<span class='badge badge-primary badge-pill'>".$first_element['type']."</span></li>";
               $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('IP')."<span class='badge badge-primary badge-pill'>".$first_element['ip']."</span></li>";
               $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Class')."<span class='badge badge-primary badge-pill'>".$first_element['class']."</span></li>";
               $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('TTL')."<span class='badge badge-primary badge-pill'>".$first_element['ttl']."</span></li>";
               $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('TTL')."<a href='#' class='btn btn-icon icon-left btn-danger details float-right' data-details=".json_encode($check_data)."><i class='fas fa-info-circle'></i> ".__("Details")."</a></li>";

               $str.= "</ul></div>";
              }
              else{
                $str.="<div class='tab-pane fade' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";

                      $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Host')."<span class='badge badge-primary badge-pill'>".$first_element['host']."</span></li>";
                      $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Type')."<span class='badge badge-primary badge-pill'>".$first_element['type']."</span></li>";
                      $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('IP')."<span class='badge badge-primary badge-pill'>".$first_element['ip']."</span></li>";
                      $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Class')."<span class='badge badge-primary badge-pill'>".$first_element['class']."</span></li>";
                      $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('TTL')."<span class='badge badge-primary badge-pill'>".$first_element['ttl']."</span></li>";
                      $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('TTL')."<a href='#' class='btn btn-icon icon-left btn-danger details float-right' data-details=".json_encode($check_data)."><i class='fas fa-info-circle'></i> ".__("Details")."</a></li>";
                
                $str.= "</ul></div>";
              }

           }
         }
        }
        $str.="</div>
                </div>";

       
       $str.="</div></div></div>";  

         echo json_encode(array('url_lists' => $str));       
    } 

    public function server_info_index()
    {
        
        $data['body'] = "seo-tools.analysis-tools.domain-analysis.server-info";
        return $this->_viewcontroller($data);       
    }

    public function server_info_action(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }     

        $url_lists = array();
        $url_values = explode(',',strip_tags($request->input('domain_name')));

        $str = "<div class='card'>
                    <div class='card-header'>
                      <h4><i class='fas fa-server'></i> ".__("Server Information")."</h4>
                    </div>
                    <div class='card-body'>";

        $str .="<div class='row'>";
        $str .="<div class='col-12 col-sm-12 col-md-4'>
                  <ul class='nav nav-pills flex-column' id='myTab4' role='tablist'>";
        if (count($url_values) <= 50) {
           $tab = 0;
           foreach ($url_values as $key => $value) {
                $tab++;
                if ($tab == 1) {
                   $str.="<li class='nav-item'>
                                 <a class='nav-link active p-3' id='home-tab".$tab."' data-toggle='tab' href='#home".$tab."' role='tab' aria-controls='home' aria-selected='true'>".$value."</a>
                               </li>";
                }
                else{
                   $str.="<li class='nav-item'>
                                <a class='nav-link p-3' id='home-tab".$tab."' data-toggle='tab' href='#home".$tab."' role='tab' aria-controls='home' aria-selected='true'>".$value."</a>
                              </li>";
                }

           }
        }

        $str.="</ul>
                </div>";
        //col end
        $str.="<div class='col-12 col-sm-12 col-md-8'>
                  <div class='tab-content no-padding' id='myTab2Content'>";
        if (count($url_values) <= 50) {

            $tab = 0;
            foreach ($url_values as $url_value) {
                $tab++;
                $url_value = trim($url_value);
                if (is_valid_url($url_value) === TRUE || is_valid_domain_name($url_value) === TRUE) {
                    $server = '';
                    $connection = '';
                    $response = $this->web_repport->get_header_response($url_value);

                    $response = explode(PHP_EOL, $response);
                   
                    foreach ($response as $single_response) {
                        $semicolon_position = strpos($single_response, ':');
                        if ($semicolon_position !== FALSE) {
                            $title = substr($single_response, 0, $semicolon_position);
                            $value = str_replace($title . ': ','',$single_response);
                            if($title == 'Server')
                                $server = $value;
                            if($title == 'Connection')
                                $connection = $value;
                        }
                    }
                }
                if ($tab == 1) {
                    $str.="<div class='tab-pane fade active show' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                            <ul class='list-group'>";

                            $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Server')."<span class='badge badge-primary badge-pill'>".$server."</span></li>";
                            $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Connection')."<span class='badge badge-primary badge-pill'>".$connection."</span></li>";

                    $str.= "</ul></div>";
                }
                else{
                    $str.="<div class='tab-pane fade' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                            <ul class='list-group'>";

                            $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Server')."<span class='badge badge-primary badge-pill'>".$server."</span></li>";
                            $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Connection')."<span class='badge badge-primary badge-pill'>".$connection."</span></li>";
                    
                    $str.= "</ul></div>";
                }


            }
        }

        $str.="</div>
                </div>";
        echo $str.="</div></div></div>";

            
    } 




}
