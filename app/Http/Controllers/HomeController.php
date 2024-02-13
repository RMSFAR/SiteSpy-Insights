<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\SimpleHtmlEmail;
use Illuminate\Http\Request;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request as RQ;


use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;
use App\Services\Custom\WebCommonReportServiceInterface;


class HomeController extends BaseController
{
    public $module_access=[];
    public $language='English';
    public $is_rtl=false;
    public $is_demo='0';
    public $is_trial=false;

    public $is_ad_enabled=false;
    public $is_ad_enabled1=false;
    public $is_ad_enabled2=false;
    public $is_ad_enabled3=false;
    public $is_ad_enabled4=false;

    public $ad_content1="";
    public $ad_content1_mobile="";
    public $ad_content2="";
    public $ad_content3="";
    public $ad_content4="";
    public $app_product_id=15641449;
    public $APP_VERSION="";


    public $is_admin = true;
    public $is_agent = false;
    public $is_member = false;
    public $is_manager = false;
    public $user_id;
    public $user_type = '';
    public $module_ids = [];
    public $monthly_limit = [];
    public $user_limit = '-1';
    public $parent_user_id = '1';
    public $parent_package_id = null;
    public $expired_date = null;
    public $module_id_no_of_website = 1;
    public $module_id_recorded_sessions = 2;
    public $module_id_month_of_data_storage = 3;
    public $module_id_affiliate_system = 12;
    public $module_id_team_member = 13;
    public $current_package = '1';

    protected function set_global_userdata($check_validity=false,$allowed_role=[], $denied_role=[] ,$module_id=null)
    {
        set_time_limit(0);
        $this->middleware('auth');
        $this->middleware(function ($request, $next) use ($check_validity,$allowed_role,$denied_role,$module_id) {
            if(Auth::user())
            {
                if(Auth::user()->status=='0') {
                    header('Location:'.route('logout'));
                    die;
                }
                $this->set_auth_variables();

                if(!empty($denied_role)){
                    $deny_access = false;
                    if(in_array('Admin',$denied_role) && $this->is_admin) $deny_access  = true;
                    if(in_array('Member',$denied_role) && $this->is_member) $deny_access  = true;
                    if($deny_access) abort('403');
                }

                if(!empty($allowed_role)){
                    $allow_access = false;
                    if(in_array('Admin',$allowed_role) && $this->is_admin) $allow_access  = true;
                    if(in_array('Member',$allowed_role) && $this->is_member) $allow_access  = true;
                    if(!$allow_access) abort('403');
                }
                if($check_validity) {
                    $this->member_validity();
                    $is_passed = $this->important_feature(false);
                    if(!$is_passed) {
                        if($this->is_admin) return redirect()->route('credential-check');
                        else abort(403);
                    }
                }
                if(!$this->is_admin && !empty($module_id)){
                    if(!is_array($module_id) && !in_array($module_id,$this->module_ids)) abort('403');
                    else if(is_array($module_id) && count(array_intersect($this->module_ids,$module_id))==0) abort('403');
                }
                return $next($request);
            }
        });
    }

    protected function set_auth_variables(){
        $user_id = Auth::user()->id;
        $this->user_id = $user_id;
        session(['auth_user_id' => $this->user_id]);

        $user_type = Auth::user()->user_type;

        if($user_type=='Admin') $this->is_admin = true;
        else $this->is_member = true;

        $this->user_type = $user_type;
        $this->current_package = Auth::user()->package_id;
        if($this->is_member){
            $package_data = $this->get_package($this->current_package);
            $module_ids = isset($package_data->module_ids) ? explode(',',$package_data->module_ids) : [];
           
            $this->is_trial = isset($package_data->is_default) ? (bool) $package_data->is_default : false;
            $monthly_limit = isset($package_data->monthly_limit) && !empty($package_data->monthly_limit) ? json_decode($package_data->monthly_limit,true) : [];
            Auth::user()->module_ids = $this->module_ids = $module_ids;
            if(!empty($monthly_limit)) Auth::user()->monthly_limit = $this->monthly_limit = $monthly_limit;
        }
        $this->expired_date = Auth::user()->expired_date;
        return true;
    }


    // protected function set_global_userdata()
    // {
    //     set_time_limit(0);
    //     $this->is_rtl=FALSE;

    //     $is_demo = config("my_config.is_demo");
    //     if($is_demo=="") $is_demo="0";
    //     $this->is_demo=$is_demo;

    //     $this->language="";
    //     // $this->_language_loader();

    //     $this->is_ad_enabled=false;
    //     $this->is_ad_enabled1=false;
    //     $this->is_ad_enabled2=false;
    //     $this->is_ad_enabled3=false;
    //     $this->is_ad_enabled4=false;

    //     $this->ad_content1="";
    //     $this->ad_content1_mobile="";
    //     $this->ad_content2="";
    //     $this->ad_content3="";
    //     $this->ad_content4="";
    //     $this->APP_VERSION="";

    //     ignore_user_abort(TRUE);

    //     $this->user_id = Auth::user()->id;


    //     // $ad_config = DB::table('ad_config')->get();
    //     // if(isset($ad_config[0]->status))
    //     // {
    //     //    if($ad_config[0]->status == "1")
    //     //    {
    //     //         $this->is_ad_enabled = ($ad_config[0]->status == "1") ? true : false;
    //     //         if($this->is_ad_enabled)
    //     //         {
    //     //             $this->is_ad_enabled1 = ($ad_config[0]->section1_html=="" && $ad_config[0]->section1_html_mobile=="") ? false : true;
    //     //             $this->is_ad_enabled2 = ($ad_config[0]->section2_html=="") ? false : true;
    //     //             $this->is_ad_enabled3 = ($ad_config[0]->section3_html=="") ? false : true;
    //     //             $this->is_ad_enabled4 = ($ad_config[0]->section4_html=="") ? false : true;

    //     //             $this->ad_content1          = htmlspecialchars_decode($ad_config[0]->section1_html,ENT_QUOTES);
    //     //             $this->ad_content1_mobile   = htmlspecialchars_decode($ad_config[0]->section1_html_mobile,ENT_QUOTES);
    //     //             $this->ad_content2          = htmlspecialchars_decode($ad_config[0]->section2_html,ENT_QUOTES);
    //     //             $this->ad_content3          = htmlspecialchars_decode($ad_config[0]->section3_html,ENT_QUOTES);
    //     //             $this->ad_content4          = htmlspecialchars_decode($ad_config[0]->section4_html,ENT_QUOTES);
    //     //         }
    //     //    }
    //     // }

        
    //     if (session('logged_in') == 1 && session('user_type') != 'Admin')
    //     {
    //         $package_info = session("package_info");
    //         $module_ids='';
    //         if(isset($package_info["module_ids"])) $module_ids = $package_info["module_ids"];
    //         $this->module_access=explode(',', $module_ids);
    //     }

    //     // $version_data=DB::table("version")->where("current","1")->get();
    //     // $appversion=isset($version_data[0]->version) ? $version_data[0]->version : "";
    //     // $this->APP_VERSION=$appversion;

    //     if(config('my_config.force_https') == '1')
    //     {
    //         $currentUrl = RQ::path();
    //         $actualLink = url($currentUrl);
            
    //         if (strpos($actualLink, 'http://') === 0) {
    //             $newLink = str_replace('http://', 'https://', $actualLink);
    //             return Redirect::to($newLink);
    //         }
    //     }


    // }

    protected function paypal_stripe_currency_list()
    {
        return array('USD','AUD','BRL','CAD','CZK','DKK','EUR','HKD','HUF','ILS','JPY','MYR','MXN','TWD','NZD','NOK','PHP','PLN','GBP','RUB','SGD','SEK','CHF','VND');
    }


    //  used in all types of bulk message campaign

    protected function currecny_list_all()
    {
        $list =  array
        (
            "AED"=> "United Arab Emirates dirham",
            "AFN"=> "Afghan afghani",
            "ALL"=> "Albanian lek",
            "AMD"=> "Armenian dram",
            "ANG"=> "Netherlands Antillean guilder",
            "AOA"=> "Angolan kwanza",
            "ARS"=> "Argentine peso",
            "AUD"=> "Australian dollar",
            "AWG"=> "Aruban florin",
            "AZN"=> "Azerbaijani manat",
            "BAM"=> "Bosnia and Herzegovina convertible mark",
            "BBD"=> "Barbados dollar",
            "BDT"=> "Bangladeshi taka",
            "BGN"=> "Bulgarian lev",
            "BHD"=> "Bahraini dinar",
            "BIF"=> "Burundian franc",
            "BMD"=> "Bermudian dollar",
            "BND"=> "Brunei dollar",
            "BOB"=> "Boliviano",
            "BRL"=> "Brazilian real",
            "BSD"=> "Bahamian dollar",
            "BTN"=> "Bhutanese ngultrum",
            "BWP"=> "Botswana pula",
            "BYN"=> "New Belarusian ruble",
            "BYR"=> "Belarusian ruble",
            "BZD"=> "Belize dollar",
            "CAD"=> "Canadian dollar",
            "CDF"=> "Congolese franc",
            "CHF"=> "Swiss franc",
            "CLF"=> "Unidad de Fomento",
            "CLP"=> "Chilean peso",
            "CNY"=> "Renminbi|Chinese yuan",
            "COP"=> "Colombian peso",
            "CRC"=> "Costa Rican colon",
            "CUC"=> "Cuban convertible peso",
            "CUP"=> "Cuban peso",
            "CVE"=> "Cape Verde escudo",
            "CZK"=> "Czech koruna",
            "DJF"=> "Djiboutian franc",
            "DKK"=> "Danish krone",
            "DOP"=> "Dominican peso",
            "DZD"=> "Algerian dinar",
            "EGP"=> "Egyptian pound",
            "ERN"=> "Eritrean nakfa",
            "ETB"=> "Ethiopian birr",
            "EUR"=> "Euro",
            "FJD"=> "Fiji dollar",
            "FKP"=> "Falkland Islands pound",
            "GBP"=> "Pound sterling",
            "GEL"=> "Georgian lari",
            "GHS"=> "Ghanaian cedi",
            "GIP"=> "Gibraltar pound",
            "GMD"=> "Gambian dalasi",
            "GNF"=> "Guinean franc",
            "GTQ"=> "Guatemalan quetzal",
            "GYD"=> "Guyanese dollar",
            "HKD"=> "Hong Kong dollar",
            "HNL"=> "Honduran lempira",
            "HRK"=> "Croatian kuna",
            "HTG"=> "Haitian gourde",
            "HUF"=> "Hungarian forint",
            "IDR"=> "Indonesian rupiah",
            "ILS"=> "Israeli new shekel",
            "INR"=> "Indian rupee",
            "IQD"=> "Iraqi dinar",
            "IRR"=> "Iranian rial",
            "ISK"=> "Icelandic króna",
            "JMD"=> "Jamaican dollar",
            "JOD"=> "Jordanian dinar",
            "JPY"=> "Japanese yen",
            "KES"=> "Kenyan shilling",
            "KGS"=> "Kyrgyzstani som",
            "KHR"=> "Cambodian riel",
            "KMF"=> "Comoro franc",
            "KPW"=> "North Korean won",
            "KRW"=> "South Korean won",
            "KWD"=> "Kuwaiti dinar",
            "KYD"=> "Cayman Islands dollar",
            "KZT"=> "Kazakhstani tenge",
            "LAK"=> "Lao kip",
            "LBP"=> "Lebanese pound",
            "LKR"=> "Sri Lankan rupee",
            "LRD"=> "Liberian dollar",
            "LSL"=> "Lesotho loti",
            "LYD"=> "Libyan dinar",
            "MAD"=> "Moroccan dirham",
            "MDL"=> "Moldovan leu",
            "MGA"=> "Malagasy ariary",
            "MKD"=> "Macedonian denar",
            "MMK"=> "Myanmar kyat",
            "MNT"=> "Mongolian tögrög",
            "MOP"=> "Macanese pataca",
            "MRO"=> "Mauritanian ouguiya",
            "MUR"=> "Mauritian rupee",
            "MVR"=> "Maldivian rufiyaa",
            "MWK"=> "Malawian kwacha",
            "MXN"=> "Mexican peso",
            "MXV"=> "Mexican Unidad de Inversion",
            "MYR"=> "Malaysian ringgit",
            "MZN"=> "Mozambican metical",
            "NAD"=> "Namibian dollar",
            "NGN"=> "Nigerian naira",
            "NIO"=> "Nicaraguan córdoba",
            "NOK"=> "Norwegian krone",
            "NPR"=> "Nepalese rupee",
            "NZD"=> "New Zealand dollar",
            "OMR"=> "Omani rial",
            "PAB"=> "Panamanian balboa",
            "PEN"=> "Peruvian Sol",
            "PGK"=> "Papua New Guinean kina",
            "PHP"=> "Philippine peso",
            "PKR"=> "Pakistani rupee",
            "PLN"=> "Polish złoty",
            "PYG"=> "Paraguayan guaraní",
            "QAR"=> "Qatari riyal",
            "RON"=> "Romanian leu",
            "RSD"=> "Serbian dinar",
            "RUB"=> "Russian ruble",
            "RWF"=> "Rwandan franc",
            "SAR"=> "Saudi riyal",
            "SBD"=> "Solomon Islands dollar",
            "SCR"=> "Seychelles rupee",
            "SDG"=> "Sudanese pound",
            "SEK"=> "Swedish krona",
            "SGD"=> "Singapore dollar",
            "SHP"=> "Saint Helena pound",
            "SLL"=> "Sierra Leonean leone",
            "SOS"=> "Somali shilling",
            "SRD"=> "Surinamese dollar",
            "SSP"=> "South Sudanese pound",
            "STD"=> "São Tomé and Príncipe dobra",
            "SVC"=> "Salvadoran colón",
            "SYP"=> "Syrian pound",
            "SZL"=> "Swazi lilangeni",
            "THB"=> "Thai baht",
            "TJS"=> "Tajikistani somoni",
            "TMT"=> "Turkmenistani manat",
            "TND"=> "Tunisian dinar",
            "TOP"=> "Tongan paʻanga",
            "TRY"=> "Turkish lira",
            "TTD"=> "Trinidad and Tobago dollar",
            "TWD"=> "New Taiwan dollar",
            "TZS"=> "Tanzanian shilling",
            "UAH"=> "Ukrainian hryvnia",
            "UGX"=> "Ugandan shilling",
            "USD"=> "United States dollar",
            "UYI"=> "Uruguay Peso en Unidades Indexadas",
            "UYU"=> "Uruguayan peso",
            "UZS"=> "Uzbekistan som",
            "VEF"=> "Venezuelan bolívar",
            "VND"=> "Vietnamese đồng",
            "VUV"=> "Vanuatu vatu",
            "WST"=> "Samoan tala",
            "XAF"=> "Central African CFA franc",
            "XCD"=> "East Caribbean dollar",
            "XOF"=> "West African CFA franc",
            "XPF"=> "CFP franc",
            "XXX"=> "No currency",
            "YER"=> "Yemeni rial",
            "ZAR"=> "South African rand",
            "ZMW"=> "Zambian kwacha",
            "ZWL"=> "Zimbabwean dollar"
        );
        asort($list);
        $return = array();
        foreach ($list as $key => $val)
        {
            $return[$key] = $val;
        }
        return $return;
    }
    
    protected function addon_exist($unique_name="")
    {
        if(DB::table('add_ons')->where('unique_name', $unique_name)->exists()) return true;
        return false;
    }

    public function _scanAll($myDir)
    {
        $dirTree = array();
        $di = new RecursiveDirectoryIterator($myDir,RecursiveDirectoryIterator::SKIP_DOTS);

        $i=0;
        foreach (new RecursiveIteratorIterator($di) as $filename) {

            $dir = str_replace($myDir, '', dirname($filename));
            // $dir = str_replace('/', '>', substr($dir,1));

            $org_dir=str_replace("\\", "/", $dir);

            if($org_dir)
                $file_path = $org_dir. "/". basename($filename);
            else
                $file_path = basename($filename);

            $file_full_path=$myDir."/".$file_path;
            $file_size= filesize($file_full_path);
            $file_modification_time=date('Y-m-d:H:i:s',filemtime($file_full_path));

            $dirTree[$i]['file'] = $file_full_path;
            $dirTree[$i]['size'] = $file_size;
            $dirTree[$i]['time'] = $file_modification_time;
            $i++;
        }
        return $dirTree;
    }

    public function user_delete_action($user_id=0 , Request $request)
    {
        // $this->ajax_check();
        // $this->csrf_token_check();


        if(config('app.is_demo') == '1' && Auth::user()->user_type=="Admin")
        {

            $response['status'] = 0;
            $response['message'] = "This feature is disabled in this demo.";
            echo json_encode($response);
            exit();

        }

        if($user_id == 0) exit;

        if(Auth::user()->user_type != 'Admin')
            if($user_id != Auth::user()->id) exit;

            DB::beginTransaction();

            $tables = DB::select('SHOW TABLES;');
            foreach ($tables as $table) {
                foreach ($table as $table_name) {
                    if ($table_name == 'users') {
                        DB::table('users')->where('id', $user_id)->delete();
                    }
                    if (Schema::hasColumn($table_name, 'user_id')) {
                        DB::table($table_name)->where('user_id', $user_id)->delete();
                    }
                }
            }

        if (DB::transactionLevel() > 0) {
            DB::commit();
            $response['status'] = 1;
            $response['message'] = __("Account and all of it's corresponding campaigns have been deleted successfully.");
        } else {
            DB::rollBack();
            if(Auth::user()->user_type != 'Admin')
                $request->session()->invalidate();
            $response['status'] = 0;
            $response['message'] = __("Something went wrong, please try again.");
        }

        echo json_encode($response);

    }

    public function _language_list()
    {
        $myDir =app_path('language');
        $file_list = $this->_scanAll($myDir);
        foreach ($file_list as $file) {
            $i = 0;
            $one_list[$i] = $file['file'];
            $one_list[$i]=str_replace("\\", "/",$one_list[$i]);
            $one_list_array[] = explode("/",$one_list[$i]);
        }
        foreach ($one_list_array as $value)
        {
            $pos=count($value)-2;
            $lang_folder=$value[$pos];
            $final_list_array[] = $lang_folder;
        }
        $final_array = array_unique($final_list_array);
        $array_keys = array_values($final_array);
        foreach ($final_array as $value) {
            $uc_array_valus[] = ucfirst($value);
        }
        $array_values = array_values($uc_array_valus);
        $final_array_done = array_combine($array_keys, $array_values);
        return $final_array_done;
    }

