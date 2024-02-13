<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Services\Custom\WebCommonReportServiceInterface;

class CronJobController extends HomeController
{


    public function __construct(WebCommonReportServiceInterface $web_common_repport)
    {


        $this->web_repport= $web_common_repport;

    }

    public function index()
    {
        if (session('logged_in') != 1)
        return redirect()->route('login');

        if(Auth::user()->user_type != 'Admin' && !in_array(15,$this->module_access))
        return redirect()->route('login');
        
        $this->member_validity();
        
        $api_data=DB::table('native_api')->where('user_id',Auth::user()->id)->get();
        $data["api_key"]="";
        if(count($api_data)>0) $data["api_key"]=$api_data[0]->api_key;
        
        $data['is_demo'] = config('app.is_demo');
        $data['body'] = 'system.cron-job.index';
        return $this->_viewcontroller($data);    
    }

    public function api_member_validity($user_id='')
    {
        if($user_id!='') {
            $user_expire_date = DB::table('users')->where('id',$user_id)->select('expired_date')->get();
            $expire_date = strtotime($user_expire_date[0]->expired_date);
            $current_date = strtotime(date("Y-m-d"));
            $package_data = DB::table('users')
                ->leftJoin('package', 'users.package_id', '=', 'package.id')
                ->select('package.price as price', 'users.user_type')
                ->where('users.id', $user_id)
                ->get();
            $package_data = json_decode(json_encode($package_data));
            if(is_array($package_data) && array_key_exists(0, $package_data) && $package_data[0]->user_type == 'Admin' )
                return true;

            $price = '';
            if(is_array($package_data) && array_key_exists(0, $package_data))
            $price=$package_data[0]->price;
            if($price=="Trial") $price=1;

            
            if ($expire_date < $current_date && ($price>0 && $price!=""))
            return false;
            else return true;
            

        }
    }

