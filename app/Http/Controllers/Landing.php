<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;

// use Config;


class Landing extends HomeController
{

    public function index(){

        if(config('frontend.display_landing_page')=='0') return redirect()->route('dashboard');
        if (!isset($data['page_title'])) {
            $data['page_title']="";
        }

        $language = config('my_config.language');
        if(isset($language) && !empty($language)) {
            App::setLocale($language);
        }

        $config_data=array();
        $data=array();
        $price=0;
        $currency="USD";
        $config_data = DB::table('settings_payments')->first();
        
        if(isset($config_data) && !empty($config_data))
        {
            $currency=$config_data->currency;
        }
        $data['price']=$price;
        $data['currency']=$currency;

        $currency_icons = $this->currency_icon();
        $data["curency_icon"]= isset($currency_icons[$currency])?$currency_icons[$currency]:"$";

        //catcha for contact page
        $data['contact_num1']=$this->_random_number_generator(2);
        $data['contact_num2']=$this->_random_number_generator(1);
        $contact_captcha= $data['contact_num1']+ $data['contact_num2'];
        session()->put('contact_captcha', $contact_captcha);
        // $data["language_info"] = $this->_language_list();
        $data["pricing_table_data"] = DB::table('package')
        ->where('is_default', '0')
        ->where('price', '>', 0)
        ->where('validity', '>', 0)
        ->where('visible', '1')
        ->orderByRaw('CAST(price AS SIGNED)')
        ->get();
            
        $data["default_package"] = DB::table('package')
                                ->where('is_default', '1')
                                ->where('validity', '>', 0)
                                ->where('price', 'Trial')
                                ->get();

        if(config('frontend.theme_front')!="") $loadthemebody=config('frontend.theme_front');


        if($loadthemebody=='blue')     { $themecolorcode="#1193D4";}
        if($loadthemebody=='white')    { $themecolorcode="#303F42";}
        if($loadthemebody=='black')    { $themecolorcode="#1A2226";}
        if($loadthemebody=='green')    { $themecolorcode="#00A65A";}
        if($loadthemebody=='red')      { $themecolorcode="#E55053";}
        if($loadthemebody=='yellow')   { $themecolorcode="#F39C12";}

        $data['THEMECOLORCODE']=$themecolorcode;


        $data['APP_VERSION']=$this->APP_VERSION;
        $data['app_product_id']=$this->app_product_id;
        $data['ad_content4']=$this->ad_content4;
        $data['ad_content3']=$this->ad_content3;
        $data['ad_content2']=$this->ad_content2;
        $data['ad_content1_mobile']=$this->ad_content1_mobile;
        $data['ad_content1']=$this->ad_content1;
        $data['is_ad_enabled4']=$this->is_ad_enabled4;
        $data['is_ad_enabled3']=$this->is_ad_enabled3;
        $data['is_ad_enabled2']=$this->is_ad_enabled2;
        $data['is_ad_enabled1']=$this->is_ad_enabled1;
        $data['is_ad_enabled']=$this->is_ad_enabled;


        return view('landing/index', $data);
    }


    public function policy_privacy(){
        $data['body'] = 'landing.policy.privacy';
        $data['title'] = __('Privacy Policy');
        return $this->_front_viewcontroller($data);
    }

    public function policy_terms(){
        $data['body'] = 'landing.policy.terms';
        $data['title'] = __('Terms of Service');
        return $this->_front_viewcontroller($data);
    }

    public function policy_refund(){
        $data['body'] = 'landing.policy.refund';
        $data['title'] = __('Refund Policy');
        return $this->_front_viewcontroller($data);
    }

    public function policy_gdpr(){
        $data['body'] = 'landing.policy.gdpr';
        $data['title'] = __('GDPR');
        return $this->_front_viewcontroller($data);
    }

    public function accept_cookie(){
        session(['allow_cookie'=>'yes']);
    }

