<?php

namespace App\Http\Controllers\SEO_tools;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Services\Custom\WebCommonReportServiceInterface;


class KeywordAnalysisController extends HomeController
{

    public function __construct(WebCommonReportServiceInterface $web_common_repport)
    {
        $this->set_global_userdata(true,[],[],7);    
        $this->web_repport= $web_common_repport;        
    }

    public function keyword_analyzer()
    {
        
        $data['body'] = "seo-tools.analysis-tools.keyword-analysis.keyword-analyzer";
        return $this->_viewcontroller($data);   
    }
    
    public function index()
    {
        
        $data['body'] = "seo-tools.analysis-tools.keyword-analysis.position-index";
        return $this->_viewcontroller($data);    
    }
    public function position_keyword_new()
    {
        
        $data['body'] = "seo-tools.analysis-tools.keyword-analysis.position-new";
        return $this->_viewcontroller($data);    
    }
    
    public function keyword_suggestion()
    {
        
        $data['body'] = "seo-tools.analysis-tools.keyword-analysis.keyword-suggestion-index";
        return $this->_viewcontroller($data);     
    }
    public function keyword_suggestion_new()
    {
        
        $data['body'] = "seo-tools.analysis-tools.keyword-analysis.keyword-suggestion-new";
        return $this->_viewcontroller($data);    
    }

    public function keyword_analyzer_data(Request $request)
    {
        //************************************************//
        $status=$this->_check_usage($module_id=8,$re=1);
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

        $domain_name = strip_tags($request->input('keyword_domain_name'));
        $data['domain_name'] = $domain_name;
        $data['meta_tag_info'] = $this->web_repport->content_analysis($domain_name);
       
        //******************************//
        // insert data to useges log table
        $this->_insert_usage_log($module_id=8,$req=1);   
        //******************************//
        $str = view('seo-tools.analysis-tools.keyword-analysis.meta-tag-details', $data)->render();
        // $str = $this->load->view('keyword_analysis/meta_tag_details',$data);
        echo $str;
    }