    public function _api_key_generator()
    {
        if (session('logged_in') != 1)
        return redirect()->route('login');

        if(Auth::user()->user_type != 'Admin' && !in_array(15,$this->module_access))
        return redirect()->route('login');;

        $this->member_validity();
        // $val=Auth::user()->user_id."-".substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 7 ).time()
        // .substr(str_shuffle('abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789') , 0 , 7 );
        $val=Auth::user()->id."-".substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 7 ).time()
        .substr(str_shuffle('abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789') , 0 , 7 );
        return $val;
    }

    public function get_api_action()
    { 
        if(config('app.is_demo') == '1' && Auth::user()->user_type == 'Admin')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }
        if (session('logged_in') != 1)
        return redirect()->route('login');

        if(Auth::user()->user_type != 'Admin' && !in_array(15,$this->module_access))
        return redirect()->route('login');

        $api_key=$this->_api_key_generator(); 
        if(DB::table('native_api')->where('api_key', $api_key)->exists())
        $this->get_api_action();

        $user_id=Auth::user()->id;        
        if(DB::table('native_api')->where('user_id', $user_id)->exists())
        DB::table('native_api')->where('user_id',$user_id)->update(["api_key"=>$api_key]);
        else {
            DB::table('native_api')->insert([
                'api_key' => $api_key,
                'user_id' => $user_id
            ]);
        }   
            
        return redirect()->route('cron_job');

    }

    public function send_notification($api_key="")
    {
        if ($api_key=="") exit();
        $user_id=substr($api_key, 0, 1);

        if(!DB::table('native_api')->where('api_key', $api_key)->where('user_id', $user_id)->exists())
        {
            echo "API Key does not match with any user.";
            exit();
        }   

        if(!DB::table('users')->where('id', $user_id)->where('status', '1')->where('deleted', '0')->where('user_type','Admin')->exists())
        {
            echo "Invalid user.";
            exit();
        }     

        $current_date = date("Y-m-d");
        $tenth_day_before_expire = date("Y-m-d", strtotime("$current_date + 10 days"));
        $one_day_before_expire = date("Y-m-d", strtotime("$current_date + 1 days"));
        $one_day_after_expire = date("Y-m-d", strtotime("$current_date - 1 days"));

        // echo $tenth_day_before_expire."<br/>".$one_day_before_expire."<br/>".$one_day_after_expire;

        //send notification to members before 10 days of expire date
        $where = [['user_type', '!=', 'Admin'],
        ['expired_date', '=', $tenth_day_before_expire]];
        $info = array();
        $value = array();
        $info = DB::table('users')->where($where)->get();               
        $from = "";
        $mask = config('my_config.product_name');

        // getting email template info
        // $email_template_info = $this->basic->get_data("email_template_management",array('where'=>array('template_type'=>'membership_expiration_10_days_before')),array('subject','message'));
        $email_template_info = DB::table('email_template_management')
                            ->select('subject', 'message')
                            ->where('template_type', '=', 'membership_expiration_10_days_before')
                            ->get();
        $email_template_info =  json_decode(json_encode($email_template_info));
        if(isset($email_template_info[0]) && $email_template_info[0]->subject !='' && $email_template_info[0]->message !='') {

            $subject = $email_template_info[0]->subject;
            foreach ($info as $value) 
            {
                if(!$this->api_member_validity($value->id)) continue;
                $url = url('/');

                $message = str_replace(array('#USERNAME#','#APP_URL#','#APP_NAME#'),array($value->name,$url,$mask),$email_template_info[0]->message);

                $to = $value->email;
                $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
            }
        } else {

            $subject = "Payment Notification";
            foreach ($info as $value) 
            {
                $message = "Dear {$value->first_name} {$value->last_name},<br/> your account will expire after 10 days, Please pay your fees.<br/><br/>Thank you,<br/><a href='".url('/')."'>{$mask}</a> team";
                $to = $value->email;
                $this->_mail_sender($from, $to, $subject, $message, $mask, $html=0);
            }
        }

        //send notificatio to members before 1 day of expire date
        $where = [['user_type', '!=', 'Admin'],
            ['expired_date', '=', $one_day_before_expire]];
        $info = array();
        $value = array();
        $info = DB::table('users')->where($where)->get();
        $from = config('my_config.institute_email');
        $mask = config('my_config.product_name');

        // getting email template info
        // $email_template_info_01 = $this->basic->get_data("email_template_management",array('where'=>array('template_type'=>'membership_expiration_1_day_before')),array('subject','message'));
        $email_template_info = DB::table('email_template_management')
        ->select('subject', 'message')
        ->where('template_type', '=', 'membership_expiration_1_day_before')
        ->get();

        $email_template_info =  json_decode(json_encode($email_template_info));

        if(isset($email_template_info_01[0]) && $email_template_info_01[0]->subject != '' && $email_template_info_01[0]->message != '') {

            $subject = $email_template_info_01[0]->subject;
            foreach ($info as $value) {
                if(!$this->api_member_validity($value->id)) continue;
                $url = url('/');
                $message = str_replace(array('#USERNAME#','#APP_URL#','#APP_NAME#'),array($value->name,$url,$mask),$email_template_info_01[0]->message);

                $to = $value->email;
                $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
            }

        }
        else {
            $subject = "Payment Notification";
            foreach ($info as $value) {
                $message = "Dear {$value->first_name} {$value->last_name},<br/> your account will expire tomorrow, Please pay your fees.<br/><br/>Thank you,<br/><a href='".url('/')."'>{$mask}</a> team";
                $to = $value->email;
                $this->_mail_sender($from, $to, $subject, $message, $mask, $html=0);
            }
        }

        //send notificatio to members after 1 day of expire date
        $where = [['user_type', '!=', 'Admin'],
            ['expired_date', '=', $one_day_after_expire]];
        $info = array();
        $value = array();
        $info = DB::table('users')->where($where)->get();
        $from = config('my_config.institute_email');
        $mask = config('my_config.product_name');

        // $email_template_info_02 = $this->basic->get_data("email_template_management",array('where'=>array('template_type'=>'membership_expiration_1_day_after')),array('subject','message'));
        $email_template_info_02 = DB::table('email_template_management')
        ->select('subject', 'message')
        ->where('template_type', '=', 'membership_expiration_1_day_after')
        ->get();

        $email_template_info_02 =  json_decode(json_encode($email_template_info));

        if(isset($email_template_info_02[0]) && $email_template_info_02[0]->subject != '' && $email_template_info_02[0]->message != '') {

            $subject = $email_template_info_02[0]->subject;

            foreach ($info as $value) {
                if(!$this->api_member_validity($value->id)) continue;
                $url = url('/');
                $message = str_replace(array('#USERNAME#','#APP_URL#','#APP_NAME#'),array($value->name,$url,$mask),$email_template_info_02[0]->message);
                $to = $value->email;
                $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
            }

        } else {
            $subject = "Payment Notification";
            foreach ($info as $value) {
                $message = "Dear {$value->name},<br/> your account has been expired, Please pay your fees for continuity.<br/><br/>Thank you,<br/><a href='".url('/')."'>{$mask}</a> team";
                $to = $value->email;
                $this->_mail_sender($from, $to, $subject, $message, $mask, $html=0);
            }
        }

    }

    public function auction_domain($api_key)
    {
        if ($api_key=="") exit();
        $user_id=substr($api_key, 0, 1);

        // if(!$this->basic->is_exist("native_api",array("api_key"=>$api_key,"user_id"=>$user_id)))
        if(!DB::table('native_api')->where('api_key', $api_key)->where('user_id', $user_id)->exists())
        {
            echo "API Key does not match with any user.";
            exit();
        }   

        // if(!$this->basic->is_exist("users",array("id"=>$user_id,"status"=>"1","deleted"=>"0","user_type"=>"Admin")))
        if(!DB::table('users')->where('id', $user_id)->where('status', '1')->where('deleted', '0')->where('user_type','Admin')->exists())
        {
            echo "Invalid user.";
            exit();
        } 
        
        $this->_grab_auction_list_data();
    }

    public function get_keyword_position_data($api_key="")
    {
        $user_id="";
        if($api_key!="")
        {
            $explde_api_key=explode('-',$api_key);
            $user_id="";
            if(array_key_exists(0, $explde_api_key))
            $user_id=$explde_api_key[0];
        }

        if($api_key=="")
        {        
            echo "API Key is required.";    
            exit();
        }

        if(!DB::table('native_api')->where('api_key', $api_key)->where('user_id', $user_id)->exists())
        {
           echo "API Key does not match with any user.";
           exit();
        }

        if(!DB::table('users')->where('id', $user_id)->where('status', '1')->where('deleted', '0')->where('user_type','Admin')->exists())
        {
            echo "API Key does not match with any authentic user.";
            exit();
        }

        // $keywords = $this->basic->get_data("keyword_position_set",['where'=>['last_scan_date !='=>date('Y-m-d')]],'','',50);
        $keywords = DB::table('keyword_position_set')
            ->where('last_scan_date', '!=', date('Y-m-d'))
            ->limit(50)
            ->get();

        $keywords = json_decode(json_encode($keywords));

        foreach($keywords as $value){
            // $this->basic->update_data('keyword_position_set',['id'=>$value['id']],['last_scan_date'=>date('Y-m-d')]);
            DB::table('keyword_position_set')
                ->where('id', $value->id)
                ->update(['last_scan_date' => date('Y-m-d')]);
        }

        foreach($keywords as $value){

            $keyword = $value->keyword;
            $country = $value->country;
            $language = $value->language;
            $domain = $value->website;

            $keyword_position_google_data=$this->web_repport->keyword_position_google($keyword, $page_number=0, $proxy="",$country,$language,$domain);

            $keyword_position_bing_data=$this->web_repport->keyword_position_bing($keyword, $page_number=0, $proxy="",$country,$language,$domain);

            $keyword_position_yahoo_data=$this->web_repport->keyword_position_yahoo($keyword, $page_number=0, $proxy="",$country,$language,$domain);

            $data = array(
                "keyword_id" => $value->id,
                "user_id" => $value->user_id,
                "google_position" => $keyword_position_google_data["status"],
                "bing_position" => $keyword_position_bing_data["status"],
                "yahoo_position" => $keyword_position_yahoo_data["status"],
                "date" => date("Y-m-d")
                );
            // $this->basic->insert_data("keyword_position_report",$data);
            DB::table('keyword_position_report')->insert($data);

        }
    }

    public function delete_junk_files($api_key="")
    {
        if ($api_key=="") exit();
        $user_id=substr($api_key, 0, 1);

        if(!DB::table('native_api')->where('api_key', $api_key)->where('user_id', $user_id)->exists())
        {
            echo "API Key does not match with any user.";
            exit();
        }   

        if(!DB::table('users')->where('id', $user_id)->where('status', '1')->where('deleted', '0')->where('user_type','Admin')->exists())
        {
            echo "Invalid user.";
            exit();
        }

        $delete_junk_data_after_how_many_days = config('my_config.delete_junk_data_after_how_many_days');
        if($delete_junk_data_after_how_many_days == '') $delete_junk_data_after_how_many_days = 30;
        $to_date = date("Y-m-d H:i:s");
        $from_date = date("Y-m-d H:i:s",strtotime("$to_date-$delete_junk_data_after_how_many_days days"));
        DB::table('visitor_analysis_domain_list_data')
            ->where('date_time', '<', $from_date)
            ->delete();


        /****Clean Cache Directory , keep all files of last 24 hours******/
        $deletable_paths = [
            "url_shortener",
            "who_is",
            "url_encode",
            "url_decode",
            "unique_email",
            "social",
            "search_engine_index",
            "robot",
            "rank",
            "page_status",
            "metatag",
            "link",
            "keyword_position",
            "ip",
            "expired_domain",
            "email_validator",
            "email_encode_decode",
            "antivirus"
        ];
        foreach($deletable_paths as $path){
            @$this->delete_cache(storage_path('public/download/'.$dir));
        }
        @$this->delete_cache(storage_path('public/upload/tmp'));
        @file_put_contents(storage_path('logs/laravel.log'), '');

    }

    protected function delete_cache($myDir) //delete_junk_data
    {

        $cur_time=date('Y-m-d H:i:s');
        $yesterday=date("Y-m-d H:i:s",strtotime($cur_time." -1 day"));
        $yesterday=strtotime($yesterday);


        $dirTree = array();
        $di = new RecursiveDirectoryIterator($myDir,RecursiveDirectoryIterator::SKIP_DOTS);
        
        foreach (new RecursiveIteratorIterator($di) as $filename) {
        
        $dir = str_replace($myDir, '', dirname($filename));
        //$dir = str_replace('/', '>', substr($dir,1));
        
        $org_dir=str_replace("\\", "/", $dir);
        
        
        if($org_dir)
        $file_path = $org_dir. "/". basename($filename);
        else
        $file_path = basename($filename);

        $path_explode = explode(".",$file_path);
        $extension= array_pop($path_explode);

        if($file_path!='.htaccess' && $file_path!='index.html'){

             $full_file_path=$myDir."/".$file_path;

             $file_creation_time=filemtime($full_file_path);
             $file_creation_time=date('Y-m-d H:i:s',$file_creation_time); //convert unix time to system time zone 
             $file_creation_time=strtotime($file_creation_time);


             if($file_creation_time<$yesterday){
                $dirTree[] = trim($file_path,"/");
                unlink($full_file_path);

             }
                
        }

        
        }
        
        return $dirTree;
            
    }
}