    public function install(){
        \Artisan::call('storage:link');
        $source = base_path('assets');
        $target = public_path('assets');
        if (!file_exists($target)) {
            @File::link($source, $target);
        }

        $install_txt_permission = File::isWritable(public_path("install.txt"));
        $env_file_permission = File::isWritable(base_path('.env'));

        $views_file_permission =
        File::isWritable(base_path('resources/views')) &&
        File::isWritable(base_path('resources/views/auth')) &&
        File::isWritable(base_path('resources/views/announcement')) &&
        File::isWritable(base_path('resources/views/design')) &&
        File::isWritable(base_path('resources/views/shared')) &&
        File::isWritable(base_path('resources/views/landing')) &&
        File::isWritable(base_path('resources/views/member')) &&
        File::isWritable(base_path('resources/views/profile')) &&
        File::isWritable(base_path('resources/views/seo-tools')) &&
        File::isWritable(base_path('resources/views/subscription')) &&
        File::isWritable(base_path('resources/views/support')) &&
        File::isWritable(base_path('resources/views/system')) &&
        File::isWritable(base_path('resources/views/user')) ;

        $lang_file_permission =
        File::isWritable(base_path('resources/lang/en')) ;

        $helpers_file_permission = File::isWritable(base_path('app/Helpers'));

        $controllers_file_permission =
        File::isWritable(base_path('app/Http/Controllers')) &&
        File::isWritable(base_path('app/Http/Controllers/SEO_tools')) &&
        File::isWritable(base_path('app/Http/Controllers/System')) &&
        File::isWritable(base_path('app/Http/Controllers/Auth'));

        $middleware_file_permission = File::isWritable(base_path('app/Http/Middleware'));

        $services_file_permission =
        File::isWritable(base_path('app/Services')) &&
        File::isWritable(base_path('app/Services/Custom')) &&
        File::isWritable(base_path('app/Services/Payment')) &&
        File::isWritable(base_path('app/Providers')) &&
        File::isWritable(base_path('app/Providers/Custom')) &&
        File::isWritable(base_path('app/Providers/Payment'));

        $config_file_permission = File::isWritable(base_path('config/app.php'))&&
        File::isWritable(base_path('config/my_config.php'))&&
        File::isWritable(base_path('config/frontend.php'))&&
        File::isWritable(base_path('config/infyom'));

        $assets_file_permission =
        File::isWritable(base_path('assets/custom-css')) &&
        File::isWritable(base_path('assets/custom-css/analysis-tools')) &&
        File::isWritable(base_path('assets/custom-css/seo-tools')) &&
        File::isWritable(base_path('assets/custom-css/url-shortner')) &&
        File::isWritable(base_path('assets/custom-js')) &&
        File::isWritable(base_path('assets/custom-js/analysis-tools')) &&
        File::isWritable(base_path('assets/custom-js/announcement')) &&
        File::isWritable(base_path('assets/custom-js/auth')) &&
        File::isWritable(base_path('assets/custom-js/codeminify')) &&
        File::isWritable(base_path('assets/custom-js/keyword-tracking')) &&
        File::isWritable(base_path('assets/custom-js/security-tools')) &&
        File::isWritable(base_path('assets/custom-js/seo-tools/keyword-tracking')) &&
        File::isWritable(base_path('assets/custom-js/social-apps')) &&
        File::isWritable(base_path('assets/custom-js/subscription')) &&
        File::isWritable(base_path('assets/custom-js/url-shortner')) &&
        File::isWritable(base_path('assets/custom-js/utilities')) &&
        File::isWritable(base_path('assets/favicon')) &&
        File::isWritable(base_path('assets/images')) &&
        File::isWritable(base_path('assets/images/flags')) &&
        File::isWritable(base_path('assets/images/payment')) &&
        File::isWritable(base_path('assets/modules')) &&
        File::isWritable(base_path('assets/js')) &&
        File::isWritable(base_path('assets/js/custom')) &&
        File::isWritable(base_path('assets/js/posts')) &&
        File::isWritable(base_path('assets/css')) &&
        File::isWritable(base_path('assets/language')) &&
        File::isWritable(base_path('assets/modules')) &&
        File::isWritable(base_path('assets/pdf')) &&
        File::isWritable(base_path('assets/plugins')) &&
        File::isWritable(base_path('assets/pre-loader')) &&
        File::isWritable(base_path('assets/site_new')) &&
        File::isWritable(base_path('assets/js/custom')) &&
        File::isWritable(base_path('assets/js/posts')) &&
        File::isWritable(base_path('assets/mainlanding'));

        $routes_file_permission = File::isWritable(base_path('routes/web.php'));

        $storage_file_permission =
        File::isWritable(base_path('storage/framework/cache/data')) &&
        File::isWritable(base_path('storage/logs')) &&
        File::isWritable(base_path('storage/app/public/upload/tmp')) &&
        File::isWritable(base_path('storage/app/public/download')) &&
        File::isWritable(base_path('storage/app/public/profile'));

        $data['body'] = 'auth.install';
        $data['install_txt_permission'] = $install_txt_permission;
        $data['env_file_permission'] = $env_file_permission;
        $data['resource_file_permission'] = $views_file_permission && $lang_file_permission;
        $data['http_file_permission'] = $controllers_file_permission && $middleware_file_permission;
        $data['helpers_file_permission'] = $helpers_file_permission;
        $data['services_file_permission'] = $services_file_permission;
        $data['config_file_permission'] = $config_file_permission;
        $data['assets_file_permission'] = $assets_file_permission;
        $data['routes_file_permission'] = $routes_file_permission;
        $data['storage_file_permission'] = $storage_file_permission;

        // return $this->_site_viewcontroller($data);
        return view($data['body'],$data);
    }

