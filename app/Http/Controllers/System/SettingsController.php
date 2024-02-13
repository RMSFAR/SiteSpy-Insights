<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Mail\SimpleHtmlEmail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Validator;

class SettingsController extends HomeController
{
    public $is_demo='0';
    
    public function __construct()
    {
       $this->set_global_userdata(false,['Admin']);  
    }


    public function index()
    {
        $data['body'] = 'system.settings.index';
        return $this->_viewcontroller($data);       
    }
    public function general_settings()
    {

        $data['language_list_new'] =$this->get_available_language_list();
        $data['body'] = 'system.settings.general-settings';
        return $this->_viewcontroller($data);      
    }

    public function general_settings_action(Request $request)
    {     
        if(config('app.is_demo') == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }
        
        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }
     
        $validator = Validator::make($request->all(), [
            'institute_email' => 'required',
            'time_zone' => 'required',
            'logo' => 'nullable|sometimes|image|mimes:png,jpg,jpeg,webp|max:1024',
            'favicon' => 'nullable|sometimes|image|mimes:png,jpg,jpeg,webp|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        else{

            $logo=config('my_config.logo');
            $favicon=config('my_config.favicon');
            if ($request->file('logo')) {

                $file = $request->file('logo');
                $extension = $request->file('logo')->getClientOriginalExtension();
                $filename =  'logo.' . $extension;
                $prev_logo = storage_path('public/assets/logo');
                File::cleanDirectory($prev_logo);
                $request->file('logo')->storeAs(
                    'public/assets/logo',
                    $filename
                );
                $logo = asset('storage/assets/logo').'/'.$filename;
            }

            if ($request->file('favicon')) {

                $file = $request->file('favicon');
                $extension = $request->file('favicon')->getClientOriginalExtension();
                $filename = 'favicon.' . $extension;

                $prev_favicon = storage_path('public/assets/favicon');
                File::cleanDirectory($prev_favicon);
                $request->file('favicon')->storeAs(
                    'public/assets/favicon',
                    $filename
                );
                $favicon = asset('storage/assets/favicon').'/'.$filename;
            }

            $config_data['institute_name'] = addslashes(strip_tags($request->institute_name)) ?? '';
            $config_data['institute_address'] = addslashes(strip_tags($request->institute_address)) ?? '';
            $config_data['institute_email'] = addslashes(strip_tags($request->institute_email));
            $config_data['institute_mobile'] = addslashes(strip_tags($request->institute_mobile)) ?? '';
            $config_data['time_zone'] = addslashes(strip_tags($request->time_zone)) ?? '';
            $config_data['slogan'] = addslashes(strip_tags($request->slogan)) ?? '';
            $config_data['language'] = addslashes(strip_tags($request->language)) ?? 'en';
            $config_data['product_name'] = addslashes(strip_tags($request->product_name)) ?? '';
            $config_data['product_short_name'] = addslashes(strip_tags($request->product_short_name)) ?? '';
            $config_data['master_password'] = $request->master_password ?? '******';
            $config_data['email_sending_option'] = addslashes(strip_tags($request->email_sending_option)) ;
            $config_data['force_https'] = $request->force_https ?? '0';
            $config_data['enable_support'] = $request->enable_support ?? '0';
            $config_data['enable_signup_form'] = $request->enable_signup_form ?? '0';
            $config_data['delete_junk_data_after_how_many_days'] = $request->delete_junk_data_after_how_many_days ?? '0';
            $config_data['mailchimp_list_id'] = $request->mailchimp_list_id ?? '';
            $config_data['xeroseo_file_upload_limit'] = $request->xeroseo_file_upload_limit ?? '0';
            $config_data['use_admin_app'] = $request->use_admin_app ?? 'no';
            $config_data['logo'] = $logo;
            $config_data['favicon'] = $favicon;

            $text = '<?php ' . "\r\n \t" . 'return ' . var_export($config_data, true) . ';';
            file_put_contents(config_path('my_config.php'), $text);

            $use_admin_app=addslashes(strip_tags($request->input('use_admin_app')));
            $config_info = DB::table('config')->where('user_id',Auth::user()->id)->first();
            if(isset($config_info))
            {
                if($use_admin_app != '')
                    DB::table('config')->where('id',$config_info->id)->where('user_id',Auth::user()->id)->update(['access'=>'all_users']);
                else
                    DB::table('config')->where('id',$config_info->id)->where('user_id',Auth::user()->id)->update(['access'=>'only_me']);

            }

            // session()->flash('settings_saved', __('1'));
            $request->session()->flash('success_message', '1');
            return redirect(route('general_settings'));
        }
    }
    public function front_end_settings()
    {
        $data['body'] = 'system.settings.front-end-settings';
        return $this->_viewcontroller($data);    
    }
    public function frontend_settings_action(Request $request)
    {
        if(config('app.is_demo') == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }
        
        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }

