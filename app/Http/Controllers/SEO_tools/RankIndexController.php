<?php

namespace App\Http\Controllers\SEO_tools;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Services\Custom\WebCommonReportServiceInterface;

class RankIndexController extends HomeController
{



    public function __construct(WebCommonReportServiceInterface $web_common_repport)
    {
        $this->set_global_userdata(true,[],[],4);
        $this->web_repport= $web_common_repport;

    }

    public function moz_rank_index()
    {
        if(Auth::user()->user_type != 'Admin' && !in_array(4,$this->module_access))
        redirect('home/login_page', 'location');
        $this->important_feature();
        $this->member_validity();
        $data['body'] = "seo-tools.analysis-tools.rank-analysis.moz-rank";
        return $this->_viewcontroller($data);    
    }
    public function moz_rank_analysis()
    {
        $this->important_feature();
        $this->member_validity();
        $data['body'] = "seo-tools.analysis-tools.rank-analysis.moz-rank-analysis";
        return $this->_viewcontroller($data);       
    }

    public function search_engine_index()
    {
        $this->important_feature();
        $this->member_validity();
        $data['body'] = "seo-tools.analysis-tools.rank-analysis.search-engine-index";
        return $this->_viewcontroller($data);       
    }
    public function search_engine()
    {
        $this->important_feature();
        $this->member_validity();
        $data['body'] = "seo-tools.analysis-tools.rank-analysis.search-engine";
        return $this->_viewcontroller($data);        
    }


    public function moz_rank_data(Request $request)
    {

        $searching       = trim($request->input("searching"));
        $post_date_range = $request->input("post_date_range");
        $display_columns = ["#",'CHECKBOX','id','mozrank_subdomain_normalized','mozrank_subdomain_raw','mozrank_url_normalized','mozrank_url_raw','http_status_code','domain_authority','page_authority','external_equity_links','links','url','checked_at'];
        $search_columns = ['url','checked_at'];

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
        $table = "moz_info";
        $query = DB::table($table);
        if($from_date!='') $query->where("checked_at", ">=", $from_date);
        if($to_date!='') $query->where("checked_at", "<=", $to_date);



        if ($searching != '')
        {
            $query->where(function($query) use ($search_columns,$searching){
                foreach ($search_columns as $key => $value) $query->orWhere($value, 'like',  "%$searching%");
            });
        }
        $query->where(function($query) use ($user_id){
            $query->orWhere('moz_info.user_id', '=', $user_id);
        });


        // $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        $info = $query->orderByRaw($order_by)->offset($start)->limit($limit)->get();

        $query = DB::table($table);
        $query->where(function($query) use ($user_id){
            $query->orWhere('moz_info.user_id', '=', $user_id);
        });

        $total_result=$query->count();

        for($i=0;$i<count($info);$i++)
        {  
            $info[$i]->checked_at = date("jS M y",strtotime($info[$i]->checked_at));        
        }

        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }


