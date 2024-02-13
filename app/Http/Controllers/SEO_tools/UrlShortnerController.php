<?php

namespace App\Http\Controllers\SEO_tools;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\HomeController;

class UrlShortnerController extends HomeController
{

  public function __construct()
  {
    $this->set_global_userdata(true,[],[],18);
  }

  public function index()
  {
    $data['body'] ='seo-tools.url-shortner.index';
    return $this->_viewcontroller($data);
  }

  public function rebrandly_shortener_index()
  {
    $this->member_validity();
    $data['body'] ='seo-tools.url-shortner.rebrandly-shortner-index';
    return $this->_viewcontroller($data);
  }
  public function rebrandly()
  {
    $this->member_validity();
    $data['body'] ='seo-tools.url-shortner.rebrandly-shortner';
    return $this->_viewcontroller($data);
  }

  public function rebrandly_shortener_action(Request $request)
  {
      $urls=strip_tags($request->input('long_url'));
      $title=strip_tags($request->input('title'));
      
      $download_id= time();
      if (!file_exists(storage_path("app/public/download/url_shortener"))) {
        mkdir(storage_path("app/public/download/url_shortener"), 0777, true);
      }
      $download_path=fopen(storage_path("app/public/download/url_shortener/rebrandly_short_url_{$download_id}.csv"), "w");

      // make output csv file unicode compatible
      fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
        
      /**Write header in csv file***/

      $write_data=[];            
      $write_data[]="Long URL";          
      $write_data[]="Short URL";               
      $write_data[]="Created at";            
                                      
      
      fputcsv($download_path, $write_data);
      
      $short_url_complete=0;

      $count=0;
      $str = "<div class='card'>
      <div class='card-header'>
        <h4><i class='fas fa-cut'></i> ".__("Rebrandly URL Shortener")."</h4>
          <div class='card-header-action'>
            <div class='badges'>
            <a  class='btn btn-primary float-right' href='".url('/')."/storage/download/url_shortener/rebrandly_short_url_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
            </div>                    
          </div>
      </div>
      <div class='card-body'>
        <div class='table-responsive'>
          <table class='table table-bordered table-hover'>
            <tbody>
              <tr>";
      $str.="<th>".__("Long URL")."</th>"; 
      $str.="<th>".__("Short URL")."</th>";  
      $str.="</tr>";        
      $domain_data=[];
      $domain_data=$this->rebrandly_shortener_creator($urls,$title);
      if (isset($domain_data['error_message'])) {
          
          echo "<div class='card-body'>
                  <div class='alert alert-warning alert-has-icon'>
                    <div class='alert-icon'><i class='far fa-lightbulb'></i></div>
                    <div class='alert-body'>
                      <div class='alert-title'>".__('warning')."</div>
                          ".$domain_data['error_message']."
                      <br>
                  
                    </div>
                  </div>
              </div>";
          exit();
      }  
      $created_at= date("Y-m-d:H:i:s");
      
      $original_url = isset($domain_data['destination']) ? $domain_data['destination']: "";
      $short_url = isset($domain_data['shortUrl']) ? $domain_data['shortUrl']: ""; 
      $short_url_id = isset($domain_data['id']) ? $domain_data['id'] : "";
      $title = isset($domain_data['title']) ? $domain_data['title'] : "";
      $domainId = isset($domain_data['domainId']) ? $domain_data['domainId'] : "";
      $slashtag = isset($domain_data['slashtag']) ? $domain_data['slashtag'] : "";

      $write_data=[];
  
      $write_data[]=$original_url;
      $write_data[]=$short_url;
      $write_data[]=$short_url_id;
      $write_data[]=$created_at;
      fputcsv($download_path, $write_data);
      
      /** Insert into database ***/
  
      $insert_data=[
      
          'user_id'        => Auth::user()->id,
          'long_url'       => $original_url,
          'short_url'      => $short_url,
          'short_url_id'   => $short_url_id,
          'title'   => $title,
          'domainId'   => $domainId,
          'slashtag'   => $slashtag,
          'created_at'     => $created_at
      ];



      $str.=
      "<tr>
      <td>".$original_url."</td>
      <td>".$short_url."</td>
      </tr>";
      
      DB::table('rebrandly_url_shortener')->insert($insert_data);         


      //******************************//
      // insert data to useges log table
      $this->_insert_usage_log($module_id=18,$req=1);   
      //******************************//

      echo $str.="</tbody></table></div></div></div>";
  }
  public function rebrandly_shortener_creator($urls,$title="")
  {
    $rebrandly_api_key_gen=DB::table('config')->select('rebrandly_api_key')->first();
    $use_admin_app = config('my_config.use_admin_app');
    if($use_admin_app == '' || $use_admin_app == 'no')
      $config_data = DB::table('config')->where('user_id', Auth::user()->id)->get();
    else
      $config_data = DB::table('config')->where('access', 'all_users')->limit(1)->get();


    $rebrandly_api_key="";
    if(count($config_data)>0)
      {
        $rebrandly_api_key=$rebrandly_api_key_gen->rebrandly_api_key ?? '';
      }

      $data = [
      "title"=> $title,
      "destination" => $urls
      ];
      $url = 'https://api.rebrandly.com/v1/links';
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
      curl_setopt($ch, CURLOPT_URL,$url); 
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
          "apikey:{$rebrandly_api_key}",
          "Content-Type:application/json"
      ]);
      $content = curl_exec($ch);
      $result = json_decode($content,true);

      if (isset($result['errors'])) {
          
          $rebrandly_error['error_message'] = $result['errors'][0]['message'];
          return $rebrandly_error;
      }
      else{
          return  $result;
      }
      
  }


  public function rebrandly_short_url_delete(Request $request)
  {
    $all=$request->input("ids");

    $query=DB::table('rebrandly_url_shortener')->select('rebrandly_url_shortener.*');

      if($all!=0)
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

  public function rebrandly_short_url_download(Request $request)
  {
    $all=$request->input("ids");
    $table = 'rebrandly_url_shortener';
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
    $info=json_decode(json_encode($info));

    $download_id=time();
    if (!file_exists(storage_path("app/public/download/url_shortener"))) {
      mkdir(storage_path("app/public/download/url_shortener"), 0777, true);
    }
    $fp = fopen(base_path("storage/app/public/download/url_shortener/rebrandly_short_url_{$download_id}.csv"), "w");
      // make output csv file unicode compatible
    fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
      

    $write_data=[];            
    $write_data[]="Long URL";          
    $write_data[]="Short URL";           
    $write_data[]="Short URL ID";           
    $write_data[]="Created at";   
                  
    fputcsv($fp, $write_data);
    $write_info = [];

    foreach ($info as  $value) 
    {
                  
      $write_info[]=$value["long_url"];      
      $write_info[]=$value["short_url"];
      $write_info[]=$value["short_url_id"];
      $write_info[]=$value["created_at"];   
        
      fputcsv($fp, $write_info);
    }

      fclose($fp);
      $file_name = "/public/storage/public/download/url_shortener/rebrandly_short_url_{$download_id}.csv";
      echo "<p>".__("Your file is ready to download")."</p> <a href=".url('/').$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".__("Download")."</a>";
  }

  public function rebrandly_shortener_data(Request $request)
  {
      
    $searching = trim($request->post("searching"));
    $post_date_range = $request->post("post_date_range");
    $display_columns = ["#",'CHECKBOX','id','long_url','short_url','short_url_id','analytics','created_at'];
    $search_columns = ["long_url", "created_at"];

    $page = $request->post("page", 1);
    $start = $request->post("start", 0);
    $limit = $request->post("length", 10);
    $sort_index = $request->post("order.0.column", 2);
    $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : "id";
    $order = $request->post("order.0.dir", "desc");
    $order_by=$sort." ".$order;

    $from_date = $to_date = "";
    if($post_date_range!="")
    {
        $exp = explode('|', $post_date_range);
        $from_date = isset($exp[0])?$exp[0]:"";
        $to_date   = isset($exp[1])?$exp[1]:"";

    }

    $user_id = Auth::user()->id;
    $table = "rebrandly_url_shortener";
    $select= ["rebrandly_url_shortener.long_url","rebrandly_url_shortener.id","rebrandly_url_shortener.short_url","rebrandly_url_shortener.short_url_id","rebrandly_url_shortener.created_at"];
    $query = DB::table($table)->select($select);
    if($from_date!='') $query->where("created_at", ">=", $from_date);
    if($to_date!='') $query->where("created_at", "<=", $to_date);  
    if ($searching != '')
    {
        $query->where(function($query) use ($search_columns,$searching){
            foreach ($search_columns as $key => $value) $query->orWhere($value, 'like',  "%$searching%");
        });
    }
    $query->where(function($query) use ($user_id){
      $query->orWhere('rebrandly_url_shortener.user_id', '=', $user_id);
    });

      // $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
    $info = $query->orderByRaw($order_by)->offset($start)->limit($limit)->get();

    $query = DB::table($table)->select($select);
    $query->where(function($query) use ($user_id){
      $query->Where('rebrandly_url_shortener.user_id', '=', $user_id);
    });
    $total_result=$query->count();

    for($i=0;$i<count($info);$i++)
    {  
      $info[$i]->created_at = date("jS M y",strtotime($info[$i]->created_at));
      $url_analytics = url('url_shortener/rebrandly_url_analytics').'/'.$info[$i]->id;
      $info[$i]->analytics = "<a target='_BLANK' href='".$url_analytics."' title='".__("URL Analytics")."' class='btn btn-circle btn-outline-primary'><i class='far fa-chart-bar'></i></a>&nbsp;&nbsp;";       
    }

    $data['draw'] = (int)$_POST['draw'] + 1;
    $data['recordsTotal'] = $total_result;
    $data['recordsFiltered'] = $total_result;
    $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

    echo json_encode($data);
  }

  public function rebrandly_url_analytics($id = 0)
  {
      if($id==0) exit();

      $select = ['short_url_id','short_url'];
      $get_data = DB::table('rebrandly_url_shortener')->select($select)->where('id',$id)->where('user_id',Auth::user()->id)->get();
      $get_data=json_decode(json_encode($get_data));


      if (!empty($get_data)) {
        $data['rebrandly_shortener'] = $get_data[0]->short_url;;
        $total_click_data = $this->rebrandly_total_clicks($get_data[0]->short_url_id);
        $data['total_click_data'] = $total_click_data;
        $data['body'] ='seo-tools.url-shortner.rebrandly-analytics';
        return $this->_viewcontroller($data);
        

      }
      else
      {
        return redirect()->route('access_forbidden');
      }


  }

  public function rebrandly_total_clicks($short_url_id)
  {
    $rebrandly_api_key_gen=DB::table('config')->select('config.rebrandly_api_key')->first();
    $use_admin_app = config('my_config.use_admin_app');
    if($use_admin_app == '' || $use_admin_app == 'no')
      $config_data = DB::table('config')->where('user_id', Auth::user()->id)->get();
    else
      $config_data = $config_data = DB::table('config')->where('access', 'all_users')->limit(1)->get();


    $rebrandly_api_key="";
    if(count($config_data)>0)
      {
        $rebrandly_api_key=$rebrandly_api_key_gen->rebrandly_api_key;
      }

      $url = "https://api.rebrandly.com/v1/links/{$short_url_id}";
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_URL,$url); 
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
          "apikey:{$rebrandly_api_key}",
          "Content-Type:application/json"
      ]);
      $content = curl_exec($ch);
      $result = json_decode($content,true);
      if (isset($result['errors'])) {
          
          $rebrandly_error['error_message'] = $result['errors'][0]['message'];
          return $rebrandly_error;
      }
      else if (isset($result['code'])){
          $rebrandly_error['error_message'] = $result['message'];
          return $rebrandly_error;
      }
      else{
          return  $result;
      }
  }

  public function bitly_shortener_index()
  {
    $this->member_validity();
    $data['body'] ='seo-tools.url-shortner.bitly-shortner-index';
    return $this->_viewcontroller($data);

  }
  public function bitly()
  {
    $this->important_feature();
    $this->member_validity();
    $data['body'] ='seo-tools.url-shortner.bitly-shortner';
    return $this->_viewcontroller($data);

  }

  public function short_url_delete(Request $request)
  {
    $all=$request->input("ids");

    $query=DB::table('bitly_url_shortener')->select('bitly_url_shortener.*');

      if($all!=0)
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

  public function short_url_download(Request $request)
  {
    $all=$request->input("ids");
      $table = 'bitly_url_shortener';
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
      $info=json_decode(json_encode($info));

      $download_id=time();

      if (!file_exists(storage_path("app/public/download/url_shortener"))) {
        mkdir(storage_path("app/public/download/url_shortener"), 0777, true);
      }

      $fp = fopen(storage_path("app/public/download/url_shortener/short_url_{$download_id}.csv"), "w");
      // make output csv file unicode compatible
      fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
      

      $write_data= [];            
      $write_data[]="Long URL";          
      $write_data[]="Short URL";           
      $write_data[]="Short URL ID";           
      $write_data[]="Created at";   
                  
      fputcsv($fp, $write_data);
      $write_info = [];

      foreach ($info as  $value) 
      {            
          $write_info[]=$value->long_url;      
          $write_info[]=$value->short_url;
          $write_info[]=$value->short_url_id;
          $write_info[]=$value->created_at;   
      
          fputcsv($fp, $write_info);
      }

      fclose($fp);
      $file_name = "/storage/download/url_shortener/short_url_{$download_id}.csv";
      echo "<p>".__("Your file is ready to download")."</p> <a href=".url('/').$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".__("Download")."</a>";
  }

  public function url_shortener_data(Request $request)
  {
    $searching = trim($request->post("searching"));
    $post_date_range = $request->post("post_date_range");
    $display_columns = ["#",'CHECKBOX','id','long_url','short_url','short_url_id','analytics','created_at'];
    $search_columns = ["long_url", "created_at"];

    $page = $request->post("page", 1);
    $start = $request->post("start", 0);
    $limit = $request->post("length", 10);
    $sort_index = $request->post("order.0.column", 2);
    $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : "id";
    $order = $request->post("order.0.dir", "desc");
    $order_by=$sort." ".$order;

    

    $from_date = $to_date = "";
    if($post_date_range!="")
    {
        $exp = explode('|', $post_date_range);
        $from_date = isset($exp[0])?$exp[0]:"";
        $to_date   = isset($exp[1])?$exp[1]:"";

    }

    $user_id = Auth::user()->id;
    $table = "bitly_url_shortener";
    $select= ["bitly_url_shortener.long_url","bitly_url_shortener.id","bitly_url_shortener.short_url","bitly_url_shortener.short_url_id","bitly_url_shortener.created_at"];
    $query = DB::table($table)->select($select);
    if($from_date!='') $query->where("created_at", ">=", $from_date);
    if($to_date!='') $query->where("created_at", "<=", $to_date);  
    if ($searching != '')
    {
        $query->where(function($query) use ($search_columns,$searching){
            foreach ($search_columns as $key => $value) $query->orWhere($value, 'like',  "%$searching%");
        });
    }
    $query->where(function($query) use ($user_id){
      $query->orWhere('bitly_url_shortener.user_id', '=', $user_id);
    });

      // $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
    $info = $query->orderByRaw($order_by)->offset($start)->limit($limit)->get();

    $query = DB::table($table);
    $query->where(function($query) use ($user_id){
      $query->Where('bitly_url_shortener.user_id', '=', $user_id);
    });
    $total_result=$query->count();

    for($i=0;$i<count($info);$i++)
    {  
      $info[$i]->created_at = date("jS M y",strtotime($info[$i]->created_at));
      $url_analytics = url('url_shortener/url_analytics').'/'.$info[$i]->id;
      $info[$i]->analytics = "<a target='_BLANK' href='".$url_analytics."' title='".__("URL Analytics")."' class='btn btn-circle btn-outline-primary'><i class='far fa-chart-bar'></i></a>&nbsp;&nbsp;";       
    }

    $data['draw'] = (int)$_POST['draw'] + 1;
    $data['recordsTotal'] = $total_result;
    $data['recordsFiltered'] = $total_result;
    $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

    echo json_encode($data);
  }

  public function url_shortener_action(Request $request)
  {
      $urls=strip_tags($request->input('domain_name'));
      
      $urls=str_replace("\n", ",", $urls);
      $url_array=explode(",", $urls);
      $url_array=array_filter($url_array);
      $url_array=array_unique($url_array);

      //************************************************//
      $status=$this->_check_usage($module_id=18,$req=count($url_array));
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
      
    
      session(['url_shortener_bulk_total_search'=>count($url_array)]);
      session(['url_shortener_complete_search'=>0]);
      $download_id= time();

      if (!file_exists(storage_path("app/public/download/url_shortener"))) {
        mkdir(storage_path("app/public/download/url_shortener"), 0777, true);
      }
      
      $download_path=fopen(storage_path("app/public/download/url_shortener/short_url_{$download_id}.csv"), "w");

      // make output csv file unicode compatible
      fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
        
      /**Write header in csv file***/

      $write_data=[];            
      $write_data[]="Original URL";          
      $write_data[]="Short URL";               
      $write_data[]="Created at";            
                                      
      
      fputcsv($download_path, $write_data);
      
      $short_url_complete=0;

      $count=0;
      $str = "<div class='card'>
      <div class='card-header'>
        <h4><i class='fas fa-cut'></i> ".__("Bitly URL Shortener")."</h4>
          <div class='card-header-action'>
            <div class='badges'>
              <a  class='btn btn-primary float-right' href='".url('/')."/storage/download/url_shortener/short_url_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
            </div>                    
          </div>
      </div>
      <div class='card-body'>
        <div class='table-responsive'>
          <table class='table table-bordered table-hover'>
            <tbody>
              <tr>";
      $str.="<th>".__("SL")."</th>"; 
      $str.="<th>".__("Long URL")."</th>"; 
      $str.="<th>".__("Short URL")."</th>";  
      $str.="</tr>";  
      foreach ($url_array as $domain) 
      {        
          $domain_data=[];
          $domain_data=$this->bitly_short_url_creator($domain);
          if (isset($domain_data['error_message'])) {
              
              echo "<div class='card-body'>
                      <div class='alert alert-warning alert-has-icon'>
                        <div class='alert-icon'><i class='far fa-lightbulb'></i></div>
                        <div class='alert-body'>
                          <div class='alert-title'>".__('warning')."</div>
                          ".__("Sorry, bitly generic access token is missing or invalid long url.").'<br>'.$domain_data['error_message']."
                          <br>
                          <a target='_BLANK' href='".url("social_apps/connectivity_settings")."'>".__("click here to insert bitly generic access token.")."</a>
                        </div>
                      </div>
                  </div>";
              exit();
          }  
          $created_at= date("Y-m-d:H:i:s");
          
          $original_url = isset($domain_data['long_url']) ? $domain_data['long_url']: "";
          $short_url = isset($domain_data['link']) ? $domain_data['link']: ""; 
          $short_url_id = isset($domain_data['id']) ? $domain_data['id'] : "";

          $write_data=[];
    
          $write_data[]=$original_url;
          $write_data[]=$short_url;
          $write_data[]=$short_url_id;

          $write_data[]=$created_at;
          fputcsv($download_path, $write_data);
          
          /** Insert into database ***/
  
          $insert_data=array
          (
              'user_id'        => Auth::user()->id,
              'long_url'       => $original_url,
              'short_url'      => $short_url,
              'short_url_id'   => $short_url_id,
              'created_at'     => $created_at
          );

          $count++;

          $str.=
          "<tr>
          <td>".$count."</td>
          <td>".$original_url."</td>
          <td>".$short_url."</td>
          </tr>";
          
          
          DB::table('bitly_url_shortener')->insert($insert_data);      
          $short_url_complete++;
          session(['url_shortener_complete_search' => $short_url_complete]);       
      }

      //******************************//
      // insert data to useges log table
      $this->_insert_usage_log($module_id=18,$req=count($url_array));   
      //******************************//

      echo $str.="</tbody></table></div></div></div>";

  }

  public function bitly_short_url_creator($long_url)
  {
    $bitly_access_token=DB::table('config')->select('config.bitly_access_token')->first();
    $use_admin_app = config('my_config.use_admin_app');
    if($use_admin_app == '' || $use_admin_app == 'no')
      $config_data = DB::table('config')->where('user_id', Auth::user()->id)->get();
    else
      $config_data = $config_data = DB::table('config')->where('access', 'all_users')->limit(1)->get();


    $bitly_generic_access_token="";
    if(count($config_data)>0)
      {
        $bitly_generic_access_token=$bitly_access_token->bitly_access_token;
      }
      // print_r($bitly_generic_access_token);
      // exit;
    $apiv4 = 'https://api-ssl.bitly.com/v4/bitlinks';

    $data = [
          'long_url' => $long_url
    ];
    $payload = json_encode($data);

    $header = [
          'Authorization: Bearer ' . $bitly_generic_access_token,
          'Content-Type: application/json'
    ];
    $ch = curl_init($apiv4);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $content = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($content,true);

    if (isset($result['message'])) {
          
          $bitly_data['error_message'] = $result['message'];
          return $bitly_data;
    }
    else{
          return $result;
    }


  }

  public function url_analytics($id=0)
  {
      if($id==0) exit();

      $select = ['short_url_id','short_url'];
      $get_data = DB::table('bitly_url_shortener')->select($select)->where('id',$id)->where('user_id',Auth::user()->id)->get();
      $get_data=json_decode(json_encode($get_data));
      
      $data['bitly_shortener'] = $get_data[0]->short_url;
      
      $monthly_click_data = $this->bitly_monthly_clicks_report($get_data[0]->short_url_id);
      $monthly_bitly_montly_referring_domains_data = $this->bitly_montly_referring_domains($get_data[0]->short_url_id);
      $monthly_bitly_monthly_countries_data = $this->bitly_monthly_countries($get_data[0]->short_url_id);
      $data['monthly_click_data'] = $monthly_click_data;
      $data['monthly_bitly_montly_referring_domains_data'] = $monthly_bitly_montly_referring_domains_data;
      $data['monthly_bitly_monthly_countries_data'] = $monthly_bitly_monthly_countries_data;       
      $data['body'] = "seo-tools.url-shortner.bitly-analytics";
      return $this->_viewcontroller($data);
  }
  public function read_text_csv_file_backlink(Request $request)
  {
    if ($request->isMethod('get')) {
      return redirect()->route('access_forbidden');
    }

    $ret=array();
    if (!file_exists(storage_path("app/public/upload/tmp"))) {
      mkdir(storage_path("app/public/upload/tmp"), 0777, true);
    }
    $output_dir = storage_path('app/public/upload/tmp');
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

  public function read_after_delete_csv_txt(Request $request) // deletes the uploaded video to upload another one
  {
    if ($request->isMethod('get')) {
      return redirect()->route('access_forbidden');
    }
        
    $output_dir = storage_path('app/public/upload/tmp');
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

  public function bitly_monthly_clicks_report($short_url_id)
  {
    $bitly_access_token=DB::table('config')->select('config.bitly_access_token')->first();
    $use_admin_app = config('my_config.use_admin_app');
    if($use_admin_app == '' || $use_admin_app == 'no')
      $config_data = DB::table('config')->where('user_id', Auth::user()->id)->get();
    else
      $config_data = $config_data = DB::table('config')->where('access', 'all_users')->limit(1)->get();


    $bitly_generic_access_token="";
    if(count($config_data)>0)
      {
        $bitly_generic_access_token=$bitly_access_token->bitly_access_token;
      }
    $apiv4 = "https://api-ssl.bitly.com/v4/bitlinks/{$short_url_id}/clicks?units=28";

    $header = [
          'Authorization: Bearer ' .$bitly_generic_access_token,
          'Content-Type: application/json'
      ];

    $ch = curl_init($apiv4);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $content = curl_exec($ch);

    $result = json_decode($content,true);
    if (isset($result['message'])) {
          
      $bitly_data['error_message'] = $result['message'];
      return $bitly_data;
    }
    else{
      
      return $result;
    }
      

  }

  public function bitly_montly_referring_domains($short_url_id)
  {
    $bitly_access_token=DB::table('config')->select('config.bitly_access_token')->first();
    $use_admin_app = config('my_config.use_admin_app');
    if($use_admin_app == '' || $use_admin_app == 'no')
      $config_data = DB::table('config')->where('user_id', Auth::user()->id)->get();
    else
      $config_data = $config_data = DB::table('config')->where('access', 'all_users')->limit(1)->get();


    $bitly_generic_access_token="";
    if(count($config_data)>0)
      {
        $bitly_generic_access_token=$bitly_access_token->bitly_access_token;
      }

      $apiv4 = "https://api-ssl.bitly.com/v4/bitlinks/{$short_url_id}/referring_domains?units=28";

      $header = array(
          'Authorization: Bearer ' .$bitly_generic_access_token,
          'Content-Type: application/json'
      );

      $ch = curl_init($apiv4);

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $content = curl_exec($ch);

      $result = json_decode($content,true);
      if (isset($result['message'])) {
        
        $bitly_data['error_message'] = $result['message'];
        return $bitly_data;
      }
      else{
          return $result;
      }

  }

  public function bitly_monthly_countries($short_url_id)
  {
    $bitly_access_token=DB::table('config')->select('config.bitly_access_token')->first();
    $use_admin_app = config('my_config.use_admin_app');
    if($use_admin_app == '' || $use_admin_app == 'no')
      $config_data = DB::table('config')->where('user_id', Auth::user()->id)->get();
    else
      $config_data = $config_data = DB::table('config')->where('access', 'all_users')->limit(1)->get();


    $bitly_generic_access_token="";
    if(count($config_data)>0)
      {
        $bitly_generic_access_token=$bitly_access_token->bitly_access_token;
      }
      $apiv4 = "https://api-ssl.bitly.com/v4/bitlinks/{$short_url_id}/countries?units=28";

      $header = array(
          'Authorization: Bearer ' .$bitly_generic_access_token,
          'Content-Type: application/json'
      );

      $ch = curl_init($apiv4);

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $content = curl_exec($ch);

      $result = json_decode($content,true);
      if (isset($result['message'])) {
          
          $bitly_data['error_message'] = $result['message'];
          return $bitly_data;
      }
      else{
          return $result;
      }
  }


  public function bulk_url_short_progress_count()
  {
      $bulk_tracking_total_search=session('url_shortener_bulk_total_search'); 
      $bulk_complete_search=session('url_shortener_complete_search'); 
      
      $response['search_complete']=$bulk_complete_search;
      $response['search_total']=$bulk_tracking_total_search;
      
      echo json_encode($response);
      
  }


}