    public function _theme_list()
    {
        return array();
        $myDir = 'css/skins';
        $file_list = $this->_scanAll($myDir);
        $theme_list=array();
        foreach ($file_list as $file) {
            $i = 0;
            $one_list[$i] = $file['file'];
            $one_list[$i]=str_replace("\\", "/",$one_list[$i]);
            $one_list_array = explode("/",$one_list[$i]);
            $theme=array_pop($one_list_array);
            $pos=strpos($theme, '.min.css');
            if($pos!==FALSE) continue; // only loading unminified css
            if($theme=="_all-skins.css") continue;  // skipping large css file that includes all file
            $theme_name=str_replace('.css','', $theme);
            $theme_display=str_replace(array('skin-','.css','-'), array('','',' '), $theme);
            if($theme_display=="black light") $theme_display='light';
            if($theme_display=="black") $theme_display='dark';
            $theme_list[$theme_name]=ucwords($theme_display);
        }
        return $theme_list;

    }

    public function _theme_list_front()
    {
        return array
        (
            "white"=>"Light",
            "black"=>"Dark",
            "blue"=>"Blue",
            "green"=>"Green",
            "purple"=>"Purple",
            "red"=>"Red",
            "yellow"=>"Yellow"
        );
    }

    protected function currency_icon()
    {
        $currency_symbols = array(
            'AED' => '&#1583;.&#1573;', // ?
            'AFN' => '&#65;&#102;',
            'ALL' => '&#76;&#101;&#107;',
            'AMD' => 'AMD',
            'ANG' => '&#402;',
            'AOA' => '&#75;&#122;', // ?
            'ARS' => '&#36;',
            'AUD' => '&#36;',
            'AWG' => '&#402;',
            'AZN' => '&#1084;&#1072;&#1085;',
            'BAM' => '&#75;&#77;',
            'BBD' => '&#36;',
            'BDT' => '&#2547;', // ?
            'BGN' => '&#1083;&#1074;',
            'BHD' => '.&#1583;.&#1576;', // ?
            'BIF' => '&#70;&#66;&#117;', // ?
            'BMD' => '&#36;',
            'BND' => '&#36;',
            'BOB' => '&#36;&#98;',
            'BRL' => '&#82;&#36;',
            'BSD' => '&#36;',
            'BTN' => '&#78;&#117;&#46;', // ?
            'BWP' => '&#80;',
            'BYR' => '&#112;&#46;',
            'BZD' => '&#66;&#90;&#36;',
            'CAD' => '&#36;',
            'CDF' => '&#70;&#67;',
            'CHF' => '&#67;&#72;&#70;',
            'CLF' => 'CLF', // ?
            'CLP' => '&#36;',
            'CNY' => '&#165;',
            'COP' => '&#36;',
            'CRC' => '&#8353;',
            'CUP' => '&#8396;',
            'CVE' => '&#36;', // ?
            'CZK' => '&#75;&#269;',
            'DJF' => '&#70;&#100;&#106;', // ?
            'DKK' => '&#107;&#114;',
            'DOP' => '&#82;&#68;&#36;',
            'DZD' => '&#1583;&#1580;', // ?
            'EGP' => '&#163;',
            'ETB' => '&#66;&#114;',
            'EUR' => '&#8364;',
            'FJD' => '&#36;',
            'FKP' => '&#163;',
            'GBP' => '&#163;',
            'GEL' => '&#4314;', // ?
            'GHS' => '&#162;',
            'GIP' => '&#163;',
            'GMD' => '&#68;', // ?
            'GNF' => '&#70;&#71;', // ?
            'GTQ' => '&#81;',
            'GYD' => '&#36;',
            'HKD' => '&#36;',
            'HNL' => '&#76;',
            'HRK' => '&#107;&#110;',
            'HTG' => '&#71;', // ?
            'HUF' => '&#70;&#116;',
            'IDR' => '&#82;&#112;',
            'ILS' => '&#8362;',
            'INR' => '&#8377;',
            'IQD' => '&#1593;.&#1583;', // ?
            'IRR' => '&#65020;',
            'ISK' => '&#107;&#114;',
            'JEP' => '&#163;',
            'JMD' => '&#74;&#36;',
            'JOD' => '&#74;&#68;', // ?
            'JPY' => '&#165;',
            'KES' => '&#75;&#83;&#104;', // ?
            'KGS' => '&#1083;&#1074;',
            'KHR' => '&#6107;',
            'KMF' => '&#67;&#70;', // ?
            'KPW' => '&#8361;',
            'KRW' => '&#8361;',
            'KWD' => '&#1583;.&#1603;', // ?
            'KYD' => '&#36;',
            'KZT' => '&#1083;&#1074;',
            'LAK' => '&#8365;',
            'LBP' => '&#163;',
            'LKR' => '&#8360;',
            'LRD' => '&#36;',
            'LSL' => '&#76;', // ?
            'LTL' => '&#76;&#116;',
            'LVL' => '&#76;&#115;',
            'LYD' => '&#1604;.&#1583;', // ?
            'MAD' => '&#1583;.&#1605;.', //?
            'MDL' => '&#76;',
            'MGA' => '&#65;&#114;', // ?
            'MKD' => '&#1076;&#1077;&#1085;',
            'MMK' => '&#75;',
            'MNT' => '&#8366;',
            'MOP' => '&#77;&#79;&#80;&#36;', // ?
            'MRO' => '&#85;&#77;', // ?
            'MUR' => '&#8360;', // ?
            'MVR' => '.&#1923;', // ?
            'MWK' => '&#77;&#75;',
            'MXN' => '&#36;',
            'MYR' => '&#82;&#77;',
            'MZN' => '&#77;&#84;',
            'NAD' => '&#36;',
            'NGN' => '&#8358;',
            'NIO' => '&#67;&#36;',
            'NOK' => '&#107;&#114;',
            'NPR' => '&#8360;',
            'NZD' => '&#36;',
            'OMR' => '&#65020;',
            'PAB' => '&#66;&#47;&#46;',
            'PEN' => '&#83;&#47;&#46;',
            'PGK' => '&#75;', // ?
            'PHP' => '&#8369;',
            'PKR' => '&#8360;',
            'PLN' => '&#122;&#322;',
            'PYG' => '&#71;&#115;',
            'QAR' => '&#65020;',
            'RON' => '&#108;&#101;&#105;',
            'RSD' => '&#1044;&#1080;&#1085;&#46;',
            'RUB' => '&#1088;&#1091;&#1073;',
            'RWF' => '&#1585;.&#1587;',
            'SAR' => '&#65020;',
            'SBD' => '&#36;',
            'SCR' => '&#8360;',
            'SDG' => '&#163;', // ?
            'SEK' => '&#107;&#114;',
            'SGD' => '&#36;',
            'SHP' => '&#163;',
            'SLL' => '&#76;&#101;', // ?
            'SOS' => '&#83;',
            'SRD' => '&#36;',
            'STD' => '&#68;&#98;', // ?
            'SVC' => '&#36;',
            'SYP' => '&#163;',
            'SZL' => '&#76;', // ?
            'THB' => '&#3647;',
            'TJS' => '&#84;&#74;&#83;', // ? TJS (guess)
            'TMT' => '&#109;',
            'TND' => '&#1583;.&#1578;',
            'TOP' => '&#84;&#36;',
            'TRY' => '&#8356;', // New Turkey Lira (old symbol used)
            'TTD' => '&#36;',
            'TWD' => '&#78;&#84;&#36;',
            'TZS' => '',
            'UAH' => '&#8372;',
            'UGX' => '&#85;&#83;&#104;',
            'USD' => '&#36;',
            'UYU' => '&#36;&#85;',
            'UZS' => '&#1083;&#1074;',
            'VEF' => '&#66;&#115;',
            'VND' => '&#8363;',
            'VUV' => '&#86;&#84;',
            'WST' => '&#87;&#83;&#36;',
            'XAF' => '&#70;&#67;&#70;&#65;',
            'XCD' => '&#36;',
            'XDR' => 'XDR',
            'XOF' => 'XOF',
            'XPF' => '&#70;',
            'YER' => '&#65020;',
            'ZAR' => '&#82;',
            'ZMK' => '&#90;&#75;', // ?
            'ZWL' => '&#90;&#36;',
        );

        return $currency_symbols;
    }

    protected function ajax_check(Request $request)
    {
      if(!$request->ajax()) exit();
    }

    protected function get_available_language_list(){
        $user_id = Auth::user()->id;

        $all_language_list = get_language_list();

        $languages = ['en'=>'English'];
        $files = File::allFiles(resource_path().DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'translation');
        foreach ($files as $key=>$value){
            $getRelativePath = $value->getRelativePath();
            if(!isset($languages[$getRelativePath])){
                $langName = rtrim($getRelativePath,'-'.$user_id);
                $languages[$getRelativePath] = $all_language_list[$langName] ?? $langName;
            }
        }
        return $languages;
    }