    public function moz_rank_action(Request $request)
    {
        $urls=strip_tags($request->input('domain_name'));
       
        $urls=str_replace("\n", ",", $urls);
        $url_array=explode(",", $urls);
        $url_array=array_filter($url_array);
        $url_array=array_unique($url_array);

        //************************************************//
        $status=$this->_check_usage($module_id=4,$req=count($url_array));
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
        
      
        session(['moz_rank_bulk_total_search'=> count($url_array)]);
        session(['moz_rank_complete_search'=> 0]);
        $download_id= time();
        if (!file_exists(storage_path("app/public/download/rank"))) {
           mkdir(storage_path("app/public/download/rank"), 0777, true);
       }
       $download_path=fopen(storage_path("app/public/download/rank/moz_{$download_id}.csv"), "w");
        
        
        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
        $total_count=0;
        
        /**Write header in csv file***/
                                             
        
        $write_data[]="URL";            
        $write_data[]="Subdomain Normalized";                
        $write_data[]="Subdomain Raw";                
        $write_data[]="URL Normalized";                
        $write_data[]="URL Raw";                
        $write_data[]="HTTP Status Code";                
        $write_data[]="Domain Authority";                
        $write_data[]="Page Authority";                
        $write_data[]="External Equity Links";                
        $write_data[]="Links";           
        
        fputcsv($download_path, $write_data);

        $str = "<div class='card'>
                 <div class='card-header'>
                   <h4><i class='fas fa-trophy'></i> ".__("Moz Rank")."</h4>
                     <div class='card-header-action'>
                       <div class='badges'>
                         <a  class='btn btn-primary float-right' href='".url('/')."/storage/download/rank/moz_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
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
            $moz_data=[];

            $moz_access_ids=DB::table('config')->select('config.moz_access_id','config.moz_secret_key')->first();

            $use_admin_app = config('my_config.use_admin_app');
            if($use_admin_app == '' || $use_admin_app == 'no')
                $config_data= DB::table('config')->where("user_id",Auth::user()->id)->get();
            else
                $config_data = DB::table('config')->where('access', 'all_users')->get();

            $moz_access_id="";
            $moz_secret_key="";
            if(count($config_data)>0)
            {
                $moz_access_id=$moz_access_ids->moz_access_id;
                $moz_secret_key=$moz_access_ids->moz_secret_key;
            }

            $moz_data=$this->web_repport->get_moz_info($domain,$moz_access_id, $moz_secret_key);  
            $checked_at= date("Y-m-d H:i:s");
                  
            $mozrank_subdomain_normalized=$moz_data["mozrank_subdomain_normalized"];    
            $mozrank_subdomain_raw=$moz_data["mozrank_subdomain_raw"];  
            $mozrank_url_normalized=$moz_data["mozrank_url_normalized"];    
            $mozrank_url_raw=$moz_data["mozrank_url_raw"];  
            $http_status_code=$moz_data["http_status_code"];    
            $domain_authority=$moz_data["domain_authority"];    
            $page_authority=$moz_data["page_authority"];    
            $external_equity_links=$moz_data["external_equity_links"];  
            $links=$moz_data["links"];             


            $write_data=[];
            $write_data[]=$domain;
            $write_data[]=$mozrank_subdomain_normalized;
            $write_data[]=$mozrank_subdomain_raw;
            $write_data[]=$mozrank_url_normalized;
            $write_data[]=$mozrank_url_raw;
            $write_data[]=$http_status_code;
            $write_data[]=$domain_authority;
            $write_data[]=$page_authority;
            $write_data[]=$external_equity_links;
            $write_data[]=$links;

            
            fputcsv($download_path, $write_data);
            
            /** Insert into database ***/
            
            $insert_data=[
            
                'user_id'                           => Auth::user()->id,
                'url'                               => $domain,
                'mozrank_subdomain_normalized'      => $mozrank_subdomain_normalized,
                'mozrank_subdomain_raw'             => $mozrank_subdomain_raw,
                'mozrank_url_normalized'            => $mozrank_url_normalized,
                'mozrank_url_raw'                   => $mozrank_url_raw,
                'http_status_code'                  => $http_status_code,
                'domain_authority'                  => $domain_authority,
                'page_authority'                    => $page_authority,
                'external_equity_links'             => $external_equity_links,
                'links'                             => $links,
                'checked_at'                        => $checked_at
            ];  
            if ($tab == 1) {
                $str.="<div class='tab-pane fade active show' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Domain')."<span class='badge badge-primary badge-pill'>{$domain}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Subdomain Normalized')."<span class='badge badge-primary badge-pill'>{$mozrank_subdomain_normalized}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Subdomain Raw')."<span class='badge badge-primary badge-pill'>{$mozrank_subdomain_raw}</span></li>";
                        
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('URL Normalized')."<span class='badge badge-primary badge-pill'>{$mozrank_url_normalized}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('URL Raw')."<span class='badge badge-primary badge-pill'>{$mozrank_url_raw}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('HTTP Status Code')."<span class='badge badge-primary badge-pill'>{$http_status_code}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Domain Authority')."<span class='badge badge-primary badge-pill'>{$domain_authority}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Page Authority')."<span class='badge badge-primary badge-pill'>{$page_authority}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('External Equity Links')."<span class='badge badge-primary badge-pill'>{$external_equity_links}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Links')."<span class='badge badge-primary badge-pill'>{$links}</span></li>";
                $str.= "</ul></div>";
            }
            else{
                $str.="<div class='tab-pane fade' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Domain')."<span class='badge badge-primary badge-pill'>{$domain}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Subdomain Normalized')."<span class='badge badge-primary badge-pill'>{$mozrank_subdomain_normalized}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Subdomain Raw')."<span class='badge badge-primary badge-pill'>{$mozrank_subdomain_raw}</span></li>";
                        
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('URL Normalized')."<span class='badge badge-primary badge-pill'>{$mozrank_url_normalized}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('URL Raw')."<span class='badge badge-primary badge-pill'>{$mozrank_url_raw}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('HTTP Status Code')."<span class='badge badge-primary badge-pill'>{$http_status_code}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Domain Authority')."<span class='badge badge-primary badge-pill'>{$domain_authority}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Page Authority')."<span class='badge badge-primary badge-pill'>{$page_authority}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('External Equity Links')."<span class='badge badge-primary badge-pill'>{$external_equity_links}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Links')."<span class='badge badge-primary badge-pill'>{$links}</span></li>";                
                $str.= "</ul></div>";
            }

            // $this->basic->insert_data('moz_info', $insert_data);
            DB::table('moz_info')->insert($insert_data);
            sleep(10);
        }
        $str.="</div>
                </div>";

        $this->_insert_usage_log($module_id=4,$req=count($url_array)); 
        echo $str.="</div></div></div>";

    }

    public function moz_rank_download(Request $request)
    {
        $all=$request->input("ids");
        $table = 'moz_info';
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

        $info = $query->where('user_id',Auth::user()->id)->orderBy('id')->get(); 
        $download_id=time();
        if (!file_exists(storage_path("app/public/download/rank"))) {
            mkdir(storage_path("app/public/download/rank"), 0777, true);
        }
        $fp=fopen(storage_path("app/public/download/rank/moz_{$download_id}.csv"), "w");

        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));


        $head=["URL","Subdomain Normalized","Subdomain Raw","URL Normalized","URL Raw","HTTP Status Code","Domain Authority","Page Authority","External Equity Links","Links","Checked at"];
                    
        fputcsv($fp, $head);
        $write_info = [];

        foreach ($info as  $value) 
        {
         
            $write_info['url'] = $value->url;
            $write_info['mozrank_subdomain_normalized'] = $value->mozrank_subdomain_normalized;
            $write_info['mozrank_subdomain_raw'] = $value->mozrank_subdomain_raw;
            $write_info['mozrank_url_normalized'] = $value->mozrank_url_normalized;
            $write_info['mozrank_url_raw'] = $value->mozrank_url_raw;
            $write_info['http_status_code'] = $value->http_status_code;
            $write_info['domain_authority'] = $value->domain_authority;
            $write_info['page_authority'] = $value->page_authority;
            $write_info['external_equity_links'] = $value->external_equity_links;
            $write_info['links'] = $value->links;
            $write_info['checked_at'] = $value->checked_at;
            
            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "/storage/download/rank/moz_{$download_id}.csv";
        echo "<p>".__("Your file is ready to download")."</p> <a href=".url('/').$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".__("Download")."</a>";
    }


    

    public function moz_rank_delete(Request $request)
    {
        if(session('user_type') != 'Admin' && !in_array(4,$this->module_access))
        exit();

        $all=$request->input("ids");
        $query=DB::table('moz_info')->select('moz_info.*');
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


    public function read_text_csv_file_backlink()
    {
          if ($_SERVER['REQUEST_METHOD'] === 'GET') exit();

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
              $filename="alexa_rank_analysis_".Auth::user()->id."_".time().substr(uniqid(mt_rand(), true), 0, 6).".".$ext;

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

    public function read_after_delete_csv_txt() // deletes the uploaded video to upload another one
    {
          if(!$_POST) exit();
         
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



    public function search_engine_index_data(Request $request)
    {

        $searching       = trim($request->input("searching"));
        $post_date_range = $request->input("post_date_range");
        $display_columns = ["#",'CHECKBOX','id','domain_name','google_index','bing_index','yahoo_index','checked_at'];
        $search_columns = ['domain_name','checked_at'];

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
        $table = "search_engine_index";
        $query = DB::table($table);
        if($from_date!='') $query->where("checked_at", ">=", $from_date);
        if($to_date!='') $query->where("checked_at", "<=", $to_date);

        if ($searching != '')
        {
            $query->where(function($query) use ($search_columns,$searching){
                foreach ($search_columns as $key => $value) $query->orWhere($value, 'like',  "%$searching%");
            });
        }
        $query->where(function($query) use ($user_id){
            $query->orWhere('search_engine_index.user_id', '=', $user_id);
        });

        // $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        $info = $query->orderByRaw($order_by)->offset($start)->limit($limit)->get();

        $query = DB::table($table);
        $query->where(function($query) use ($user_id){
            $query->orWhere('search_engine_index.user_id', '=', $user_id);
        });
        $total_result=$query->count();
        for($i=0;$i<count($info);$i++)
        {  
         $info[$i]->checked_at = date("jS M y",strtotime($info[$i]->checked_at));
        
        }

        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);

    }


    public function search_engine_index_action(Request $request)
    {
        $urls=$request->input('domain_name');
        $is_google=$request->input('is_google');
        $is_bing=$request->input('is_bing');
        $is_yahoo=$request->input('is_yahoo');
        
        $urls=str_replace("\n", ",", $urls);
        $url_array=explode(",", $urls);
        $url_array=array_filter($url_array);
        $url_array=array_unique($url_array);

        //************************************************//
        $status=$this->_check_usage($module_id=4,$req=count($url_array));
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
        
      
        // $this->session->set_userdata('search_engine_index_bulk_total_search',count($url_array));
        // $this->session->set_userdata('search_engine_index_complete_search',0);
        session(['search_engine_index_bulk_total_search'=> count($url_array)]);
        session(['search_engine_index_complete_search'=> 0]);
        $download_id= time();
        if (!file_exists(storage_path("app/public/download/search_engine_index"))) {
            mkdir(storage_path("app/public/download/search_engine_index"), 0777, true);
        }
        $download_path=fopen(storage_path("app/public/download/search_engine_index/search_engine_index_{$download_id}.csv"), "w");
        
        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
        $total_count=0;
        
        /**Write header in csv file***/
        $write_data[]="Domain";
        if($is_google==1) $write_data[]="Google Index";
        if($is_bing==1) $write_data[]="Bing Index";
        if($is_yahoo==1) $write_data[]="Yahoo Index";
        $write_data[]="Checked at";            
        
        fputcsv($download_path, $write_data);

        $str = "<div class='card'>
                  <div class='card-header'>
                    <h4><i class='fas fa-trophy'></i> ".__("Search Engine Index")."</h4>
                      <div class='card-header-action'>
                        <div class='badges'>
                          <a  class='btn btn-primary float-right' href='".url('/')."/storage/download/search_engine_index/search_engine_index_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
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

            $google_index="";
            $bing_index="";
            $yahoo_index="";
            
            if($is_google==1)   
              $google_index = $this->web_repport->GoogleIP($domain,$proxy="");
            if($is_bing==1) 
              $bing_index = $this->web_repport->bing_index($domain,$proxy="");
            if($is_yahoo==1)    
              $yahoo_index = $this->web_repport->yahoo_index($domain,$proxy="");
            
            $checked_at= date("Y-m-d H:i:s");
                  
            $write_data=[];
            $write_data[]=$domain;
            if($is_google==1)   $write_data[]=$google_index;
            if($is_bing==1)     $write_data[]=$bing_index;
            if($is_yahoo==1)    $write_data[]=$yahoo_index;
            $write_data[]=$checked_at;
            
            fputcsv($download_path, $write_data);
            
            /** Insert into database ***/
            
            $insert_data=[
            
                'user_id'           => Auth::user()->id,
                'domain_name'       => $domain,
                'checked_at'        => $checked_at
            ];
            if($is_google==1)   
              $insert_data["google_index"]=$google_index;
            if($is_bing==1)     
              $insert_data["bing_index"]=$bing_index;
            if($is_yahoo==1)    
              $insert_data["yahoo_index"]=$yahoo_index;

            if ($tab == 1) {
                $str.="<div class='tab-pane fade active show' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";

                if($is_google==1) 
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Google Index')."<span class='badge badge-primary badge-pill'>{$google_index}</span></li>";
                if($is_bing==1) 
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Bing Index')."<span class='badge badge-primary badge-pill'>{$bing_index}</span></li>";
                if($is_yahoo==1) 
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Yahoo Index')."<span class='badge badge-primary badge-pill'>{$yahoo_index}</span></li>";

                $str.= "</ul></div>";
            }
            else{
                $str.="<div class='tab-pane fade' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";
                        
                        if($is_google==1) 
                            $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Google Index')."<span class='badge badge-primary badge-pill'>{$google_index}</span></li>";
                        if($is_bing==1) 
                            $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Bing Index')."<span class='badge badge-primary badge-pill'>{$bing_index}</span></li>";
                        if($is_yahoo==1) 
                            $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".__('Yahoo Index')."<span class='badge badge-primary badge-pill'>{$yahoo_index}</span></li>";
                $str.= "</ul></div>";
            }

            // $this->basic->insert_data('search_engine_index', $insert_data);
            DB::table('search_engine_index')->insert($insert_data);

        }
        $str.="</div>
                </div>";

        $this->_insert_usage_log($module_id=4,$req=count($url_array)); 
        echo $str.="</div></div></div>";

    }

  

    public function search_engine_index_download(Request $request)
    {
        $all=$request->input("ids");
        $table = 'search_engine_index';
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

        $info = $query->where('user_id',Auth::user()->id)->orderBy('id')->get(); 
        $download_id=time();
        if (!file_exists(storage_path("app/public/download/search_engine_index"))) {
            mkdir(storage_path("app/public/download/search_engine_index"), 0777, true);
        }
        $fp=fopen(storage_path("app/public/download/search_engine_index/search_engine_index_{$download_id}.csv"), "w");
        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        $head=["Doamin","Google Index","Bing Index","Yahoo Index","Scanned at"];
                    
        fputcsv($fp, $head);
        $write_info = [];

        foreach ($info as  $value) 
        {
            $write_info['domain_name']  = $value->domain_name;
            $write_info['google_index'] = $value->google_index;
            $write_info['bing_index']   = $value->bing_index;
            $write_info['yahoo_index']  = $value->yahoo_index;
            $write_info['checked_at']   = $value->checked_at;
            
            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "/storage/download/search_engine_index/search_engine_index_{$download_id}.csv";
        echo "<p>".__("Your file is ready to download")."</p> <a href=".url('/').$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".__("Download")."</a>";
    }


    

    public function search_engine_index_delete(Request $request)
    {
        $all=$request->input("ids");
        $query=DB::table('search_engine_index')->select('moz_info.*');
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


    
    public function bulk_search_engine_index_progress_count()
    {
        $bulk_tracking_total_search=session('search_engine_index_bulk_total_search'); 
        $bulk_complete_search=session('search_engine_index_complete_search'); 
        
        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        
        echo json_encode($response);
        
    }

    public function read_sengine_text_csv_file_backlink(Request $request)
    {
        if ($request->method() === 'GET') {
            exit();
        }

        $ret=array();
        $download_id=time();
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
            $filename="alexa_rank_analysis_".Auth::user()->id."_".time().substr(uniqid(mt_rand(), true), 0, 6).".".$ext;
  
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

    public function read_sengine_after_delete_csv_txt(Request $request) // deletes the uploaded video to upload another one
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
