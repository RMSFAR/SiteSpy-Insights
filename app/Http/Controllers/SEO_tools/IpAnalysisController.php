<?php

namespace App\Http\Controllers\SEO_tools;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Services\Custom\WebCommonReportServiceInterface;


class IpAnalysisController extends HomeController
{

    
    public function __construct(WebCommonReportServiceInterface $web_common_repport)
    {
        $this->set_global_userdata(true,[],[],6);       
        $this->web_repport= $web_common_repport;    
    }

    public function index()
    {
        
        $data["my_ip"]=$this->real_ip();
        $data["ip_info"]=$this->web_repport->ip_info($data["my_ip"]);
        $data['body'] = "seo-tools.analysis-tools.ip-analysis.index";
        return $this->_viewcontroller($data);       
    }
    public function domain_info_index()
    {
        
        $data['body'] = "seo-tools.analysis-tools.ip-analysis.ip-domain-info-index";
        return $this->_viewcontroller($data);  
    }
    public function domain_info_new()
    {
        
        $data['body'] = "seo-tools.analysis-tools.ip-analysis.ip-domain-info-new";
        return $this->_viewcontroller($data);    
    }
    public function site_this_ip()
    {
        
        $data['body'] = "seo-tools.analysis-tools.ip-analysis.this-site-ip";
        return $this->_viewcontroller($data);   
    }
    public function site_this_ip_new()
    {
        
        $data['body'] = "seo-tools.analysis-tools.ip-analysis.this-site-ip-new";
        return $this->_viewcontroller($data); 
    }
    public function ipv6_check()
    {
        
        $data['body'] = "seo-tools.analysis-tools.ip-analysis.ipv6-compability-index";
        return $this->_viewcontroller($data);  
    }
    public function ipv6_check_new()
    {
        
        $data['body'] = "seo-tools.analysis-tools.ip-analysis.ipv6-compability-new";
        return $this->_viewcontroller($data);    
    }
    public function ip_canonical_check()
    {
        
        $data['body'] = "seo-tools.analysis-tools.ip-analysis.ip-canonical";
        return $this->_viewcontroller($data);   
    }
    public function ip_traceout($ip="")
    {
        
        $data['check_ip'] = $ip;
        $data['body'] = "seo-tools.analysis-tools.ip-analysis.ip-traceoute";
        return $this->_viewcontroller($data);   
    }

    public function traceout_check_data(Request $request)
    {
        $ip_address = $request->input('domain_name');
        $blacklist_details = $this->web_repport->ip_traceout($ip_address);

        $str = "<div class='card'>
        <div class='card-header'>
          <h4><i class='fas fa-map-marker-alt'></i> ".__("IP Traceroute")."</h4>

        </div>
        <div class='card-body p-0'>
          <div class='table-responsive'>
            <table class='table table-hover table-bordered'>
              <tbody>
                <tr>
                  <th>".__("Hop")."</th>
                  <th>".__("Time")."</th>
                  <th>".__("Host")."</th>
                  <th>".__("IP")."</th>
                  <th>".__("Location")."</th>
                </tr>";

        if(is_array($blacklist_details) && isset($blacklist_details[0]))unset($blacklist_details[0]);
        if(is_array($blacklist_details))
        foreach($blacklist_details as $value)
        {

            $str .="<tr>
            <td>".$value['hop']."</td>
            <td>".$value['time']."</td>
            <td>".$value['host']."</td>
            <td>".$value['ip']."</td>
            <td>".$value['location']."</td>
            </tr>";

        }

        $str .= '</tbody></table></div></div></div>';

        
        $data["ip_info"]=$this->web_repport->ip_info($ip_address);
        $data["my_ip"]=$ip_address;

        // $data["google_api"]=$this->basic->get_data("config",array("where"=>array("user_id"=>$this->session->userdata("user_id"))));
        $data["google_api"] = DB::table('config')->where('user_id',Auth::user()->id)->get();
        $data["google_api"] = json_decode(json_encode($data["google_api"]));
        // $str.=$this->load->view("ip_analysis/ip_info",$data);
        $str .= view('seo-tools.analysis-tools.ip-analysis.ip-info', $data)->render();

        echo $str;

    }

    public function ip_canonical_action(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }      