    //     // ***************************************************************
    //         // front end website analysis section
    // // ***************************************************************
    public function front_end_website_analysis(WebCommonReportServiceInterface $web_common_repport , Request $request)
    {   

        if ($request->isMethod('get')) 
        {
            return redirect()->route('access_forbidden');
        }

        $this->web_repport= $web_common_repport;

        $common_result=[];
        $user_id =Auth::user()->id ?? 0;
        if($user_id)
        {
            // $user_id = Auth::user()->id;
            $common_result['user_id'] = $user_id;
        }
        else
        {
            $user_info = DB::table('users')->where(['user_type' => 'Admin', 'status' => 1, 'deleted' => 0])->get();
            $user_info = json_decode(json_encode($user_info));

            if(!empty($user_info))
                $user_id = $user_info[0]->id;
        }


        $domain_name = strtolower(request()->input('domain_name'));

        $all_access_ids=DB::table('config')->first();

        $use_admin_app = config('my_config.use_admin_app');

        if($use_admin_app == '' || $use_admin_app == 'no')
            $config_data = DB::table('config')->where('user_id', $user_id)->get();

        else
            $config_data = DB::table('config')->where('access', 'all_users')->get();

        $moz_access_id="";
        $moz_secret_key="";
        $mobile_ready_api_key="";
        $api='';
        if(isset($config_data))
        {
            $moz_access_id=$all_access_ids->moz_access_id;
            $moz_secret_key=$all_access_ids->moz_secret_key;
            $mobile_ready_api_key=$all_access_ids->mobile_ready_api_key;
            $api=$all_access_ids->google_safety_api;
        }
        // Getting screenshot from page speed insight

        $domain=addHttp($domain_name);
        $desktop_result= $this->web_repport->google_page_speed_insight($api,$domain,"desktop");

        if (isset($desktop_result['error'])) {
            $common_result['screenshot_error'] = $desktop_result['error']['message'];
        }
        else{
           $common_result['screenshot'] = isset($desktop_result['lighthouseResult']['audits']['final-screenshot']['details']['data']) ? $desktop_result['lighthouseResult']['audits']['final-screenshot']['details']['data'] : "";
        }

        $ready_data =  $this->web_repport->mobile_ready($domain_name,$mobile_ready_api_key);

        if (isset($ready_data['error'])) {
            $common_result['google_api_error'] = $ready_data['error']['message'];
        }
        else {
            if (isset($ready_data['loadingExperience'])) {
                $common_result["loadingexperience_metrics"] =  isset($ready_data['loadingExperience']) ? json_encode($ready_data['loadingExperience']) : "";
            }
            if (isset($ready_data['originLoadingExperience'])) {
                $common_result["originloadingexperience_metrics"] =  isset($ready_data['originLoadingExperience']) ? json_encode($ready_data['originLoadingExperience']) : "";
            }

            if (isset($ready_data['lighthouseResult']['configSettings'])) {
               $common_result["lighthouseresult_configsettings"] =  isset($ready_data['lighthouseResult']['configSettings']) ? json_encode($ready_data['lighthouseResult']['configSettings']) : "";
            }


            if (isset($ready_data['lighthouseResult']['audits'])) {
                if(isset($ready_data['lighthouseResult']['audits']['resource-summary']))
                    unset($ready_data['lighthouseResult']['audits']['resource-summary']['details']);

                if (isset($ready_data['lighthouseResult']['audits']['efficient-animated-content']))
                    unset($ready_data['lighthouseResult']['audits']['efficient-animated-content']['details']);

                if (isset($ready_data['lighthouseResult']['audits']['metrics']))
                    unset($ready_data['lighthouseResult']['audits']['metrics']);   

                if (isset($ready_data['lighthouseResult']['audits']['network-server-latency']))
                    unset($ready_data['lighthouseResult']['audits']['network-server-latency']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['offscreen-images']))
                    unset($ready_data['lighthouseResult']['audits']['offscreen-images']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['uses-responsive-images']))
                    unset($ready_data['lighthouseResult']['audits']['uses-responsive-images']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['unused-css-rules']))
                    unset($ready_data['lighthouseResult']['audits']['unused-css-rules']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['total-byte-weight']))
                    unset($ready_data['lighthouseResult']['audits']['total-byte-weight']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['mainthread-work-breakdown']))
                    unset($ready_data['lighthouseResult']['audits']['mainthread-work-breakdown']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['uses-webp-images']))
                    unset($ready_data['lighthouseResult']['audits']['uses-webp-images']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['critical-request-chains']))
                    unset($ready_data['lighthouseResult']['audits']['critical-request-chains']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['dom-size']))
                    unset($ready_data['lighthouseResult']['audits']['dom-size']['details']);                


                if (isset($ready_data['lighthouseResult']['audits']['unminified-javascript']))
                    unset($ready_data['lighthouseResult']['audits']['unminified-javascript']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['redirects']))
                    unset($ready_data['lighthouseResult']['audits']['redirects']['details']);   


                if (isset($ready_data['lighthouseResult']['audits']['time-to-first-byte']))
                    unset($ready_data['lighthouseResult']['audits']['time-to-first-byte']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['render-blocking-resources']))
                    unset($ready_data['lighthouseResult']['audits']['render-blocking-resources']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['font-display']))
                    unset($ready_data['lighthouseResult']['audits']['font-display']['details']);


                if (isset($ready_data['lighthouseResult']['audits']['estimated-input-latency']))
                    unset($ready_data['lighthouseResult']['audits']['estimated-input-latency']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['uses-rel-preconnect']))
                    unset($ready_data['lighthouseResult']['audits']['uses-rel-preconnect']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['unminified-css']))
                    unset($ready_data['lighthouseResult']['audits']['unminified-css']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['bootup-time']))
                    unset($ready_data['lighthouseResult']['audits']['bootup-time']['details']);                


                if (isset($ready_data['lighthouseResult']['audits']['uses-rel-preload']))
                    unset($ready_data['lighthouseResult']['audits']['uses-rel-preload']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['user-timings']))
                    unset($ready_data['lighthouseResult']['audits']['user-timings']['details']);                


                if (isset($ready_data['lighthouseResult']['audits']['uses-text-compression']))
                    unset($ready_data['lighthouseResult']['audits']['uses-text-compression']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['uses-optimized-images']))
                    unset($ready_data['lighthouseResult']['audits']['uses-optimized-images']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['uses-long-cache-ttl']))
                    unset($ready_data['lighthouseResult']['audits']['uses-long-cache-ttl']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['third-party-summary']))
                    unset($ready_data['lighthouseResult']['audits']['third-party-summary']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['network-rtt']))
                    unset($ready_data['lighthouseResult']['audits']['network-rtt']['details']);                


                if (isset($ready_data['lighthouseResult']['audits']['diagnostics']))
                    unset($ready_data['lighthouseResult']['audits']['diagnostics']);                


                if (isset($ready_data['lighthouseResult']['audits']['network-requests']))
                    unset($ready_data['lighthouseResult']['audits']['network-requests']['details']);                


                if (isset($ready_data['lighthouseResult']['audits']['screenshot-thumbnails']))
                    unset($ready_data['lighthouseResult']['audits']['screenshot-thumbnails']);                


                if (isset($ready_data['lighthouseResult']['audits']['main-thread-tasks']))
                    unset($ready_data['lighthouseResult']['audits']['main-thread-tasks']);


                if (isset($ready_data['lighthouseResult']['categories']['performance']))
                    unset($ready_data['lighthouseResult']['categories']['performance']['auditRefs']);                

            
                $common_result['lighthouseresult_audits'] = isset($ready_data['lighthouseResult']['audits']) ? json_encode($ready_data['lighthouseResult']['audits']) : "";                   

            }



            if (isset($ready_data['lighthouseResult']['categories'])) {
                $common_result['lighthouseresult_categories'] = isset($ready_data['lighthouseResult']['categories']) ? json_encode($ready_data['lighthouseResult']['categories']) : "";
            }
  

        }


        $common_result['domain_name'] = $domain_name;
        $common_result['search_at'] = date("Y-m-d H:i:s");

        //for dynamic progress bar data

        $add_complete = 0;
        $website_analysis_completed_function_str='';
        $common_result['completed_step_count'] = $add_complete;
        $common_result['completed_step_string'] = '';
        if($user_id != '')
        {
            $search_user_id = $user_id;
        }

        else $search_user_id = 0;
        $search_existing_info = DB::table('website_analysis_info')
        ->where([
            ['user_id', $search_user_id],
            ['domain_name', $domain_name]
        ])
        ->select('id')
        ->get();



        if(!isset($search_existing_info) || count($search_existing_info) == 0)
        {

            if(!isset($common_result['user_id'])) $common_result['user_id']=0;

            $web_common_info_id = DB::table('website_analysis_info')->insertGetId($common_result);

        }
        else
        {
            DB::table('website_analysis_info')->where('id', $search_existing_info[0]->id)->update($common_result);
            $web_common_info_id = $search_existing_info[0]->id;
        }


        // get moz info
           

        $get_moz_info =  $this->web_repport->get_moz_info($domain_name,$moz_access_id, $moz_secret_key);

        $common_result['moz_subdomain_normalized'] = $get_moz_info['mozrank_subdomain_normalized'];

        $common_result['moz_subdomain_raw'] = $get_moz_info['mozrank_subdomain_raw'];

        $common_result['moz_url_normalized'] = $get_moz_info['mozrank_url_normalized'];

        $common_result['moz_url_raw'] = $get_moz_info['mozrank_url_raw'];

        $common_result['moz_http_status_code'] = $get_moz_info['http_status_code'];

        $common_result['moz_domain_authority'] = $get_moz_info['domain_authority'];

        $common_result['moz_page_authority'] = $get_moz_info['page_authority'];

        $common_result['moz_external_equity_links'] = $get_moz_info['external_equity_links'];

        $common_result['moz_links'] = $get_moz_info['links'];



        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  MOZ ".__("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        DB::table('website_analysis_info')
            ->where('id', $web_common_info_id)
            ->update(['completed_step_count' => $add_complete, 'completed_step_string' => $website_analysis_completed_function_str]);

        // end of get moz info



        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Mobile Friendly ".__("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        DB::table('website_analysis_info')
            ->where('id', $web_common_info_id)
            ->update(['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);

        // end of get mobile ready


        $backlink_count=$common_result['moz_external_equity_links'];
        if($backlink_count=="")
            $backlink_count=0;


        $common_result['google_back_link_count'] = number_format($backlink_count);
        $add_complete++;
        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Backlink ".__("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        DB::table('website_analysis_info')
            ->where('id', $web_common_info_id)
            ->update(['completed_step_count' => $add_complete, 'completed_step_string' => $website_analysis_completed_function_str]);
        


        $common_result['yahoo_back_link_count'] = 0;
        $common_result['bing_back_link_count'] = 0;

        if (strpos($domain_name, 'https://') === false)  $domain_name_fb = 'https://' . $domain_name;
        $fb_like_comment_share =  $this->web_repport->fb_like_comment_share($domain_name_fb);


        if(isset($fb_like_comment_share['total_share']))

            $common_result['fb_total_share'] = $fb_like_comment_share['total_share'];

        else $common_result['fb_total_share'] = 0;


        if(isset($fb_like_comment_share['total_like']))

            $common_result['fb_total_like'] = $fb_like_comment_share['total_like'];

        else $common_result['fb_total_like'] = 0;


        if(isset($fb_like_comment_share['total_comment']))

            $common_result['fb_total_comment'] = $fb_like_comment_share['total_comment'];

        else $common_result['fb_total_comment'] = 0;


        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Facebook ".__("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        DB::table('website_analysis_info')
        ->where('id', $web_common_info_id)
        ->update(['completed_step_count' => $add_complete, 'completed_step_string' => $website_analysis_completed_function_str]);


        $pinterest_info =  $this->web_repport->pinterest_pin($domain_name);

        $common_result['pinterest_pin'] = $pinterest_info;

        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Pinterest ".__("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        DB::table('website_analysis_info')
        ->where('id', $web_common_info_id)
        ->update(['completed_step_count' => $add_complete, 'completed_step_string' => $website_analysis_completed_function_str]);


        $stumbleupon_info =  $this->web_repport->stumbleupon_info($domain_name);

        $common_result['stumbleupon_total_view'] = $stumbleupon_info['total_view'];

        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Stumbleupon ".__("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        DB::table('website_analysis_info')
        ->where('id', $web_common_info_id)
        ->update(['completed_step_count' => $add_complete, 'completed_step_string' => $website_analysis_completed_function_str]);


        $buffer_info =  $this->web_repport->buffer_share($domain_name);

        $common_result['buffer_share_count'] = $buffer_info;

        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Buffer ".__("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        // $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);
        DB::table('website_analysis_info')
        ->where('id', $web_common_info_id)
        ->update(['completed_step_count' => $add_complete, 'completed_step_string' => $website_analysis_completed_function_str]);


        $GoogleIP =  $this->web_repport->GoogleIP($domain_name);

        $common_result['google_index_count'] = $GoogleIP;

        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Google Index ".__("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        // $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);
        DB::table('website_analysis_info')
        ->where('id', $web_common_info_id)
        ->update(['completed_step_count' => $add_complete, 'completed_step_string' => $website_analysis_completed_function_str]);


        $reddit_count =  $this->web_repport->reddit_count($domain_name);

        $common_result['reddit_score'] = $reddit_count['score'];

        $common_result['reddit_ups'] = $reddit_count['ups'];

        $common_result['reddit_downs'] = $reddit_count['downs'];

        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Reddit ".__("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        // $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);
        DB::table('website_analysis_info')
        ->where('id', $web_common_info_id)
        ->update(['completed_step_count' => $add_complete, 'completed_step_string' => $website_analysis_completed_function_str]);


        $xing_share_count =  $this->web_repport->xing_share_count($domain_name);
        $common_result['xing_share_count'] = empty($xing_share_count) ? 0 : $xing_share_count;

        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Xing ".__("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        // $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);
        DB::table('website_analysis_info')
        ->where('id', $web_common_info_id)
        ->update(['completed_step_count' => $add_complete, 'completed_step_string' => $website_analysis_completed_function_str]);



        $bing_index =  $this->web_repport->bing_index($domain_name);
        $common_result['bing_index_count'] = $bing_index;

        //for dynamic progress bar data

        $add_complete++;
        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Bing ".__("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        // $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);
            DB::table('website_analysis_info')
        ->where('id', $web_common_info_id)
        ->update(['completed_step_count' => $add_complete, 'completed_step_string' => $website_analysis_completed_function_str]);



        $yahoo_index =  $this->web_repport->yahoo_index($domain_name);
        $common_result['yahoo_index_count'] = $yahoo_index;

        //for dynamic progress bar data

        $add_complete++;
        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Yahoo ".__("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        // $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);
        DB::table('website_analysis_info')
        ->where('id', $web_common_info_id)
        ->update(['completed_step_count' => $add_complete, 'completed_step_string' => $website_analysis_completed_function_str]);


        $meta_tag_info =  $this->web_repport->content_analysis($domain_name);

        $common_result['h1'] = json_encode($meta_tag_info['h1']);

        $common_result['h2'] = json_encode($meta_tag_info['h2']);

        $common_result['h3'] = json_encode($meta_tag_info['h3']);

        $common_result['h4'] = json_encode($meta_tag_info['h4']);

        $common_result['h5'] = json_encode($meta_tag_info['h5']);

        $common_result['h6'] = json_encode($meta_tag_info['h6']);

        $common_result['blocked_by_robot_txt'] = $meta_tag_info['blocked_by_robot_txt'];

        $common_result['meta_tag_information'] = json_encode($meta_tag_info['meta_tag_information']);

        $common_result['blocked_by_meta_robot'] = $meta_tag_info['blocked_by_meta_robot'];

        $common_result['nofollowed_by_meta_robot'] = $meta_tag_info['nofollowed_by_meta_robot'];

        $common_result['one_phrase'] = json_encode($meta_tag_info['one_phrase']);

        $common_result['two_phrase'] = json_encode($meta_tag_info['two_phrase']);

        $common_result['three_phrase'] = json_encode($meta_tag_info['three_phrase']);

        $common_result['four_phrase'] = json_encode($meta_tag_info['four_phrase']);

        $common_result['total_words'] = $meta_tag_info['total_words'];

        //for dynamic progress bar data
        $add_complete++;
        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Metatag ".__("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";
        // $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);
        DB::table('website_analysis_info')
        ->where('id', $web_common_info_id)
        ->update(['completed_step_count' => $add_complete, 'completed_step_string' => $website_analysis_completed_function_str]);


        $whois_info = $this->web_repport->whois_email2($domain_name);

        $common_result['whois_is_registered'] = $whois_info['is_registered'];

        $common_result['whois_tech_email'] = $whois_info['tech_email'];

        $common_result['whois_admin_email'] = $whois_info['admin_email'];

        $common_result['whois_name_servers'] = $whois_info['name_servers'];

        $common_result['whois_created_at'] = $whois_info['created_at'];

        $common_result['whois_changed_at'] = $whois_info['changed_at'];

        $common_result['whois_expire_at'] = $whois_info['expire_at'];

        $common_result['whois_registrar_url'] = $whois_info['registrar_url'];

        $common_result['whois_registrant_name'] = $whois_info['registrant_name'];

        $common_result['whois_registrant_organization'] = $whois_info['registrant_organization'];

        $common_result['whois_registrant_street'] = $whois_info['registrant_street'];

        $common_result['whois_registrant_city'] = $whois_info['registrant_city'];

        $common_result['whois_registrant_state'] = $whois_info['registrant_state'];

        $common_result['whois_registrant_postal_code'] = $whois_info['registrant_postal_code'];

        $common_result['whois_registrant_email'] = $whois_info['registrant_email'];

        $common_result['whois_registrant_country'] = $whois_info['registrant_country'];

        $common_result['whois_registrant_phone'] = $whois_info['registrant_phone'];

        $common_result['whois_admin_name'] = $whois_info['admin_name'];

        $common_result['whois_admin_street'] = $whois_info['admin_street'];

        $common_result['whois_admin_city'] = $whois_info['admin_city'];

        $common_result['whois_admin_postal_code'] = $whois_info['admin_postal_code'];

        $common_result['whois_admin_country'] = $whois_info['admin_country'];

        $common_result['whois_admin_phone'] = $whois_info['admin_phone'];

        //for dynamic progress bar data

        $add_complete++;
        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Whois ".__("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";
        // $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);
        DB::table('website_analysis_info')
        ->where('id', $web_common_info_id)
        ->update(['completed_step_count' => $add_complete, 'completed_step_string' => $website_analysis_completed_function_str]);

        $get_ip_country =  $this->web_repport->get_ip_country($domain_name);

        $common_result['ipinfo_isp'] = $get_ip_country['isp'];

        $common_result['ipinfo_ip'] = $get_ip_country['ip'];

        $common_result['ipinfo_city'] = $get_ip_country['city'];

        $common_result['ipinfo_region'] = $get_ip_country['region'];

        $common_result['ipinfo_country'] = $get_ip_country['country'];

        $common_result['ipinfo_time_zone'] = $get_ip_country['time_zone'];

        $common_result['ipinfo_longitude'] = $get_ip_country['longitude'];

        $common_result['ipinfo_latitude'] = $get_ip_country['latitude'];

        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  IP ".__("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        // $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);
        DB::table('website_analysis_info')
        ->where('id', $web_common_info_id)
        ->update(['completed_step_count' => $add_complete, 'completed_step_string' => $website_analysis_completed_function_str]);


        $this->web_repport->get_site_in_same_ip($common_result['ipinfo_ip'],$page=1,$proxy="");

        $sites_in_same_ip= $this->web_repport->same_site_in_ip;

        $common_result['sites_in_same_ip']=json_encode($sites_in_same_ip);

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Site's in same IP - ".__("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        // $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);
        DB::table('website_analysis_info')
        ->where('id', $web_common_info_id)
        ->update(['completed_step_count' => $add_complete, 'completed_step_string' => $website_analysis_completed_function_str]);


        $macafee_safety_analysis =  $this->web_repport->macafee_safety_analysis($domain_name,$proxy="");
        $common_result['macafee_status'] = $macafee_safety_analysis;
        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Macafee ".__("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        // $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);
        DB::table('website_analysis_info')
        ->where('id', $web_common_info_id)
        ->update(['completed_step_count' => $add_complete, 'completed_step_string' => $website_analysis_completed_function_str]);






        $norton_safety_check =  $this->web_repport->norton_safety_check($domain_name,$proxe="");
        $common_result['norton_status'] = $norton_safety_check;

        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Norton ".__("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        // $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);
        DB::table('website_analysis_info')
        ->where('id', $web_common_info_id)
        ->update(['completed_step_count' => $add_complete, 'completed_step_string' => $website_analysis_completed_function_str]);








        $google_safety_check =  $this->web_repport->google_safety_check($domain_name,$api);
        $common_result['google_safety_status'] = $google_safety_check;

        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Google Safety ".__("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        // $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);
        DB::table('website_analysis_info')
        ->where('id', $web_common_info_id)
        ->update(['completed_step_count' => $add_complete, 'completed_step_string' => $website_analysis_completed_function_str]);




        $similar_site_from_google =  $this->web_repport->similar_site_from_google($domain_name);
        $filtered_similar_sites = array_filter($similar_site_from_google);
        $common_result['similar_sites'] = implode(',', $filtered_similar_sites);


        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Similar Site ".__("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        // $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);
         DB::table('website_analysis_info')
        ->where('id', $web_common_info_id)
        ->update(['completed_step_count' => $add_complete, 'completed_step_string' => $website_analysis_completed_function_str]);

        unset($common_result['completed_step_count']);
        unset($common_result['completed_step_string']);

        // $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],$common_result);
        DB::table('website_analysis_info')
        ->where('id', $web_common_info_id)
        ->where('user_id',$common_result['user_id'] ?? 0)
        ->update($common_result);

        $link = url('/')."/home/frontend_domain_details_view/".$web_common_info_id;
        echo '<a href="'.$link.'" class="btn btn-primary"><i class="fa fa-eye"></i> '.__("Detailed report").'</a><br/>';


    }

    public function front_end_bulk_scan_progress_count(Request $request)
    {
        // $domain_name = session('website_domain_name_for_analysis');
        $domain_name=trim($request->domain_name);
        $bulk_complete_search = 0;
        $bulk_tracking_total_search= 20;
        $website_info = DB::table('website_analysis_info')->where('domain_name', $domain_name)->select('completed_step_string', 'completed_step_count','id')->get();
        $insert_table_id = isset($website_info[0]->id) ? $website_info[0]->id : 0;
        $bulk_complete_search=isset($website_info[0]->completed_step_count) ? (int)$website_info[0]->completed_step_count : 0;
        $website_analysis_completed_function_str=isset($website_info[0]->completed_step_string) ? $website_info[0]->completed_step_string : '';

        // $bulk_tracking_total_search=session('website_analysis_bulk_total_search');
        // $website_analysis_completed_function_str=$website_info[0]->completed_step_string;

        $response['view_details_button'] = 'not_set';
        if($insert_table_id != '')
        {

            $link = url('/')."/home/frontend_domain_details_view/".$insert_table_id;
            $view_button = '<a href="'.$link.'" class="btn btn-primary"><i class="fa fa-eye"></i> '.__("Detailed report").'</a><br/>';
            $response['view_details_button'] = $view_button;
        }

        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        $response['completed_function_str'] = $website_analysis_completed_function_str;

        echo json_encode($response);

    }


    public function frontend_domain_details_view($id=0)
    {
        $data['id'] = $id;

        $domain_info = DB::table('website_analysis_info')->where('id',$id)->get();
        $domain_info= json_decode(json_encode($domain_info));

        $data['country_list'] = get_country_names();

        $data['body'] = 'landing/domain-details';
        $data['page_title'] = __("website analysis");
        $data['domain_info'] = $domain_info;
        return $this->_frontend_website_details_theme($data);
    }


    public function _frontend_website_details_theme($data=array())
    {

        if (!isset($data['body'])) return false;

        return view($data['body'],$data);
    }


    public function front_ajax_get_general_data(Request $request)
    {
        $domain_id = $request->input('domain_id');

        $domain_info =DB::table('website_analysis_info')->where('id',$domain_id)->get();
        $domain_info = json_decode(json_encode($domain_info)); 
        $info['country_list'] = get_country_names();
        $info['domain_info'] = $domain_info;
        $domain_details = view('seo-tools.analysis-tools.website.general',$info)->render();

        echo $domain_details;

    }

    public function front_ajax_get_alexa_info_data(Request $request)
    {

        $domain_id = $request->input('domain_id');
        $data["alexa_data"]= DB::table('website_analysis_info')->where('id', $domain_id)->get();
        $data["alexa_data"] = json_decode(json_encode($data["alexa_data"]));
        $alexa_details = view('seo-tools.analysis-tools.website.alexa-details-one',$data)->render();

        echo $alexa_details;
    }


    public function front_ajax_get_social_network_data(Request $request)
    {
        $domain_id = $request->input('domain_id');

        $infos = DB::table('website_analysis_info')->where('id',$domain_id)->get();
        $infos= json_decode(json_encode($infos));

        $domain_info = array();  
        $domain_info['domain_name'] = $infos[0]->domain_name;

        $domain_info['fb_total_share'] = is_numeric($infos[0]->fb_total_share) ? number_format($infos[0]->fb_total_share) : $infos[0]->fb_total_share;

        $domain_info['fb_total_reaction'] = is_numeric($infos[0]->fb_total_like) ? number_format($infos[0]->fb_total_like) : $infos[0]->fb_total_like;

        $domain_info['fb_total_comment'] = is_numeric($infos[0]->fb_total_comment) ? number_format($infos[0]->fb_total_comment) : $infos[0]->fb_total_comment;


        $domain_info['stumbleupon_total_view'] = is_numeric($infos[0]->stumbleupon_total_view) ? number_format($infos[0]->stumbleupon_total_view) : $infos[0]->stumbleupon_total_view;
        if($domain_info['stumbleupon_total_view']=="")$domain_info['stumbleupon_total_view'] =0;

        $domain_info['reddit_score'] = is_numeric($infos[0]->reddit_score) ? number_format($infos[0]->reddit_score) : $infos[0]->reddit_score;
        $domain_info['reddit_ups'] = is_numeric($infos[0]->reddit_ups) ? number_format($infos[0]->reddit_ups) : $infos[0]->reddit_ups;
        $domain_info['reddit_downs'] = is_numeric($infos[0]->reddit_downs) ? number_format($infos[0]->reddit_downs) : $infos[0]->reddit_downs;

        $domain_info['pinterest_pin'] = is_numeric($infos[0]->pinterest_pin) ? number_format($infos[0]->pinterest_pin) : $infos[0]->pinterest_pin;
        $domain_info['buffer_share'] = is_numeric($infos[0]->buffer_share_count) ? number_format($infos[0]->buffer_share_count) : $infos[0]->buffer_share_count;
        $domain_info['xing_share'] = is_numeric($infos[0]->xing_share_count) ? number_format($infos[0]->xing_share_count) : $infos[0]->xing_share_count;


        $social_network_info = array(
            __('Facebook Total Share') => $infos[0]->fb_total_share,        
            __('Facebook Total Like') => $infos[0]->fb_total_like,        
            __('Facebook Total Comment') => $infos[0]->fb_total_comment,        
            __('Pinterest') => $infos[0]->pinterest_pin,        
            __('Reddit Score') => $infos[0]->reddit_score,        
            __('Buffer Share Count') => $infos[0]->buffer_share_count,        
            __('Xing Share Count') => $infos[0]->xing_share_count,        
        );
        
        $domain_info['social_network_info'] = $social_network_info;

        $domain_info['color_codes'] = "

            <li class='media mb-1 pb-0'>
                <div class='social_shared_icon mt-1' style='background-color: #003f5c !important;'></div>
                <div class='media-body ml-3'>
                    <h4 class='media-title'>".__('Facebook Total Share')."</h4>
                </div>
            </li>
            <li class='media mb-0 pb-0'>
                <div class='social_shared_icon mt-2' style='background-color: #4571ef !important;'></div>
                <div class='media-body ml-3'>
                    <div class='media-title'>".__('Facebook Total Like')."</div>
                </div>
            </li>
            <li class='media mb-0 pb-0'>
                <div class='social_shared_icon mt-2' style='background-color: #ce6f45 !important;'></div>
                <div class='media-body ml-3'>
                    <div class='media-title'>".__('Facebook Total Comment')."</div>
                </div>
            </li>
            <li class='media mb-0 pb-0'>
                <div class='social_shared_icon mt-2' style='background-color: #58508d !important;'></div>
                <div class='media-body ml-3'>
                    <div class='media-title'>".__('Pinterest')."</div>
                </div>
            </li>
            <li class='media mb-0 pb-0'>
                <div class='social_shared_icon mt-2' style='background-color: #bc5090 !important;'></div>
                <div class='media-body ml-3'>
                    <div class='media-title'>".__('Reddit Score')."</div>
                </div>
            </li>
            <li class='media mb-0 pb-0'>
                <div class='social_shared_icon mt-2' style='background-color: #ff6361 !important;'></div>
                <div class='media-body ml-3'>
                    <div class='media-title'>".__('Buffer Share Count')."</div>
                </div>
            </li>
            <li class='media mb-0 pb-0'>
                <div class='social_shared_icon mt-2' style='background-color: #ffa600 !important;'></div>
                <div class='media-body ml-3'>
                    <div class='media-title'>".__('Xing Share Count')."</div>
                </div>
            </li>
        ";

        echo json_encode($domain_info);
    }

    public function front_ajax_get_meta_tag_info_data(Request $request)
    {
        $domain_id = $request->input('domain_id');
        $data["meta_tag_info"]=DB::table('website_analysis_info')->where('id', $domain_id)->get();
        $data["meta_tag_info"] = json_decode(json_encode($data["meta_tag_info"]));

        $meta_tag_info = view('seo-tools.analysis-tools.website.meta-tag-details',$data)->render();

        echo $meta_tag_info;
    }


    public function frontend_download_pdf($id)
    {
        $id = $id;
        $domain_info = DB::table('website_analysis_info')->where('id',$id)->get();
        $domain_info = json_decode($domain_info);
        $data['country_list'] = get_country_names();
        $data['domain_info'] = $domain_info;
        $data["similar_web"] = $domain_info;
        $data["alexa_data"] = $domain_info;

        $info['fb_total_share'] = is_numeric($domain_info[0]->fb_total_share) ? number_format($domain_info[0]->fb_total_share) : 0;

        $info['fb_total_like'] = is_numeric($domain_info[0]->fb_total_like) ? number_format($domain_info[0]->fb_total_like) : 0;

        $info['fb_total_comment'] = is_numeric($domain_info[0]->fb_total_comment) ? number_format($domain_info[0]->fb_total_comment) : $domain_info[0]->fb_total_comment;


        $info['stumbleupon_total_view'] = is_numeric($domain_info[0]->stumbleupon_total_view) ? number_format($domain_info[0]->stumbleupon_total_view) : 0;

        $info['reddit_score'] = is_numeric($domain_info[0]->reddit_score) ? number_format($domain_info[0]->reddit_score) : 0;
        $info['reddit_ups'] = is_numeric($domain_info[0]->reddit_ups) ? number_format($domain_info[0]->reddit_ups) : 0;
        $info['reddit_downs'] = is_numeric($domain_info[0]->reddit_downs) ? number_format($domain_info[0]->reddit_downs) : 0;

        $info['pinterest_pin'] = is_numeric($domain_info[0]->pinterest_pin) ? number_format($domain_info[0]->pinterest_pin) : 0;
        $info['buffer_share'] = is_numeric($domain_info[0]->buffer_share_count) ? number_format($domain_info[0]->buffer_share_count) : 0;
        $info['xing_share'] = is_numeric($domain_info[0]->xing_share_count) ? number_format($domain_info[0]->xing_share_count) : 0;

        $data['info'] = $info;

        $pdf=new Mpdf();
        $pdf->autoScriptToLang = true;
        $pdf->autoLangToFont = true;
        $pdf->addPage();
        $pdf->SetDisplayMode('fullpage');
        $pdf->WriteHTML(view("seo-tools.analysis-tools.website.report.report", $data)->render());
        $domain = time();
        $download_id = $this->_random_number_generator(10);
        $file_name = "website_analysis_".$domain."_".$download_id.".pdf";
        $pdf->output($file_name,'I');
    }

    public function _language_loader()
    {

        if(!config("my_config.language") || config("my_config.language")=="")
        $this->language="english";
        else $this->language=config('my_config.language');

        if(session('selected_language') != "")
        $this->language = session('selected_language');
        else if(!config("my_config.language") || config("my_config.language")=="")
        $this->language="english";
        else $this->language=config('my_config.language');

        // if($this->language=="arabic")
        // $this->is_rtl=TRUE;

        $path=str_replace('\\', '/', base_path().'/language/'.$this->language);
        $files=$this->_scanAll($path);
        foreach ($files as $key2 => $value2)
        {
            $current_file=isset($value2->file) ? str_replace('\\', '/', $value2->file) : ""; //application/modules/addon_folder/language/language_folder/someting_lang.php
            if($current_file=="" || !is_file($current_file)) continue;
            $current_file_explode=explode('/',$current_file);
            $filename=array_pop($current_file_explode);
            $pos=strpos($filename,'_lang.php');
            if($pos!==false) // check if it is a lang file or not
            {
                $filename=str_replace('_lang.php', '', $filename);
                $this->lang->load($filename, $this->language);
            }
        }


    }



    public function member_validity()
    {
        if(session('logged_in') == 1 && session('user_type') != 'Admin')  {
            $user_expire_date = DB::table('users')->select('expired_date')->where('id',session('user_id'))->first();
            if($user_expire_date=='0000-00-00 00:00:00' || empty($user_expire_date)) return true;
            $expire_date = strtotime($user_expire_date->expired_date);
            $current_date = strtotime(date("Y-m-d H:i:s"));
            $userId = session('user_id');
            $package_data = DB::table('users')->select('package.price as price')->leftJoin('package', 'users.package_id', '=', 'package.id')->where('users.id', $userId)->first();
            if(isset($package_data)) $price=$package_data->price;
            if($price=="Trial") $price=1;
            if ($expire_date < $current_date && ($price>0 && $price!="")){
                header('Location:'.route('buy_package'));
                die;
            }
        }
    }

    public function important_feature($redirect=true)
    {
		return true;
    }

    public function credential_check()
    {
    }

    public function credential_check_action(Request $request)
    {
    }

    public function code_activation_check_action($purchase_code,$only_domain,$periodic=0)
    {
		return json_encode(['status'=>"success"]);
    }

    
    function get_general_content_with_checking($url,$proxy=""){


        $ch = curl_init(); // initialize curl handle
       /* curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);*/
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($ch, CURLOPT_AUTOREFERER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
        curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 120); // times out after 50s
        curl_setopt($ch, CURLOPT_POST, 0); // set POST method


        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      //  curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");
       // curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");

        $content = curl_exec($ch); // run the whole process
        $response['content'] = $content;

        $res = curl_getinfo($ch);
        if($res['http_code'] != 200)
            $response['error'] = 'error';
        curl_close($ch);
        return json_encode($response);

}

    public function _subscription_viewcontroller($data=array())
    {
        $language = config('my_config.language');
        if(isset($language) && !empty($language)) {
            App::setLocale($language);
        }
        $current_theme = config('my_config.current_theme');
        if($current_theme == '') $current_theme = 'default';
        if (!isset($data['body'])) $data['body']="site/default/blank";
        if (!isset($data['page_title'])) $data['page_title']="";

        $theme_file_path = "views/site/".$current_theme."/subscription_theme.php";
        if(file_exists(base_path($theme_file_path)))
            $theme_load = "site/".$current_theme."/subscription_theme";
        else
            $theme_load = "site/default/subscription_theme";

        return view($theme_load, $data);
    }

    public function _front_viewcontroller($data=array())
    {
        // $this->_disable_cache();

        $loadthemebody="purple";
        if(config('frontend.theme_front')!="") $loadthemebody=config('frontend.theme_front');

        $themecolorcode="#545096";

        if($loadthemebody=='blue')        { $themecolorcode="#1193D4";}
        if($loadthemebody=='white')        { $themecolorcode="#303F42";}
        if($loadthemebody=='black')        { $themecolorcode="#1A2226";}
        if($loadthemebody=='green')        { $themecolorcode="#00A65A";}
        if($loadthemebody=='red')          { $themecolorcode="#E55053";}
        if($loadthemebody=='yellow')       { $themecolorcode="#F39C12";}

        $data['THEMECOLORCODE']=$themecolorcode;

        // $current_theme = config('current_theme');
        // if($current_theme == '') $current_theme = 'default';
        // $body_file_path = "views/site/".$current_theme."/theme_front.php";
        // if(file_exists(base_path().$body_file_path))
        //     $body_load = "site/".$current_theme."/theme_front";
        // else
        //     $body_load = "site/default/theme_front";

        return view($data['body'],$data);
    }


    public function _viewcontroller($data=[])
    {

        if (!isset($data['body'])) return false;

        $language = config('my_config.language');
        if(isset($language) && !empty($language)) {
            App::setLocale($language);
        }
 
        if(session('download_id_front')=="")
        session(['download_id_front' => md5(time().$this->_random_number_generator(10))]);

        $data['menus'] = DB::table('menu')
                            ->orderBy('serial')
                            ->get();

        $menu_child_1_map = array();
        $menu_child_1 = DB::table('menu_child_1')
                            ->orderBy('serial')
                            ->get();
        foreach($menu_child_1 as $single_child_1)
        {
            $menu_child_1_map[$single_child_1->parent_id][$single_child_1->id] = $single_child_1;
        }
        $data['menu_child_1_map'] = $menu_child_1_map;

        $menu_child_2_map = array();
        $menu_child_2 = DB::table('menu_child_2')
                            ->orderBy('serial')
                            ->get();
        foreach($menu_child_2 as $single_child_2)
        {
            $menu_child_2_map[$single_child_2->parent_child][$single_child_2->id] = $single_child_2;
        }
        $data['menu_child_2_map'] = $menu_child_2_map;

        // announcement
        $where_custom = "(user_id=".Auth::user()->id." AND is_seen='0') OR (user_id=0 AND NOT FIND_IN_SET('".Auth::user()->id."', seen_by))";
        $data['annoucement_data'] = DB::table('announcement')
                                    ->whereRaw($where_custom)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
        $data['user_id'] = Auth::user()->id;

        $version_data=DB::table("version")->where("current","1")->get();
        $appversion=isset($version_data[0]->version) ? $version_data[0]->version : "";
        $this->APP_VERSION=$appversion;

        $data['APP_VERSION'] =$appversion;
        $data['route_name'] = Route::currentRouteName();

        if(Auth::user()->user_type != 'Admin')
        {
            $package_info = DB::table('package')->where('id',Auth::user()->package_id)->first();
            $module_ids='';
            if(isset($package_info->module_ids)) $module_ids = $package_info->module_ids;
            $this->module_access=explode(',', $module_ids);
            $data['module_access'] = $this->module_access;
        }

        return view($data['body'],$data);

    }

    public function _site_viewcontroller($data=array())
    {
        $language = config('my_config.language');
        if(isset($language) && !empty($language)) {
            App::setLocale($language);
        }
        if (!isset($data['body'])) return false;
        $config_data=array();
        $data=array();
        $price=0;
        $currency="USD";
        $config_data = DB::table('payment_config')->get();
        $config_data = json_decode(json_encode($config_data));

        if(array_key_exists(0,$config_data))
        {
            $currency=$config_data[0]->currency;
        }
        $data['price']=$price;
        $data['currency']=$currency;

        $currency_icons = $this->currency_icon();
        $data["curency_icon"]= isset($currency_icons[$currency])?$currency_icons[$currency]:"$";

        //catcha for contact page
        $data['contact_num1']=$this->_random_number_generator(2);
        $data['contact_num2']=$this->_random_number_generator(1);
        $contact_captcha= $data['contact_num1']+ $data['contact_num2'];
        // $this->session->set_userdata("contact_captcha",$contact_captcha);
        session()->put('contact_captcha', $contact_captcha);
        $data["language_info"] = $this->_language_list();
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

        $loadthemebody="purple";
        if(config('theme_front')!="") $loadthemebody=config('theme_front');

        $themecolorcode="#545096";

        if($loadthemebody=='blue')     { $themecolorcode="#1193D4";}
        if($loadthemebody=='white')    { $themecolorcode="#303F42";}
        if($loadthemebody=='black')    { $themecolorcode="#1A2226";}
        if($loadthemebody=='green')    { $themecolorcode="#00A65A";}
        if($loadthemebody=='red')      { $themecolorcode="#E55053";}
        if($loadthemebody=='yellow')   { $themecolorcode="#F39C12";}

        $data['THEMECOLORCODE']=$themecolorcode;

        // //catcha for contact page
        // $current_theme = config('my_config.current_theme');
        // if($current_theme == '') $current_theme = 'default';
        // // $body_file_path = "views/site/".$current_theme."/index.php";
        // $body_file_path = base_path("resources/views/home.blade.php");
        // if(file_exists($body_file_path)){
        //     $body_load = "site/".$current_theme."/index";
        //     $body_load = "home";
        // }
        // else{
        //     // $body_load = "site/default/index";
        //     $body_load = "home";
        // }
        return view($data['body'], $data);
    }

    protected function real_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
          $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }


    public function _time_zone_list()
    {
        return $timezones =
        array(
            'America/Adak' => '(GMT-10:00) America/Adak (Hawaii-Aleutian Standard Time)',
            'America/Atka' => '(GMT-10:00) America/Atka (Hawaii-Aleutian Standard Time)',
            'America/Anchorage' => '(GMT-9:00) America/Anchorage (Alaska Standard Time)',
            'America/Juneau' => '(GMT-9:00) America/Juneau (Alaska Standard Time)',
            'America/Nome' => '(GMT-9:00) America/Nome (Alaska Standard Time)',
            'America/Yakutat' => '(GMT-9:00) America/Yakutat (Alaska Standard Time)',
            'America/Dawson' => '(GMT-8:00) America/Dawson (Pacific Standard Time)',
            'America/Ensenada' => '(GMT-8:00) America/Ensenada (Pacific Standard Time)',
            'America/Los_Angeles' => '(GMT-8:00) America/Los_Angeles (Pacific Standard Time)',
            'America/Tijuana' => '(GMT-8:00) America/Tijuana (Pacific Standard Time)',
            'America/Vancouver' => '(GMT-8:00) America/Vancouver (Pacific Standard Time)',
            'America/Whitehorse' => '(GMT-8:00) America/Whitehorse (Pacific Standard Time)',
            'Canada/Pacific' => '(GMT-8:00) Canada/Pacific (Pacific Standard Time)',
            'Canada/Yukon' => '(GMT-8:00) Canada/Yukon (Pacific Standard Time)',
            'Mexico/BajaNorte' => '(GMT-8:00) Mexico/BajaNorte (Pacific Standard Time)',
            'America/Boise' => '(GMT-7:00) America/Boise (Mountain Standard Time)',
            'America/Cambridge_Bay' => '(GMT-7:00) America/Cambridge_Bay (Mountain Standard Time)',
            'America/Chihuahua' => '(GMT-7:00) America/Chihuahua (Mountain Standard Time)',
            'America/Dawson_Creek' => '(GMT-7:00) America/Dawson_Creek (Mountain Standard Time)',
            'America/Denver' => '(GMT-7:00) America/Denver (Mountain Standard Time)',
            'America/Edmonton' => '(GMT-7:00) America/Edmonton (Mountain Standard Time)',
            'America/Hermosillo' => '(GMT-7:00) America/Hermosillo (Mountain Standard Time)',
            'America/Inuvik' => '(GMT-7:00) America/Inuvik (Mountain Standard Time)',
            'America/Mazatlan' => '(GMT-7:00) America/Mazatlan (Mountain Standard Time)',
            'America/Phoenix' => '(GMT-7:00) America/Phoenix (Mountain Standard Time)',
            'America/Shiprock' => '(GMT-7:00) America/Shiprock (Mountain Standard Time)',
            'America/Yellowknife' => '(GMT-7:00) America/Yellowknife (Mountain Standard Time)',
            'Canada/Mountain' => '(GMT-7:00) Canada/Mountain (Mountain Standard Time)',
            'Mexico/BajaSur' => '(GMT-7:00) Mexico/BajaSur (Mountain Standard Time)',
            'America/Belize' => '(GMT-6:00) America/Belize (Central Standard Time)',
            'America/Cancun' => '(GMT-6:00) America/Cancun (Central Standard Time)',
            'America/Chicago' => '(GMT-6:00) America/Chicago (Central Standard Time)',
            'America/Costa_Rica' => '(GMT-6:00) America/Costa_Rica (Central Standard Time)',
            'America/El_Salvador' => '(GMT-6:00) America/El_Salvador (Central Standard Time)',
            'America/Guatemala' => '(GMT-6:00) America/Guatemala (Central Standard Time)',
            'America/Knox_IN' => '(GMT-6:00) America/Knox_IN (Central Standard Time)',
            'America/Managua' => '(GMT-6:00) America/Managua (Central Standard Time)',
            'America/Menominee' => '(GMT-6:00) America/Menominee (Central Standard Time)',
            'America/Merida' => '(GMT-6:00) America/Merida (Central Standard Time)',
            'America/Mexico_City' => '(GMT-6:00) America/Mexico_City (Central Standard Time)',
            'America/Monterrey' => '(GMT-6:00) America/Monterrey (Central Standard Time)',
            'America/Rainy_River' => '(GMT-6:00) America/Rainy_River (Central Standard Time)',
            'America/Rankin_Inlet' => '(GMT-6:00) America/Rankin_Inlet (Central Standard Time)',
            'America/Regina' => '(GMT-6:00) America/Regina (Central Standard Time)',
            'America/Swift_Current' => '(GMT-6:00) America/Swift_Current (Central Standard Time)',
            'America/Tegucigalpa' => '(GMT-6:00) America/Tegucigalpa (Central Standard Time)',
            'America/Winnipeg' => '(GMT-6:00) America/Winnipeg (Central Standard Time)',
            'Canada/Central' => '(GMT-6:00) Canada/Central (Central Standard Time)',
            'Canada/East-Saskatchewan' => '(GMT-6:00) Canada/East-Saskatchewan (Central Standard Time)',
            'Canada/Saskatchewan' => '(GMT-6:00) Canada/Saskatchewan (Central Standard Time)',
            'Chile/EasterIsland' => '(GMT-6:00) Chile/EasterIsland (Easter Is. Time)',
            'Mexico/General' => '(GMT-6:00) Mexico/General (Central Standard Time)',
            'America/Atikokan' => '(GMT-5:00) America/Atikokan (Eastern Standard Time)',
            'America/Bogota' => '(GMT-5:00) America/Bogota (Colombia Time)',
            'America/Cayman' => '(GMT-5:00) America/Cayman (Eastern Standard Time)',
            'America/Coral_Harbour' => '(GMT-5:00) America/Coral_Harbour (Eastern Standard Time)',
            'America/Detroit' => '(GMT-5:00) America/Detroit (Eastern Standard Time)',
            'America/Fort_Wayne' => '(GMT-5:00) America/Fort_Wayne (Eastern Standard Time)',
            'America/Grand_Turk' => '(GMT-5:00) America/Grand_Turk (Eastern Standard Time)',
            'America/Guayaquil' => '(GMT-5:00) America/Guayaquil (Ecuador Time)',
            'America/Havana' => '(GMT-5:00) America/Havana (Cuba Standard Time)',
            'America/Indianapolis' => '(GMT-5:00) America/Indianapolis (Eastern Standard Time)',
            'America/Iqaluit' => '(GMT-5:00) America/Iqaluit (Eastern Standard Time)',
            'America/Jamaica' => '(GMT-5:00) America/Jamaica (Eastern Standard Time)',
            'America/Lima' => '(GMT-5:00) America/Lima (Peru Time)',
            'America/Louisville' => '(GMT-5:00) America/Louisville (Eastern Standard Time)',
            'America/Montreal' => '(GMT-5:00) America/Montreal (Eastern Standard Time)',
            'America/Nassau' => '(GMT-5:00) America/Nassau (Eastern Standard Time)',
            'America/New_York' => '(GMT-5:00) America/New_York (Eastern Standard Time)',
            'America/Nipigon' => '(GMT-5:00) America/Nipigon (Eastern Standard Time)',
            'America/Panama' => '(GMT-5:00) America/Panama (Eastern Standard Time)',
            'America/Pangnirtung' => '(GMT-5:00) America/Pangnirtung (Eastern Standard Time)',
            'America/Port-au-Prince' => '(GMT-5:00) America/Port-au-Prince (Eastern Standard Time)',
            'America/Resolute' => '(GMT-5:00) America/Resolute (Eastern Standard Time)',
            'America/Thunder_Bay' => '(GMT-5:00) America/Thunder_Bay (Eastern Standard Time)',
            'America/Toronto' => '(GMT-5:00) America/Toronto (Eastern Standard Time)',
            'Canada/Eastern' => '(GMT-5:00) Canada/Eastern (Eastern Standard Time)',
            'America/Caracas' => '(GMT-4:-30) America/Caracas (Venezuela Time)',
            'America/Anguilla' => '(GMT-4:00) America/Anguilla (Atlantic Standard Time)',
            'America/Antigua' => '(GMT-4:00) America/Antigua (Atlantic Standard Time)',
            'America/Aruba' => '(GMT-4:00) America/Aruba (Atlantic Standard Time)',
            'America/Asuncion' => '(GMT-4:00) America/Asuncion (Paraguay Time)',
            'America/Barbados' => '(GMT-4:00) America/Barbados (Atlantic Standard Time)',
            'America/Blanc-Sablon' => '(GMT-4:00) America/Blanc-Sablon (Atlantic Standard Time)',
            'America/Boa_Vista' => '(GMT-4:00) America/Boa_Vista (Amazon Time)',
            'America/Campo_Grande' => '(GMT-4:00) America/Campo_Grande (Amazon Time)',
            'America/Cuiaba' => '(GMT-4:00) America/Cuiaba (Amazon Time)',
            'America/Curacao' => '(GMT-4:00) America/Curacao (Atlantic Standard Time)',
            'America/Dominica' => '(GMT-4:00) America/Dominica (Atlantic Standard Time)',
            'America/Eirunepe' => '(GMT-4:00) America/Eirunepe (Amazon Time)',
            'America/Glace_Bay' => '(GMT-4:00) America/Glace_Bay (Atlantic Standard Time)',
            'America/Goose_Bay' => '(GMT-4:00) America/Goose_Bay (Atlantic Standard Time)',
            'America/Grenada' => '(GMT-4:00) America/Grenada (Atlantic Standard Time)',
            'America/Guadeloupe' => '(GMT-4:00) America/Guadeloupe (Atlantic Standard Time)',
            'America/Guyana' => '(GMT-4:00) America/Guyana (Guyana Time)',
            'America/Halifax' => '(GMT-4:00) America/Halifax (Atlantic Standard Time)',
            'America/La_Paz' => '(GMT-4:00) America/La_Paz (Bolivia Time)',
            'America/Manaus' => '(GMT-4:00) America/Manaus (Amazon Time)',
            'America/Marigot' => '(GMT-4:00) America/Marigot (Atlantic Standard Time)',
            'America/Martinique' => '(GMT-4:00) America/Martinique (Atlantic Standard Time)',
            'America/Moncton' => '(GMT-4:00) America/Moncton (Atlantic Standard Time)',
            'America/Montserrat' => '(GMT-4:00) America/Montserrat (Atlantic Standard Time)',
            'America/Port_of_Spain' => '(GMT-4:00) America/Port_of_Spain (Atlantic Standard Time)',
            'America/Porto_Acre' => '(GMT-4:00) America/Porto_Acre (Amazon Time)',
            'America/Porto_Velho' => '(GMT-4:00) America/Porto_Velho (Amazon Time)',
            'America/Puerto_Rico' => '(GMT-4:00) America/Puerto_Rico (Atlantic Standard Time)',
            'America/Rio_Branco' => '(GMT-4:00) America/Rio_Branco (Amazon Time)',
            'America/Santiago' => '(GMT-4:00) America/Santiago (Chile Time)',
            'America/Santo_Domingo' => '(GMT-4:00) America/Santo_Domingo (Atlantic Standard Time)',
            'America/St_Barthelemy' => '(GMT-4:00) America/St_Barthelemy (Atlantic Standard Time)',
            'America/St_Kitts' => '(GMT-4:00) America/St_Kitts (Atlantic Standard Time)',
            'America/St_Lucia' => '(GMT-4:00) America/St_Lucia (Atlantic Standard Time)',
            'America/St_Thomas' => '(GMT-4:00) America/St_Thomas (Atlantic Standard Time)',
            'America/St_Vincent' => '(GMT-4:00) America/St_Vincent (Atlantic Standard Time)',
            'America/Thule' => '(GMT-4:00) America/Thule (Atlantic Standard Time)',
            'America/Tortola' => '(GMT-4:00) America/Tortola (Atlantic Standard Time)',
            'America/Virgin' => '(GMT-4:00) America/Virgin (Atlantic Standard Time)',
            'Antarctica/Palmer' => '(GMT-4:00) Antarctica/Palmer (Chile Time)',
            'Atlantic/Bermuda' => '(GMT-4:00) Atlantic/Bermuda (Atlantic Standard Time)',
            'Atlantic/Stanley' => '(GMT-4:00) Atlantic/Stanley (Falkland Is. Time)',
            'Brazil/Acre' => '(GMT-4:00) Brazil/Acre (Amazon Time)',
            'Brazil/West' => '(GMT-4:00) Brazil/West (Amazon Time)',
            'Canada/Atlantic' => '(GMT-4:00) Canada/Atlantic (Atlantic Standard Time)',
            'Chile/Continental' => '(GMT-4:00) Chile/Continental (Chile Time)',
            'America/St_Johns' => '(GMT-3:-30) America/St_Johns (Newfoundland Standard Time)',
            'Canada/Newfoundland' => '(GMT-3:-30) Canada/Newfoundland (Newfoundland Standard Time)',
            'America/Araguaina' => '(GMT-3:00) America/Araguaina (Brasilia Time)',
            'America/Bahia' => '(GMT-3:00) America/Bahia (Brasilia Time)',
            'America/Belem' => '(GMT-3:00) America/Belem (Brasilia Time)',
            'America/Buenos_Aires' => '(GMT-3:00) America/Buenos_Aires (Argentine Time)',
            'America/Catamarca' => '(GMT-3:00) America/Catamarca (Argentine Time)',
            'America/Cayenne' => '(GMT-3:00) America/Cayenne (French Guiana Time)',
            'America/Cordoba' => '(GMT-3:00) America/Cordoba (Argentine Time)',
            'America/Fortaleza' => '(GMT-3:00) America/Fortaleza (Brasilia Time)',
            'America/Godthab' => '(GMT-3:00) America/Godthab (Western Greenland Time)',
            'America/Jujuy' => '(GMT-3:00) America/Jujuy (Argentine Time)',
            'America/Maceio' => '(GMT-3:00) America/Maceio (Brasilia Time)',
            'America/Mendoza' => '(GMT-3:00) America/Mendoza (Argentine Time)',
            'America/Miquelon' => '(GMT-3:00) America/Miquelon (Pierre & Miquelon Standard Time)',
            'America/Montevideo' => '(GMT-3:00) America/Montevideo (Uruguay Time)',
            'America/Paramaribo' => '(GMT-3:00) America/Paramaribo (Suriname Time)',
            'America/Recife' => '(GMT-3:00) America/Recife (Brasilia Time)',
            'America/Rosario' => '(GMT-3:00) America/Rosario (Argentine Time)',
            'America/Santarem' => '(GMT-3:00) America/Santarem (Brasilia Time)',
            'America/Sao_Paulo' => '(GMT-3:00) America/Sao_Paulo (Brasilia Time)',
            'Antarctica/Rothera' => '(GMT-3:00) Antarctica/Rothera (Rothera Time)',
            'Brazil/East' => '(GMT-3:00) Brazil/East (Brasilia Time)',
            'America/Noronha' => '(GMT-2:00) America/Noronha (Fernando de Noronha Time)',
            'Atlantic/South_Georgia' => '(GMT-2:00) Atlantic/South_Georgia (South Georgia Standard Time)',
            'Brazil/DeNoronha' => '(GMT-2:00) Brazil/DeNoronha (Fernando de Noronha Time)',
            'America/Scoresbysund' => '(GMT-1:00) America/Scoresbysund (Eastern Greenland Time)',
            'Atlantic/Azores' => '(GMT-1:00) Atlantic/Azores (Azores Time)',
            'Atlantic/Cape_Verde' => '(GMT-1:00) Atlantic/Cape_Verde (Cape Verde Time)',
            'Africa/Abidjan' => '(GMT+0:00) Africa/Abidjan (Greenwich Mean Time)',
            'Africa/Accra' => '(GMT+0:00) Africa/Accra (Ghana Mean Time)',
            'Africa/Bamako' => '(GMT+0:00) Africa/Bamako (Greenwich Mean Time)',
            'Africa/Banjul' => '(GMT+0:00) Africa/Banjul (Greenwich Mean Time)',
            'Africa/Bissau' => '(GMT+0:00) Africa/Bissau (Greenwich Mean Time)',
            'Africa/Casablanca' => '(GMT+0:00) Africa/Casablanca (Western European Time)',
            'Africa/Conakry' => '(GMT+0:00) Africa/Conakry (Greenwich Mean Time)',
            'Africa/Dakar' => '(GMT+0:00) Africa/Dakar (Greenwich Mean Time)',
            'Africa/El_Aaiun' => '(GMT+0:00) Africa/El_Aaiun (Western European Time)',
            'Africa/Freetown' => '(GMT+0:00) Africa/Freetown (Greenwich Mean Time)',
            'Africa/Lome' => '(GMT+0:00) Africa/Lome (Greenwich Mean Time)',
            'Africa/Monrovia' => '(GMT+0:00) Africa/Monrovia (Greenwich Mean Time)',
            'Africa/Nouakchott' => '(GMT+0:00) Africa/Nouakchott (Greenwich Mean Time)',
            'Africa/Ouagadougou' => '(GMT+0:00) Africa/Ouagadougou (Greenwich Mean Time)',
            'Africa/Sao_Tome' => '(GMT+0:00) Africa/Sao_Tome (Greenwich Mean Time)',
            'Africa/Timbuktu' => '(GMT+0:00) Africa/Timbuktu (Greenwich Mean Time)',
            'America/Danmarkshavn' => '(GMT+0:00) America/Danmarkshavn (Greenwich Mean Time)',
            'Atlantic/Canary' => '(GMT+0:00) Atlantic/Canary (Western European Time)',
            'Atlantic/Faeroe' => '(GMT+0:00) Atlantic/Faeroe (Western European Time)',
            'Atlantic/Faroe' => '(GMT+0:00) Atlantic/Faroe (Western European Time)',
            'Atlantic/Madeira' => '(GMT+0:00) Atlantic/Madeira (Western European Time)',
            'Atlantic/Reykjavik' => '(GMT+0:00) Atlantic/Reykjavik (Greenwich Mean Time)',
            'Atlantic/St_Helena' => '(GMT+0:00) Atlantic/St_Helena (Greenwich Mean Time)',
            'Europe/Belfast' => '(GMT+0:00) Europe/Belfast (Greenwich Mean Time)',
            'Europe/Dublin' => '(GMT+0:00) Europe/Dublin (Greenwich Mean Time)',
            'Europe/Guernsey' => '(GMT+0:00) Europe/Guernsey (Greenwich Mean Time)',
            'Europe/Isle_of_Man' => '(GMT+0:00) Europe/Isle_of_Man (Greenwich Mean Time)',
            'Europe/Jersey' => '(GMT+0:00) Europe/Jersey (Greenwich Mean Time)',
            'Europe/Lisbon' => '(GMT+0:00) Europe/Lisbon (Western European Time)',
            'Europe/London' => '(GMT+0:00) Europe/London (Greenwich Mean Time)',
            'Africa/Algiers' => '(GMT+1:00) Africa/Algiers (Central European Time)',
            'Africa/Bangui' => '(GMT+1:00) Africa/Bangui (Western African Time)',
            'Africa/Brazzaville' => '(GMT+1:00) Africa/Brazzaville (Western African Time)',
            'Africa/Ceuta' => '(GMT+1:00) Africa/Ceuta (Central European Time)',
            'Africa/Douala' => '(GMT+1:00) Africa/Douala (Western African Time)',
            'Africa/Kinshasa' => '(GMT+1:00) Africa/Kinshasa (Western African Time)',
            'Africa/Lagos' => '(GMT+1:00) Africa/Lagos (Western African Time)',
            'Africa/Libreville' => '(GMT+1:00) Africa/Libreville (Western African Time)',
            'Africa/Luanda' => '(GMT+1:00) Africa/Luanda (Western African Time)',
            'Africa/Malabo' => '(GMT+1:00) Africa/Malabo (Western African Time)',
            'Africa/Ndjamena' => '(GMT+1:00) Africa/Ndjamena (Western African Time)',
            'Africa/Niamey' => '(GMT+1:00) Africa/Niamey (Western African Time)',
            'Africa/Porto-Novo' => '(GMT+1:00) Africa/Porto-Novo (Western African Time)',
            'Africa/Tunis' => '(GMT+1:00) Africa/Tunis (Central European Time)',
            'Africa/Windhoek' => '(GMT+1:00) Africa/Windhoek (Western African Time)',
            'Arctic/Longyearbyen' => '(GMT+1:00) Arctic/Longyearbyen (Central European Time)',
            'Atlantic/Jan_Mayen' => '(GMT+1:00) Atlantic/Jan_Mayen (Central European Time)',
            'Europe/Amsterdam' => '(GMT+1:00) Europe/Amsterdam (Central European Time)',
            'Europe/Andorra' => '(GMT+1:00) Europe/Andorra (Central European Time)',
            'Europe/Belgrade' => '(GMT+1:00) Europe/Belgrade (Central European Time)',
            'Europe/Berlin' => '(GMT+1:00) Europe/Berlin (Central European Time)',
            'Europe/Bratislava' => '(GMT+1:00) Europe/Bratislava (Central European Time)',
            'Europe/Brussels' => '(GMT+1:00) Europe/Brussels (Central European Time)',
            'Europe/Budapest' => '(GMT+1:00) Europe/Budapest (Central European Time)',
            'Europe/Copenhagen' => '(GMT+1:00) Europe/Copenhagen (Central European Time)',
            'Europe/Gibraltar' => '(GMT+1:00) Europe/Gibraltar (Central European Time)',
            'Europe/Ljubljana' => '(GMT+1:00) Europe/Ljubljana (Central European Time)',
            'Europe/Luxembourg' => '(GMT+1:00) Europe/Luxembourg (Central European Time)',
            'Europe/Madrid' => '(GMT+1:00) Europe/Madrid (Central European Time)',
            'Europe/Malta' => '(GMT+1:00) Europe/Malta (Central European Time)',
            'Europe/Monaco' => '(GMT+1:00) Europe/Monaco (Central European Time)',
            'Europe/Oslo' => '(GMT+1:00) Europe/Oslo (Central European Time)',
            'Europe/Paris' => '(GMT+1:00) Europe/Paris (Central European Time)',
            'Europe/Podgorica' => '(GMT+1:00) Europe/Podgorica (Central European Time)',
            'Europe/Prague' => '(GMT+1:00) Europe/Prague (Central European Time)',
            'Europe/Rome' => '(GMT+1:00) Europe/Rome (Central European Time)',
            'Europe/San_Marino' => '(GMT+1:00) Europe/San_Marino (Central European Time)',
            'Europe/Sarajevo' => '(GMT+1:00) Europe/Sarajevo (Central European Time)',
            'Europe/Skopje' => '(GMT+1:00) Europe/Skopje (Central European Time)',
            'Europe/Stockholm' => '(GMT+1:00) Europe/Stockholm (Central European Time)',
            'Europe/Tirane' => '(GMT+1:00) Europe/Tirane (Central European Time)',
            'Europe/Vaduz' => '(GMT+1:00) Europe/Vaduz (Central European Time)',
            'Europe/Vatican' => '(GMT+1:00) Europe/Vatican (Central European Time)',
            'Europe/Vienna' => '(GMT+1:00) Europe/Vienna (Central European Time)',
            'Europe/Warsaw' => '(GMT+1:00) Europe/Warsaw (Central European Time)',
            'Europe/Zagreb' => '(GMT+1:00) Europe/Zagreb (Central European Time)',
            'Europe/Zurich' => '(GMT+1:00) Europe/Zurich (Central European Time)',
            'Africa/Blantyre' => '(GMT+2:00) Africa/Blantyre (Central African Time)',
            'Africa/Bujumbura' => '(GMT+2:00) Africa/Bujumbura (Central African Time)',
            'Africa/Cairo' => '(GMT+2:00) Africa/Cairo (Eastern European Time)',
            'Africa/Gaborone' => '(GMT+2:00) Africa/Gaborone (Central African Time)',
            'Africa/Harare' => '(GMT+2:00) Africa/Harare (Central African Time)',
            'Africa/Johannesburg' => '(GMT+2:00) Africa/Johannesburg (South Africa Standard Time)',
            'Africa/Kigali' => '(GMT+2:00) Africa/Kigali (Central African Time)',
            'Africa/Lubumbashi' => '(GMT+2:00) Africa/Lubumbashi (Central African Time)',
            'Africa/Lusaka' => '(GMT+2:00) Africa/Lusaka (Central African Time)',
            'Africa/Maputo' => '(GMT+2:00) Africa/Maputo (Central African Time)',
            'Africa/Maseru' => '(GMT+2:00) Africa/Maseru (South Africa Standard Time)',
            'Africa/Mbabane' => '(GMT+2:00) Africa/Mbabane (South Africa Standard Time)',
            'Africa/Tripoli' => '(GMT+2:00) Africa/Tripoli (Eastern European Time)',
            'Asia/Amman' => '(GMT+2:00) Asia/Amman (Eastern European Time)',
            'Asia/Beirut' => '(GMT+2:00) Asia/Beirut (Eastern European Time)',
            'Asia/Damascus' => '(GMT+2:00) Asia/Damascus (Eastern European Time)',
            'Asia/Gaza' => '(GMT+2:00) Asia/Gaza (Eastern European Time)',
            'Asia/Istanbul' => '(GMT+2:00) Asia/Istanbul (Eastern European Time)',
            'Asia/Jerusalem' => '(GMT+2:00) Asia/Jerusalem (Israel Standard Time)',
            'Asia/Nicosia' => '(GMT+2:00) Asia/Nicosia (Eastern European Time)',
            'Asia/Tel_Aviv' => '(GMT+2:00) Asia/Tel_Aviv (Israel Standard Time)',
            'Europe/Athens' => '(GMT+2:00) Europe/Athens (Eastern European Time)',
            'Europe/Bucharest' => '(GMT+2:00) Europe/Bucharest (Eastern European Time)',
            'Europe/Chisinau' => '(GMT+2:00) Europe/Chisinau (Eastern European Time)',
            'Europe/Helsinki' => '(GMT+2:00) Europe/Helsinki (Eastern European Time)',
            'Europe/Istanbul' => '(GMT+2:00) Europe/Istanbul (Eastern European Time)',
            'Europe/Kaliningrad' => '(GMT+2:00) Europe/Kaliningrad (Eastern European Time)',
            'Europe/Kiev' => '(GMT+2:00) Europe/Kiev (Eastern European Time)',
            'Europe/Mariehamn' => '(GMT+2:00) Europe/Mariehamn (Eastern European Time)',
            'Europe/Minsk' => '(GMT+2:00) Europe/Minsk (Eastern European Time)',
            'Europe/Nicosia' => '(GMT+2:00) Europe/Nicosia (Eastern European Time)',
            'Europe/Riga' => '(GMT+2:00) Europe/Riga (Eastern European Time)',
            'Europe/Simferopol' => '(GMT+2:00) Europe/Simferopol (Eastern European Time)',
            'Europe/Sofia' => '(GMT+2:00) Europe/Sofia (Eastern European Time)',
            'Europe/Tallinn' => '(GMT+2:00) Europe/Tallinn (Eastern European Time)',
            'Europe/Tiraspol' => '(GMT+2:00) Europe/Tiraspol (Eastern European Time)',
            'Europe/Uzhgorod' => '(GMT+2:00) Europe/Uzhgorod (Eastern European Time)',
            'Europe/Vilnius' => '(GMT+2:00) Europe/Vilnius (Eastern European Time)',
            'Europe/Zaporozhye' => '(GMT+2:00) Europe/Zaporozhye (Eastern European Time)',
            'Africa/Addis_Ababa' => '(GMT+3:00) Africa/Addis_Ababa (Eastern African Time)',
            'Africa/Asmara' => '(GMT+3:00) Africa/Asmara (Eastern African Time)',
            'Africa/Asmera' => '(GMT+3:00) Africa/Asmera (Eastern African Time)',
            'Africa/Dar_es_Salaam' => '(GMT+3:00) Africa/Dar_es_Salaam (Eastern African Time)',
            'Africa/Djibouti' => '(GMT+3:00) Africa/Djibouti (Eastern African Time)',
            'Africa/Kampala' => '(GMT+3:00) Africa/Kampala (Eastern African Time)',
            'Africa/Khartoum' => '(GMT+3:00) Africa/Khartoum (Eastern African Time)',
            'Africa/Mogadishu' => '(GMT+3:00) Africa/Mogadishu (Eastern African Time)',
            'Africa/Nairobi' => '(GMT+3:00) Africa/Nairobi (Eastern African Time)',
            'Antarctica/Syowa' => '(GMT+3:00) Antarctica/Syowa (Syowa Time)',
            'Asia/Aden' => '(GMT+3:00) Asia/Aden (Arabia Standard Time)',
            'Asia/Baghdad' => '(GMT+3:00) Asia/Baghdad (Arabia Standard Time)',
            'Asia/Bahrain' => '(GMT+3:00) Asia/Bahrain (Arabia Standard Time)',
            'Asia/Kuwait' => '(GMT+3:00) Asia/Kuwait (Arabia Standard Time)',
            'Asia/Qatar' => '(GMT+3:00) Asia/Qatar (Arabia Standard Time)',
            'Europe/Moscow' => '(GMT+3:00) Europe/Moscow (Moscow Standard Time)',
            'Europe/Volgograd' => '(GMT+3:00) Europe/Volgograd (Volgograd Time)',
            'Indian/Antananarivo' => '(GMT+3:00) Indian/Antananarivo (Eastern African Time)',
            'Indian/Comoro' => '(GMT+3:00) Indian/Comoro (Eastern African Time)',
            'Indian/Mayotte' => '(GMT+3:00) Indian/Mayotte (Eastern African Time)',
            'Asia/Tehran' => '(GMT+3:30) Asia/Tehran (Iran Standard Time)',
            'Asia/Baku' => '(GMT+4:00) Asia/Baku (Azerbaijan Time)',
            'Asia/Dubai' => '(GMT+4:00) Asia/Dubai (Gulf Standard Time)',
            'Asia/Muscat' => '(GMT+4:00) Asia/Muscat (Gulf Standard Time)',
            'Asia/Tbilisi' => '(GMT+4:00) Asia/Tbilisi (Georgia Time)',
            'Asia/Yerevan' => '(GMT+4:00) Asia/Yerevan (Armenia Time)',
            'Europe/Samara' => '(GMT+4:00) Europe/Samara (Samara Time)',
            'Indian/Mahe' => '(GMT+4:00) Indian/Mahe (Seychelles Time)',
            'Indian/Mauritius' => '(GMT+4:00) Indian/Mauritius (Mauritius Time)',
            'Indian/Reunion' => '(GMT+4:00) Indian/Reunion (Reunion Time)',
            'Asia/Kabul' => '(GMT+4:30) Asia/Kabul (Afghanistan Time)',
            'Asia/Aqtau' => '(GMT+5:00) Asia/Aqtau (Aqtau Time)',
            'Asia/Aqtobe' => '(GMT+5:00) Asia/Aqtobe (Aqtobe Time)',
            'Asia/Ashgabat' => '(GMT+5:00) Asia/Ashgabat (Turkmenistan Time)',
            'Asia/Ashkhabad' => '(GMT+5:00) Asia/Ashkhabad (Turkmenistan Time)',
            'Asia/Dushanbe' => '(GMT+5:00) Asia/Dushanbe (Tajikistan Time)',
            'Asia/Karachi' => '(GMT+5:00) Asia/Karachi (Pakistan Time)',
            'Asia/Oral' => '(GMT+5:00) Asia/Oral (Oral Time)',
            'Asia/Samarkand' => '(GMT+5:00) Asia/Samarkand (Uzbekistan Time)',
            'Asia/Tashkent' => '(GMT+5:00) Asia/Tashkent (Uzbekistan Time)',
            'Asia/Yekaterinburg' => '(GMT+5:00) Asia/Yekaterinburg (Yekaterinburg Time)',
            'Indian/Kerguelen' => '(GMT+5:00) Indian/Kerguelen (French Southern & Antarctic Lands Time)',
            'Indian/Maldives' => '(GMT+5:00) Indian/Maldives (Maldives Time)',
            'Asia/Calcutta' => '(GMT+5:30) Asia/Calcutta (India Standard Time)',
            'Asia/Colombo' => '(GMT+5:30) Asia/Colombo (India Standard Time)',
            'Asia/Kolkata' => '(GMT+5:30) Asia/Kolkata (India Standard Time)',
            'Asia/Katmandu' => '(GMT+5:45) Asia/Katmandu (Nepal Time)',
            'Antarctica/Mawson' => '(GMT+6:00) Antarctica/Mawson (Mawson Time)',
            'Antarctica/Vostok' => '(GMT+6:00) Antarctica/Vostok (Vostok Time)',
            'Asia/Almaty' => '(GMT+6:00) Asia/Almaty (Alma-Ata Time)',
            'Asia/Bishkek' => '(GMT+6:00) Asia/Bishkek (Kirgizstan Time)',
            'Asia/Dhaka' => '(GMT+6:00) Asia/Dhaka (Bangladesh Time)',
            'Asia/Novosibirsk' => '(GMT+6:00) Asia/Novosibirsk (Novosibirsk Time)',
            'Asia/Omsk' => '(GMT+6:00) Asia/Omsk (Omsk Time)',
            'Asia/Qyzylorda' => '(GMT+6:00) Asia/Qyzylorda (Qyzylorda Time)',
            'Asia/Thimbu' => '(GMT+6:00) Asia/Thimbu (Bhutan Time)',
            'Asia/Thimphu' => '(GMT+6:00) Asia/Thimphu (Bhutan Time)',
            'Indian/Chagos' => '(GMT+6:00) Indian/Chagos (Indian Ocean Territory Time)',
            'Asia/Rangoon' => '(GMT+6:30) Asia/Rangoon (Myanmar Time)',
            'Indian/Cocos' => '(GMT+6:30) Indian/Cocos (Cocos Islands Time)',
            'Antarctica/Davis' => '(GMT+7:00) Antarctica/Davis (Davis Time)',
            'Asia/Bangkok' => '(GMT+7:00) Asia/Bangkok (Indochina Time)',
            'Asia/Ho_Chi_Minh' => '(GMT+7:00) Asia/Ho_Chi_Minh (Indochina Time)',
            'Asia/Hovd' => '(GMT+7:00) Asia/Hovd (Hovd Time)',
            'Asia/Jakarta' => '(GMT+7:00) Asia/Jakarta (West Indonesia Time)',
            'Asia/Krasnoyarsk' => '(GMT+7:00) Asia/Krasnoyarsk (Krasnoyarsk Time)',
            'Asia/Phnom_Penh' => '(GMT+7:00) Asia/Phnom_Penh (Indochina Time)',
            'Asia/Pontianak' => '(GMT+7:00) Asia/Pontianak (West Indonesia Time)',
            'Asia/Saigon' => '(GMT+7:00) Asia/Saigon (Indochina Time)',
            'Asia/Vientiane' => '(GMT+7:00) Asia/Vientiane (Indochina Time)',
            'Indian/Christmas' => '(GMT+7:00) Indian/Christmas (Christmas Island Time)',
            'Antarctica/Casey' => '(GMT+8:00) Antarctica/Casey (Western Standard Time (Australia))',
            'Asia/Brunei' => '(GMT+8:00) Asia/Brunei (Brunei Time)',
            'Asia/Choibalsan' => '(GMT+8:00) Asia/Choibalsan (Choibalsan Time)',
            'Asia/Chongqing' => '(GMT+8:00) Asia/Chongqing (China Standard Time)',
            'Asia/Chungking' => '(GMT+8:00) Asia/Chungking (China Standard Time)',
            'Asia/Harbin' => '(GMT+8:00) Asia/Harbin (China Standard Time)',
            'Asia/Hong_Kong' => '(GMT+8:00) Asia/Hong_Kong (Hong Kong Time)',
            'Asia/Irkutsk' => '(GMT+8:00) Asia/Irkutsk (Irkutsk Time)',
            'Asia/Kashgar' => '(GMT+8:00) Asia/Kashgar (China Standard Time)',
            'Asia/Kuala_Lumpur' => '(GMT+8:00) Asia/Kuala_Lumpur (Malaysia Time)',
            'Asia/Kuching' => '(GMT+8:00) Asia/Kuching (Malaysia Time)',
            'Asia/Macao' => '(GMT+8:00) Asia/Macao (China Standard Time)',
            'Asia/Macau' => '(GMT+8:00) Asia/Macau (China Standard Time)',
            'Asia/Makassar' => '(GMT+8:00) Asia/Makassar (Central Indonesia Time)',
            'Asia/Manila' => '(GMT+8:00) Asia/Manila (Philippines Time)',
            'Asia/Shanghai' => '(GMT+8:00) Asia/Shanghai (China Standard Time)',
            'Asia/Singapore' => '(GMT+8:00) Asia/Singapore (Singapore Time)',
            'Asia/Taipei' => '(GMT+8:00) Asia/Taipei (China Standard Time)',
            'Asia/Ujung_Pandang' => '(GMT+8:00) Asia/Ujung_Pandang (Central Indonesia Time)',
            'Asia/Ulaanbaatar' => '(GMT+8:00) Asia/Ulaanbaatar (Ulaanbaatar Time)',
            'Asia/Ulan_Bator' => '(GMT+8:00) Asia/Ulan_Bator (Ulaanbaatar Time)',
            'Asia/Urumqi' => '(GMT+8:00) Asia/Urumqi (China Standard Time)',
            'Australia/Perth' => '(GMT+8:00) Australia/Perth (Western Standard Time (Australia))',
            'Australia/West' => '(GMT+8:00) Australia/West (Western Standard Time (Australia))',
            'Australia/Eucla' => '(GMT+8:45) Australia/Eucla (Central Western Standard Time (Australia))',
            'Asia/Dili' => '(GMT+9:00) Asia/Dili (Timor-Leste Time)',
            'Asia/Jayapura' => '(GMT+9:00) Asia/Jayapura (East Indonesia Time)',
            'Asia/Pyongyang' => '(GMT+9:00) Asia/Pyongyang (Korea Standard Time)',
            'Asia/Seoul' => '(GMT+9:00) Asia/Seoul (Korea Standard Time)',
            'Asia/Tokyo' => '(GMT+9:00) Asia/Tokyo (Japan Standard Time)',
            'Asia/Yakutsk' => '(GMT+9:00) Asia/Yakutsk (Yakutsk Time)',
            'Australia/Adelaide' => '(GMT+9:30) Australia/Adelaide (Central Standard Time (South Australia))',
            'Australia/Broken_Hill' => '(GMT+9:30) Australia/Broken_Hill (Central Standard Time (South Australia/New South Wales))',
            'Australia/Darwin' => '(GMT+9:30) Australia/Darwin (Central Standard Time (Northern Territory))',
            'Australia/North' => '(GMT+9:30) Australia/North (Central Standard Time (Northern Territory))',
            'Australia/South' => '(GMT+9:30) Australia/South (Central Standard Time (South Australia))',
            'Australia/Yancowinna' => '(GMT+9:30) Australia/Yancowinna (Central Standard Time (South Australia/New South Wales))',
            'Antarctica/DumontDUrville' => '(GMT+10:00) Antarctica/DumontDUrville (Dumont-d\'Urville Time)',
            'Asia/Sakhalin' => '(GMT+10:00) Asia/Sakhalin (Sakhalin Time)',
            'Asia/Vladivostok' => '(GMT+10:00) Asia/Vladivostok (Vladivostok Time)',
            'Australia/ACT' => '(GMT+10:00) Australia/ACT (Eastern Standard Time (New South Wales))',
            'Australia/Brisbane' => '(GMT+10:00) Australia/Brisbane (Eastern Standard Time (Queensland))',
            'Australia/Canberra' => '(GMT+10:00) Australia/Canberra (Eastern Standard Time (New South Wales))',
            'Australia/Currie' => '(GMT+10:00) Australia/Currie (Eastern Standard Time (New South Wales))',
            'Australia/Hobart' => '(GMT+10:00) Australia/Hobart (Eastern Standard Time (Tasmania))',
            'Australia/Lindeman' => '(GMT+10:00) Australia/Lindeman (Eastern Standard Time (Queensland))',
            'Australia/Melbourne' => '(GMT+10:00) Australia/Melbourne (Eastern Standard Time (Victoria))',
            'Australia/NSW' => '(GMT+10:00) Australia/NSW (Eastern Standard Time (New South Wales))',
            'Australia/Queensland' => '(GMT+10:00) Australia/Queensland (Eastern Standard Time (Queensland))',
            'Australia/Sydney' => '(GMT+10:00) Australia/Sydney (Eastern Standard Time (New South Wales))',
            'Australia/Tasmania' => '(GMT+10:00) Australia/Tasmania (Eastern Standard Time (Tasmania))',
            'Australia/Victoria' => '(GMT+10:00) Australia/Victoria (Eastern Standard Time (Victoria))',
            'Australia/LHI' => '(GMT+10:30) Australia/LHI (Lord Howe Standard Time)',
            'Australia/Lord_Howe' => '(GMT+10:30) Australia/Lord_Howe (Lord Howe Standard Time)',
            'Asia/Magadan' => '(GMT+11:00) Asia/Magadan (Magadan Time)',
            'Antarctica/McMurdo' => '(GMT+12:00) Antarctica/McMurdo (New Zealand Standard Time)',
            'Antarctica/South_Pole' => '(GMT+12:00) Antarctica/South_Pole (New Zealand Standard Time)',
            'Asia/Anadyr' => '(GMT+12:00) Asia/Anadyr (Anadyr Time)',
            'Asia/Kamchatka' => '(GMT+12:00) Asia/Kamchatka (Petropavlovsk-Kamchatski Time)'
        );
    }

    public function read_text_file(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }

        $ret = [];
        if (!file_exists(storage_path("app/public/upload/tmp"))) {
            mkdir(storage_path("app/public/upload/tmp"), 0777, true);
        }
        $output_dir = storage_path('app/public/upload/tmp');

        if ($request->hasFile('myfile')) {
            $file = $request->file('myfile');
            $post_fileName = $file->getClientOriginalName();
            $post_fileName_array = explode(".", $post_fileName);
            $ext = array_pop($post_fileName_array);
            $filename = implode('.', $post_fileName_array);
            $filename = "image_" . Auth::user()->id . "_" . time() . substr(uniqid(mt_rand(), true), 0, 6) . "." . $ext;

            $allow = ".csv,.txt,.doc";
            $allow = str_replace('.', '', $allow);
            $allow = explode(',', $allow);

            if (!in_array(strtolower($ext), $allow)) {
                return response()->json(["are_u_kidding_me" => "yarki"]);
            }

            $file->move($output_dir, $filename);
            $path = $output_dir . '/' . $filename;

            $read_handle = fopen($path, "r");
            $context_array = ['file_name' => $filename];
            $context = "";

            while (!feof($read_handle)) {
                $information = fgetcsv($read_handle);
                if (!empty($information)) {
                    foreach ($information as $info) {
                        if (!is_numeric($info)) {
                            $context .= $info . "\n";
                        }
                    }
                }
            }

            $context_array['content'] = trim($context, "\n");
            return response()->json($context_array);
        }
    }

    public function read_after_delete(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }

        $outputDir = public_path('upload/tmp/');
        if ($request->input('op') == 'delete' && $request->has('name')) {
            $fileName = $request->input('name');
            $fileName = str_replace('..', '.', $fileName); // required to prevent accessing parent directory files
            $filePath = $outputDir . $fileName;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

    public function _random_number_generator($length=6)
    {
        $rand = substr(uniqid(mt_rand(), true), 0, $length);
        return $rand;
    }

    public function language_changer(Request $request)
    {
        $language[]=$request->input("language");
        session()->put("selected_language",$language);
        return view('shared.variables',['language' => $language]);
    }

    public function allow_cookie()
    {
        session(['allow_cookie'=>'yes']);
        // redirect($_SERVER['HTTP_REFERER'],'location');
    }

//================================================================
//=========================WEBSITE FUNCTIOS=======================
    function _mail_sender($from = '', $to = '', $subject = '', $message = '', $mask = "", $html = 1, $smtp = 1,$attachement="",$test_mail="")
    {
        if ($to!= '' && $subject!='' && $message!= '')
        {
            if(config('my_config.email_sending_option') == '') $email_sending_option = 'smtp';
            else $email_sending_option = config('my_config.email_sending_option');

            if($test_mail == 1) $email_sending_option = 'smtp';

            // $message=$message."<br/><br/>".__("The email was sent by"). ": ".$from;

            if($email_sending_option == 'smtp')
            {
                if ($smtp == '1') {
                    // $where2 = array("where" => array('status' => '1','deleted' => '0'));
                    // $email_config_details = $this->basic->get_data("email_config", $where2, $select = '', $join = '', $limit = '', $start = '', $group_by = '', $num_rows = 0);
                    $email_config_details = DB::table('email_config')->where('status', '1')->where('deleted', '0')->get();

                    if (count($email_config_details) == 0) {
                        // $this->load->library('email');
                    } else {
                        foreach ($email_config_details as $send_info) {
                            $send_email = trim($send_info->email_address);
                            $smtp_host = trim($send_info->smtp_host);
                            $smtp_port = trim($send_info->smtp_port);
                            $smtp_user = trim($send_info->smtp_user);
                            $smtp_password = trim($send_info->smtp_password);
                            $smtp_type = trim($send_info->smtp_type);
                        }

                    /*****Email Sending Code ******/
                    $config = array(
                    'protocol' => 'smtp',
                    'smtp_host' => "{$smtp_host}",
                    'smtp_port' => "{$smtp_port}",
                    'smtp_user' => "{$smtp_user}", // change it to yours
                    'smtp_pass' => "{$smtp_password}", // change it to yours
                    'mailtype' => 'html',
                    'charset' => 'utf-8',
                    'newline' =>  "\r\n",
                    'set_crlf'=>"\r\n",
                    'smtp_timeout' => '30'
                    );
                    if($smtp_type != 'Default')
                        $config['smtp_crypto'] = $smtp_type;

                        // $this->load->library('email', $config);
                    }
                } /*** End of If Smtp== 1 **/

                if (isset($send_email) && $send_email!= "") {
                    $from = $send_email;
                }
                $this->email->from($from, $mask);
                $this->email->to($to);
                $this->email->subject($subject);
                $this->email->message($message);
                if ($html == 1) {
                    $this->email->set_mailtype('html');
                }
                if ($attachement!="") {
                    $this->email->attach($attachement);
                }

                if ($this->email->send()) {
                    return true;
                } else {

                    if($test_mail==1) {
                        return $this->email->print_debugger();
                    } else {
                        return false;
                    }
                }
            }

            if($email_sending_option == 'php_mail')
            {
                $from = get_domain_only(base_url());
                $from = "support@".$from;
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= "From: {$from}" . "\r\n";
                if(mail($to, $subject, $message, $headers))
                    return true;
                else
                    return false;
            }



        } else {
            return false;
        }
    }


    protected  function send_email($email='', $email_reply_message='', $email_reply_subject='', $email_reply_message_header='')
    {
        
        set_email_config();
        if(empty($email) || empty($email_reply_message) || empty($email_reply_subject) ) return ['error'=>true,'message'=>__('Missing params.')];
        try
        {
            Mail::to($email)->send(new SimpleHtmlEmail($email_reply_message_header,$email_reply_message,$email_reply_subject));
            return ['error'=>false,'message'=>__('Email sent successfully.')];
        }
        catch(\Swift_TransportException $e){
            return ['error'=>true,'message'=>$e->getMessage()];
        }
        catch(\GuzzleHttp\Exception\RequestException $e){
            return ['error'=>true,'message'=>$e->getMessage()];
        }
        catch(Exception $e) {
            return ['error'=>true,'message'=>$e->getMessage()];
        }

    }



        //==========================================================================
    //=======================USAGE LOG & LICENSE FUNCTIONS======================

    public function _insert_usage_log($moduleId = 0, $usageCount = 0, $userId = 0)
    {
        if ($moduleId == 0 || $usageCount == 0) {
            return false;
        }

        if ($userId == 0) {
            // $userId = session()->get('user_id');
            $userId = Auth::id();
        }

        if ($userId == 0 || $userId == '') {
            return false;
        }

        $usageMonth = date('n');
        $usageYear = date('Y');
        $where = [
            'module_id' => $moduleId,
            'user_id' => $userId,
            'usage_month' => $usageMonth,
            'usage_year' => $usageYear,
        ];

        $insertData = [
            'module_id' => $moduleId,
            'user_id' => $userId,
            'usage_month' => $usageMonth,
            'usage_year' => $usageYear,
            'usage_count' => $usageCount,
        ];

        if (DB::table('usage_log')->where($where)->exists()) {
            DB::table('usage_log')->where($where)->increment('usage_count', $usageCount);
        } else {
            DB::table('usage_log')->insert($insertData);
        }

        return true;
    }


    protected function delete_usage_log($module_id=0,$usage_count=0,$user_id=0)
    {
        if($module_id==0 || $usage_count==0) return false;
        if($user_id==0) $user_id=Auth::user()->id;
        if($user_id==0 || $user_id=="") return false;

        $usage_month=date("n");
        $usage_year=date("Y");

        $where=array("module_id"=>$module_id,"user_id"=>$user_id,"usage_month"=>$usage_month,"usage_year"=>$usage_year);

        // insert new entry if not exit, decrement usage_count otherwise
        $usage_log = DB::table('usage_log')->firstOrNew($where);
        if($usage_log) $usage_log->usage_count = ($usage_log->usage_count - $usage_count);
        else $usage_log->usage_count = 0;
        $usage_log->save();

        return true;
    }

    protected function _check_usage($module_id=0,$request=0,$user_id=0)
    {
        if($this->is_admin) return '1';

        if($module_id==0 || $request==0) return "0";
        if($user_id==0) $user_id=$this->user_id;
        if($user_id==0 || $user_id=="") return false;

        $usage_month=date("n");
        $usage_year=date("Y");

        $module = DB::table('modules')->select('extra_text')->where('id',$module_id)->first();
        $extra_text = $module->extra_text;

        if($extra_text=="") $where = [
            ['module_id', '=', $module_id],
            ['user_id', '=', $user_id]
        ];
        else $where = [
            ['module_id', '=', $module_id],
            ['user_id', '=', $user_id],
            ['usage_month', '=', $usage_month],
            ['usage_year', '=', $usage_year]
        ];

        $usage_count = DB::table('usage_log')->where($where)->sum('usage_count');

        $monthly_limit=array();
        $bulk_limit=array();
        $module_ids=array();

        $package_id = $this->current_package;
        $package_info = DB::table('package')->where('id',$package_id)->first();

        if(isset($package_info->bulk_limit))    $bulk_limit=json_decode($package_info->bulk_limit,true);
        if(isset($package_info->monthly_limit)) $monthly_limit=json_decode($package_info->monthly_limit,true);
        if(isset($package_info->module_ids))    $module_ids=explode(',', $package_info->module_ids);

        $return = "0";
        if(in_array($module_id, $module_ids) && $bulk_limit[$module_id] > 0 && $bulk_limit[$module_id]<$request)
            $return = "2"; // bulk limit crossed | 0 means unlimited
        else if(in_array($module_id, $module_ids) && $monthly_limit[$module_id] > 0 && $monthly_limit[$module_id]<($request+$usage_count))
            $return = "3"; // montly limit crossed | 0 means unlimited
        else  $return = "1"; //success

        return $return;
    }

    protected function print_limit_message($module_id=0,$request=0)
    {
        $status=$this->check_usage($module_id,$request);
        if($status=="2") {
            Session::flash('module_limit_exceed_message', __("Sorry, bulk action limit has been exceeded for this module."));
            return false;
        }
        else if($status=="3") {
            Session::flash('module_limit_exceed_message', __("Sorry, usage limit has been exceeded for this module."));
            return false;
        }
        return true;
    }


    public function _grab_auction_list_data()
    {
        $url="http://www.namejet.com/download/StandardAuctions.csv";
        if (!file_exists(storage_path("app/public/download/expired_domain"))) {
            mkdir(storage_path("app/public/download/expired_domain"), 0777, true);
        }
        $save_path = storage_path('app/public/download/expired_domain/');
        $fp = fopen($save_path.basename($url), 'w');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        $data = curl_exec($ch);
        curl_close($ch);
        fclose($fp);


          $read_handle=fopen($save_path.basename($url),"r");
          $i=0;
          while (!feof($read_handle) )
          {

                $information = fgetcsv($read_handle);


                if($i!=0)
                {
                    $domain_name=$information[0];
                    $auction_end_date =$information[1];


                      if($domain_name!="")
                      {
                        $auction_end_date = date('Y-m-d:H:i:s',strtotime($auction_end_date));
                        $insert_data=array(
                                    'domain_name'        => $domain_name,
                                    'auction_type'       => "public_auction",
                                    'auction_end_date'   =>$auction_end_date,
                                    'sync_at'            => date("Y-m-d")
                                    );


                     DB::table('expired_domain_list')->insert($insert_data);
                    }

                }
                $i++;
           }

            $current_date = date("Y-m-d");
            $three_days_before = date("Y-m-d", strtotime("$current_date - 3 days"));
            DB::table("expired_domain_list")->where("sync_at",'<', $three_days_before)->delete();
    }


        //================================================================
    //========================= ADDON FUNCTIONS ======================
    //loads language files of addons
    protected function language_loader_addon(Request $request)
    {

        $controller_name=strtolower($request->segment(1));
        $path_without_filename="application/modules/".$controller_name."/language/".$this->language."/";
        if(file_exists($path_without_filename.$controller_name."_lang.php"))
        {
            $filename=$controller_name;
            __($filename,$this->language,FALSE,TRUE,$path_without_filename);
        }

    }

    // delete any direcory with it childs even it is not empty
    protected function delete_directory($dirPath="")
    {
        if (!is_dir($dirPath))
        return false;

        if(substr($dirPath, strlen($dirPath) - 1, 1) != '/') $dirPath .= '/';

        $files = glob($dirPath . '*', GLOB_MARK);
        foreach($files as $file)
        {
            if(is_dir($file)) $this->delete_directory($file);
            else @unlink($file);
        }
        rmdir($dirPath);
    }

    // takes addon controller path as input and extract add on data from comment block
    protected function get_addon_data($path="")
    {
        $path=str_replace('\\','/',$path);
        $tokens=token_get_all(file_get_contents($path));
        $addon_data=array();

        $addon_path=explode('/', $path);
        $controller_name=array_pop($addon_path);
        array_pop($addon_path);
        $addon_path=implode('/',$addon_path);

        $comments = array();
        foreach($tokens as $token)
        {
            if($token[0] == T_COMMENT || $token[0] == T_DOC_COMMENT)
            {
                $comments[] = isset( $token[1]) ?  $token[1] : "";
            }
        }
        $comment_str=isset($comments[0]) ? $comments[0] : "";

        preg_match( '/^.*?addon name:(.*)$/mi', $comment_str, $match);
        $addon_data['addon_name'] = isset($match[1]) ? trim($match[1]) : "";

        preg_match( '/^.*?unique name:(.*)$/mi', $comment_str, $match);
        $addon_data['unique_name'] = isset($match[1]) ? trim($match[1]) : "";

        preg_match( '#modules:(.*?)Project ID#si', $comment_str, $match);
        $addon_data['modules'] = isset($match[1]) ? trim($match[1]) : "";

        preg_match( '/^.*?project id:(.*)$/mi', $comment_str, $match);
        $addon_data['project_id'] = isset($match[1]) ? trim($match[1]) : "";

        preg_match( '/^.*?addon uri:(.*)$/mi', $comment_str, $match);
        $addon_data['addon_uri'] = isset($match[1]) ? trim($match[1]) : "";

        preg_match( '/^.*?author:(.*)$/mi', $comment_str, $match);
        $addon_data['author'] = isset($match[1]) ? trim($match[1]) : "";

        preg_match( '/^.*?author uri:(.*)$/mi', $comment_str, $match);
        $addon_data['author_uri'] = isset($match[1]) ? trim($match[1]) : "";

        preg_match( '/^.*?version:(.*)$/mi', $comment_str, $match);
        $addon_data['version'] = isset($match[1]) ? trim($match[1]) : "1.0";

        preg_match( '/^.*?description:(.*)$/mi', $comment_str, $match);
        $addon_data['description'] = isset($match[1]) ? trim($match[1]) : "";

        $addon_data['controller_name'] = isset($controller_name) ? trim($controller_name) : "";

        if(file_exists($addon_path.'/install.txt'))
        $addon_data['installed']='0';
        else $addon_data['installed']='1';

        return $addon_data;
    }

    // checks purchase code , returns boolean
    protected function addon_credential_check($purchase_code="",$item_name="")
    {
		return json_encode(['status'=>1]);
    }

    // validataion of addon data
    protected function check_addon_data($addon_data=array())
    {
        if(!isset($addon_data['unique_name']) || $addon_data['unique_name']=="")
        {
            echo json_encode(array('status'=>'0','message'=>__('Add-on unique name has not been provided.')));
            exit();
        }

        if(!$this->is_unique_check("addon_check",$addon_data['unique_name']))  //  unique name must be unique
        {
            echo json_encode(array('status'=>'0','message'=>__('Add-on is already active. Duplicate unique name found.')));
            exit();
        }
    }

    // inserts data to add_ons table + modules + menu + menuchild1 + removes install.txt, returns json status,message
    protected function register_addon($addon_controller_name="",$sidebar=array(),$sql=array(),$purchase_code="",$default_module_name="")
    {
        if(Auth::user()->user_type != 'Admin')
        {
            echo json_encode(array('status'=>'0','message'=>__('Access Forbidden')));
            exit();
        }

        if(config('app.is_demo') == '1')
        {
            echo json_encode(array('status'=>'0','message'=>__('Access Forbidden')));
            exit();
        }

        if($addon_controller_name=="")
        {
            echo json_encode(array('status'=>'0','message'=>__('Add-on controller has not been provided.')));
            exit();
        }

        $path=APPPATH."modules/".strtolower($addon_controller_name)."/controllers/".$addon_controller_name.".php"; // path of addon controller
        $install_txt_path=APPPATH."modules/".strtolower($addon_controller_name)."/install.txt"; // path of install.txt
        if(!file_exists($path))
        {
            echo json_encode(array('status'=>'0','message'=>__('Add-on controller not found.')));
            exit();
        }

        $addon_data=$this->get_addon_data($path);

        $this->check_addon_data($addon_data);

        try
        {
            $this->db->trans_start();

            // addon table entry
            $this->basic->insert_data("add_ons",array("add_on_name"=>$addon_data['addon_name'],"unique_name"=>$addon_data["unique_name"],"version"=>$addon_data["version"],"installed_at"=>date("Y-m-d H:i:s"),"purchase_code"=>$purchase_code,"module_folder_name"=>strtolower($addon_controller_name),"project_id"=>$addon_data["project_id"]));
            $add_ons_id=$this->db->insert_id();

            $parent_module_id="";
            $modules = isset($addon_data['modules']) ? json_decode(trim($addon_data['modules']),true) : array();

            if(json_last_error() === 0 && is_array($modules))
            {
                $module_ids = array_keys($modules);
                $parent_module_id=implode(',', $module_ids);

                foreach($modules as $key => $value)
                {
                    if(!$this->basic->is_exist("modules",array("id"=>$key)))
                    $this->basic->insert_data("modules",array("id"=>$key,"extra_text"=>$value['extra_text'],"module_name"=>$value['module_name'],'bulk_limit_enabled'=>$value['bulk_limit_enabled'],'limit_enabled'=>$value['limit_enabled'],"add_ons_id"=>$add_ons_id,"deleted"=>"0"));
                }
            }

            //--------------- sidebar entry--------------------
            //-------------------------------------------------
            if(is_array($sidebar))
            foreach ($sidebar as $key => $value)
            {
                $parent_name        = isset($value['name']) ? $value['name'] : "";
                $parent_icon        = isset($value['icon']) ? $value['icon'] : "";
                $parent_url         = isset($value['url']) ? $value['url'] : "#";
                $parent_is_external = isset($value['is_external']) ? $value['is_external'] : "0";
                $child_info         = isset($value['child_info']) ? $value['child_info'] : array();
                $have_child         = isset($child_info['have_child']) ? $child_info['have_child'] : '0';
                $only_admin         = isset($value['only_admin']) ? $value['only_admin'] : '0';
                $only_member        = isset($value['only_member']) ? $value['only_member'] : '0';
                $parent_serial      = 50;

                $parent_menu=array('name'=>$parent_name,'icon'=>$parent_icon,'url'=>$parent_url,'serial'=>$parent_serial,'module_access'=>$parent_module_id,'have_child'=>$have_child,'only_admin'=>$only_admin,'only_member'=>$only_member,'add_ons_id'=>$add_ons_id,'is_external'=>$parent_is_external);
                $this->basic->insert_data('menu',$parent_menu); // parent menu entry
                $parent_id=$this->db->insert_id();

                if($have_child=='1')
                {
                    if(!empty($child_info))
                    {
                        $child = isset($child_info['child']) ? $child_info['child'] : array();

                        $child_serial=0;
                        if(!empty($child))
                        foreach ($child as $key2 => $value2)
                        {
                            $child_serial++;
                            $child_name         = isset($value2['name']) ? $value2['name'] : "";
                            $child_icon         = isset($value2['icon']) ? $value2['icon'] : "";
                            $child_url          = isset($value2['url']) ? $value2['url'] : "#";
                            $child_info_1       = isset($value2['child_info']) ? $value2['child_info'] : array();
                            $child_is_external  = isset($value2['is_external']) ? $value2['is_external'] : "0";
                            $have_child         = isset($child_info_1['have_child']) ? $child_info_1['have_child'] : '0';
                            $only_admin         = isset($value2['only_admin']) ? $value2['only_admin'] : '0';
                            $only_member        = isset($value2['only_member']) ? $value2['only_member'] : '0';
                            $module_access      = isset($value2['module_access']) ? $value2['module_access'] : '';
                            if($module_access=='') $module_access = $parent_module_id;

                            $child_menu=array('name'=>$child_name,'icon'=>$child_icon,'url'=>$child_url,'serial'=>$child_serial,'module_access'=>$module_access,'parent_id'=>$parent_id,'have_child'=>$have_child,'only_admin'=>$only_admin,'only_member'=>$only_member,'is_external'=>$child_is_external);
                            $this->basic->insert_data('menu_child_1',$child_menu); // child menu entry
                            $sub_parent_id=$this->db->insert_id();

                            if($have_child=='1')
                            {
                                if(!empty($child_info_1))
                                {
                                    $child = isset($child_info_1['child']) ? $child_info_1['child'] : array();

                                    $child_child_serial=0;
                                    if(!empty($child))
                                    foreach ($child as $key3 => $value3)
                                    {
                                        $child_child_serial++;
                                        $child_name         = isset($value3['name']) ? $value3['name'] : "";
                                        $child_icon         = isset($value3['icon']) ? $value3['icon'] : "";
                                        $child_url          = isset($value3['url']) ? $value3['url'] : "#";
                                        $child_is_external  = isset($value3['is_external']) ? $value3['is_external'] : "0";
                                        $have_child         = '0';
                                        $only_admin         = isset($value3['only_admin']) ? $value3['only_admin'] : '0';
                                        $only_member        = isset($value3['only_member']) ? $value3['only_member'] : '0';
                                        $module_access2     = isset($value3['module_access']) ? $value3['module_access'] : '';
                                        if($module_access2=='') $module_access2 = $module_access;

                                        $child_menu=array('name'=>$child_name,'icon'=>$child_icon,'url'=>$child_url,'serial'=>$child_child_serial,'module_access'=>$module_access2,'parent_child'=>$sub_parent_id,'only_admin'=>$only_admin,'only_member'=>$only_member,'is_external'=>$child_is_external);
                                        $this->basic->insert_data('menu_child_2',$child_menu); // child menu entry

                                    }
                                }
                            }
                        }
                    }
                }

            }
            //--------------- sidebar entry--------------------
            //-------------------------------------------------

            $this->db->trans_complete();


            if ($this->db->trans_status() === FALSE)
            {
                echo json_encode(array('status'=>'0','message'=>__('Database error. Something went wrong.')));
                exit();
            }
            else
            {

                //--------Custom SQL------------
                $this->db->db_debug = FALSE; //disable debugging for queries
                if(is_array($sql))
                foreach ($sql as $key => $query)
                {
                    try
                    {
                        $this->db->query($query);
                    }
                    catch(Exception $e)
                    {
                    }
                }
                //--------Custom SQL------------
                @unlink($install_txt_path); // removing install.txt
                echo json_encode(array('status'=>'1','message'=>__('Add-on has been activated successfully.')));
            }

        } //end of try
        catch(Exception $e)
        {
            $error = $e->getMessage();
            echo json_encode(array('status'=>'0','message'=>__($error)));
        }
    }

    // deletes data from add_ons table + modules + menu + menuchild1 + puts install.txt, returns json status,message
    protected function unregister_addon($addon_controller_name="")
    {
        if(Auth::user()->user_type != 'Admin')
        {
            echo json_encode(array('status'=>'0','message'=>__('Access Forbidden')));
            exit();
        }

        if(config('app.is_demo') == '1')
        {
            echo json_encode(array('status'=>'0','message'=>__('Access Forbidden')));
            exit();
        }


        if($addon_controller_name=="")
        {
            echo json_encode(array('status'=>'0','message'=>__('Add-on controller has not been provided.')));
            exit();
        }

        $path=APPPATH."modules/".strtolower($addon_controller_name)."/controllers/".$addon_controller_name.".php"; // path of addon controller
        $install_txt_path=APPPATH."modules/".strtolower($addon_controller_name)."/install.txt"; // path of install.txt
        if(!file_exists($path))
        {
            echo json_encode(array('status'=>'0','message'=>__('Add-on controller not found.')));
            exit();
        }

        $addon_data=$this->get_addon_data($path);

        if(!isset($addon_data['unique_name']) || $addon_data['unique_name']=="")
        {
            echo json_encode(array('status'=>'0','message'=>__('Add-on unique name has not been provided.')));
            exit();
        }


        try
        {
            $this->db->trans_start();

            // delete addon table entry
            $get_addon=$this->basic->get_data("add_ons",array("where"=>array("unique_name"=>$addon_data['unique_name'])));
            $add_ons_id=isset($get_addon[0]['id']) ? $get_addon[0]['id'] : 0;
            if($add_ons_id>0)
            $this->basic->delete_data("add_ons",array("id"=>$add_ons_id));

            // delete modules table entry
            if($add_ons_id>0)
            $this->basic->delete_data("modules",array("add_ons_id"=>$add_ons_id));

            // delete menu+menu_child1 table entry
            $get_menu=array();
            if($add_ons_id>0)
            $get_menu=$this->basic->get_data("menu",array("where"=>array("add_ons_id"=>$add_ons_id)));

            foreach($get_menu as $key => $value)
            {
               $parent_id=isset($value['id']) ? $value['id'] : 0;
               if($parent_id>0)
               {
                  $this->basic->delete_data("menu",array("id"=>$parent_id));
                  $this->basic->delete_data("menu_child_1",array("parent_id"=>$parent_id));
               }
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE)
            {
                echo json_encode(array('status'=>'0','message'=>__('Database error. Something went wrong.')));
                exit();
            }
            else
            {
                if(!file_exists($install_txt_path)) // putting install.txt
                fopen($install_txt_path, "w");

                echo json_encode(array('status'=>'1','message'=>__('Add-on has been deactivated successfully.')));
            }
        }
        catch(Exception $e)
        {
            $error = $e->getMessage();
            echo json_encode(array('status'=>'0','message'=>__($error)));
        }
    }

    // deletes data from add_ons table + modules + menu + menuchild1 + custom sql + folder, returns json status,message
    protected function delete_addon($addon_controller_name="",$sql=array())
    {
        if(Auth::user()->user_type != 'Admin')
        {
            echo json_encode(array('status'=>'0','message'=>__('Access Forbidden')));
            exit();
        }

        if(config('app.is_demo') == '1')
        {
            echo json_encode(array('status'=>'0','message'=>__('Access Forbidden')));
            exit();
        }

        if($addon_controller_name=="")
        {
            echo json_encode(array('status'=>'0','message'=>__('Add-on controller has not been provided.')));
            exit();
        }

        $path=APPPATH."modules/".strtolower($addon_controller_name)."/controllers/".$addon_controller_name.".php"; // path of addon controller
        $addon_path=APPPATH."modules/".strtolower($addon_controller_name); // path of module folder
        if(!file_exists($path))
        {
            echo json_encode(array('status'=>'0','message'=>__('Add-on controller not found.')));
            exit();
        }

        $addon_data=$this->get_addon_data($path);

        if(!isset($addon_data['unique_name']) || $addon_data['unique_name']=="")
        {
            echo json_encode(array('status'=>'0','message'=>__('Add-on unique name has not been provided.')));
            exit();
        }


        try
        {
            $this->db->trans_start();

            // delete addon table entry
            $get_addon=$this->basic->get_data("add_ons",array("where"=>array("unique_name"=>$addon_data['unique_name'])));
            $add_ons_id=isset($get_addon[0]['id']) ? $get_addon[0]['id'] : 0;
            $purchase_code=isset($get_addon[0]['purchase_code']) ? $get_addon[0]['purchase_code'] : '';
            if($add_ons_id>0)
            $this->basic->delete_data("add_ons",array("id"=>$add_ons_id));

            // delete modules table entry
            if($add_ons_id>0)
            $this->basic->delete_data("modules",array("add_ons_id"=>$add_ons_id));

            // delete menu+menu_child1 table entry
            $get_menu=array();
            if($add_ons_id>0)
            $get_menu=$this->basic->get_data("menu",array("where"=>array("add_ons_id"=>$add_ons_id)));

            foreach($get_menu as $key => $value)
            {
               $parent_id=isset($value['id']) ? $value['id'] : 0;
               if($parent_id>0)
               {
                  $this->basic->delete_data("menu",array("id"=>$parent_id));
                  $this->basic->delete_data("menu_child_1",array("parent_id"=>$parent_id));
               }
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE)
            {
                echo json_encode(array('status'=>'0','message'=>__('Database error. Something went wrong.')));
                exit();
            }
            else
            {
                //--------Custom SQL------------
                $this->db->db_debug = FALSE; //disable debugging for queries
                if(is_array($sql))
                foreach ($sql as $key => $query)
                {
                    try
                    {
                        $this->db->query($query);
                    }
                    catch(Exception $e)
                    {
                    }
                }
                //--------Custom SQL------------

                $this->delete_directory($addon_path);

                echo json_encode(array('status'=>'1','message'=>__('add-on has been deleted successfully.')));
            }
        }
        catch(Exception $e)
        {
            $error = $e->getMessage();
            echo json_encode(array('status'=>'0','message'=>__($error)));
        }
    }


    // check a addon or module id is usable or already used, returns boolean, true if unique
    protected function is_unique_check($type='addon_check',$value="") // type=addon_check/module_check | $value=column.value
    {
        $is_unique=false;
        if($type=="addon_check")  $is_unique=$this->basic->is_unique("add_ons",array("unique_name"=>$value),"id");
        if($type=="module_check") $is_unique=$this->basic->is_unique("modules",array("id"=>$value),"id");
        return $is_unique;
    }

    //========================= ADDON FUNCTIONS ======================
    //================================================================



    //========================= PAYMENT FUNCTIONS ======================
    //================================================================

    protected function get_payment_validity_data($buyer_user_id=0,$package_id=0)
    {
        $package_data = $this->get_package($package_id,['package_name','price','validity','discount_data','product_data']);
        $package_name = $package_data->package_name ?? '';
        $discount_data = $package_data->discount_data ?? null;
        $product_data = $package_data->product_data ?? null;
        $price = $package_data->price ?? 0;
        $validity = $package_data->validity ?? 0;
        $validity_str='+'.$validity.' day';

        $prev_payment_info = DB::table('transaction_logs')->select('cycle_start_date','cycle_expired_date')
            ->where(['buyer_user_id'=>$buyer_user_id])->whereNotNull('package_id')
            ->orderByRaw('id DESC')->first();
        $prev_cycle_expired_date = $prev_payment_info->cycle_expired_date ?? '';
        $cycle_start_date = $cycle_expired_date = date('Y-m-d');
        if(empty($prev_cycle_expired_date)) $cycle_expired_date = date("Y-m-d",strtotime($validity_str,strtotime($cycle_start_date)));
        else if (strtotime($prev_cycle_expired_date) <= strtotime(date('Y-m-d'))) $cycle_expired_date = date("Y-m-d",strtotime($validity_str,strtotime($cycle_start_date)));
        else if (strtotime($prev_cycle_expired_date) > strtotime(date('Y-m-d')))
        {
            $cycle_start_date = date("Y-m-d",strtotime('+1 day',strtotime($prev_cycle_expired_date)));
            $cycle_expired_date = date("Y-m-d",strtotime($validity_str,strtotime($cycle_start_date)));
        }

        $user_data = DB::table("users")->where(['id'=>$buyer_user_id])->select('id','email','name')->first();
        $parent_user_id = $user_data->id ?? 0;
        $email = $user_data->email ?? '';
        $name = $user_data->name ?? '';

        return ['parent_user_id'=>$parent_user_id,'email'=>$email,'name'=>$name,'package_name'=>$package_name,'price'=>$price,'cycle_start_date'=>$cycle_start_date,'cycle_expired_date'=>$cycle_expired_date,'validity'=>$validity,'discount_data'=>$discount_data,'product_data'=>$product_data];
    }

    protected function get_package($id=0,$select='*',$where='')
    {
        if($id==0) $id = Auth::user()->package_id;
        if(empty($where)) $where = ['id'=>$id];
        return DB::table('package')->select($select)->where($where)->first();
    }

    protected function get_payment_config_parent($parent_user_id=0,$select='*')
    {
        if($parent_user_id == 0) $parent_user_id = '1';
    
        return DB::table('settings_payments')->select($select)->addSelect('settings_payments.currency')->where(['user_id'=>$parent_user_id,'users.status'=>'1','users.deleted'=>'0'])->leftJoin('users', 'users.id', '=', 'settings_payments.user_id')->first();
       
    }

    protected function complete_payment($insert_data=[],$payment_type='')
    {
        $curtime = date("Y-m-d H:i:s");
        $last_payment_method = $payment_type;
        $user_email = $insert_data['user_email'] ?? '';
        $user_name = $insert_data['user_name'] ?? '';
        $package_name = $insert_data['package_name'] ?? '';
        $package_id = $insert_data['package_id'] ?? null;
        $paid_currency = $insert_data['paid_currency'] ?? "USD";
        $paid_amount = $insert_data['paid_amount'] ?? 0;
        $buyer_user_id = $insert_data['buyer_user_id'] ?? 0;
        $parent_user_id = $insert_data['user_id'] ?? 0;
        $cycle_expired_date = $insert_data['cycle_expired_date'] ?? null;
        $paypal_next_check_time = $insert_data['paypal_next_check_time'] ?? null;
        $update_data = array
        (
            "updated_at"=>$curtime,
            "purchase_date"=>$curtime,
            "last_payment_method"=>$last_payment_method
        );
        if(!empty($paypal_next_check_time)) $update_data['paypal_next_check_time'] = $paypal_next_check_time;
        if(!empty($cycle_expired_date)) $update_data['expired_date'] = $cycle_expired_date;
        if(!empty($package_id)) $update_data['package_id'] = $package_id;
        $update_data['user_type'] =  'Member';

        $error = false;
        try {
            DB::beginTransaction();
            unset($insert_data['user_email']);
            unset($insert_data['user_name']);
            if(isset($insert_data['paypal_next_check_time'])) unset($insert_data['paypal_next_check_time']);
            DB::table('transaction_logs')->insert($insert_data);
            DB::table('users')->where(['id'=>$buyer_user_id])->update($update_data);

            $insert_data = [
                'title'=> __('Payment Confirmation'),
                'description'=> __('We have received your payment of')." {$paid_currency} {$paid_amount}",
                'created_at' => date("Y-m-d H:i:s"),
                'user_id' => $buyer_user_id,
                'color_class' => 'success',
                'icon' => 'fas fa-shopping-bag',
                'is_seen' => '0'
            ];
            DB::table("announcement")->insert($insert_data);
            $insert_data['title'] = __('New Payment Received');
            $insert_data['description'] =  __('You have received a payment of')." {$paid_currency} {$paid_amount}";
            $insert_data['user_id'] =  $parent_user_id;
            $insert_data['icon'] =  'fas fa-dollar-sign';
            DB::table("announcement")->insert($insert_data);

            DB::commit();
        }
        catch (\Throwable $e){
            DB::rollBack();
            $error = true;
            $error_message = $e->getMessage();
        }

        if($error) dd($error_message);
        else
        {
            // $user_info = DB::table('users')->where(['id'=>$buyer_user_id])->select('under_which_affiliate_user')->first();
            // if($user_info->under_which_affiliate_user != 0)
            //     $this->affiliate_commission($user_info->under_which_affiliate_user,$buyer_user_id,$event='payment',$paid_amount);
            $param_subject = __('Payment Confirmation');
            $param_name = 'Hello'.' '.$user_name;
            $param_message = __("Congratulation, We have received your payment of")." {$paid_currency} {$paid_amount} ({$package_name}) ".__("New billing cycle will continue until")." {$cycle_expired_date}.";
            if(!empty($user_email)) Mail::to($user_email)->send(new SimpleHtmlEmail($param_name,$param_message,$param_subject));

            $parent_userdata = DB::table('users')->select('email','name')->where(['id'=>$parent_user_id])->first();
            $param_subject = __('New Payment Received');
            $admin_email = $parent_userdata->email ?? '';
            $admin_name = $parent_userdata->name ?? '';
            $param_name = 'Hello'.' '.$admin_name;
            $param_message = __("Congratulation, You have received a new payment of")." {$paid_currency} {$paid_amount} ({$package_name}).".__("The payment was sent by")." : {$user_name}.";
            if(!empty($admin_email)) Mail::to($admin_email)->send(new SimpleHtmlEmail($param_name,$param_message,$param_subject));
        }
        return true;
    }

    protected function get_user($id=0,$select='*')
    {
        if($id==0) return null;
        $user_data = DB::table("users")->select($select)->where(['id' => $id])->first();
        return $user_data;
    }
    protected function get_payment_config($select='*')
    {
        return DB::table('settings_payments')->select($select)->first();
    }

    public function env_remove(){
        $envFilePath = base_path('.env');

        if (file_exists($envFilePath)) {
            $envFileContent = file_get_contents($envFilePath);
            $app_path = base_path('config/app.php');
            $database_path = base_path('config/database.php');
            $app_content = file_get_contents($app_path);
            $database_content = file_get_contents($database_path);

            $replacements = [
                "env('APP_KEY')" => "'".env('APP_KEY')."'",
                "env('APP_DEBUG', false)" => "'".(env('APP_DEBUG') ?? false)."'",
                "env('APP_NAME', 'Laravel')" => "'".(env('APP_NAME') ?? '')."'",
                "env('DB_HOST', '127.0.0.1')" => "'".(env('DB_HOST') ?? 'localhost')."'",
                "env('DB_PORT', '3306')" => "'".(env('DB_PORT') ?? '3306')."'",
                "env('DB_DATABASE', 'forge')" => "'".(env('DB_DATABASE') ?? '')."'",
                "env('DB_USERNAME', 'forge')" => "'".(env('DB_USERNAME') ?? '')."'",
                "env('DB_PASSWORD', '')" => "'".(env('DB_PASSWORD') ?? '')."'",
            ];

            foreach ($replacements as $search => $replace) {
                $app_content = str_replace($search, $replace, $app_content);
                $database_content = str_replace($search, $replace, $database_content);
            }

            $app_final_content = file_put_contents($app_path, $app_content);
            $database_final_content = file_put_contents($database_path, $database_content);

            $check_app_data = config('app');
            $check_database_data = config('database');
            if(isset($check_app_data) && isset($check_database_data))
            {
                $app_key = $check_app_data['key'] ?? '';
                $db_name = $check_database_data['connections']['mysql']['database'] ?? '';
                if (
                    $app_final_content && 
                    $database_final_content &&
                    $app_key == (env('APP_KEY')) &&
                    $db_name == (env('DB_DATABASE'))
                ){
                    @unlink($envFilePath);
                    \Artisan::call('clear-compiled');
                    \Artisan::call('cache:clear');
                    \Artisan::call('config:clear');
                    
                }
            }

        }
    }

}