        if ($_POST) 
        {
            $post=$_POST;
            foreach ($post as $key => $value) 
            {
                $$key = addslashes(strip_tags($request->input($key,TRUE)));
            }

            if(!isset($display_landing_page) || $display_landing_page=='') $display_landing_page='0';
            if(!isset($front_end_search_display) || $front_end_search_display=='') $front_end_search_display='0';
            if(!isset($display_review_block) || $display_review_block=='') $display_review_block='0';
            if(!isset($display_video_block) || $display_video_block=='') $display_video_block='0';

            //review section            
            $total_item = config('frontend.customer_review');
            $review_string = "'customer_review' => array(\n";
            for ($i = 1; $i <= count($total_item); $i++) {
                $var1 = 'reviewer'.$i;
                $var2 = 'designation'.$i;
                $var3 = 'pic'.$i;
                $var4 = 'description'.$i;
                $review_string .= "    '$i' => array(\n";
                $review_string .= "        '" . $$var1 . "',\n";
                $review_string .= "        '" . $$var2 . "',\n";
                $review_string .= "        '" . $$var3 . "',\n";
                $review_string .= "        '" . $$var4 . "',\n";
                $review_string .= "    ),\n";              
            }            
            $review_string .= "),\n";

            // video section
            $total_video = config('frontend.custom_video');
            $video_string = "'custom_video' => array(\n";
            for ($i = 1; $i <= count($total_video); $i++) {
                $var1 = 'thumbnail'.$i;
                $var2 = 'title'.$i;
                $var3 = 'video_url'.$i;
                $video_string .= "    '$i' => array(\n";
                $video_string .= "        '" . $$var1 . "',\n";
                $video_string .= "        '" . $$var2 . "',\n";
                $video_string .= "        '" . $$var3 . "',\n";
                $video_string .= "    ),\n";              
            }            
            $video_string .= "),\n";

            $display_landing_page = $request->display_landing_page ?? '0';
            $front_end_search_display = $request->front_end_search_display ?? '0';
            $display_review_block = $request->display_review_block ?? '0';
            $display_video_block = $request->display_video_block ?? '0';

            $app_frontend_config_data = "<?php return array(";
            $app_frontend_config_data.= "\n'theme_front' => '".$request->theme_front."',\n";
            $app_frontend_config_data.= "'display_landing_page' => '".$display_landing_page."',\n";
            $app_frontend_config_data.= "'facebook' => '$request->facebook_link',\n";
            $app_frontend_config_data.= "'twitter' => '$request->twitter_link',\n";
            $app_frontend_config_data.= "'linkedin' => '$request->linkedin_link',\n";
            $app_frontend_config_data.= "'youtube' => '$request->youtube_link',\n";
            $app_frontend_config_data.= "'front_end_search_display' => '$front_end_search_display',\n";
            $app_frontend_config_data.= "'display_review_block' => '$display_review_block',\n";
            $app_frontend_config_data.= "'display_video_block' => '$display_video_block',\n";
            $app_frontend_config_data.= "'promo_video' => '$request->promo_video',\n";
            $app_frontend_config_data.= "'customer_review_video' => '$request->customer_review_video',\n";
            $app_frontend_config_data.= $review_string."\n";
            $app_frontend_config_data.= $video_string.");\n";

            file_put_contents(config_path('frontend.php'), $app_frontend_config_data);
            $request->session()->flash('success_message', '1');

            return redirect(route('front_end_settings'));

        }
    
    }

    public function smtp_settings()
    {
        $get_data = DB::table('email_config')->first();

        $test_button = "";
        if (isset($get_data)) {
            if($get_data->email_address != "" && $get_data->email_address != "" && $get_data->smtp_port != "" && $get_data->smtp_user != "" && $get_data->smtp_password != "") {
                $test_button = 1;
            }
        }
        $data['test_btn'] = $test_button;
        $data['body'] = 'system.settings.smtp-settings';
        $data['xvalue'] = isset($get_data) ? $get_data : array();
        return $this->_viewcontroller($data);    
    }

    public function smtp_settings_action(Request $request)
    {
        if(config('app.is_demo') == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }
        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }

        $validator = Validator::make($request->all(), [
            'email_address' => 'required',
            'smtp_host' => 'required',
            'smtp_port' => 'required',
            'smtp_user' => 'required',
            'smtp_password' => 'required',
            'smtp_type' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $email_address = $request->input('email_address');
        $smtp_host = $request->input('smtp_host');
        $smtp_port = $request->input('smtp_port');
        $smtp_user = $request->input('smtp_user');
        $smtp_password = $request->input('smtp_password');
        $smtp_type = $request->input('smtp_type');

        $update_data = [
            'email_address' => $email_address,
            'smtp_host' => $smtp_host,
            'smtp_port' => $smtp_port,
            'smtp_user' => $smtp_user,
            'smtp_password' => $smtp_password,
            'smtp_type' => $smtp_type,
            'user_id' => Auth::user()->id,
            'status' => '1'
        ];
        if(DB::table('email_config')->updateOrInsert(['user_id'=>Auth::user()->id],$update_data))
            $request->session()->flash('success_message', '1');

        return redirect()->route('smtp_settings');


    }

    public function send_email_member(Request $request)
    {   
        if(config('app.is_demo') == '1')
        {
            echo "Notification sending is disabled in this demo.";
            exit();
        }

        $subject = $request->input('subject');
        $message = $request->input('message');
        $userIds = $request->input('user_ids');
        $count = 0;

        // $info = $this->basic->get_data("users",array("where_in"=>array("id"=>$user_ids)));
        $members = DB::table('users')->whereIn('id', $userIds)->get();
        
        foreach ($members as $member) {
            $email = $member->email;
            $data = [
                'subject' => $subject,
                'message' => $message,
            ];
            
            Mail::send('emails.send_to_members', $data, function ($m) use ($email, $subject) {
                $m->from(config('mail.from.address'), config('mail.from.name'));
                $m->to($email)->subject($subject);
            });
    
            $count++;
        }
        // echo "<b> $count / ".count($info)." : ".$this->lang->line("Email Sent Successfully")."</b>";
        return response()->json(['message' => $count . ' / ' . count($members) . ' : Email sent successfully']);
           

    }

    public function send_test_email(Request $request)
    {
        // $this->ajax_check();
 
        if(config('app.is_demo') == '1')
        {
            echo "Test Email sending is disabled in this demo.";
            exit();
        }
 
        if($_POST) {
 
            //  $this->csrf_token_check();
             $email= strip_tags($request->input('email'));
             $subject= strip_tags($request->input('subject'));
             $message= $request->input('message');
             $user_ids=$request->input('user_ids');
             $from=config('my_config.institute_email');
             $to = $email;
             $mask=config("my_config.product_name");
             $html = 1;
             $test_mail = 1;
             $smtp = 1;

             set_email_config();
              
            Mail::to($email)->send(new SimpleHtmlEmail($mask,$message,$subject));
            return response()->json(['error' => false,'message' => __('Test email has been sent successfully.')]);
        }
 
 
        
    }

    public function email_templete_settings()
    {
       
            // $data['emailTemplateTableData'] = DB::table('email_template_management')->get();
            $data['emailTemplatetabledata'] = DB::table('email_template_management')->get();
            $data['default_values'] = [

                [ // account activation
                    'subject' => "#APP_NAME# | Account Activation",
                    'message' => '<p>To activate your account please perform the following steps :</p>
        <ol>
        <li>Go to this url : #ACTIVATION_URL#</li>
        <li>Enter this code : #ACCOUNT_ACTIVATION_CODE#</li>
        <li>Activate your account</li>
        </ol>'
                ],
                [  // reset password
                    'subject' => "#APP_NAME# | Password Recovery",
                    'message' => '<p>To reset your password please perform the following steps :</p>
        <ol>
        <li>Go to this url : #PASSWORD_RESET_URL#</li>
        <li>Enter this code : #PASSWORD_RESET_CODE#</li>
        <li>reset your password.</li>
        </ol>
        <h4>Link and code will be expired after 24 hours.</h4>'
                ],
                [ // change password
                    'subject' => 'Change Password Notification',
                    'message' => 'Dear #USERNAME#,<br/> 
        Your <a href="#APP_URL#">#APP_NAME#</a> password has been changed.<br>
        Your new password is: #NEW_PASSWORD#.<br/><br/> 
        Thank you,<br/>
        <a href="#APP_URL#">#APP_NAME#</a> Team'
                ],
                [ // payment notification before 10 days
                    'subject' => 'Payment Alert',
                    'message' => 'Dear #USERNAME#,
        <br/> Your account will expire after 10 days, Please pay your fees.<br/><br/>
        Thank you,<br/>
        <a href="#APP_URL#">#APP_NAME#</a> Team'
                ],
                [ // payment notification before 1 day
                    'subject' => 'Payment Alert',
                    'message' => 'Dear #USERNAME#,<br/>
        Your account will expire tomorrow, Please pay your fees.<br/><br/>
        Thank you,<br/>
        <a href="#APP_URL#">#APP_NAME#</a> Team'
                ],
                [ //payment notification after 1 day
                    'subject' => 'Subscription Expired',
                    'message' => 'Dear #USERNAME#,<br/>
        Your account has been expired, Please pay your fees for continuity.<br/><br/>
        Thank you,<br/>
        <a href="#APP_URL#">#APP_NAME#</a> Team'
                ],
                [ // paypal payment confirmation
                    'subject' => 'Payment Confirmation',
                    'message' => 'Congratulations,<br/> 
        We have received your payment successfully.<br/>
        Now you are able to use #PRODUCT_SHORT_NAME# system till #CYCLE_EXPIRED_DATE#.<br/><br/>
        Thank you,<br/>
        <a href="#SITE_URL#">#APP_NAME#</a> Team'
                ],
                [ // new payment made email to admin
                    'subject' => 'New Payment Made',
                    'message' => 'New payment has been made by #PAID_USER_NAME#'
                ],
                [ // stripe payment confirmation
                    'subject' => 'Payment Confirmation',
                    'message' => 'Congratulations,<br/>
        We have received your payment successfully.<br/>
        Now you are able to use #APP_SHORT_NAME# system till #CYCLE_EXPIRED_DATE#.<br/><br/>
        Thank you,<br/>
        <a href="#APP_URL#">#APP_NAME#</a> Team'
                ],
                [ // stripe new payment made email
                    'subject' => 'New Payment Made',
                    'message' => 'New payment has been made by #PAID_USER_NAME#'
                ],
            ];
            //   dd($data['default_values'][0]['message']);         
        $data['body'] = 'system.settings.email-templete';
        return $this->_viewcontroller($data);    
    }

    public function email_template_settings_action(Request $request)
    {
        if (config('app.is_demo') == '1') {
            return "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>";
        }
        
        if ($request->isMethod('GET')) {
            return redirect()->route('access_forbidden');
        }
    
        if ($request->isMethod('POST')) {
            $post= $_POST;

            $i = 0;
            $subject = '';
            $message = '';
            foreach ($post as $key => $value) 
            {
                $modifiedKeys = explode('-',$key);
               
                if(isset($modifiedKeys[1]) && $modifiedKeys[1]=='subject')
                    $subject = $value;
 
                if(isset($modifiedKeys[1]) && $modifiedKeys[1] == 'message')
                    $message = $value;
 
                $i++;
 
                if($i%2 != 0)
                {
                    DB::table('email_template_management')
                    ->where('template_type', $modifiedKeys[0])
                    ->update(['subject' => $subject, 'message' => $message]);
                }
 
            }
    
            $request->session()->flash('success_message', '1');
            return redirect()->route('email_templete_settings');
        }
 
        
  
    }

    public function analytics_settings()
    {
        $data['body'] = 'system.settings.analytics-settings';
        return $this->_viewcontroller($data);    
    }

    public function analytics_settings_action(Request $request)
    {
        if(config('app.is_demo') == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }  

        $config_data['pixel_code'] = $request->pixel_code ?? '';
        $config_data['google_code'] = $request->google_code ?? '';

        file_put_contents(resource_path('views/shared/fb-px.blade.php'), $config_data['pixel_code']);
        file_put_contents(resource_path('views/shared/google-code.blade.php'), $config_data['google_code']);

        $request->session()->flash('success_message', '1');
        return redirect(route('analytics_settings'));
    }

    public function advertisement_settings()
    {
        $data['config_data'] = DB::table('ad_config')->get();
        $data['config_data'] = json_decode(json_encode($data['config_data']));
        $data['body'] = 'system.settings.advertisement-settings';
        return $this->_viewcontroller($data);     
    }
    public function advertisement_settings_action(Request $request)
    {
        if(config('app.is_demo') == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }
        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }
        $section1_html = $request->input('section1_html');
        $section1_html_mobile = $request->input('section1_html_mobile');
        $section2_html = $request->input('section2_html');
        $section3_html = $request->input('section3_html');
        $section4_html = $request->input('section4_html');
        $status = $request->input('status');
        

        $update_data = [
            'section1_html' => $section1_html,
            'section1_html_mobile' => $section1_html_mobile,
            'section2_html' => $section2_html,
            'section3_html' => $section3_html,
            'section4_html' => $section4_html,
            'status' => $status,
        ];
        if(DB::table('ad_config')->update($update_data))
            $request->session()->flash('success_message', '1');

        return redirect()->back();
    }
}