        $domain_lists = [];
        $domain_values = explode(',',strip_tags($request->input('domain_name')));
   
        $str = "<div class='card'>
                    <div class='card-header'>
                      <h4><i class='fas fa-map-marker-alt'></i> ".__("IP Canonical Check")."</h4>
                    </div>
                    <div class='card-body'>";

        $str .="<div class='row'>";
        $str .="<div class='col-12 col-sm-12 col-md-4'>
                  <ul class='nav nav-pills flex-column' id='myTab4' role='tablist'>";
        if (count($domain_values) <= 50) {
            $tab = 0;
            foreach ($domain_values as $key => $value) {
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
        if (count($domain_values) <= 50) {

           $tab = 0;
           foreach ($domain_values as $domain_value) {
               $tab++;
               $domain_value = trim($domain_value);
               if (is_valid_domain_name($domain_value) === TRUE) {
                   $check_data = $this->web_repport->ip_canonical_check($domain_value); 
                   if ($check_data['ip_canonical'] =='1')
                       $check_data['ip_canonical'] = "Yes";
                    else
                       $check_data['ip_canonical'] = "No";
                        
               }
               if ($tab == 1) {
                   $str.="<div class='tab-pane fade active show' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                           <ul class='list-group'>";

                       $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('IP')."<span class='badge badge-primary badge-pill'>".$check_data['ip']."</span></li>";
                       $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Canonical')."<span class='badge badge-primary badge-pill'>".$check_data['ip_canonical']."</span></li>";

                   $str.= "</ul></div>";
               }
               else{
                   $str.="<div class='tab-pane fade' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                           <ul class='list-group'>";
                           
                       $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('IP')."<span class='badge badge-primary badge-pill'>".$check_data['ip']."</span></li>";
                       $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Canonical')."<span class='badge badge-primary badge-pill'>".$check_data['ip_canonical']."</span></li>";
                   
                   $str.= "</ul></div>";
               }

           }

        }
        $str.="</div>
                </div>";

        echo $str.="</div></div></div>";


    }

    public function read_text_csv_file_backlink(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }
        if (!file_exists(storage_path("app/public/upload/tmp/"))) {
            mkdir(storage_path("app/public/upload/tmp/"), 0777, true);
        }

        $ret=array();
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


    public function ipv6_check_data(Request $request)
    {

        $searching       = trim($request->input("searching"));
        $post_date_range = $request->input("post_date_range");
        $display_columns = array("#",'CHECKBOX','id','domain_name','ip','ipv6','searched_at');
        $search_columns = array('ip','searched_at');

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
        $table = "ip_v6_check";
        $query = DB::table($table);
        if($from_date!='') $query->where("searched_at", ">=", $from_date);
        if($to_date!='') $query->where("searched_at", "<=", $to_date);

        if ($searching != '')
        {
            $query->where(function($query) use ($search_columns,$searching){
                foreach ($search_columns as $key => $value) $query->orWhere($value, 'like',  "%$searching%");
            });
        }
        $query->where(function($query) use ($user_id){
            $query->orWhere('ip_v6_check.user_id', '=', $user_id);
        });

        $info = $query->orderByRaw($order_by)->offset($start)->limit($limit)->get();

        $query = DB::table($table);
        $query->where(function($query) use ($user_id){
            $query->Where('ip_v6_check.user_id', '=', $user_id);
        });

        $total_result=$query->count();
        for($i=0;$i<count($info);$i++)
        {  
         if ($info[$i]->ipv6 == 'Not Compatible') 
               $info[$i]->ipv6 = "<span><i class='fas fa-times-circle text-danger'></i> ".$info[$i]->ipv6."</span>";

         $info[$i]->searched_at = date("jS M y",strtotime($info[$i]->searched_at));
        }

        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }


    public function ipv6_check_action(Request $request)
    {
        $urls=strip_tags($request->input('domain_name'));
       
        $urls=str_replace("\n", ",", $urls);
        $url_array=explode(",", $urls);
        $url_array=array_filter($url_array);
        $url_array=array_unique($url_array);

        //************************************************//
        $status=$this->_check_usage($module_id=6,$req=count($url_array));
        if($status=="2") 
        {
            echo "<div class='card-body'>
                    <div class='alert alert-warning alert-has-icon'>
                     <div class='alert-icon'><i class='far fa-lightbulb'></i></div>
                     <div class='alert-body'>
                        <div class='alert-title'>".__('warning')."</div>
                       ".__("Sorry, your bulk limit is exceeded for this module.")."
                        <a target='_BLANK' href='".url("payment/usage_history")."'>".__("click here to see usage log.")."</a>
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
                        <a target='_BLANK' href='".url("payment/usage_history")."'>".__("click here to see usage log.")."</a>
                     </div>
                    </div>
                </div>";
            exit();
        }
        //************************************************//
        
      
        session(['ipv6_check_bulk_total_search'=>count($url_array)]);
        session(['ipv6_check_complete_search'=>0]);
        $download_id= time();
        if (!file_exists(storage_path("app/public/download/ip"))) {
            mkdir(storage_path("app/public/download/ip"), 0777, true);
        }
        $download_path=fopen(storage_path("app/public/download/ip/ipv6_{$download_id}.csv"), "w");

        
        $download_path=fopen("download/ip/ipv6_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
          
        /**Write header in csv file***/

        $write_data=[];            
        $write_data[]="Domain";          
        $write_data[]="IP";            
        $write_data[]="IPv6";            
        $write_data[]="Searched at";            
                                        
        fputcsv($download_path, $write_data);

        $str = "<div class='card'>
                   <div class='card-header'>
                     <h4><i class='fas fa-map-marker-alt'></i> ".__("IPv6 Compability Check")."</h4>
                      <div class='card-header-action'>
                        <div class='badges'>
                          <a  class='btn btn-primary float-right' href='".url('/')."/storage/download/ip/ipv6_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
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
            $domain_data=[];
            $domain_data=$this->web_repport->ipv6_check($domain);  
            $searched_at= date("Y-m-d H:i:s");
                  
            $write_data=[];
            
            $write_data[]=$domain;
            $write_data[]=$domain_data["ip"];

            if($domain_data["is_ipv6_support"]=="1")
                $ipv6=$domain_data["ipv6"];
            else 
                $ipv6="Not Compatible";
            $write_data[]=$ipv6;

            $write_data[]=$searched_at;
            fputcsv($download_path, $write_data);
            
            /** Insert into database ***/
            
            $insert_data=array
            (
                'user_id'           => Auth::user()->id,
                'domain_name'       => $domain,
                'ip'                => $domain_data["ip"],
                'ipv6'              => $ipv6,
                'is_ipv6_support'   => $domain_data["is_ipv6_support"],
                'searched_at'       => $searched_at
            );

            if ($tab == 1) {
                $str.="<div class='tab-pane fade active show' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";

                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('IP')."<span class='badge badge-primary badge-pill'>".$domain_data["ip"]."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('IPv6')."<span class='badge badge-primary badge-pill'>".$ipv6."</span></li>";

                $str.= "</ul></div>";
            }
            else{
                $str.="<div class='tab-pane fade' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";

                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('IP')."<span class='badge badge-primary badge-pill'>".$domain_data["ip"]."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('IPv6')."<span class='badge badge-primary badge-pill'>".$ipv6."</span></li>";
                
                $str.= "</ul></div>";
            }

            // $this->basic->insert_data('ip_v6_check', $insert_data);
            DB::table('ip_v6_check')->insert($insert_data);
        }
        $str.="</div>
                </div>";

        $this->_insert_usage_log($module_id=6,$req=count($url_array)); 
        echo $str.="</div></div></div>";

    }

  

    public function ipv6_check_download(Request $request)
    {
        $all=$request->input("ids");

        $table = 'ip_v6_check';
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

        if (!file_exists(storage_path("app/public/download/ip"))) {
            mkdir(storage_path("app/public/download/ip"), 0777, true);
        }
        $fp=fopen(storage_path("app/public/download/ip/ipv6_{$download_id}.csv"), "w");

        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        

        $write_data=[];            
        $write_data[]="Domain";          
        $write_data[]="IP";            
        $write_data[]="IPv6";            
        $write_data[]="Searched at";   
                    
        fputcsv($fp, $write_data);
        $write_info = [];

        foreach ($info as  $value) 
        {
         
                if($value->is_ipv6_support=="1")
                $ipv6=$value->ipv6;
                else $ipv6="Not Compatible";

                $write_data=array();            
                $write_data[]=$value->domain_name;      
                $write_data[]=$value->ip;            
                $write_data[]=$ipv6;    
                $write_data[]=$value->searched_at;   
            
                fputcsv($fp, $write_data);
        }

        fclose($fp);
        $file_name = "/storage/download/ip/ipv6_{$download_id}.csv";
        echo "<p>".__("Your file is ready to download")."</p> <a href=".url('/').$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".__("Download")."</a>";
    }
 
    public function ipv6_check_delete(Request $request)
    {
        $all=$request->input("ids");

        $query=DB::table('ip_v6_check')->select('ip_v6_check.*');
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
   
    public function bulk_ipv6_check_progress_count()
    {
        $bulk_tracking_total_search=session('ipv6_check_bulk_total_search'); 
        $bulk_complete_search=session('ipv6_check_complete_search'); 
        
        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        
        echo json_encode($response);
        
    }

    
    public function site_this_ip_data(Request $request)
    {

        $searching       = trim($request->input("searching"));
        $post_date_range = $request->input("post_date_range");
        $display_columns = array("#",'CHECKBOX','id','ip','searched_at');
        $search_columns = array('ip','searched_at');

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
        $table = "ip_same_site";
        $query = DB::table($table);
        if($from_date!='') $query->where("searched_at", ">=", $from_date);
        if($to_date!='') $query->where("searched_at", "<=", $to_date);

        if ($searching != '')
        {
            $query->where(function($query) use ($search_columns,$searching){
                foreach ($search_columns as $key => $value) $query->orWhere($value, 'like',  "%$searching%");
            });
        }
        $query->where(function($query) use ($user_id){
            $query->orWhere('ip_same_site.user_id', '=', $user_id);
        });

        // $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        $info = $query->orderByRaw($order_by)->offset($start)->limit($limit)->get();

        $total_result=$query->count();

        for($i=0;$i<count($info);$i++)
        {  
         $info[$i]->searched_at = date("jS M y",strtotime($info[$i]->searched_at));
        }

        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }


    public function site_this_ip_action(Request $request)
    {
        //************************************************//
        $status=$this->_check_usage($module_id=6,$req=1);
        if($status=="2") 
        {
            echo "<div class='card-body'>
                    <div class='alert alert-warning alert-has-icon'>
                     <div class='alert-icon'><i class='far fa-lightbulb'></i></div>
                     <div class='alert-body'>
                        <div class='alert-title'>".__('warning')."</div>
                       ".__("Sorry, your bulk limit is exceeded for this module.")."
                        <a target='_BLANK' href='".url("payment/usage_history")."'>".__("click here to see usage log.")."</a>
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
                        <a target='_BLANK' href='".url("payment/usage_history")."'>".__("click here to see usage log.")."</a>
                     </div>
                    </div>
                </div>";
            exit();
        }
        //************************************************//


        $ip=strip_tags($request->input('domain_name'));
       
       
        $download_id= time();

        if (!file_exists(storage_path("app/public/download/ip"))) {
            mkdir(storage_path("app/public/download/ip"), 0777, true);
        }
        $download_path=fopen(storage_path("download/ip/same_site_{$download_id}.csv"), "w");
        
        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
     
        
        /**Write header in csv file***/

        $write_data=array();              
        $write_data[]="IP";            
        $write_data[]="Website";            
                                        
        
        fputcsv($download_path, $write_data);
        
        $site_this_ip_complete=0;

        $count=0;
        $str= "<div class='card card-hero'>
                  <div class='card-header' style='border-radius:0!important;'>
                    <div class='card-description'>".__("Sites In Same IP : ").$ip."</div>
                    <div class='card-header-action'>
                      <div class='badges'>
                        <a  class='btn btn-primary float-right' href='".url('/')."/storage/download/ip/same_site_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
                      </div>                    
                    </div>
                  </div>
                  <div class='card-body'>
                    <div class='tickets-list'>";
  
        $same_site_data=[];
        $this->web_repport->get_site_in_same_ip($ip,$page=1,$proxy="");  
        $same_site_data=$this->web_repport->same_site_in_ip;  
        session(['site_this_ip_complete_search'=>0]);
        session(['site_this_ip_bulk_total_search'=>count($same_site_data)]);
        $searched_at= date("Y-m-d H:i:s");
               
       
       foreach ($same_site_data as $key => $value) 
       {
            $count++;
            //$site_linkable="<a target='_BLANL' title='Visit Now' href='".addHttp($value)."'>".$value."</a>";
            $str.="<a href='".addHttp($value)."' class='ticket-item' target='_BLANK'>
                    <div class='ticket-title'>
                      <h4>".$value."</h4>
                    </div>
                  </a>";

            $write_data=array(); 
            $write_data[]=$ip;
            $write_data[]=$value;
            fputcsv($download_path, $write_data);

            $site_this_ip_complete++;
            session(["site_this_ip_complete_search"=>$site_this_ip_complete]);  
       }

       if(count($same_site_data)==0) $str.="<h4 class='text-center mt-5 mb-5'>".__("No data found!")."</h4>";
        
         /** Insert into database ***/
        $insert_data=array
        (
            'user_id'           => Auth::user()->id,
            'ip'                => $ip,
            'website'           => json_encode($same_site_data),
            'searched_at'       => $searched_at
        );
       
       //******************************//
        // insert data to useges log table
        $this->_insert_usage_log($module_id=6,$req=1);   
        //******************************//
        
       if(DB::table('ip_same_site')->insert($insert_data))
       echo $str.="</div></div></div>";

    }

  

    public function site_this_ip_download(Request $request)
    {
        $all=$request->input("ids");
        $table = 'ip_same_site';
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
        if (!file_exists(storage_path("app/public/download/ip"))) {
            mkdir(storage_path("app/public/download/ip"), 0777, true);
        }
        $fp=fopen(storage_path("app/public/download/ip/same_site_{$download_id}.csv"), "w");

        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        
        $write_data=array();            
        $write_data[]="IP";            
        $write_data[]="Website"; 
        $write_data[]="Searched at";  
                    
        fputcsv($fp, $write_data);
        $write_info = array();

        foreach ($info as  $value) 
        {
            $website_array=json_decode($value->website);
            foreach ($website_array as $row) 
            {
                $write_data=array();            
                $write_data[]=$value->ip;    
                $write_data[]=$row;    
                $write_data[]=$value->searched_at;    
            
                fputcsv($fp, $write_data);
            }                
        }
        fclose($fp);
        $file_name = "/storage/download/ip/same_site_{$download_id}.csv";
        echo "<p>".__("Your file is ready to download")."</p> <a href=".url('/').$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".__("Download")."</a>";
    }

    

    public function site_this_ip_delete(Request $request)
    {
        $all=$request->input("ids");

        $query=DB::table('ip_same_site')->select('ip_same_site.*');
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

   
    public function bulk_site_this_ip_progress_count()
    {
        $bulk_tracking_total_search=session('site_this_ip_bulk_total_search'); 
        $bulk_complete_search=session('site_this_ip_complete_search'); 
        
        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        
        echo json_encode($response);
        
    }


    public function domain_info_data(Request $request)
    {

        $searching       = trim($request->input("searching"));
        $post_date_range = $request->input("post_date_range");
        $display_columns = array("#",'CHECKBOX','id','domain_name','isp','ip','organization','country','city','time_zone','latitude','longitude','searched_at');
        $search_columns = array('domain_name','searched_at');

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
        $table = "ip_domain_info";
        $query = DB::table($table);
        if($from_date!='') $query->where("searched_at", ">=", $from_date);
        if($to_date!='') $query->where("searched_at", "<=", $to_date);


        if ($searching != '')
        {
            $query->where(function($query) use ($search_columns,$searching){
                foreach ($search_columns as $key => $value) $query->orWhere($value, 'like',  "%$searching%");
            });
        }
        $query->where(function($query) use ($user_id){
            $query->orWhere('ip_domain_info.user_id', '=', $user_id);
        });


        // $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        $info = $query->orderByRaw($order_by)->offset($start)->limit($limit)->get();

        $query = DB::table($table);
        $query->where(function($query) use ($user_id){
            $query->Where('ip_domain_info.user_id', '=', $user_id);
        });

        $total_result=$query->count();


        for($i=0;$i<count($info);$i++)
        {  
         $info[$i]->searched_at = date("jS M y",strtotime($info[$i]->searched_at));
        }

        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }


    public function domain_info_action(Request $request)
    {

        $urls=strip_tags($request->input('domain_name'));
       
        $urls=str_replace("\n", ",", $urls);
        $url_array=explode(",", $urls);
        $url_array=array_filter($url_array);
        $url_array=array_unique($url_array);

        //************************************************//
        $status=$this->_check_usage($module_id=6,$req=count($url_array));
        if($status=="2") 
        {
            echo "<div class='card-body'>
                    <div class='alert alert-warning alert-has-icon'>
                     <div class='alert-icon'><i class='far fa-lightbulb'></i></div>
                     <div class='alert-body'>
                        <div class='alert-title'>".__('warning')."</div>
                       ".__("Sorry, your bulk limit is exceeded for this module.")."
                        <a target='_BLANK' href='".url("payment/usage_history")."'>".__("click here to see usage log.")."</a>
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
                        ".__("sorry, your monthly limit is exceeded for this module.")."
                        <a target='_BLANK' href='".url("payment/usage_history")."'>".__("click here to see usage log.")."</a>
                     </div>
                    </div>
                </div>";

            exit();
        }
        //************************************************//
        
      
        session(['domain_info_bulk_total_search'=>count($url_array)]);
        session(['domain_info_complete_search'=>0]);

        $download_id=time();
        if (!file_exists(storage_path("app/public/download/ip"))) {
            mkdir(storage_path("app/public/download/ip"), 0777, true);
        }
        $download_path=fopen(storage_path("app/public/download/ip/domain_{$download_id}.csv"), "w");

        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
          
        /**Write header in csv file***/

        $write_data=array();            
        $write_data[]="Domain";            
        $write_data[]="ISP";            
        $write_data[]="IP";   
        $write_data[]="Organization";         
        $write_data[]="Country";            
        $write_data[]="Region";            
        $write_data[]="City";            
        $write_data[]="Time Zone";            
        $write_data[]="Latitude";            
        $write_data[]="Longitude";            
                                        
        
        fputcsv($download_path, $write_data);

        $str = "<div class='card'>
                  <div class='card-header'>
                    <h4><i class='fas fa-map-marker-alt'></i> ".__("Domain IP Information")."</h4>
                    <div class='card-header-action'>
                      <div class='badges'>
                        <a  class='btn btn-primary float-right' href='".url('/')."/storage/download/ip/domain_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
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

            $domain_data=array();
            $domain_data=$this->web_repport->get_ip_country($domain,$proxy="");  
            $searched_at= date("Y-m-d H:i:s");
                  
            $write_data=array();
            
            $write_data[]=$domain;
            $write_data[]=$domain_data["isp"];
            $write_data[]=$domain_data["ip"];
            $write_data[]=$domain_data["organization"];
            $write_data[]=$domain_data["country"];
            $write_data[]=$domain_data["region"];
            $write_data[]=$domain_data["city"];
            $write_data[]=$domain_data["time_zone"];
            $write_data[]=$domain_data["latitude"];
            $write_data[]=$domain_data["longitude"];
            fputcsv($download_path, $write_data);
            
            /** Insert into database ***/
            
            $insert_data=array
            (
                'user_id'           => Auth::user()->id,
                'domain_name'       => $domain,
                'ip'                => $domain_data["ip"],
                'isp'               => $domain_data["isp"],
                'organization'      => $domain_data["organization"],
                'country'           => $domain_data["country"],
                'region'            => $domain_data["region"],
                'city'              => $domain_data["city"],
                'time_zone'         => $domain_data["time_zone"],
                'latitude'          => $domain_data["latitude"],
                'longitude'         => $domain_data["longitude"],
                'searched_at'       => $searched_at,
            );
            if ($tab == 1) {
                $str.="<div class='tab-pane fade active show' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";
                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("ISP")." <span class='badge badge-primary badge-pill'>".$domain_data["isp"]."</span></li>";  
                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("IP")." <span class='badge badge-primary badge-pill'>".$domain_data["ip"]."</span></li>";  
                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Organization")." <span class='badge badge-primary badge-pill'>".$domain_data["organization"]."</span></li>";    
                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Country")." <span class='badge badge-primary badge-pill'>".$domain_data["country"]."</span></li>";  
                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Region")." <span class='badge badge-primary badge-pill'>".$domain_data["region"]."</span></li>";  
                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("City")." <span class='badge badge-primary badge-pill'>".$domain_data["city"]."</span></li>";  
                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Time Zone")." <span class='badge badge-primary badge-pill'>".$domain_data["time_zone"]."</span></li>";  
                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Latitude")." <span class='badge badge-primary badge-pill'>".$domain_data["latitude"]."</span></li>";  
                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Longitude")." <span class='badge badge-primary badge-pill'>".$domain_data["longitude"]."</span></li>";  

                $str.= "</ul></div>";
            }
            else{
                $str.="<div class='tab-pane fade' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("ISP")." <span class='badge badge-primary badge-pill'>".$domain_data["isp"]."</span></li>";  
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("IP")." <span class='badge badge-primary badge-pill'>".$domain_data["ip"]."</span></li>";  
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Organization")." <span class='badge badge-primary badge-pill'>".$domain_data["organization"]."</span></li>";    
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Country")." <span class='badge badge-primary badge-pill'>".$domain_data["country"]."</span></li>";  
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Region")." <span class='badge badge-primary badge-pill'>".$domain_data["region"]."</span></li>";  
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("City")." <span class='badge badge-primary badge-pill'>".$domain_data["city"]."</span></li>";  
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Time Zone")." <span class='badge badge-primary badge-pill'>".$domain_data["time_zone"]."</span></li>";  
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Latitude")." <span class='badge badge-primary badge-pill'>".$domain_data["latitude"]."</span></li>";  
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Longitude")." <span class='badge badge-primary badge-pill'>".$domain_data["longitude"]."</span></li>";                  
                $str.= "</ul></div>";
            }

            DB::table('ip_domain_info')->insert($insert_data);
        }
        $str.="</div>
                </div>";

        $this->_insert_usage_log($module_id=6,$request=count($url_array)); 
        echo $str.="</div></div></div>";

    }


    public function domain_info_download(Request $request)
    {
        $all=$request->input("ids");
        $table = 'ip_domain_info';
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
        if (!file_exists(storage_path("app/public/download/ip"))) {
            mkdir(storage_path("app/public/download/ip"), 0777, true);
        }
        $fp=fopen(storage_path("app/public/download/ip/domain_{$download_id}.csv"), "w");

        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        

        $write_data=array();            
        $write_data[]="Domain";            
        $write_data[]="ISP";            
        $write_data[]="IP";            
        $write_data[]="Country";            
        $write_data[]="Region";            
        $write_data[]="City";            
        $write_data[]="Time Zone";            
        $write_data[]="Latitude";            
        $write_data[]="Longitude";  
        $write_data[]="Searched at";  
                    
        fputcsv($fp, $write_data);
        $write_info = array();

        foreach ($info as  $value) 
        {
         
                $write_data=array();            
                $write_data[]=$value->domain_name;            
                $write_data[]=$value->isp;            
                $write_data[]=$value->ip;            
                $write_data[]=$value->country;   
                $write_data[]=$value->region;   
                $write_data[]=$value->city;     
                $write_data[]=$value->time_zone;            
                $write_data[]=$value->latitude;          
                $write_data[]=$value->longitude;   
                $write_data[]=$value->searched_at;   
            
                fputcsv($fp, $write_data);
        }

        fclose($fp);
        $file_name = "/storage/download/ip/domain_{$download_id}.csv";
       
        echo "<p>".__("Your file is ready to download")."</p> <a href=".url('/ ').$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".__("Download")."</a>";
    }
 

    public function domain_info_delete(Request $request)
    {
        $all=$request->input("ids");

        $query=DB::table('ip_domain_info')->select('ip_domain_info.*');
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
   
    public function bulk_domain_info_progress_count(Request $request)
    {
        $bulk_tracking_total_search=session('domain_info_bulk_total_search'); 
        $bulk_complete_search=session('domain_info_complete_search'); 
        
        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        
        echo json_encode($response);
        
    }




}
