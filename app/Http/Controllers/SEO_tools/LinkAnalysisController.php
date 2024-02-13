<?php

namespace App\Http\Controllers\SEO_tools;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Services\Custom\WebCommonReportServiceInterface;


class LinkAnalysisController extends HomeController
{
   
    
    public function __construct(WebCommonReportServiceInterface $web_common_repport)
    {
        $this->set_global_userdata(true,[],[],7);
        $this->web_repport= $web_common_repport;
  
    }

    public function link_analysis_index()
    {
        $this->important_feature();
        $this->member_validity();
        $data['body'] = "seo-tools.analysis-tools.link-analysis.link-analysis-index";
        return $this->_viewcontroller($data);        
    }
    public function link_analysis()
    {
        $this->important_feature();
        $this->member_validity();
        $data['body'] = "seo-tools.analysis-tools.link-analysis.link-analysis-new";
        return $this->_viewcontroller($data);        
    }

    public function page_status_index()
    {
        $this->important_feature();
        $this->member_validity();
        $data['body'] = "seo-tools.analysis-tools.link-analysis.page-status-index";
        return $this->_viewcontroller($data);       
    }

    public function page_status()
    {
        $this->important_feature();
        $this->member_validity();
        $data['body'] = "seo-tools.analysis-tools.link-analysis.page-status-new";
        return $this->_viewcontroller($data);       
    }

    
    public function link_analysis_data(Request $request)
    {

        $searching       = trim($request->input("searching"));
        $post_date_range = $request->input("post_date_range");
        $display_columns = ["#",'CHECKBOX','id','url','external_link_count','internal_link_count','nofollow_count','do_follow_count','searched_at'];
        $search_columns = ['url','searched_at'];

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
        $table = "link_analysis";
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
            $query->orWhere('link_analysis.user_id', '=', $user_id);
        });
    

        // $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        $info = $query->orderByRaw($order_by)->offset($start)->limit($limit)->get();

        $query = DB::table($table);
        $query->where(function($query) use ($user_id){
            $query->Where('link_analysis.user_id', '=', $user_id);
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


    public function link_analysis_action(Request $request)
    {

        $url=strip_tags($request->input('keyword'));

        //************************************************//
        $status=$this->_check_usage($module_id=7,$req='1');

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

        
         
      
        $download_id= time();
        $download_id=time();
        if (!file_exists(storage_path("app/public/download/link"))) {
           mkdir(storage_path("app/public/download/link"), 0777, true);
       }
       $download_path=fopen(storage_path("app/public/download/link/link_analysis_{$download_id}.csv"), "w");
        
        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
        
        /**Write header in csv file***/      
        
        $link_analysis_complete=0;
  

        $link_analysis_data=array();
        $link_analysis_data=$this->web_repport->link_statistics($url);  
        $searched_at= date("Y-m-d H:i:s");

        $total_external_link=$link_analysis_data["external_link_count"];
        $total_internal_link=$link_analysis_data["internal_link_count"];
        $total_nofollow=$link_analysis_data["nofollow_count"];
        $total_dofollow=$link_analysis_data["do_follow_count"];
        $total_link=$total_external_link+$total_internal_link;

        session()->put('link_analysis_complete_search', 0);
        session()->put('link_analysis_bulk_total_search', $total_link);

        $write_data=[];            
        $write_data[]="URL";            
        $write_data[]=$url;            
        $write_data[]="Total Link";            
        $write_data[]=$total_link;            
        $write_data[]="External Link Count";            
        $write_data[]=$total_external_link;            
        $write_data[]="Internal Link Count";            
        $write_data[]=$total_internal_link;            
        $write_data[]="DoFollow Count";                
        $write_data[]=$total_dofollow;            
        $write_data[]="NoFollow Count";                
        $write_data[]=$total_nofollow;   

        fputcsv($download_path, $write_data);




        $str="<div class='card'>
                      <div class='card-header'>
                        <h4><i class='fas fa-anchor'></i> ".__("Link Analyzer")."</h4>
                        <div class='card-header-action'>
                          <div class='badges'>
                            <a  class='btn btn-primary float-right' href='".url('/')."/storage/download/link/link_analysis_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
                          </div>                    
                        </div>
                      </div>";
        $str.="<div class='card-body'>
                   
                    <ul class='list-group'>";  

        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Total Links")." <span class='badge badge-primary badge-pill'>".$total_link."</span></li>";

        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("External Links")." <span class='badge badge-primary badge-pill'>".$total_external_link."</span></li>";

        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Internal Links")." <span class='badge badge-primary badge-pill'>".$total_internal_link."</span></li>";

        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("DoFollow Links")." <span class='badge badge-primary badge-pill'>".$total_dofollow."</span></li>";

        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("NoFollow Links")." <span class='badge badge-primary badge-pill'>".$total_nofollow."</span></li>";

        $str.="</ul></div></div>";

        $write_data=[];            
        $write_data[]="Links"; 
        $write_data[]="Type"; 
        $write_data[]="DoFollow/NoFollow"; 
        fputcsv($download_path, $write_data);


        $str.="<div class='card'>
                      <div class='card-header'>
                        <h4><i class='fas fa-anchor'></i> ".__("Internal Links")."</h4>
                        <div class='card-header-action'>
                          <div class='badges'>
                            <a  class='btn btn-primary float-right' href='".url('/')."/storage/download/link/link_analysis_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
                          </div>                    
                        </div>
                      </div>";
        if(count($link_analysis_data["internal_link"])==0)  
        $str.="<h6 class='text-center'>".__("No Data Found:")."</h6>";

        $str.="<div class='card-body'>
                <h6 class='text-center'>".__("Internal Links")."</h6>
                    <ul class='list-group'>"; 



        $count=0;
        foreach ($link_analysis_data["internal_link"] as $key => $value) 
        {
            $count++;
            $write_data=[];            
            $write_data[]=$value['link']; 
            $write_data[]="Internal"; 
            $write_data[]=$value['type']; 
            fputcsv($download_path, $write_data); 

            if($value["type"]=="dofollow")
                $dofollow_nofollow="<span class='badge badge-success badge-pill'>".$value['type']."</span>";
            else 
                $dofollow_nofollow="<span class='badge badge-danger badge-pill'>".$value['type']."</span>";


            $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$value['link']." ".$dofollow_nofollow."</li>";

            $link_analysis_complete++;
            session()->put("link_analysis_complete_search",$link_analysis_complete);   
        }
        $str.="</ul></div></div>";
        

        $str.="<div class='card'>
                      <div class='card-header'>
                        <h4><i class='fas fa-anchor'></i> ".__("External Links")."</h4>
                        <div class='card-header-action'>
                          <div class='badges'>
                            <a  class='btn btn-primary float-right' href='".url('/')."/storage/download/link/link_analysis_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
                          </div>                    
                        </div>
                      </div>";
        if(count($link_analysis_data["external_link"])==0)  
            $str.="<h6 class='text-center'>".__("No Data Found:")."</h6>";

        $str.="<div class='card-body'>
                <h6 class='text-center'>".__("External Links")."</h6>
                    <ul class='list-group'>"; 


        $count=0;
        foreach ($link_analysis_data["external_link"] as $key => $value) 
        {
            $count++;
            $write_data=[];            
            $write_data[]=$value['link']; 
            $write_data[]="External"; 
            $write_data[]=$value['type']; 
            fputcsv($download_path, $write_data); 

            if($value["type"]=="dofollow")
                $dofollow_nofollow="<span class='badge badge-success badge-pill'>".$value['type']."</span>";
            else 
                $dofollow_nofollow="<span class='badge badge-danger badge-pill'>".$value['type']."</span>";


            $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$value['link']." ".$dofollow_nofollow."</li>";

            $link_analysis_complete++;
            session()->put("link_analysis_complete_search",$link_analysis_complete);  
        }
        $str.="</ul></div></div>";


        /** Insert into database ***/

        $insert_data=[
        
            'user_id'                           => Auth::user()->id,
            'searched_at'                       => $searched_at,
            'url'                               => $url,
            'external_link_count'               => $total_external_link,
            'internal_link_count'               => $total_internal_link,
            'nofollow_count'                    => $total_nofollow,
            'do_follow_count'                   => $total_dofollow,
            'external_link'                     => json_encode($link_analysis_data["external_link"]),
            'internal_link'                     => json_encode($link_analysis_data["internal_link"]),
            'searched_at'                       => $searched_at
        ];

     
        if(DB::table('link_analysis')->insert($insert_data))
        {
            //******************************//
            // insert data to useges log table
            $this->_insert_usage_log($module_id=7,$req=1);   
            //******************************//
            
            echo $str;     
        }
       

    } 

    public function link_analysis_download(Request $request)
    {
        $all=$request->input("ids");
        $table = 'link_analysis';
        $query=DB::table($table);
        if($all!=0)
        {
            $id_array = [];
            foreach ($all as  $value) 
            {
                $id_array[] = $value;
            }
            
            $query->whereIn('id',$id_array);
        }

        $where['where'] = array('user_id'=>Auth::user()->id);

        // $info = $this->basic->get_data($table, $where, $select ='', $join='', $limit='', $start=null, $order_by='id asc');
        $info = $query->where('user_id',Auth::user()->id)->orderBy('id')->get(); 
        $download_id=time();
        $download_id=time();
        if (!file_exists(storage_path("app/public/download/link"))) {
           mkdir(storage_path("app/public/download/link"), 0777, true);
       }
       $fp=fopen(storage_path("app/public/download/link/link_analysis_{$download_id}.csv"), "w");

        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        
        $write_data=array();            
        $write_data[]="URL"; 
        $write_data[]="Links"; 
        $write_data[]="Type"; 
        $write_data[]="DoFollow/NoFollow"; 
        fputcsv($fp, $write_data);


        foreach ($info as $row) 
        {
            
            $internal_json_data=json_decode($row->internal_link);
            foreach ($internal_json_data as $key => $value) 
            {              
                $write_data=[];            
                $write_data[]=$row->url; 
                $write_data[]=$value->link; 
                $write_data[]="Internal"; 
                $write_data[]=$value->type; 
                fputcsv($fp, $write_data);  
            }

            $external_json_data=json_decode($row->external_link);
            foreach ($external_json_data as $key => $value) 
            {                
                $write_data=[];            
                $write_data[]=$row->url; 
                $write_data[]=$value->link; 
                $write_data[]="External"; 
                $write_data[]=$value->type; 
                fputcsv($fp, $write_data);                
            }       
                
        }

        fclose($fp);
        $file_name = "/storage/download/link/link_analysis_{$download_id}.csv";
       
        echo "<p>".__("Your file is ready to download")."</p> <a href=".url('/').$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".__("Download")."</a>";
    }

    public function link_analysis_delete(Request $request)
    {
        $all=$request->input("ids");
        $query=DB::table('link_analysis')->select('link_analysis.*');
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

   
    public function bulk_link_analysis_progress_count(Request $request)
    {
        $bulk_tracking_total_search=session('link_analysis_bulk_total_search'); 
        $bulk_complete_search=session('link_analysis_complete_search'); 
        
        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        
        echo json_encode($response);
        
    }


    public function page_status_list_data(Request $request)
    {

         $searching       = trim($request->input("searching"));
         $post_date_range = $request->input("post_date_range");
         $display_columns = array("#",'CHECKBOX','id','url','http_code','status','total_time','namelookup_time','connect_time','speed_download','check_date');
         $search_columns = array('url','check_date');
 
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
         $table = "page_status";
         $query = DB::table($table);
         if($from_date!='') $query->where("check_date", ">=", $from_date);
         if($to_date!='') $query->where("check_date", "<=", $to_date);
     
 
         if ($searching != '')
         {
             $query->where(function($query) use ($search_columns,$searching){
                 foreach ($search_columns as $key => $value) $query->orWhere($value, 'like',  "%$searching%");
             });
         }
         $query->where(function($query) use ($user_id){
             $query->orWhere('page_status.user_id', '=', $user_id);
         });
 
        //  $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        $info = $query->orderByRaw($order_by)->offset($start)->limit($limit)->get();

        $query = DB::table($table);
        $query->where(function($query) use ($user_id){
            $query->Where('page_status.user_id', '=', $user_id);
        });

        $total_result=$query->count();
         for($i=0;$i<count($info);$i++)
         {  
          $info[$i]->check_date = date("jS M y",strtotime($info[$i]->check_date));
         }

         $data['draw'] = (int)$_POST['draw'] + 1;
         $data['recordsTotal'] = $total_result;
         $data['recordsFiltered'] = $total_result;
         $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");
 
         echo json_encode($data);
 
    }
 
    public function page_status_action(Request $request)
    {
 
         $urls=strip_tags($request->input('domain_name'));      
        
         $urls=str_replace("\n", ",", $urls);
         $url_array=explode(",", $urls);
         $url_array=array_filter($url_array);
         $url_array=array_unique($url_array);
 
 
         //************************************************//
        $status=$this->_check_usage($module_id=7,$req=count($url_array));
        
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
         
       
         session()->put('page_status_bulk_total_search', count($url_array));
         session()->put('page_status_complete_search', 0);
         $download_id= time();
         $download_id=time();
         if (!file_exists(storage_path("app/public/download/page_status"))) {
            mkdir(storage_path("app/public/download/page_status"), 0777, true);
        }
        $download_path=fopen(storage_path("app/public/download/page_status/page_status_{$download_id}.csv"), "w");
         
        // make output csv file unicode compatible
         fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
         $total_count=0;
         
         /**Write header in csv file***/
         $write_domain[]="URL";
         $write_domain[]="HTTP Code";
         $write_domain[]="Status";
         $write_domain[]="Total Time (sec)";
         $write_domain[]="Name Lookup Time (sec)";
         $write_domain[]="Connect Time (sec)";
         $write_domain[]="Download Speed Time";
         $write_domain[]="Check Status Date";           
         
         fputcsv($download_path, $write_domain);
 
         $http_codes = array( 100 => 'Continue', 101 => 'Switching Protocols', 102 => 'Processing', 200 => 'OK',
          201 => 'Created', 202 => 'Accepted', 203 => 'Non-Authoritative Information', 204 => 'No Content',
          205 => 'Reset Content', 206 => 'Partial Content', 207 => 'Multi-Status', 300 => 'Multiple Choices',
          301 => 'Moved Permanently', 302 => 'Found', 303 => 'See Other', 304 => 'Not Modified', 305 => 'Use Proxy',
          306 => 'Switch Proxy', 307 => 'Temporary Redirect', 400 => 'Bad Request', 401 => 'Unauthorized',
          402 => 'Payment Required', 403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed',
          406 => 'Not Acceptable', 407 => 'Proxy Authentication Required', 408 => 'Request Timeout', 409 => 'Conflict',410 => 'Gone', 411 => 'Length Required', 412 => 'Precondition Failed', 413 => 'Request Entity Too Large',414 => 'Request-URI Too Long', 415 => 'Unsupported Media Type', 416 => 'Requested Range Not Satisfiable',417 => 'Expectation Failed', 418 => 'I\'m a teapot', 422 => 'Unprocessable Entity', 423 => 'Locked',
          424 => 'Failed Dependency', 425 => 'Unordered Collection', 426 => 'Upgrade Required', 449 => 'Retry With',
          450 => 'Blocked by Windows Parental Controls', 500 => 'Internal Server Error', 501 => 'Not Implemented',
          502 => 'Bad Gateway', 503 => 'Service Unavailable', 504 => 'Gateway Timeout',
          505 => 'HTTP Version Not Supported', 506 => 'Variant Also Negotiates', 507 => 'Insufficient Storage',
          509 => 'Bandwidth Limit Exceeded', 510 => 'Not Extended',
          0 => 'Not Registered' );
         
        $google_safety_apis=DB::table('config')->first();
         $api="";
         $config_data= DB::table('config')->where("user_id",Auth::user()->id)->get();
         if(count($config_data)>0) $api=$google_safety_apis->google_safety_api;
 
         $str = "<div class='card'>
                     <div class='card-header'>
                       <h4><i class='fas fa-anchor'></i> ".__("Page Status Check")."</h4>
                       <div class='card-header-action'>
                         <div class='badges'>
                           <a  class='btn btn-primary float-right' href='".url('/')."/storage/download/page_status/page_status_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
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
             $time=date("Y-m-d H:i:s");
             $domain_info=  $this->web_repport->page_status_check($domain);
             $write_domain=[];
             $write_domain[]=$domain;
             $write_domain[]=$domain_info['http_code'];
             $write_domain[]=$http_codes[$domain_info['http_code']];
             $write_domain[]=$domain_info['total_time'];
             $write_domain[]=$domain_info['namelookup_time'];
             $write_domain[]=$domain_info['connect_time'];
             $write_domain[]=$domain_info['speed_download'];
             $write_domain[]=$time;
             fputcsv($download_path, $write_domain);
             
             $insert_data=[
                                 'url'        => $domain,
                                 'user_id'    => Auth::user()->id,
                                 'http_code'    => $domain_info['http_code'],
                                 'status'    => $http_codes[$domain_info['http_code']],
                                 'total_time'    => $domain_info['total_time'],
                                 'namelookup_time'    =>$domain_info['namelookup_time'],
                                 'connect_time' =>$domain_info['connect_time'],
                                 'speed_download'    =>$domain_info['speed_download'],
                                 'check_date'        => $time
             ];
 
             if ($tab == 1) {
                 $str.="<div class='tab-pane fade active show' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                         <ul class='list-group'>";
 
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("HTTP Code")." <span class='badge badge-primary badge-pill'>".$domain_info['http_code']."</span></li>";
 
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Status")." <span class='badge badge-primary badge-pill'>".$http_codes[$domain_info['http_code']]."</span></li>";    
 
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Total Time (sec)")." <span class='badge badge-primary badge-pill'>".$domain_info['total_time']."</span></li>";  
 
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Name Lookup Time (sec)")." <span class='badge badge-primary badge-pill'>".$domain_info['namelookup_time']."</span></li>";
 
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Connect Time (sec)")." <span class='badge badge-primary badge-pill'>".$domain_info['connect_time']."</span></li>";  
 
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Download Speed Time")." <span class='badge badge-primary badge-pill'>".$domain_info['speed_download']."</span></li>";
 
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Check Date Time")." <span class='badge badge-primary badge-pill'>".$time."</span></li>";     
 
 
                 $str.= "</ul></div>";
             }
             else{
                 $str.="<div class='tab-pane fade' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                         <ul class='list-group'>";
 
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("HTTP Code")." <span class='badge badge-primary badge-pill'>".$domain_info['http_code']."</span></li>";
 
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Status")." <span class='badge badge-primary badge-pill'>".$http_codes[$domain_info['http_code']]."</span></li>";    
 
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Total Time (sec)")." <span class='badge badge-primary badge-pill'>".$domain_info['total_time']."</span></li>";  
 
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Name Lookup Time (sec)")." <span class='badge badge-primary badge-pill'>".$domain_info['namelookup_time']."</span></li>";
 
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Connect Time (sec)")." <span class='badge badge-primary badge-pill'>".$domain_info['connect_time']."</span></li>";  
 
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Download Speed Time")." <span class='badge badge-primary badge-pill'>".$domain_info['speed_download']."</span></li>";
 
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".__("Check Date Time")." <span class='badge badge-primary badge-pill'>".$time."</span></li>";     
                 
                 $str.= "</ul></div>";
             }
 
            //  $this->basic->insert_data('page_status', $insert_data);
            DB::table('page_status')->insert($insert_data);
         }
         $str.="</div>
                 </div>";
 
         $this->_insert_usage_log($module_id=7,$req=count($url_array)); 
         echo $str.="</div></div></div>";
    }
 
     public function page_status_download(Request $request)
     {
        $all=$request->input("ids");
         $table = 'page_status';
         $query=DB::table($table);
         if($all!=0)
         {
             $id_array = [];
             foreach ($all as  $value) 
             {
                 $id_array[] = $value;
             }
             
             $query->whereIn('id',$id_array);
         }
    
 
        //  $info = $this->basic->get_data($table, $where, $select ='', $join='', $limit='', $start=null, $order_by='id asc');
        $info = $query->where('user_id',Auth::user()->id)->orderBy('id')->get(); 
        $download_id=time();
        $download_id=time();
        if (!file_exists(storage_path("app/public/download/page_status"))) {
           mkdir(storage_path("app/public/download/page_status"), 0777, true);
       }
       $fp=fopen(storage_path("app/public/download/page_status/page_status_{$download_id}.csv"), "w");
        
         // make output csv file unicode compatible
         fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
         $head=array("Domain Name","HTTP Code","Status","Total Time","Name Lookup Time","Connect Time","Download Speed","Check At");
                     
         fputcsv($fp, $head);
         $write_info = [];
 
         foreach ($info as  $value) 
         {
             $write_info['url'] = $value->url;
             $write_info['http_code'] = $value->http_code;
             $write_info['status'] = $value->status;
             $write_info['total_time'] = $value->total_time;
             $write_info['namelookup_time'] = $value->namelookup_time;
             $write_info['connect_time'] = $value->connect_time;
             $write_info['speed_download'] = $value->speed_download;
             $write_info['check_date'] = $value->check_date;
             
             fputcsv($fp, $write_info);
         }
 
         fclose($fp);
         $file_name = "/storage/download/page_status/page_status_{$download_id}.csv";
 
         echo "<p>".__("Your file is ready to download")."</p> <a href=".url('/').$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".__("Download")."</a>";
     }
 
 
     
 
     public function page_status_delete(Request $request)
     {
        $all=$request->input("ids");
        $query=DB::table('page_status')->select('page_status.*');
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
         $bulk_tracking_total_search=session('page_status_bulk_total_search'); 
         $bulk_complete_search=session('page_status_complete_search'); 
         
         $response['search_complete']=$bulk_complete_search;
         $response['search_total']=$bulk_tracking_total_search;
         
         echo json_encode($response);
         
     }
 
     public function read_text_csv_file_backlink(Request $request)
     {
        if ($request->method() === 'GET') {
            exit();
        }

        $ret=array();
        if (!file_exists(storage_path("app/public/upload/tmp"))) {
            mkdir(storage_path("app/public/upload/tmp"), 0777, true);
        }
        $output_dir = storage_path("app/public/upload/tmp");
        if (isset($_FILES["myfile"])) {
            $error =$_FILES["myfile"]["error"];
            $post_fileName =$_FILES["myfile"]["name"];
            $post_fileName_array=explode(".", $post_fileName);
            $ext=array_pop($post_fileName_array);
            $filename=implode('.', $post_fileName_array);
            $filename="page_check_status_".Auth::user()->id."_".time().substr(uniqid(mt_rand(), true), 0, 6).".".$ext;
  
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
        if ($request->method() === 'GET') {
            exit();
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




}