    public function installation_submit(Request $request)
    {
        $current_url = trim(url()->current(),'/');
        $https = str_starts_with($current_url,'https');
        $domain = get_domain_only($current_url);
        $rules = [];
        $rules['host_name'] = 'required';
        $rules['database_name'] = 'required';
        $rules['database_username'] = 'required';

        $rules['app_username'] = 'required|email';
        $rules['app_password'] = 'required';
        $request->validate($rules);

        $host_name = $request->host_name;
        $database_name = $request->database_name;

        $database_username = $request->database_username;
        $database_password = $request->database_password;

        $app_username = $request->app_username;
        $app_password = $request->app_password;


        $con=@mysqli_connect($host_name, $database_username, $database_password);
        if (!$con) {
            $mysql_error = "Could not connect to MySQL : ";
            $mysql_error .= mysqli_connect_error();

            die($mysql_error);
        }
        if (!@mysqli_select_db($con,$database_name)) {
            die("database not found");
        }

        Config::set('database.connections.mysql.host', $host_name);
        Config::set('database.connections.mysql.database',  $database_name);
        Config::set('database.connections.mysql.username', $database_username);
        Config::set('database.connections.mysql.password', $database_password);

        $path = base_path('.env');
        $initial_env = public_path('initial_env.txt');
        $test = file_get_contents($initial_env);
        if (file_exists($path))
        {
            $test = str_replace('DB_HOST=', 'DB_HOST="'.$host_name.'"', $test);
            $test = str_replace('DB_DATABASE=', 'DB_DATABASE="'.$database_name.'"', $test);
            $test = str_replace('DB_USERNAME=', 'DB_USERNAME="'.$database_username.'"', $test);
            $test = str_replace('DB_PASSWORD=', 'DB_PASSWORD="'.$database_password.'"', $test);
            $test = str_replace('APP_DOMAIN="sitespy.test"', 'APP_DOMAIN="'.$domain.'"', $test);
            if($https) {
                $test = str_replace('APP_PROTOCOL="http://"', 'APP_PROTOCOL="https://"', $test);
                $test = str_replace('FORCE_HTTPS=false', 'FORCE_HTTPS=true', $test);
            }
            file_put_contents($path,$test);
        }

        $dump_sql_path = public_path('initial_db.sql');
        $dump_file = $this->import_dump($dump_sql_path,$con);
        DB::table('version')->insert(['version'=>trim(env('APP_VERSION')),'current'=>'1','date'=>date('Y-m-d H:i:s')]);
        //generating hash password for admin and updaing database
        $app_password = Hash::make($app_password);
        DB::table('users')->where('user_type','Admin')->update(["email" => $app_username, "password" => $app_password,"status" => "1", "deleted" => "0"]);
        //generating hash password for admin and updaing database

        //deleting the install.txt file,because installation is complete
        if (file_exists(public_path('install.txt'))) {
          unlink(public_path('install.txt'));                  
        }
        //deleting the install.txt file,because installation is complete
        return redirect(route('login'));
    }

    public function import_dump($filename = '',$con='')
    {
        if ($filename=='') {
            return false;
        }
        if (!file_exists($filename)) {
            return false;
        }
        // Temporary variable, used to store current query
        $templine = '';
        // Read in entire file
        $lines = file($filename);
        // Loop through each line
        foreach ($lines as $line) {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '') {
                continue;
            }

            // Add this line to the current segment
            $templine .= $line;
            // If it has a semicolon at the end, it's the end of the query
            if (substr(trim($line), -1, 1) == ';') {

                mysqli_query($con, $templine);
                // Reset temp variable to empty
                $templine = '';
            }
        }
        return true;

    }

    public function account_activation()
    {
        $data["page_title"] = __("Account Activation");

        $data["body"] = 'auth.account-activation';
        return $this->_front_viewcontroller($data);
    }

    public function account_activation_action(Request $request)
    {
        if ($_POST) {
            $code=$request->input('code', true);
            $email=$request->input('email', true);

            $table='users';
            $where['where']=array('activation_code'=>$code,'email'=>$email,'status'=>"0");
            $select=array('id');

            // $result=$this->basic->get_data($table, $where, $select);
            $result=DB::table($table)
                    ->where('activation_code',$code)
                    ->where('email',$email)
                    ->where('status',"0")
                    ->select('id')
                    ->first();

            if (!isset($result)) {
                echo 0;
            } else {
                $user_id=$result->id;

                // $this->basic->update_data('users', array('id'=>$user_id), array('status'=>'1'));
                DB::table('users')->where('id',$user_id)->update(['status'=>'1']);
                echo 2;

            }
        }
    }
}