    public function keyword_suggestion_data(Request $request)
    {

         $searching       = trim($request->input("searching"));
         $post_date_range = $request->input("post_date_range");
         $display_columns = array("#",'CHECKBOX','id','keyword','searched_at');
         $search_columns = array('keyword','searched_at');
 
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
         $table = "keyword_suggestion";
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
             $query->orWhere('keyword_suggestion.user_id', '=', $user_id);
         });
     
 
        //  $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        $info = $query->orderByRaw($order_by)->offset($start)->limit($limit)->get();

        $query = DB::table($table);
        $query->where(function($query) use ($user_id){
            $query->Where('keyword_suggestion.user_id', '=', $user_id);
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
 
 
 
     public function keyword_suggestion_action(Request $request)
     {
         //************************************************//
         $status=$this->_check_usage($module_id=8,$req=1);
         
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
 
         
         $keyword=strip_tags($request->input('keyword'));
         $is_google=$request->input('is_google');
         $is_bing=$request->input('is_bing');
         $is_yahoo=$request->input('is_yahoo');
         $is_wiki=$request->input('is_wiki');
         $is_amazon=$request->input('is_amazon');
 
         //$caption="Keyword: ".$keyword;
 
         $download_id=time();      
         if (!file_exists(storage_path("app/public/download/keyword_position"))) {
            mkdir(storage_path("app/public/download/keyword_position"), 0777, true);
        }
        $download_path=fopen(storage_path("app/public/download/keyword_position/keyword_suggestion_{$download_id}.csv"), "w");  
         
        // make output csv file unicode compatible
         fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
         $total_count=0;
         
         /**Write header in csv file***/
         $write_data=[];            
         $write_data[]="Keyword";            
         $write_data[]="Search Engine";                  
         $write_data[]="Auto Suggestion";                  
         
         fputcsv($download_path, $write_data); 
        
         $no_process=0;
         $no_process=$is_google+$is_bing+$is_yahoo+$is_wiki+$is_amazon;
         session(['keyword_suggestion_complete_search'=>0]);
         session(['keyword_suggestion_bulk_total_search'=>$no_process]);
         $keyword_suggestion_complete_search=0;
 
         $searched_at= date("Y-m-d H:i:s");
 
         $keyword_suggestion_google_data=array();
         $keyword_suggestion_bing_data=array();        
         $keyword_suggestion_yahoo_data=array();        
         $keyword_suggestion_wiki_data=array();        
         $keyword_suggestion_amazon_data=array();        
         if($is_google==1)   
         {
             $keyword_suggestion_google_data=$this->web_repport->google_auto_sugesstion_keyword($keyword); 
             $keyword_suggestion_complete_search++; 
             session(['keyword_suggestion_complete_search'=>$keyword_suggestion_complete_search]);
         }
         if($is_bing==1)     
         {
             $keyword_suggestion_bing_data=$this->web_repport->bing_auto_sugesstion_keyword($keyword);
             $keyword_suggestion_complete_search++;  
             session(['keyword_suggestion_complete_search'=>$keyword_suggestion_complete_search]);
         }
         if($is_yahoo==1)    
         {
             $keyword_suggestion_yahoo_data=$this->web_repport->yahoo_auto_sugesstion_keyword($keyword); 
             $keyword_suggestion_complete_search++; 
             session(['keyword_suggestion_complete_search'=>$keyword_suggestion_complete_search]);
         }
         if($is_wiki==1)    
         {
             $keyword_suggestion_wiki_data=$this->web_repport->wiki_auto_sugesstion_keyword($keyword);  
             $keyword_suggestion_complete_search++;
             session(['keyword_suggestion_complete_search'=>$keyword_suggestion_complete_search]);
         }
         if($is_amazon==1)    
         {
             $keyword_suggestion_amazon_data=$this->web_repport->amazon_auto_sugesstion_keyword($keyword);
             $keyword_suggestion_complete_search++;  
             session(['keyword_suggestion_complete_search'=>$keyword_suggestion_complete_search]);
         }
 
         $google_suggestion=[];
         $bing_suggestion=[];
         $yahoo_suggestion=[];
         $wiki_suggestion=[];
         $amazon_suggestion=[];
         
         
         if(!is_array($keyword_suggestion_google_data))
             $keyword_suggestion_google_data=[];
             
         if(!is_array($keyword_suggestion_bing_data))
             $keyword_suggestion_bing_data=[];
             
         if(!is_array($keyword_suggestion_yahoo_data))
             $keyword_suggestion_yahoo_data=[];
             
         if(!is_array($keyword_suggestion_wiki_data))
             $keyword_suggestion_wiki_data=[];
         
         if(!is_array($keyword_suggestion_amazon_data))
             $keyword_suggestion_amazon_data=[];
             
 
         if($is_google==1    && array_key_exists(1, $keyword_suggestion_google_data))    $google_suggestion=$keyword_suggestion_google_data[1];
         if($is_bing==1      && array_key_exists(1, $keyword_suggestion_bing_data))      $bing_suggestion=$keyword_suggestion_bing_data[1];
         if($is_yahoo==1     && array_key_exists(1, $keyword_suggestion_yahoo_data))     $yahoo_suggestion=$keyword_suggestion_yahoo_data[1];
         if($is_wiki==1      && array_key_exists(1, $keyword_suggestion_wiki_data))      $wiki_suggestion=$keyword_suggestion_wiki_data[1];
         if($is_amazon==1    && array_key_exists(1, $keyword_suggestion_amazon_data))    $amazon_suggestion=$keyword_suggestion_amazon_data[1];
          
         $str="";
 
         if($is_google==1 && count($google_suggestion)!=0)
         {
             $str.="<div class='card'>
                           <div class='card-header'>
                             <h4><i class='fas fa-tag'></i> ".__("Keyword Auto Suggestion | Google Suggestion")."</h4>
                             <div class='card-header-action'>
                               <div class='badges'>
                                 <a  class='btn btn-primary float-right' href='".url('/')."/storage/download/keyword_position/keyword_suggestion_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
                               </div>                    
                             </div>
                           </div>";
             $str.="<div class='card-body'>
                         <h6 class='text-center'>".__("Keyword:").$keyword."</h6>
                         <ul class='list-group'>";   
 
             $count1=0;
 
             foreach ($google_suggestion as $key => $value) 
             {
                 
                 $count1++;
 
                 $write_data=array();
                 $write_data[]=$keyword;            
                 $write_data[]="Google";            
                 $write_data[]=$value;
                 fputcsv($download_path, $write_data);
 
 
              $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$value." <span class='badge badge-primary badge-pill'>".$count1."</span></li>";
                  
             }
             $str.="</ul></div></div>";
         }
 
 
         if($is_bing==1 && count($bing_suggestion)!=0)
         {
 
             $str.="<div class='card'>
                           <div class='card-header'>
                             <h4><i class='fas fa-tag'></i> ".__("Keyword Auto Suggestion | Bing Suggestion")."</h4>
                             <div class='card-header-action'>
                               <div class='badges'>
                                 <a  class='btn btn-primary float-right' href='".url('/')."/storage/download/keyword_position/keyword_suggestion_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
                               </div>                    
                             </div>
                           </div>";
             $str.="<div class='card-body'>
                         <h6 class='text-center'>".__("Keyword:").$keyword."</h6>
                         <ul class='list-group'>";   
 
             $count2=0;
 
             foreach ($bing_suggestion as $key => $value) 
             {
                 
                 $count2++;
 
                 $write_data=array();
                 $write_data[]=$keyword;            
                 $write_data[]="Bing";            
                 $write_data[]=$value;
                 fputcsv($download_path, $write_data);
 
                  $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$value." <span class='badge badge-primary badge-pill'>".$count2."</span></li>";
                      
                 }
                 $str.="</ul></div></div>";
         }
 
 
         if($is_yahoo==1 && count($yahoo_suggestion)!=0)
         {
 
             $str.="<div class='card'>
                           <div class='card-header'>
                             <h4><i class='fas fa-tag'></i> ".__("Keyword Auto Suggestion | Yahoo Suggestion")."</h4>
                             <div class='card-header-action'>
                               <div class='badges'>
                                 <a  class='btn btn-primary float-right' href='".url('/')."/storage/download/keyword_position/keyword_suggestion_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
                               </div>                    
                             </div>
                           </div>";
             $str.="<div class='card-body'>
                         <h6 class='text-center'>".__("Keyword:").$keyword."</h6>
                         <ul class='list-group'>";   
 
 
             $count3=0;
 
             foreach ($yahoo_suggestion as $key => $value) 
             {
                 
                 $count3++;
 
                 $write_data=array();
                 $write_data[]=$keyword;            
                 $write_data[]="Yahoo";            
                 $write_data[]=$value;
                 fputcsv($download_path, $write_data);
 
                 $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$value." <span class='badge badge-primary badge-pill'>".$count3."</span></li>";
                      
                 }
                 $str.="</ul></div></div>";
         }
 
 
 
         if($is_wiki==1 && count($wiki_suggestion)!=0)
         {
             $str.="<div class='card'>
                           <div class='card-header'>
                             <h4><i class='fas fa-tag'></i> ".__("Keyword Auto Suggestion | Wiki Suggestion")."</h4>
                             <div class='card-header-action'>
                               <div class='badges'>
                                 <a  class='btn btn-primary float-right' href='".url('/')."/storage/download/keyword_position/keyword_suggestion_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
                               </div>                    
                             </div>
                           </div>";
             $str.="<div class='card-body'>
                         <h6 class='text-center'>".__("Keyword:").$keyword."</h6>
                         <ul class='list-group'>";   
 
             $count4=0;
 
             foreach ($wiki_suggestion as $key => $value) 
             {
                 
                 $count4++;
 
                 $write_data=array();
                 $write_data[]=$keyword;            
                 $write_data[]="Wiki";            
                 $write_data[]=$value;
                 fputcsv($download_path, $write_data);
 
                 $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$value." <span class='badge badge-primary badge-pill'>".$count4."</span></li>";
                      
             }
             $str.="</ul></div></div>";
         }
 
 
         if($is_amazon==1 && count($amazon_suggestion)!=0)
         {
             $str.="<div class='card'>
                           <div class='card-header'>
                             <h4><i class='fas fa-tag'></i> ".__("Keyword Auto Suggestion | Amazon Suggestion")."</h4>
                             <div class='card-header-action'>
                               <div class='badges'>
                                 <a  class='btn btn-primary float-right' href='".url('/')."/storage/download/keyword_position/keyword_suggestion_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
                               </div>                    
                             </div>
                           </div>";
             $str.="<div class='card-body'>
                         <h6 class='text-center'>".__("Keyword:").$keyword."</h6>
                         <ul class='list-group'>";   
 
             $count5=0;
 
             foreach ($amazon_suggestion as $key => $value) 
             {
                 
                 $count5++;
 
                 $write_data=array();
                 $write_data[]=$keyword;            
                 $write_data[]="Amazon";            
                 $write_data[]=$value;
                 fputcsv($download_path, $write_data);
 
                 $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$value." <span class='badge badge-primary badge-pill'>".$count5."</span></li>";
                          
             }
             $str.="</ul></div></div>";
         }
 
 
         $insert_data=array
         (
              'user_id'                          => Auth::user()->id,
              'searched_at'                      => $searched_at,
              'keyword'                          => $keyword          
         );
 
         if($is_google==1 && count($google_suggestion)!=0)
         {
              $insert_data['google_suggestion']= json_encode($google_suggestion);
         }
 
         if($is_bing==1 && count($bing_suggestion)!=0)
         {
              $insert_data['bing_suggestion']= json_encode($bing_suggestion);
         }
 
         if($is_yahoo==1 && count($yahoo_suggestion)!=0)
         {
              $insert_data['yahoo_suggestion']= json_encode($yahoo_suggestion);
         }
 
         if($is_wiki==1 && count($wiki_suggestion)!=0)
         {
            $insert_data['wiki_suggestion']= json_encode($wiki_suggestion);
         }
 
         if($is_amazon==1 && count($amazon_suggestion)!=0)
         {
             $insert_data['amazon_suggestion']= json_encode($amazon_suggestion);
         }
 
         if(count($google_suggestion)==0 && count($bing_suggestion)==0 && count($yahoo_suggestion)==0 && count($wiki_suggestion)==0 && count($amazon_suggestion)==0)
         {
             $insert_data=array();
         }
 
         if(!empty($insert_data)){
             
            DB::table('keyword_suggestion')->insert($insert_data);
 
             //******************************//
             // insert data to useges log table
             $this->_insert_usage_log($module_id=8,$req=1);   
             //******************************//  
           
             echo $str;      
         }
 
     }
 
   
 
     public function keyword_suggestion_download(Request $request)
     {
        $all=$request->input("ids");
        $table = 'keyword_suggestion';
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
        if (!file_exists(storage_path("app/public/download/keyword_position"))) {
            mkdir(storage_path("app/public/download/keyword_position"), 0777, true);
        }
        $fp=fopen(storage_path("app/public/download/keyword_position/keyword_suggestion_{$download_id}.csv"), "w");

         // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
    
        $write_data=[];            
        $write_data[]="Keyword";            
        $write_data[]="Search Engine";                  
        $write_data[]="Auto Suggestion";
        $write_data[]="Searched at";
                     
        fputcsv($fp, $write_data);

        $write_data=[];   
        foreach ($info as  $value) 
        {         
                    
            $keyword = $value->keyword;
            $searched_at = $value->searched_at;

            $google_suggestion= json_decode($value->google_suggestion);
            $bing_suggestion= json_decode($value->bing_suggestion);
            $yahoo_suggestion= json_decode($value->yahoo_suggestion);
            $wiki_suggestion= json_decode($value->wiki_suggestion);
            $amazon_suggestion= json_decode($value->amazon_suggestion);


        if(count($google_suggestion)>0)
        {
            foreach($google_suggestion as $key=>$val)
            {  
            
                $write_data=[]; 
                $write_data[]=$keyword;            
                $write_data[]="Google";  
                $write_data[]=$val;  
                $write_data[]=$searched_at;                  
                fputcsv($fp, $write_data);                               
            }
        }

            if(count($bing_suggestion)>0)
            {
                foreach($bing_suggestion as $key=>$val)
                {  
                
                    $write_data=[]; 
                    $write_data[]=$keyword;            
                    $write_data[]="Bing";  
                    $write_data[]=$val;  
                    $write_data[]=$searched_at;                  
                    fputcsv($fp, $write_data);                               
                }
            }

            if(count($yahoo_suggestion)>0)
            {
                foreach($yahoo_suggestion as $key=>$val)
                {  
                
                    $write_data=[]; 
                    $write_data[]=$keyword;            
                    $write_data[]="Yahoo";  
                    $write_data[]=$val;  
                    $write_data[]=$searched_at;                  
                    fputcsv($fp, $write_data);                               
                }
            }

            if(count($wiki_suggestion)>0)
            {
                foreach($wiki_suggestion as $key=>$val)
                {  
                
                    $write_data=[]; 
                    $write_data[]=$keyword;            
                    $write_data[]="Wiki";  
                    $write_data[]=$val;  
                    $write_data[]=$searched_at;                  
                    fputcsv($fp, $write_data);                               
                }
            }

            if(count($amazon_suggestion)>0)
            {
                foreach($amazon_suggestion as $key=>$val)
                {  
                
                    $write_data=[]; 
                    $write_data[]=$keyword;            
                    $write_data[]="Amazon";  
                    $write_data[]=$val;  
                    $write_data[]=$searched_at;                  
                    fputcsv($fp, $write_data);                               
                }
            }
            
        }
             
         fclose($fp);
         $file_name = "/storage/download/keyword_position/keyword_suggestion_{$download_id}.csv";
       
         echo "<p>".__("Your file is ready to download")."</p> <a href=".url('/').$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".__("Download")."</a>";
     }
 
 
     
 
     public function keyword_suggestion_delete(Request $request)
     {
        $all=$request->input("ids");
        $query=DB::table('keyword_suggestion')->select('keyword_suggestion.*');
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
 
 
     
     public function bulk_keyword_suggestion_progress_count()
     {
         $bulk_tracking_total_search=session('keyword_suggestion_bulk_total_search'); 
         $bulk_complete_search=session('keyword_suggestion_complete_search'); 
         
         $response['search_complete']=$bulk_complete_search;
         $response['search_total']=$bulk_tracking_total_search;
         
         echo json_encode($response);
         
     }


     function keyword_position_data(Request $request)
     {

         $searching       = trim($request->input("searching"));
         $post_date_range = $request->input("post_date_range");
         $display_columns = array("#",'CHECKBOX','id','domain_name','keyword','location','language','proxy','google_position','bing_position','yahoo_position','searched_at');
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
         $table = "keyword_position";
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
             $query->orWhere('keyword_position.user_id', '=', $user_id);
         });
 
        //  $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        $info = $query->orderByRaw($order_by)->offset($start)->limit($limit)->get();

        $query = DB::table($table);
        $query->where(function($query) use ($user_id){
            $query->Where('keyword_position.user_id', '=', $user_id);
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
 
 
 
     public function keyword_position_action(Request $request)
     {
         //************************************************//
         $status=$this->_check_usage($module_id=8,$req=1);
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
 

           
        $domain=strip_tags($request->input('domain_name',true));
        $keyword=strip_tags($request->input('keyword',true));        	
        $country=$request->input('country',true);
        $language=$request->input('language',true);
        $is_google=$request->input('is_google', true);
        $is_bing=$request->input('is_bing', true);
        $is_yahoo=$request->input('is_yahoo', true);

         $caption="Domain: ".$domain." - Keyword: ".$keyword." - Location: ".$country." - Language: ".$language;
         
         if($country=='all') $country="";				
         if($language=='all') $language="";		
 
 
         $download_id=time();
         if (!file_exists(storage_path("app/public/download/keyword_position"))) {
            mkdir(storage_path("app/public/download/keyword_position"), 0777, true);
        }
        $download_path=fopen(storage_path("app/public/download/keyword_position/keyword_position_{$download_id}.csv"), "w");
         
        // make output csv file unicode compatible
         fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
         $total_count=0;
         
         /**Write header in csv file***/
         $write_data=array();            
         $write_data[]="Search Engine";            
         $write_data[]="Domain";            
         $write_data[]="Keyword";            
         $write_data[]="Location";            
         $write_data[]="Language";           
         $write_data[]="Top Site";                  
         $write_data[]="Top URL";                  
         
         fputcsv($download_path, $write_data);
  
        
         $no_process=0;
         $no_process=$is_google+$is_bing+$is_yahoo;
         session()->put('keyword_position_complete_search',0);
         session()->put('keyword_position_bulk_total_search',$no_process);
         $keyword_position_complete_search=0;
 
         $searched_at= date("Y-m-d H:i:s");
 
         $keyword_position_google_data=array();
         $keyword_position_bing_data=array();        
         $keyword_position_yahoo_data=array();        
         if($is_google==1)	
         {
             $keyword_position_google_data=$this->web_repport->keyword_position_google($keyword, $page_number=0, $proxy="",$country,$language,$domain);  
             $keyword_position_complete_search++;
             session()->put('keyword_position_complete_search',$keyword_position_complete_search);
         }
         if($is_bing==1)		
         {
             $keyword_position_bing_data=$this->web_repport->keyword_position_bing($keyword, $page_number=0, $proxy="",$country,$language,$domain);  
             $keyword_position_complete_search++;
             session()->put('keyword_position_complete_search',$keyword_position_complete_search);
         }
         if($is_yahoo==1)	
         {
             $keyword_position_yahoo_data=$this->web_repport->keyword_position_yahoo($keyword, $page_number=0, $proxy="",$country,$language,$domain);  
            
             $keyword_position_complete_search++;
             session()->put('keyword_position_complete_search',$keyword_position_complete_search);
         }
         $google_postition="";
         $bing_postition="";
         $yahoo_postition="";
 
         if($is_google==1)
             $google_postition=$keyword_position_google_data["status"];
         if($is_bing==1)
             $bing_postition=$keyword_position_bing_data["status"];
         if($is_yahoo==1)
             $yahoo_postition=$keyword_position_yahoo_data["status"];
             
        
 
         $str="";
 
         if($is_google==1 && $google_postition!="caught_0_dolphin" && $google_postition!="")
         {
             $str.="<div class='card card-hero pb-3'>
                       <div class='card-header' style='border-radius:0!important;'>
                         <h5>".__("Google Position: &nbsp;").$google_postition."</h5>
                         <div class='card-description'>".__("Domain:&nbsp; ").$domain."</div>
                          ".__("Goolge Top Site &amp; URL List")."
                         <div class='card-header-action'>
                           <div class='badges'>
                              <a  class='btn btn-primary float-right' href='".url('/')."/storage/download/keyword_position/keyword_position_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
                           </div>                    
                         </div>
                       </div>
                       <div class='card-body'>
                         <div class='tickets-list'>";
 
 
 
             $count1=0;
 
             foreach ($keyword_position_google_data["top_site"]["domain"] as $key => $value) 
             {
                 if(is_array($keyword_position_google_data["top_site"]["link"]) && array_key_exists($key,$keyword_position_google_data["top_site"]["link"]))
                 {
                     $site=$keyword_position_google_data["top_site"]["domain"][$key];
                     $url_array = array();
                     $url_array = explode('&', $keyword_position_google_data["top_site"]["link"][$key]);
                     $url=$url_array[0];
                 }
                 else
                 {
                     $site="";
                     $url="";
                 }
 
                 $count1++;
 
                 $write_data=array();
                 $write_data[]="Google";            
                 $write_data[]=$domain;            
                 $write_data[]=$keyword;            
                 $write_data[]=$country;            
                 $write_data[]=$language;          
                 $write_data[]=$site;                  
                 $write_data[]=$url;  
 
                 fputcsv($download_path, $write_data);
                 $str.="<div class='tickets-list'>
                           <a target='_BLANK' href='".addHttp($url)."' class='ticket-item'>
                             <div class='ticket-title'>
                               <h4>".$url."</h4>
                             </div>
                             <div class='ticket-info'>
                               <div>".__("Website")."</div>
                               <div class='bullet'></div>
                               <div class='text-primary'>".$site."</div>
                             </div>
                           </a>
 
                         </div>";
                  
             }
             $str.="</div></div></div><br><br>";
            
 
 
         }
 
 
         if($is_bing==1 && $bing_postition!="caught_0_dolphin" && $bing_postition!="")
         {
 
             $str.="<div class='card card-hero'>
                       <div class='card-header' style='border-radius:0!important;'>
                         <h5>".__("Bing Position: &nbsp;").$bing_postition."</h5>
                         <div class='card-description'>".__("Domain:&nbsp; ").$domain."</div>
                          ".__("Bing Top Site &amp; URL List")."
                         <div class='card-header-action'>
                           <div class='badges'>
                              <a  class='btn btn-primary float-right' href='".url('/')."/storage/download/keyword_position/keyword_position_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
                           </div>                    
                         </div>
                       </div>
                       <div class='card-body p-0'>
                         <div class='tickets-list'>";
 
             $count2=0;
 
             foreach ($keyword_position_bing_data["top_site"]["domain"] as $key => $value) 
             {
                 if(is_array($keyword_position_bing_data["top_site"]["link"]) && array_key_exists($key,$keyword_position_bing_data["top_site"]["link"]))
                 {
                     $site=$keyword_position_bing_data["top_site"]["domain"][$key];
                     $url=$keyword_position_bing_data["top_site"]["link"][$key];
                 }
                 else
                 {
                     $site="";
                     $url="";
                 }
 
                 $count2++;
 
                 $write_data=array();
                 $write_data[]="Bing";            
                 $write_data[]=$domain;            
                 $write_data[]=$keyword;            
                 $write_data[]=$country;            
                 $write_data[]=$language;            
                 $write_data[]=$site;                  
                 $write_data[]=$url;  
 
                 fputcsv($download_path, $write_data);
                 $str.="<div class='tickets-list'>
                           <a target='_BLANK' href='".addHttp($url)."' class='ticket-item'>
                             <div class='ticket-title'>
                               <h4>".$url."</h4>
                             </div>
                             <div class='ticket-info'>
                               <div>".__("Website")."</div>
                               <div class='bullet'></div>
                               <div class='text-primary'>".$site."</div>
                             </div>
                           </a>
 
                         </div>";
                  
             }
             $str.="</div></div></div><br><br>";
 
 
         }
 
 
         if($is_yahoo==1 && $yahoo_postition!="caught_0_dolphin" && $yahoo_postition!="")
         {
             $str.="<div class='card card-hero'>
                       <div class='card-header' style='border-radius:0!important;'>
                         <h5>".__("Yahoo Position: &nbsp;").$yahoo_postition."</h5>
                         <div class='card-description'>".__("Domain:&nbsp; ").$domain."</div>
                          ".__("Yahoo Top Site &amp; URL List")."
                         <div class='card-header-action'>
                           <div class='badges'>
                              <a  class='btn btn-primary float-right' href='".url('/')."/storage/download/keyword_position/keyword_position_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
                           </div>                    
                         </div>
                       </div>
                       <div class='card-body p-0'>
                         <div class='tickets-list'>";
 
             $count3=0;
 
             foreach ($keyword_position_yahoo_data["top_site"]["domain"] as $key => $value) 
             {
                 if(is_array($keyword_position_yahoo_data["top_site"]["link"]) && array_key_exists($key,$keyword_position_yahoo_data["top_site"]["link"]))
                 {
                     $site=$keyword_position_yahoo_data["top_site"]["domain"][$key];
                     $url=$keyword_position_yahoo_data["top_site"]["link"][$key];
                 }
                 else
                 {
                     $site="";
                     $url="";
                 }
 
                 $count3++;
 
                 $write_data=array();
                 $write_data[]="Yahoo";            
                 $write_data[]=$domain;            
                 $write_data[]=$keyword;            
                 $write_data[]=$country;            
                 $write_data[]=$language;         
                 $write_data[]=$site;                  
                 $write_data[]=$url;  
 
                 fputcsv($download_path, $write_data);
                 $str.="<div class='tickets-list'>
                               <a target='_BLANK' href='".addHttp($url)."' class='ticket-item'>
                                 <div class='ticket-title'>
                                   <h4>".$url."</h4>
                                 </div>
                                 <div class='ticket-info'>
                                   <div>".__("Website")."</div>
                                   <div class='bullet'></div>
                                   <div class='text-primary'>".$site."</div>
                                 </div>
                               </a>
 
                             </div>";
                      
                 }
                 $str.="</div></div></div>";
 
 
         }
 
 
         $insert_data=array
         (
              'user_id'                          => Auth::user()->id,
              'searched_at'                     	=> $searched_at,
              'domain_name'						=> $domain,
              'keyword'							=> $keyword,
              'location'							=> $country,
              'language'							=> $language         
         );
 
         if($is_google==1 && $google_postition!="caught_0_dolphin" && $google_postition!="")
         {
              $insert_data['google_position']= $google_postition;
              $insert_data['google_top_site_url']= json_encode($keyword_position_google_data["top_site"]);
         }
 
         if($is_bing==1 && $bing_postition!="caught_0_dolphin" && $bing_postition!="")
         {
              $insert_data['bing_position']= $bing_postition;
              $insert_data['bing_top_site_url']= json_encode($keyword_position_bing_data["top_site"]);
         }
 
         if($is_yahoo==1 && $yahoo_postition!="caught_0_dolphin" && $yahoo_postition!="")
         {
              $insert_data['yahoo_position']= $yahoo_postition;
              $insert_data['yahoo_top_site_url']= json_encode($keyword_position_yahoo_data["top_site"]);
         }
 
 
         if($google_postition=="caught_0_dolphin" && $bing_postition=="caught_0_dolphin" && $yahoo_postition=="caught_0_dolphin")
         {
             $insert_data=array();
 
             $str="<div class='card-body'>
                     <div class='alert alert-warning alert-has-icon'>
                      <div class='alert-icon'><i class='far fa-lightbulb'></i></div>
                      <div class='alert-body'>
                         <div class='alert-title'>".__('warning')."</div>
                         ".__("Operation stopped. Seach engine has detected robotic operation. Use proxy or try after taking some rest.")."
            
                      </div>
                     </div>
                 </div>";
         }
 
         if(!empty($insert_data)){
 
            //  $this->basic->insert_data('keyword_position', $insert_data);
            DB::table('keyword_position')->insert($insert_data);
 
             //******************************//
             // insert data to useges log table
             $this->_insert_usage_log($module_id=8,$req=1);   
             //******************************//  
           
             echo $str;      
         }
 
     }
 
   
 
     public function keyword_position_download(Request $request)
     {
        $all=$request->input("ids");
 
         $table = 'keyword_position';
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
        if (!file_exists(storage_path("app/public/download/keyword_position"))) {
           mkdir(storage_path("app/public/download/keyword_position"), 0777, true);
       }
       $fp=fopen(storage_path("app/public/download/keyword_position/keyword_position_{$download_id}.csv"), "w");
        
         // make output csv file unicode compatible
         fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        
         $write_data=array();            
         $write_data[]="Search Engine";            
         $write_data[]="Domain";            
         $write_data[]="Keyword";            
         $write_data[]="Location";            
         $write_data[]="Language";         
         $write_data[]="Keyword Position";            
         $write_data[]="Top Site";                  
         $write_data[]="Top URL";  
         $write_data[]="Searched at";  
                     
         fputcsv($fp, $write_data);
 
         $write_data=array();   
         foreach ($info as  $value) 
         {         
                        
             $keyword = $value->keyword;
             $domain = $value->domain_name;
             $location= $value->location;
             $language= $value->language;
             $google_position= $value->google_position;
             $bing_position= $value->bing_position;
             $yahoo_position= $value->yahoo_position;
             $searched_at= $value->searched_at;
 
             $google_top_site_url= json_decode($value->google_top_site_url);
             $bing_top_site_url= json_decode($value->bing_top_site_url);
             $yahoo_top_site_url= json_decode($value->yahoo_top_site_url);
 
 
             $google_top_site=array();
             if(!empty($google_top_site_url["domain"]))
                 $google_top_site=$google_top_site_url["domain"];
             else
                 $google_top_site = array();
 
             $google_top_link=array();
             if(!empty($google_top_site_url['link']))
                 $google_top_link=$google_top_site_url["link"];
             else
                 $google_top_link = array();
 
             if(count($google_top_site)>0 && count($google_top_link)>0)
             {
                 foreach( $google_top_site as $key=>$val)
                 {  
                    
                     $write_data=array(); 
                     $write_data[]="Google";            
                     $write_data[]=$domain;            
                     $write_data[]=$keyword;            
                     $write_data[]=$location;            
                     $write_data[]=$language;                 
                     $write_data[]=$google_position;                   
                     $write_data[]=$val;  
                     $write_data[]=$google_top_link[$key];  
                     $write_data[]=$searched_at;                  
                     fputcsv($fp, $write_data);                               
                 }
             }
 
 
 
             $bing_top_site=array();
             if(!empty($bing_top_site_url["domain"]))
                 $bing_top_site=$bing_top_site_url["domain"];
             else
                 $bing_top_site = array();
 
             $bing_top_link=array();
             if(!empty($bing_top_site_url["link"]))
                 $bing_top_link=$bing_top_site_url["link"];
             else
                 $bing_top_link = array();
 
             if(count($bing_top_site)>0 && count($bing_top_link)>0)
             {
                 foreach( $bing_top_site as $key=>$val)
                 {  
                         
                     $write_data=array();
                     $write_data[]="Bing";            
                     $write_data[]=$domain;            
                     $write_data[]=$keyword;            
                     $write_data[]=$location;            
                     $write_data[]=$language;                 
                     $write_data[]=$bing_position;                   
                     $write_data[]=$val;  
                     $write_data[]=$bing_top_link[$key];  
                     $write_data[]=$searched_at;            
                     fputcsv($fp, $write_data);                               
                 }
             }
 
             $yahoo_top_site=array();
             if(!empty($yahoo_top_site_url['domain']))
                 $yahoo_top_site=$yahoo_top_site_url["domain"];
             else
                 $yahoo_top_site = array();
 
             $yahoo_top_link=array();
             if(!empty($yahoo_top_site_url['link']))
                 $yahoo_top_link=$yahoo_top_site_url["link"];
             else
                 $yahoo_top_link = array();
 
             if(count($yahoo_top_site)>0 && count($yahoo_top_link)>0)
             {
                 foreach( $yahoo_top_site as $key=>$val)
                 {  
                     
                     $write_data=array();
                     $write_data[]="Yahoo";            
                     $write_data[]=$domain;            
                     $write_data[]=$keyword;            
                     $write_data[]=$location;            
                     $write_data[]=$language;                  
                     $write_data[]=$yahoo_position;                   
                     $write_data[]=$val;  
                     $write_data[]=$yahoo_top_link[$key];  
                     $write_data[]=$searched_at;            
                     fputcsv($fp, $write_data);                               
                 }
             }
             
         }
             
         fclose($fp);
         $file_name = "/storage/download/keyword_position/keyword_position_{$download_id}.csv";
         
         echo "<p>".__("Your file is ready to download")."</p> <a href=".url('/').$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".__("Download")."</a>";
     }
 
 
     
 
     public function keyword_position_delete(Request $request)
     {
        $all=$request->input("ids");
        $query=DB::table('keyword_position')->select('keyword_position.*');
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
 
 
     
     public function bulk_keyword_position_progress_count()
     {
         $bulk_tracking_total_search=session('keyword_position_bulk_total_search'); 
         $bulk_complete_search=session('keyword_position_complete_search'); 
         
         $response['search_complete']=$bulk_complete_search;
         $response['search_total']=$bulk_tracking_total_search;
         
         echo json_encode($response);
         
     }
 
    
}
