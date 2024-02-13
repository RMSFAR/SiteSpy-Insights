<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\SimpleHtmlEmail;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Services\Payment\MollieServiceInterface;
use App\Services\Payment\PaypalServiceInterface;
use App\Services\Payment\StripeServiceInterface;
use App\Services\Payment\XenditServiceInterface;
use App\Services\Payment\PaymayaServiceInterface;
use App\Services\Payment\PaystackServiceInterface;
use App\Services\Payment\RazorpayServiceInterface;
use App\Services\Payment\YoomoneyServiceInterface;
use App\Services\Payment\InstamojoServiceInterface;
use App\Services\Payment\SenangpayServiceInterface;
use App\Services\Payment\FlutterwaveServiceInterface;
use App\Services\Payment\ToyyibpayServiceInterface;
use App\Services\Payment\MyfatoorahServiceInterface;
use App\Services\Payment\MercadopagoServiceInterface;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController extends HomeController
{
    public $parent_user_id = '1';

    public function __construct(MollieServiceInterface $mollie_service, PaystackServiceInterface $paystack_service, RazorpayServiceInterface $razorpay_service,MyfatoorahServiceInterface $myfatoorah_sevice, MercadopagoServiceInterface $mercadopago_service, InstamojoServiceInterface $instamojo_service,PaymayaServiceInterface $paymaya_service,ToyyibpayServiceInterface $toyyibpay_service,XenditServiceInterface $xendit_service,SenangpayServiceInterface $senangpay_service,PaypalServiceInterface $paypal_service,StripeServiceInterface $stripe_service,YoomoneyServiceInterface $yoomoney_service,FlutterwaveServiceInterface $flutterwave_service)
    {

        $this->paypal = $paypal_service;
        $this->stripe = $stripe_service;
        $this->instamojo = $instamojo_service;
        $this->instamojo_v2 = $instamojo_service;
        $this->paymaya = $paymaya_service;
        $this->toyyibpay = $toyyibpay_service;
        $this->xendit = $xendit_service;
        $this->senangpay = $senangpay_service;
        $this->myfatoorah = $myfatoorah_sevice;
        $this->mercadopago = $mercadopago_service;
        $this->razorpay_class_ecommerce = $razorpay_service;
        $this->paystack_class_ecommerce = $paystack_service;
        $this->mollie_class_ecommerce = $mollie_service;
        $this->yoomoney = $yoomoney_service;
        $this->flutterwave = $flutterwave_service;
        $this->provider = new PayPalClient;
        
        
    }



    public function payment_settings()
    {     
        if(Auth::user()->user_type != 'Admin') return redirect()->route('login');
        if(check_build_version() != 'double') return redirect()->route('access_forbidden');
        $this->set_global_userdata(false,['Admin']);

        $data['body'] = "subscription/accounts";
        $data['page_title'] =__('Payment Accounts');
        $data['xdata'] = DB::table('settings_payments')->where(['user_id'=>Auth::user()->id])->first();
        $data['iframe'] = false;
        $data['load_datatable'] = true;
        // return $this->viewcontroller($data);
        return $this->_viewcontroller($data);     
    }


    public function payment_settings_action(Request $request)
    {
        // dd($request->all());

        if(config('app.is_demo')=='1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }

        if(check_build_version() == 'double'){
            $rules =
            [
                'currency' => 'required',
                'paypal_client_id' => 'required_if:paypal_status,1',
                'paypal_client_secret' => 'required_if:paypal_status,1',
                'stripe_secret_key' => 'required_if:stripe_status,1',
                'stripe_publishable_key' => 'required_if:stripe_status,1',
                'razorpay_key_id' => 'required_if:razorpay_status,1',
                'razorpay_key_secret' => 'required_if:razorpay_status,1',
                'paystack_secret_key' => 'required_if:paystack_status,1',
                'paystack_public_key' => 'required_if:paystack_status,1',
                'mercadopago_public_key' => 'required_if:mercadopago_status,1',
                'mercadopago_access_token' => 'required_if:mercadopago_status,1',
                'mercadopago_country' => 'required_if:mercadopago_status,1',
                'mollie_api_key' => 'required_if:mollie_status,1',
                'sslcommerz_store_id' => 'required_if:sslcommerz_status,1',
                'sslcommerz_store_password' => 'required_if:sslcommerz_status,1',
                'senangpay_merchent_id' => 'required_if:senangpay_status,1',
                'senangpay_secret_key' => 'required_if:senangpay_status,1',
                'instamojo_api_key' => 'required_if:instamojo_status,1',
                'instamojo_auth_token' => 'required_if:instamojo_status,1',
                'instamojo_client_id' => 'required_if:instamojo_v2_status,1',
                'instamojo_client_secret' => 'required_if:instamojo_v2_status,1',
                'toyyibpay_secret_key' => 'required_if:toyyibpay_status,1',
                'toyyibpay_category_code' => 'required_if:toyyibpay_status,1',
                'xendit_secret_api_key' => 'required_if:xendit_status,1',
                'myfatoorah_api_key' => 'required_if:myfatoorah_status,1',
                'paymaya_public_key' => 'required_if:paymaya_status,1',
                'paymaya_secret_key' => 'required_if:paymaya_status,1',
                'yoomoney_shop_id' => 'required_if:yoomoney_status,1',
                'yoomoney_secret_key' => 'required_if:yoomoney_status,1',
                'currency_position' => 'required|string',
                'decimal_point' => 'required|integer|min:0',
                'manual_payment_status' => 'nullable|sometimes|boolean',
                'manual_payment_instruction' => 'required_if:manual_payment_status,1|nullable|sometimes',
                'flutterwave_api_key' => 'required_if:flutterwave_status,1'
            ];
        }
        else {
            $rules =
            [
                'currency' => 'required',
                'currency_position' => 'required|string',
                'decimal_point' => 'required|integer|min:0',
                'manual_payment_status' => 'nullable|sometimes|boolean',
                'manual_payment_instruction' => 'required_if:manual_payment_status,1|nullable|sometimes'
            ];
        }

        $validate_data = $request->validate($rules);
        $validate_data['paypal_mode'] = isset($_POST['paypal_mode']) ? "sandbox" : "live";
        $validate_data['paypal_status'] = isset($_POST['paypal_status']) ? "1" : "0";
        $validate_data['paypal_app_id'] = '';
        if(check_build_version()=='double' && $validate_data['paypal_status']=='1'){
            
            $paypal_app_id_result = $this->paypal->paypal_get_app_id($validate_data['paypal_client_id'],$validate_data['paypal_client_secret'],$validate_data['paypal_mode']);
            if(isset($paypal_app_id_result->error)){
                  session()->flash('paypal_error', $paypal_app_id_result->error_description);
                  return redirect()->back();
            }
            else{
                $paypal_app_id = $paypal_app_id_result->app_id;
                $validate_data['paypal_app_id'] = $paypal_app_id;
            }
        }
        
        $validate_data['stripe_status'] = isset($_POST['stripe_status']) ? "1" : "0";
        $validate_data['razorpay_status'] = isset($_POST['razorpay_status']) ? "1" : "0";
        $validate_data['paystack_status'] = isset($_POST['paystack_status']) ? "1" : "0";
        $validate_data['mercadopago_status'] = isset($_POST['mercadopago_status']) ? "1" : "0";
        $validate_data['mollie_status'] = isset($_POST['mollie_status']) ? "1" : "0";
        $validate_data['sslcommerz_status'] = isset($_POST['sslcommerz_status']) ? "1" : "0";
        $validate_data['senangpay_status'] = isset($_POST['senangpay_status']) ? "1" : "0";
        $validate_data['instamojo_status'] = isset($_POST['instamojo_status']) ? "1" : "0";
        $validate_data['instamojo_v2_status'] = isset($_POST['instamojo_v2_status']) ? "1" : "0";
        $validate_data['toyyibpay_status'] = isset($_POST['toyyibpay_status']) ? "1" : "0";
        $validate_data['xendit_status'] = isset($_POST['xendit_status']) ? "1" : "0";
        $validate_data['myfatoorah_status'] = isset($_POST['myfatoorah_status']) ? "1" : "0";
        $validate_data['paymaya_status'] = isset($_POST['paymaya_status']) ? "1" : "0";
        $validate_data['yoomoney_status'] = isset($_POST['yoomoney_status']) ? "1" : "0";
        $validate_data['manual_payment_status'] = isset($_POST['manual_payment_status']) ? "1" : "0";
        $validate_data['manual_payment_instruction'] = isset($_POST['manual_payment_instruction']) ? $_POST['manual_payment_instruction'] : "";
        $validate_data['cod_enabled'] = isset($_POST['cod_enabled']) ? "1" : "0";
        $validate_data['flutterwave_status'] = isset($_POST['flutterwave_status']) ? "1" : "0";

        $validate_data['paypal_payment_type'] = isset($_POST['paypal_payment_type']) ? "recurring" : "manual";
        $validate_data['sslcommerz_mode'] = isset($_POST['sslcommerz_mode']) ? "sandbox" : "live";
        $validate_data['senangpay_mode'] = isset($_POST['senangpay_mode']) ? "sandbox" : "live";
        $validate_data['instamojo_mode'] = isset($_POST['instamojo_mode']) ? "sandbox" : "live";
        $validate_data['instamojo_v2_mode'] = isset($_POST['instamojo_v2_mode']) ? "sandbox" : "live";
        $validate_data['toyyibpay_mode'] = isset($_POST['toyyibpay_mode']) ? "sandbox" : "live";
        $validate_data['myfatoorah_mode'] = isset($_POST['myfatoorah_mode']) ? "sandbox" : "live";
        $validate_data['paymaya_mode'] = isset($_POST['paymaya_mode']) ? "sandbox" : "live";
        $validate_data['thousand_comma'] = isset($_POST['thousand_comma']) ? "1" : "0";

        $insert_data =
        array
        (
            'updated_at'=>date('Y-m-d H:i:s'),
            'manual_payment_status'=>$validate_data['manual_payment_status'],
            'manual_payment_instruction'=>$validate_data['manual_payment_instruction'],
            'currency'=>$validate_data['currency'],
            'decimal_point'=>$validate_data['decimal_point'],
            'thousand_comma'=>$validate_data['thousand_comma'],
            'currency_position'=>$validate_data['currency_position'],
            'user_id'=>Auth::user()->id,
            'cod_enabled' => $validate_data['cod_enabled'],
            'manual_payment_status' => $validate_data['manual_payment_status'],
            'manual_payment_instruction' => $validate_data['manual_payment_instruction'],

        );
        if(check_build_version()=='double'){
            $insert_data['yoomoney'] = json_encode(['yoomoney_shop_id'=>$validate_data['yoomoney_shop_id'],'yoomoney_secret_key'=>$validate_data['yoomoney_secret_key'],'yoomoney_status'=>$validate_data['yoomoney_status']]);
            $insert_data['paypal'] = json_encode(['paypal_client_id'=>$validate_data['paypal_client_id'],'paypal_client_secret'=>$validate_data['paypal_client_secret'],'paypal_app_id'=>$validate_data['paypal_app_id'],'paypal_status'=>$validate_data['paypal_status'],'paypal_mode'=>$validate_data['paypal_mode'],'paypal_payment_type'=>$validate_data['paypal_payment_type']]);
            $insert_data['stripe'] = json_encode(['stripe_secret_key'=>$validate_data['stripe_secret_key'],'stripe_publishable_key'=>$validate_data['stripe_publishable_key'],'stripe_status'=>$validate_data['stripe_status']]);
            $insert_data['razorpay'] = json_encode(['razorpay_key_id'=>$validate_data['razorpay_key_id'],'razorpay_key_secret'=>$validate_data['razorpay_key_secret'],'razorpay_status'=>$validate_data['razorpay_status']]);
            $insert_data['paystack'] = json_encode(['paystack_secret_key'=>$validate_data['paystack_secret_key'],'paystack_public_key'=>$validate_data['paystack_public_key'],'paystack_status'=>$validate_data['paystack_status']]);
            $insert_data['mercadopago'] = json_encode(['mercadopago_public_key'=>$validate_data['mercadopago_public_key'],'mercadopago_access_token'=>$validate_data['mercadopago_access_token'],'mercadopago_country'=>$validate_data['mercadopago_country'],'mercadopago_status'=>$validate_data['mercadopago_status']]);
            $insert_data['mollie'] = json_encode(['mollie_api_key'=>$validate_data['mollie_api_key'],'mollie_status'=>$validate_data['mollie_status']]);
            $insert_data['instamojo'] = json_encode(['instamojo_api_key'=>$validate_data['instamojo_api_key'],'instamojo_auth_token'=>$validate_data['instamojo_auth_token'],'instamojo_status'=>$validate_data['instamojo_status'],'instamojo_mode'=>$validate_data['instamojo_mode']]);
            $insert_data['instamojo_v2'] = json_encode(['instamojo_client_id'=>$validate_data['instamojo_client_id'],'instamojo_client_secret'=>$validate_data['instamojo_client_secret'],'instamojo_v2_status'=>$validate_data['instamojo_v2_status'],'instamojo_v2_mode'=>$validate_data['instamojo_v2_mode']]);
            $insert_data['sslcommerz'] = json_encode(['sslcommerz_store_id'=>$validate_data['sslcommerz_store_id'],'sslcommerz_store_password'=>$validate_data['sslcommerz_store_password'],'sslcommerz_status'=>$validate_data['sslcommerz_status'],'sslcommerz_mode'=>$validate_data['sslcommerz_mode']]);
            $insert_data['senangpay'] = json_encode(['senangpay_merchent_id'=>$validate_data['senangpay_merchent_id'],'senangpay_secret_key'=>$validate_data['senangpay_secret_key'],'senangpay_status'=>$validate_data['senangpay_status'],'senangpay_mode'=>$validate_data['senangpay_mode']]);
            $insert_data['toyyibpay'] = json_encode(['toyyibpay_secret_key'=>$validate_data['toyyibpay_secret_key'],'toyyibpay_category_code'=>$validate_data['toyyibpay_category_code'],'toyyibpay_status'=>$validate_data['toyyibpay_status'],'toyyibpay_mode'=>$validate_data['toyyibpay_mode']]);
            $insert_data['xendit'] = json_encode(['xendit_secret_api_key'=>$validate_data['xendit_secret_api_key'],'xendit_status'=>$validate_data['xendit_status']]);
            $insert_data['myfatoorah'] = json_encode(['myfatoorah_api_key'=>$validate_data['myfatoorah_api_key'],'myfatoorah_status'=>$validate_data['myfatoorah_status'],'myfatoorah_mode'=>$validate_data['myfatoorah_mode']]);
            $insert_data['paymaya'] = json_encode(['paymaya_public_key'=>$validate_data['paymaya_public_key'],'paymaya_secret_key'=>$validate_data['paymaya_secret_key'],'paymaya_status'=>$validate_data['paymaya_status'],'paymaya_mode'=>$validate_data['paymaya_mode']]);

            $insert_data['flutterwave'] = json_encode(['flutterwave_api_key'=>$validate_data['flutterwave_api_key'],'flutterwave_status'=>$validate_data['flutterwave_status']]);
        }

        $update_data = $insert_data;

        $xpayment_settings = $this->get_payment_config();
        $id = $xpayment_settings->id ?? 0;
        if($id>0) $query = DB::table('settings_payments')->where(['id'=>$id])->update($insert_data);
        else $query = DB::table('settings_payments')->insert($insert_data);


        $request->session()->flash('save_payment_accounts_status', '1');
        return redirect(route('accounts'));
    }

    public function earning_summary()
    {
        if(Auth::user()->user_type != 'Admin') return redirect()->route('login');
        if(check_build_version() != 'double') return redirect()->route('access_forbidden');
        $this->set_global_userdata(false,['Admin']);

        $user_data =  DB::table('users')->selectRaw('count(id) as total_user')->get();

        $year = date("Y");
        $lastyear = $year-1;
        $month = date("m");
        $date = date("Y-m-d");

        // $payment_result = $this->db->query("SELECT * FROM transaction_history WHERE  DATE_FORMAT(payment_date,'%Y')='{$year}' OR DATE_FORMAT(payment_date,'%Y')='{$lastyear}' ORDER BY payment_date DESC");
        $payment_result = DB::table('transaction_logs')
        ->whereRaw("DATE_FORMAT(paid_at, '%Y') = ? OR DATE_FORMAT(paid_at, '%Y') = ?", [$year, $lastyear])
        ->orderByDesc('paid_at')
        ->get();
        $payment_data=[];
        if(isset($payment_result))
            $payment_data = $payment_result->toArray();

        $payment_today=$payment_month=$payment_year=$payment_life=0;
        $array_month = array();
        $array_year = array();
        $this_year_earning=array();
        $last_year_earning=array();
        $this_year_top= array();
        $last_year_top= array();

        $month_names = array();
        for($m=1; $m<=$month; ++$m)
        {
            $name=date('M', mktime(0, 0, 0, $m, 1));
            $month_names[]=__($name);
            $this_year_earning[]=0;
            $last_year_earning[]=0;
        }

        foreach ($payment_data as $key => $value) 
        {
           $mon = date("F",strtotime($value->paid_at));
           $mon2 = date("m",strtotime($value->paid_at));

           if(strtotime($value->paid_at) == strtotime($date)) $payment_today += $value->paid_amount;

           if(date("m",strtotime($value->paid_at)) == $month && date("Y",strtotime($value->paid_at)) == $year) 
           {
                $payment_month += $value->paid_amount;
                $payment_date = date("jS M y",strtotime($value->paid_at));

                if(!isset($array_month[$payment_date])) $array_month[$payment_date] = 0;
                $array_month[$payment_date] += $value->paid_amount;
           }

           if(date("Y",strtotime($value->paid_at)) == $year) 
           {
                $payment_year += $value->paid_amount;
                $payment_life += $value->paid_amount;
                if(!isset($array_year[$mon])) $array_year[$mon] = 0;
                $array_year[$mon] += $value->paid_amount;

                if(isset($this_year_earning[$mon2-1])) $this_year_earning[$mon2-1] += $value->paid_amount;

                if(!isset($this_year_top[$value->country])) $this_year_top[$value->country] = 0;
                $this_year_top[$value->country] += $value->paid_amount;
           }

           if(date("Y",strtotime($value->paid_at)) == $lastyear) 
           {
                 if(isset($last_year_earning[$mon2-1])) $last_year_earning[$mon2-1] += $value->paid_amount;

                if(!isset($last_year_top[$value->country])) $last_year_top[$value->country] = 0;
                $last_year_top[$value->country] += $value->paid_amount;
           }
        }
        arsort($this_year_top);
        arsort($last_year_top);

        $data['payment_today'] = $payment_today;
        $data['payment_month'] = $payment_month;
        $data['payment_year'] = $payment_year;
        $data['payment_life'] = $payment_life;
        $data['array_month'] = $array_month;
        $data['array_year'] = $array_year;
        $data['month_names'] = $month_names;
        $data['this_year_earning'] = $this_year_earning;
        $data['last_year_earning'] = $last_year_earning;
        $data['year'] = $year;
        $data['lastyear'] = $lastyear;
        $data['this_year_top'] = $this_year_top;
        $data['last_year_top'] = $last_year_top;
        $data['country_names'] = get_country_names();

        $data['user_data'] = $user_data[0]->total_user;

        $data['body'] = 'subscription/earning-summary';
        $data['page_title'] =__("Earning Summary");

        $config_data=DB::table('settings_payments')->get();
        $config_data=json_decode(json_encode($config_data));
        $currency=isset($config_data[0]->currency)?$config_data[0]->currency:"USD";
        $currency_icons = $this->currency_icon();
        $data["curency_icon"]= isset($currency_icons[$currency])?$currency_icons[$currency]:"$";
        $data["currency"]= $currency;

        return $this->_viewcontroller($data);     
    }

    public function select_package()
    {
        if(check_build_version() == 'double' && Auth::user()->user_type == 'Member')
        {
           $data['body'] = "member/buy-package";
           $data['page_title'] = __('Buy Package');

           $config_data=DB::table('settings_payments')->get();
           $currency=isset($config_data[0]->currency)?$config_data[0]->currency:"USD";
           $currency_icons = $this->currency_icon();
           $data["currency"]=$currency;
           $data["curency_icon"]= isset($currency_icons[$currency])?$currency_icons[$currency]:"$";
           $data['currency_list'] = $this->currecny_list_all();

           $data['payment_type'] = isset($config_data[0]->paypal_payment_type)?$config_data[0]->paypal_payment_type:"manual";
           $data['manual_payment'] = isset($config_data[0]->manual_payment)?$config_data[0]->manual_payment:"no";
           $data['manual_payment_instruction'] = isset($config_data[0]->manual_payment_instruction)?$config_data[0]->manual_payment_instruction:"";
           $payment_method = DB::table('transaction_logs')->where('user_id', Auth::user()->id)->orderByDesc('paid_at')->select('payment_method')->get();
           $data['payment_method'] = isset($payment_method[0]->payment_method) ? $payment_method[0]->payment_method : 'Paypal';
           $data["payment_package"]=DB::table('package')
           ->where([
               ['is_default', '=', '0'],
               ['price', '>', '0'],
               ['validity', '>', '0'],
               ['visible', '=', '1']
           ])
           ->orderByRaw('CAST(`price` AS SIGNED)')
           ->select('*')
           ->get();

           $user_info = DB::table('users')->select('subscription_enabled', 'last_payment_method')->where('id', Auth::user()->id)->get();
           if(!isset($user_info[0])) exit();
           if($user_info[0]->subscription_enabled == '1' ) $data['has_reccuring'] = 'true';
           else $data['has_reccuring'] = 'false';
           $data['last_payment_method'] = $user_info[0]->last_payment_method;

           return $this->_viewcontroller($data);     

        }
        else return redirect()->route('access_forbidden');
    }

    public function buy_package($id=0)
    {
        if(check_build_version() != 'double' || Auth::user()->user_type != 'Member') return redirect()->route('access_forbidden');

        $package_data = $this->get_package($id,$select='*',$where=['id'=>$id,'deleted'=>'0','is_default'=>'0']);
        if(empty($package_data)) abort('403');
 
        $monthly_limit = json_decode($package_data->monthly_limit,true);
        $package_id = $id;
        $data['body'] = "member/buy-buttons";
        $data['page_title'] = __('Make Payment');
        $payment_config = $this->get_payment_config_parent();
 
        $currency = $payment_config->currency ?? 'USD';
        $paypal_data = isset($payment_config->paypal) ? json_decode($payment_config->paypal) : [];
        $stripe_data = isset($payment_config->stripe) ? json_decode($payment_config->stripe) : [];
        $razorpay_data = isset($payment_config->razorpay) ? json_decode($payment_config->razorpay) : [];
        $paystack_data = isset($payment_config->paystack) ? json_decode($payment_config->paystack) : [];
        $mercadopago_data= isset($payment_config->mercadopago) ? json_decode($payment_config->mercadopago) : [];
        $myfatoorah_data= isset($payment_config->myfatoorah) ? json_decode($payment_config->myfatoorah) : [];
        $toyyibpay_data= isset($payment_config->toyyibpay) ? json_decode($payment_config->toyyibpay) : [];
        $xendit_data= isset($payment_config->xendit) ? json_decode($payment_config->xendit) : [];
        $paymaya_data = isset($payment_config->paymaya) ? json_decode($payment_config->paymaya) : [];
        $mollie_data = isset($payment_config->mollie) ? json_decode($payment_config->mollie) : [];
        $senangpay_data= isset($payment_config->senangpay) ? json_decode($payment_config->senangpay) : [];
        $instamojo_data = isset($payment_config->instamojo) ? json_decode($payment_config->instamojo) : [];
        $instamojo_v2_data = isset($payment_config->instamojo_v2) ? json_decode($payment_config->instamojo_v2) : [];
        $sslcommerz_data= isset($payment_config->sslcommerz) ? json_decode($payment_config->sslcommerz) : [];
        $yoomoney_data= isset($payment_config->yoomoney) ? json_decode($payment_config->yoomoney) : [];
        $manual_payment_status= isset($payment_config->manual_payment_status) ? $payment_config->manual_payment_status: "0";
        $manual_payment_instruction= isset($payment_config->manual_payment_instruction) ? $payment_config->manual_payment_instruction: "";
        $flutterwave_data = isset($payment_config->flutterwave) ? json_decode($payment_config->flutterwave) : [];
 
        $paypal_status = $paypal_data->paypal_status ?? '0';
        $stripe_status = $stripe_data->stripe_status ?? '0';
        $razorpay_status = $razorpay_data->razorpay_status??'0';
        $paystack_status = $paystack_data->paystack_status??'0';
        $mercadopago_status = $mercadopago_data->mercadopago_status??'0';
        $myfatoorah_status= $myfatoorah_data->myfatoorah_status ??'0';
        $toyyibpay_status= $toyyibpay_data->toyyibpay_status ??'0';
        $xendit_status= $xendit_data->xendit_status ??'0';
        $paymaya_status= $paymaya_data->paymaya_status ??'0';
        $mollie_status = $mollie_data->mollie_status??'0';
        $instamojo_status= $instamojo_data->instamojo_status ??'0';
        $instamojo_v2_status= $instamojo_v2_data->instamojo_v2_status ??'0';
        $senangpay_status= $senangpay_data->senangpay_status ??'0';
        $senangpay_mode= $senangpay_data->senangpay_mode ?? "0";
        $sslcommerz_status= $sslcommerz_data->sslcommerz_status ??'0';
        $yoomoney_status= $yoomoney_data->yoomoney_status ??'0';
        $sslcommerz_mode = $sslcommerz_data->sslcommerz_mode ?? 'sandbox';
        $flutterwave_status = $flutterwave_data->flutterwave_status??'0';
 
        $paypal_client_id = $paypal_data->paypal_client_id ?? '';
        $paypal_client_secret = $paypal_data->paypal_client_secret ?? '';
        $paypal_app_id = $paypal_data->paypal_app_id ?? '';
        $paypal_mode = $paypal_data->paypal_mode ?? 'sandbox';
        $paypal_payment_type = $paypal_data->paypal_payment_type ?? 'manual';
        $stripe_publishable_key = $stripe_data->stripe_publishable_key ?? '';
 
        $format_settings = ['currency'=>$payment_config->currency ?? 'USD','decimal_point'=>$payment_config->decimal_point ?? null,'thousand_comma'=>'0','currency_position'=>$payment_config->currency_position ?? 'left'];
 
        $package_name = $package_data->package_name ?? '';
        $payment_amount = (float) $package_data->price ?? 0;
        $price_raw_data = format_price($payment_amount,$format_settings,$package_data->discount_data,['return_raw_array'=>true]);
        $payment_amount = (float) $price_raw_data->sale_price_formatted ?? 0;
        $discount_valid = $price_raw_data->discount_valid;
 
        $product_data = isset($package_data->product_data) && !is_null($package_data->product_data) ? json_decode($package_data->product_data) : null;
        $discount_data = isset($package_data->discount_data) && !is_null($package_data->discount_data) ? json_decode($package_data->discount_data) : null;
        $validity_extra_info = $package_data->validity_extra_info ?? '0,D';
        $validity_extra_info = explode(',', $validity_extra_info);
 
        $package_validity = $package_data->validity ?? 0;
 
        $cancel_url = route('transaction_log')."?action=cancel";
        $success_url = route('transaction_log')."?action=success";
        $no_payment_found_error = true;
 
        $user_name = !empty(Auth()->user()->name) ? Auth()->user()->name : 'DemoUser';
        $user_email = !empty(Auth()->user()->email) ? Auth()->user()->email : 'demo@demo.com';
        $user_mobile = !empty(Auth()->user()->mobile) ? Auth()->user()->mobile : '012345678901';
        $provider = $this->provider; //using the PaypalClint
 
        $paypal_button = '';
        if($paypal_status=='1')
        {
            $package_data = $this->get_package($id,$select='*',$where=['id'=>$id,'deleted'=>'0','is_default'=>'0']);
            $product_data = isset($package_data->product_data) && !is_null($package_data->product_data) ? json_decode($package_data->product_data) : null;
            $paypal_plan_id = $product_data->paypal->plan_id ?? null;
            if($paypal_plan_id == ''){
                $this->paypal->provider = $provider;
                $this->paypal->mode = $paypal_mode;
                $this->paypal->paypal_client_id = $paypal_client_id;
                $this->paypal->paypal_client_secret = $paypal_client_secret;
                $this->paypal->paypal_app_id = $paypal_app_id;
                $this->paypal->currency = $currency;
                $this->paypal->product_information = $package_data;
                $this->paypal->pay_amount = $payment_amount;
                $paypal_data = $this->paypal->paypal_plan_create();
                if(isset($paypal_data['id'])){
                 $package_data = DB::table('package')->select('product_data')->where('id',$package_id)->first();
                 $decode_package_data = json_decode($package_data->product_data,true);
                    $product_data = [
                        'paypal' =>[
                            'plan_id'=> $paypal_data['id']
                        ]
                    ];
                    $product_data = json_encode($product_data);
                 $table = DB::table('package')->where('id',$package_id)->update(['product_data'=>$product_data]);
                }
 
            }
            $package_data = $this->get_package($id,$select='*',$where=['id'=>$id,'deleted'=>'0','is_default'=>'0']);
            $product_data = isset($package_data->product_data) && !is_null($package_data->product_data) ? json_decode($package_data->product_data) : null;
            $paypal_plan_id = $product_data->paypal->plan_id ?? null;
            $this->paypal->plan_id=$paypal_plan_id;
            $no_payment_found_error = false;
            $this->paypal->mode = $paypal_mode;
            $this->paypal->cancel_url = route('transaction_log')."?action=cancel";
            $this->paypal->success_url = route('paypal-subscription-action',['buyer_user_id'=>Auth::user()->id,'parent_user_id'=>$this->parent_user_id,'package_id'=>$id]);
            $notify_url = get_domain_only(env('APP_URL'))=='sitespy.test' ? 'https://ezsoci.com/botsailor-test-ipn/paypal.php' : route('paypal-ipn',$paypal_mode);
            $this->paypal->paypal_client_id = $paypal_client_id;
            $this->paypal->paypal_client_secret = $paypal_client_secret;
            $this->paypal->paypal_app_id = $paypal_app_id;
            $paypal_url = route('payment-paypal-action',[$package_id,Auth::user()->id,$this->parent_user_id]);
            $this->paypal->paypal_url = $paypal_url;
            $this->paypal->provider = $provider;
            if($paypal_payment_type == 'recurring')
            {
                $this->paypal->a3 = $payment_amount;
                $this->paypal->p3 = $validity_extra_info[0] ?? '0';
                $this->paypal->t3 = $validity_extra_info[1] ?? 'D';
                $this->paypal->src='1';
                $this->paypal->sra='1';
                $this->paypal->is_recurring=true;
            }
            else{
             $this->paypal->amount=$payment_amount;
            } 
 
            $this->paypal->user_id = Auth::user()->id;
            $this->paypal->currency = $currency;
            $this->paypal->secondary_button=true;
            $this->paypal->button_lang = __("Pay with PayPal");
            $this->paypal->package_id = $id;
            $this->paypal->product_name = $package_name;
            $paypal_button = $this->paypal->set_button();
            $paypal_button = $paypal_button;
        }
        $stripe_button = '';
        if($stripe_status == '1')
        {
            $no_payment_found_error = false;
            $this->stripe->currency = $currency;
            $this->stripe->amount = $payment_amount;
            $this->stripe->action_url = route('stripe-ipn',['buyer_user_id'=>Auth::user()->id,'parent_user_id'=>$this->parent_user_id,'package_id'=>$id]);
            $this->stripe->description = $package_name;
            $this->stripe->publishable_key = $stripe_publishable_key;
            $stripe_button = $this->stripe->set_button();
        }
 
        $razorpay_button = '';
        if($razorpay_status=='1' && !empty($razorpay_status)){
            $no_payment_found_error = false;
            $razorpay_key_id = $razorpay_data->razorpay_key_id;
            $razorpay_key_secret = $razorpay_data->razorpay_key_secret;
            $this->razorpay_class_ecommerce->key_id=$razorpay_key_id;
            $this->razorpay_class_ecommerce->key_secret=$razorpay_key_secret;
            $this->razorpay_class_ecommerce->title=$package_name;
            $this->razorpay_class_ecommerce->description=config("my_config.product_name")." : ".$package_name." (".$package_validity." days)";
            $this->razorpay_class_ecommerce->amount=$payment_amount;
            $this->razorpay_class_ecommerce->action_url=route("payment-razorpay-action",[$package_id,Auth::user()->id,$this->parent_user_id]).'?order_id=';
            $this->razorpay_class_ecommerce->currency=$currency;
            $store_favicon = asset("assets/img/logo.png");
            $this->razorpay_class_ecommerce->img_url=$store_favicon;
            $this->razorpay_class_ecommerce->customer_name=$user_name;
            $this->razorpay_class_ecommerce->customer_email=$user_email;
            $this->razorpay_class_ecommerce->secondary_button=true;
            $this->razorpay_class_ecommerce->button_lang= __('Pay with Razorpay');
 
            // for action function, because it's not web hook based, it's js based
            session(['razorpay_payment_package_id' => $package_id]);
            session(['razorpay_payment_amount' => $payment_amount]);
            $razorpay_button =  $this->razorpay_class_ecommerce->set_button();
        }
 
        $paystack_button = '';
        if($paystack_status=='1' && !empty($paystack_status)){
            $no_payment_found_error = false;
            $paystack_secret_key = $paystack_data->paystack_secret_key;
            $paystack_public_key = $paystack_data->paystack_public_key;
            $this->paystack_class_ecommerce->secret_key=$paystack_secret_key;
            $this->paystack_class_ecommerce->public_key=$paystack_public_key;
            $this->paystack_class_ecommerce->title=$package_name;
            $this->paystack_class_ecommerce->description=config("my_config.product_name")." : ".$package_name." (".$package_validity." days)";
            $this->paystack_class_ecommerce->amount=$payment_amount;
            $this->paystack_class_ecommerce->action_url=route("payment-paystack-action",[$package_id,Auth::user()->id,$this->parent_user_id]).'?reference=';
            $this->paystack_class_ecommerce->currency=$currency;
            $this->paystack_class_ecommerce->img_url=asset("assets/img/logo.png");
            $this->paystack_class_ecommerce->customer_first_name=$user_name;
            $this->paystack_class_ecommerce->customer_email=$user_email;
            $this->paystack_class_ecommerce->secondary_button=true;
            $this->paystack_class_ecommerce->button_lang=__("Pay with Paystack");
 
            // for action function, because it's not web hook based, it's js based
            session(['paystack_payment_package_id' => $package_id]);
            session(['paystack_payment_amount' => $payment_amount]);
            $paystack_button =  $this->paystack_class_ecommerce->set_button();
        }
 
        $mercadopago_button ='';
        if($mercadopago_status=='1'){
            $no_payment_found_error = false;
            $mercadopago_public_key = $mercadopago_data->mercadopago_public_key;
            $mercadopago_access_token = $mercadopago_data->mercadopago_access_token;
            $mercadopago_country = $mercadopago_data->mercadopago_country;
            $this->mercadopago->public_key=$mercadopago_public_key;
            $this->mercadopago->mercadopago_url = 'https://www.mercadopago.com.'.$mercadopago_country;
            $this->mercadopago->redirect_url=route("payment-mercadopago-action",[$package_id,Auth::user()->id,$this->parent_user_id]);
            $this->mercadopago->transaction_amount=$payment_amount;
            $this->mercadopago->secondary_button=true;
            $this->mercadopago->button_lang=__('Pay with Mercadopago');
 
            $mercadopago_button =  $this->mercadopago->set_button();
        }
 
        $myfatoorah_button ='';
        if($myfatoorah_status=='1'){
            $no_payment_found_error = false;
            $redirect_url_myfatoorah = route('payment-myfatoorah-action',[$package_id,Auth::user()->id,$this->parent_user_id]);
            $this->myfatoorah->redirect_url = $redirect_url_myfatoorah;
            $this->myfatoorah->button_lang = __('Pay With myfatoorah');
            $myfatoorah_button = $this->myfatoorah->set_button();
        }
 
        $toyyibpay_button ='';
        if($toyyibpay_status=='1'){
            $no_payment_found_error = false;
            $redirect_url_toyyibpay = route('payment-toyyibpay-action',[$package_id,Auth::user()->id,$this->parent_user_id]);
            $this->toyyibpay->redirect_url = $redirect_url_toyyibpay;
            $this->toyyibpay->button_lang = __('Pay With Paymaya');
            $toyyibpay_button = $this->toyyibpay->set_button();
 
        }
 
        $xendit_button ='';
        if($xendit_status=='1'){
            $no_payment_found_error = false;
            $xendit_redirect_url = route('payment-xendit-action',[$package_id,Auth::user()->id,$this->parent_user_id]);
            $this->xendit->xendit_redirect_url = $xendit_redirect_url;
            $this->xendit->button_lang = __('Pay With Xendit');
            $xendit_button = $this->xendit->set_button();
 
        }
 
        $paymaya_button ='';
        if($paymaya_status=='1'){
            $no_payment_found_error = false;
            $redirect_url_paymaya = route('payment-paymaya-action',[$package_id,Auth::user()->id,$this->parent_user_id]);
            $this->paymaya->redirect_url = $redirect_url_paymaya;
            $this->paymaya->button_lang = __('Pay With Paymaya');
            $paymaya_button = $this->paymaya->set_button();
        }
 
        $mollie_button='';
 
        if($mollie_status=='1' && !empty($mollie_status)){
            $no_payment_found_error = false;
            $unique_id = Auth::user()->id.time();
            $mollie_api_key = $mollie_data->mollie_api_key;
 
            $this->mollie_class_ecommerce->api_key=$mollie_api_key;
            $this->mollie_class_ecommerce->title=$package_name;
            $this->mollie_class_ecommerce->description=config("my_config.product_name")." : ".$package_name." (".$package_validity." days)";
            $this->mollie_class_ecommerce->amount=$payment_amount;
            $this->mollie_class_ecommerce->action_url=route("payment-mollie-action",[$package_id,Auth::user()->id,$this->parent_user_id]).'?reference=';
            $this->mollie_class_ecommerce->currency=$currency;
            $this->mollie_class_ecommerce->img_url=asset("assets/img/logo.png");
            $this->mollie_class_ecommerce->customer_name=$user_name;
            $this->mollie_class_ecommerce->customer_email=$user_email;
            $this->mollie_class_ecommerce->ec_order_id=$unique_id;
            $this->mollie_class_ecommerce->secondary_button=true;
            $this->mollie_class_ecommerce->button_lang=__("Pay with Mollie");
 
            // for action function, because it's not web hook based, it's js based
             session(['mollie_payment_package_id' => $package_id]);
            session(['mollie_payment_amount' => $payment_amount]);
            session(['mollie_unique_id' => $payment_amount]);
            $mollie_button =  $this->mollie_class_ecommerce->set_button();
        }
 
 
        $instamojo_button = '';
        if($instamojo_status == '1')
        {
            $no_payment_found_error = false;
            $redirect_url_instamojo = route('payment-instamojo-action',[$package_id,Auth::user()->id,$this->parent_user_id]);
            $this->instamojo->redirect_url = $redirect_url_instamojo;
            $this->instamojo->button_lang = __('Pay With Instamojo');
            $instamojo_button = $this->instamojo->set_button();
        }
 
         $instamojo_v2_button = '';
         if($instamojo_v2_status == '1')
         {
             $no_payment_found_error = false;
             $redirect_url_instamojo_v2 = route('payment-instamojo-v2-action',[$package_id,Auth::user()->id,$this->parent_user_id]);
             $this->instamojo_v2->redirect_url_v2 = $redirect_url_instamojo_v2;
             $this->instamojo_v2->button_lang_v2 = __('Pay With Instamojo v2');
             $instamojo_v2_button = $this->instamojo_v2->set_button_v2();
         }
 
 
        $senangpay_button ='';
        if($senangpay_status=='1'){
            $no_payment_found_error = false;
            $senangpay_secret_key =  $senangpay_data->senangpay_secret_key;
            $senangpay_order_id = $package_id.'_'.Auth::user()->id;
            $hashed_string = hash_hmac('sha256', $senangpay_secret_key.urldecode($package_name).urldecode($payment_amount).urldecode($senangpay_order_id), $senangpay_secret_key);
            $merchant_id =  $senangpay_data->senangpay_merchent_id;
            $this->senangpay->secretkey = $senangpay_secret_key;
            $this->senangpay->merchant_id = $merchant_id;
            $this->senangpay->detail =$package_name;
            $this->senangpay->amount = $payment_amount;
            $this->senangpay->order_id = $senangpay_order_id;
            $this->senangpay->name = $user_name;
            $this->senangpay->email = $user_email;
            $this->senangpay->phone = $user_mobile;
            $this->senangpay->senangpay_mode = $senangpay_mode;
            $this->senangpay->hashed_string = $hashed_string;
            $this->senangpay->secondary_button = true;
            $this->senangpay->button_lang = __('Pay With Senangpay');
            $senangpay_button = $this->senangpay->set_button();
 
        }
 
 
        $sslcommerz_button = '';
        if($sslcommerz_status == '1'){
            $no_payment_found_error = false;
            $sslcommerz_store_id = $sslcommerz_data->sslcommerz_store_id;
            $sslcommerz_store_password = $sslcommerz_data->sslcommerz_store_password;
            $package_data = $this->get_package($package_id);
            $package_name = $package_data->package_name ?? '';
            $payment_amount = $package_data->price ?? 0;
            $postdata_array = [
                'total_amount' => $payment_amount,
                'currency' => $currency,
                'product_name' => $package_name,
                'product_category' => $package_name,
                'cus_name' => $user_name,
                'cus_email' => $user_email,
                'package_id' => $package_id,
                'user_id' => Auth::user()->id,
            ];
            $endpoint_url = route('payment-sslcommerz-action');
            // dynamic css, needed to be inline
            $sslcommerz_button = '<button style="display : none;" class="your-button-class" id="sslczPayBtn"
                                     token="if you have any token validation"
                                     postdata=""
                                     order="If you already have the transaction generated for current order"
                                     endpoint="'.$endpoint_url.'">'. __("Pay With SSLCOMMERZ").'
                               </button>';
 
            $sslcommerz_button .= "
                                <a href='#' class='list-group-item list-group-item-action flex-column align-items-start' onclick=\"document.getElementById('sslczPayBtn').click();\">
                                    <div class='d-flex w-100 align-items-center'>
                                      <small class='text-muted'><img class='rounded' width='60' height='60' src='".asset('assets/img/payment/sslcommerz.png')."'></small>
                                      <h6 class='mb-1'>".__('Pay With SSLCOMMERZ')."</h6>
                                    </div>
                                </a>";
 
        }
 
        $yoomoney_button = '';
        if($yoomoney_status == '1')
        {
            $no_payment_found_error = false;
            $redirect_url_yoomoney = route('payment-yoomoney-action',[$package_id,Auth::user()->id,$this->parent_user_id]);
            $this->yoomoney->yoomoney_redirect_url = $redirect_url_yoomoney;
            $this->yoomoney->button_lang = __('Pay With YooMoney');
            $yoomoney_button = $this->yoomoney->set_button();
        }
 
        $manual_payment_button='';
        if($manual_payment_status=='1') {
            $no_payment_found_error = false;
            $manual_payment_button = '
            <div class="col-12 col-md-4 pt-3">
                <a href="" class="list-group-item list-group-item-action flex-column align-items-start" id="manual-payment-button">
                    <div class="d-flex w-100 align-items-center">
                      <small class="text-muted"><img class="rounded" width="60" height="60" src="'.asset('assets/img/payment/manual.png').'"></small>
                      <h5 class="mb-1">'.__("Manual Payment").'</h5>
                    </div>
                </a>
            </div>';
        }
 
 
        $flutterwave_button='';
 
        if($flutterwave_status=='1' && !empty($flutterwave_status)){
            $no_payment_found_error = false;
            $flutterwave_api_key = $flutterwave_data->flutterwave_api_key;
            $redirect_url_flutterwave = route('payment-flutterwave-action',[$package_id,Auth::user()->id,$this->parent_user_id]);
            $this->flutterwave->redirect_url_flutterwave = $redirect_url_flutterwave;
            $no_payment_found_error = false;
 
            $this->flutterwave->button_lang = __('Pay With Flutterwave');
            $flutterwave_button = $this->flutterwave->set_button();
        }
 
 
 
        $buttons_html = '<div class="row" id="payment_options">';
        if($paypal_button != '') $buttons_html .= '<div class="col-12 col-md-4 pt-3">'.$paypal_button.'</div>';
        if($stripe_button != '') $buttons_html .= '<div class="col-12 col-md-4 pt-3">'.$stripe_button.'</div>';
        if($razorpay_button != '') $buttons_html .= '<div class="col-12 col-md-4 pt-3">'.$razorpay_button.'</div>';
        if($paystack_button != '') $buttons_html .= '<div class="col-12 col-md-4 pt-3">'.$paystack_button.'</div>';
        if($mercadopago_button != '') $buttons_html .= '<div class="col-12 col-md-4 pt-3">'.$mercadopago_button.'</div>';
        if($myfatoorah_button != '') $buttons_html .= '<div class="col-12 col-md-4 pt-3">'.$myfatoorah_button.'</div>';
        if($toyyibpay_button != '') $buttons_html .= '<div class="col-12 col-md-4 pt-3">'.$toyyibpay_button.'</div>';
        if($xendit_button != '') $buttons_html .= '<div class="col-12 col-md-4 pt-3">'.$xendit_button.'</div>';
        if($paymaya_button != '') $buttons_html .= '<div class="col-12 col-md-4 pt-3">'.$paymaya_button.'</div>';
        if($mollie_button != '') $buttons_html .= '<div class="col-12 col-md-4 pt-3">'.$mollie_button.'</div>';
        if($instamojo_button != '') $buttons_html .= '<div class="col-12 col-md-4 pt-3">'.$instamojo_button.'</div>';
        if($instamojo_v2_button != '') $buttons_html .= '<div class="col-12 col-md-4 pt-3">'.$instamojo_v2_button.'</div>';
        if($senangpay_button != '') $buttons_html .= '<div class="col-12 col-md-4 pt-3">'.$senangpay_button.'</div>';
        if($sslcommerz_button != '') $buttons_html .= '<div class="col-12 col-md-4 pt-3">'.$sslcommerz_button.'</div>';
        if($yoomoney_button != '') $buttons_html .= '<div class="col-12 col-md-4 pt-3">'.$yoomoney_button.'</div>';
        if($flutterwave_button != '') $buttons_html .= '<div class="col-12 col-md-4 pt-3">'.$flutterwave_button.'</div>';
        if($manual_payment_button != '') $buttons_html .= $manual_payment_button;
        $buttons_html .= '</div>';
 
 
        $data['buttons_html'] = $buttons_html;
        $data['no_payment_found_error'] = $no_payment_found_error;
        $data['payment_config'] = $payment_config;
        $data['buy_package_package_id'] = $package_id;
        $data['currency'] = $currency;
        $data['manual_payment_instruction'] = $manual_payment_instruction;
        $data['currency_list'] = get_country_iso_phone_currency_list("currency_name");
        $data['last_payment_method'] = Auth::user()->last_payment_method;

        $user_info = DB::table('users')->select('subscription_enabled', 'last_payment_method')->where('id', Auth::user()->id)->first();
        if(!isset($user_info)) exit();
        if($user_info->subscription_enabled == '1' ) $data['has_reccuring'] = 'true';
        else $data['has_reccuring'] = 'false';
        $data['package_id'] = $id;

        return $this->_viewcontroller($data);
    }

    public function transaction_log() // works for both admin and member
    {

        // if(check_build_version() != 'double') return redirect()->route('access_forbidden');

        $action = isset($_GET['action']) ? $_GET['action'] : ""; // if redirect after purchase
        if($action!="")
        {
            if($action=="cancel") session(['payment_cancel'=>1]);
            else if($action=="success") session(['payment_success'=>1]);
        }

        $data['body']='subscription.transaction-log';
        $data['page_title']=__("Transaction Log");
        
        // $config_data=$this->basic->get_data("payment_config");
        $config_data=DB::table("settings_payments")->get();
        $currency=isset($config_data[0]->currency) ? $config_data[0]->currency:"USD";
        $currency_icons = $this->currency_icon();
        $data["curency_icon"]= isset($currency_icons[$currency])?$currency_icons[$currency]:"$";
        return $this->_viewcontroller($data);     

    }

    public function transaction_log_data(Request $request)
    { 
        if(check_build_version() != 'double') return redirect()->route('access_forbidden');
        $payment_date_range = $request->input("payment_date_range");
        $search_value = $_POST['search']['value'];
        $display_columns = array("#","CHECKBOX",'id','buyer_email','first_name', 'last_name', 'payment_method', 'cycle_start_date','cycle_expired_date', 'paid_at','paid_amount');
        $search_columns = array('buyer_email','first_name', 'last_name','paid_amount', 'payment_method','transaction_id');

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 2;
        $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'id';
        $order = isset($_POST['order'][0]['dir']) ? strval($_POST['order'][0]['dir']) : 'desc';
        $order_by=$sort." ".$order;

        // $table="transaction_history";
        


        // if(Auth::user()->user_type=='Admin')
        // if($this->is_admin == true)
        // $user_id='1';
        // else $user_id =Auth::user()->id;


        $from_date = $to_date = "";
        if($payment_date_range!="")
        {
            $exp = explode('|', $payment_date_range);
            $from_date = isset($exp[0])?$exp[0]:"";
            $to_date   = isset($exp[1])?$exp[1]:"";

        }

        // $table = "transaction_history";
        $table="transaction_logs";
        $query = DB::table($table)->select('transaction_logs.*');
        $user_id = Auth::user()->id;
        if($from_date!='') $query->where("paid_at", ">=", $from_date);
        if($to_date!='') $query->where("paid_at", "<=", $to_date);

        if ($search_value != '')
        {
            $query->where(function($query) use ($search_columns,$search_value){
                foreach ($search_columns as $key => $value) $query->orWhere($value, 'like',  "%$search_value%");
            });
        }
          
        $info=$query->orderByRaw($order_by)->offset($start)->limit($limit)->get();

        $total_result=$query->count();

        $i=0;
        $url=url('/');
        foreach ($info as $key => $value) 
        {
            $info[$i]->cycle_start_date = date("jS M y",strtotime($info[$i]->cycle_start_date));
            $info[$i]->cycle_expired_date = date("jS M y",strtotime($info[$i]->cycle_expired_date));
            $info[$i]->paid_at = date("jS M y H:i:s",strtotime($info[$i]->paid_at));

            if($this->is_admin == true) {
                if($info[$i]->payment_method == 'PAYPAL' || $info[$i]->payment_method == "PAYPAL-Instant")
                    $info[$i]->buyer_email = "<a href='".url("/admin/edit_user/".$info[$i]->user_id)."'>".$info[$i]->paypal_email."</a>";
                else
                    $info[$i]->buyer_email = "<a href='".url("/admin/edit_user/".$info[$i]->user_id)."'>".$info[$i]->buyer_email."</a>";
            } else {

                if($info[$i]->payment_method == 'PAYPAL' || $info[$i]->payment_method == "PAYPAL-Instant")
                    $info[$i]->buyer_email = $info[$i]->paypal_email;
                else
                    $info[$i]->buyer_email = $info[$i]->buyer_email;

            }

            $i++;
        }

        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    } 
    public function member_payment_history() // kept because not sure if it is called from somewhere
    {
        if(check_build_version() != 'double') return redirect()->route('access_forbidden');
        return redirect()->route('transaction_log');
    }

    public function manual_payment_upload_file(Request $request)
    {
        $rules = (['file' => 'mimes:pdf,doc,txt,png,jpg,jpeg,zip|max:5120']);

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'error' => true,
                'message' => $validator->errors()->first(),
            ]);
        }

        $upload_dir_subpath = 'upload/manual_payment';

        $file = $request->file('file');
        $extension = $request->file('file')->extension();
        $filename = "mp_". Auth::user()->id . '_' . time() . '.' . $extension;

        if(env('AWS_UPLOAD_ENABLED')){
            try {
                $upload2S3 = Storage::disk('s3')->putFileAs($upload_dir_subpath, $file,$filename);
                session(['manual_payment_uploaded_file'=>Storage::disk('s3')->url($upload2S3)]);
                return response()->json([
                    'error' => false,
                    'filename' =>  Storage::disk('s3')->url($upload2S3)
                ]);
            }
            catch (\Exception $e){
                $error_message = $e->getMessage();
                if(empty($error_message)) $error_message =  __('Something went wrong.');
                return response()->json([
                    'error' => true,
                    'message' => $error_message
                ]);
            }
        }
        else{

            if ($request->file('file')->storeAs('public/'.$upload_dir_subpath, $filename)) {
                session(['manual_payment_uploaded_file'=>asset('storage').'/'.$upload_dir_subpath.'/'.$filename]);
                return Response::json([
                    'error' => false,
                    'filename' =>  asset('storage').'/'.$upload_dir_subpath.'/'.$filename
                ]);
            } else {
                return Response::json([
                    'error' => true,
                    'message' => __('Something went wrong.'),
                ]);
            }
        }
    }

    public function manual_payment_delete_file(Request $request)
    {
        $filename = filter_var($request->filename, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
        $filename = str_replace('storage/','public/',$filename);
        $file_paths = explode('/',$filename);
        $filename = array_pop($file_paths);

        $upload_dir_subpath = 'upload/manual_payment';

        if(env('AWS_UPLOAD_ENABLED')){
            try {
                $s3_path = $upload_dir_subpath.'/'.$filename;
                if(Storage::disk('s3')->exists($s3_path)) {
                    Storage::disk('s3')->delete($s3_path);
                    Session::forget("manual_payment_uploaded_file");
                    return Response::json(['deleted' => 'yes']);
                }
                else return Response::json(['deleted' => 'no']);

            }
            catch (\Exception $e){
                $error_message = $e->getMessage();
                if(empty($error_message)) $error_message =  __('Something went wrong.');
                return response()->json(['deleted' => 'no']);
            }
        }
        else{
            $absolute_file_path = storage_path('app/public/').$upload_dir_subpath.DIRECTORY_SEPARATOR.$filename;

            if (! is_dir($absolute_file_path) && file_exists($absolute_file_path) && unlink($absolute_file_path)) {
                Session::forget("manual_payment_uploaded_file");
                return Response::json(['deleted' => 'yes']);
            }
            else return Response::json(['deleted' => 'no']);
        }
    }

    public function manual_payment(Request $request)
    {
        if(check_build_version() != 'double') {
            return Response::json(['msg'=>'404']);
            exit;
        }

        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }

        $rules = [
            'paid_amount' =>'required|integer',
            'paid_currency' =>'required',
            'additional_info' =>'nullable',
            'package_id' =>'required|integer',
            'mp_resubmitted_id' =>'integer',
        ];

        $validate_data = Validator::make($request->all(),$rules);

        if ($validate_data->fails()) {
            $errors = $validate_data->errors();
            if($errors->has('paid_amount')) {
                $message = $errors->first('paid_amount');
            } else if($errors->has('paid_currency')) {
                $message = $errors->first('paid_currency');
            } else if($errors->has('package_id')) {
                $message = $errors->first('package_id');
            }else if($errors->has('mp_resubmitted_id')) {
                $message = $errors->first('mp_resubmitted_id');
            }

            return Response::json([
                'error'=> strip_tags($message)
            ]);
        }

        $paid_amount = $request->input('paid_amount');
        $paid_currency = $request->input('paid_currency');
        $additional_info = strip_tags($request->input('additional_info'));
        $package_id = (int) $request->input('package_id');
        $package_data = $this->get_package($package_id);
        $package_user_id = $package_data->user_id;
        $filename = session('manual_payment_uploaded_file');
        $mp_resubmitted_id = (int) $request->input('mp_resubmitted_id');

        if (! empty($mp_resubmitted_id)) {

            $mp_resubmitted_data = DB::table("transaction_manual_logs")->select(['id', 'user_id', 'filename'])->where('id',$mp_resubmitted_id)->get();

            if (1 != sizeof($mp_resubmitted_data)) {
                $message = __('Bad request.');
                return Response::json(['error'=>$message]);
            }

            $mp_resubmitted_data = $mp_resubmitted_data[0];
            if ($mp_resubmitted_data->user_id != Auth::user()->id) {
                $message = __('Bad request.');
                return Response::json(['error'=>$message]);
            }

            $updated_at = date('Y-m-d H:i:s');
            $update_where = ['id' => $mp_resubmitted_id];
            $update_data = [
                'status' => '0',
                'paid_amount' => $paid_amount,
                'paid_currency' => $paid_currency,
                'additional_info' => $additional_info,
                'updated_at' => $updated_at,
            ];

            // Deletes previous attachement if new one exists
            if (! empty($filename)) {
                // Updates filename in the db
                $update_data['filename'] = $filename;

                // Upload dir path
                $upload_dir = 'upload/manual_payment';

                // Prepares file path
                $filepath = storage_path('app/public/').$upload_dir.DIRECTORY_SEPARATOR. $mp_resubmitted_data->filename;

                // Tries to remove previously uploaded file
                if (!is_dir($filepath) && file_exists($filepath)) {
                    // Deletes file from disk
                    unlink($filepath);
                }
            }

            if (DB::table('transaction_manual_logs')->where($update_where)->update($update_data)
            ) {

                // Deletes file from session
                Session::forget('manual_payment_uploaded_file');

                $message = __('Your manual transaction has been successfully re-submitted and is now being reviewed. We would let you know once it has been approved.');
                return Response::json(["success"=>$message]);
                exit;
            }

            $message = __('Something went wrong while re-submitting your information. Please try again later or contact the administrator!');
            return Response::json(["success"=>$message]);
            exit;
        }

        // Checks whether the attachment is attached
        $filename = session('manual_payment_uploaded_file');
        if (empty($filename)) {
            $message = __('The attachment must be provided.');
            return Response::json(['error'=>$message]);
            exit;
        }

        $transaction_id = 'mp_' . hash_pbkdf2('sha512', $paid_amount, mt_rand(19999999, 99999999), 1000, 24);
        $data = [
            'paid_amount' => $paid_amount,
            'paid_currency' => $paid_currency,
            'additional_info' => $additional_info,
            'package_id' => $package_id,
            'user_id' => $package_user_id,
            'buyer_user_id' => Auth::user()->id,
            'transaction_id' => $transaction_id,
            'filename' => $filename,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if(DB::table('transaction_manual_logs')->insert($data)) {
            $message = __('Your manual transaction has been successfully submitted and is now being reviewed. We would let you konw once it has been approved.');

            // Deletes file from session
            Session::forget('manual_payment_uploaded_file');
            return Response::json(['success'=>$message]);
            exit;
        }

        $message = __('Something went wrong while saving your information. Please try again later or contact the administrator!');
        return Response::json(['error'=>$message]);
        exit;

    }
    
    public function transaction_log_manual() 
    {
        $config_data = DB::table('settings_payments')->get();
        $currency = isset($config_data[0]->currency)?$config_data[0]->currency:"USD";
        $currency_icons = $this->currency_icon();
        $data['currency_icon'] = isset($currency_icons[$currency]) ? $currency_icons[$currency] : '$';
        $data['currency_list'] = get_country_iso_phone_currency_list("currency_name");      
        $data['body'] = 'subscription/transaction-log-manual';
        $data['page_title'] = __('Manual Transaction Log');
        // return view( $data['body'], $data);
        return $this->_viewcontroller($data);     

    }

    public function transaction_log_manual_data(Request $request) 
    {

        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }

        $payment_date_range = $request->input("payment_date_range");
        
        $search_value = $_POST['search']['value'];
        $display_columns = array('id', 'name', 'email', 'paid_amount', 'status','created_at');
        $search_columns = array('name', 'email', 'paid_amount', 'additional_info');

        $config_data=DB::table("settings_payments")->get();
        $currency=isset($config_data[0]->currency)?$config_data[0]->currency:"USD";
        $currency_icons = $this->currency_icon();
        $curency_icon= isset($currency_icons[$currency])?$currency_icons[$currency]:"$";

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $sort_index = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 5;
        $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'transaction_history_manual.created_at';
        $order = isset($_POST['order'][0]['dir']) ? strval($_POST['order'][0]['dir']) : 'desc';
        $order_by = $sort . " " . $order;

        $from_date = $to_date = "";
        if($payment_date_range!="")
        {
            $exp = explode('|', $payment_date_range);
            $from_date = isset($exp[0])?$exp[0]:"";
            $to_date   = isset($exp[1])?$exp[1]:"";

        }

        $select = [
            'transaction_history_manual.id',
            'transaction_history_manual.package_id',
            'transaction_history_manual.user_id',
            'transaction_history_manual.paid_amount',
            'transaction_history_manual.paid_currency',
            'transaction_history_manual.additional_info',
            'transaction_history_manual.filename',
            'transaction_history_manual.status',
            'transaction_history_manual.created_at',
            'users.id as user_id',
            'users.name',
            'users.email',
            'package.package_name',
            'package.price',
            'package.validity',
        ];

        $join = [
            'users' => 'transaction_history_manual.user_id=users.id',
            'package' => 'transaction_history_manual.package_id=package.id'
        ];

        $table = "transaction_history_manual";
        $query = DB::table($table)->select($select);
        $user_id = Auth::user()->id;
        if($from_date!='') $query->where("created_at", ">=", $from_date);
        if($to_date!='') $query->where("created_at", "<=", $to_date);

        if ($search_value != '')
        {
            $query->where(function($query) use ($search_columns,$search_value){
                foreach ($search_columns as $key => $value) $query->orWhere($value, 'like',  "%$search_value%");
            });
        }
        // $query->where(function($query) use ($user_id){
        //   $query->orWhere('transaction_history_manual.user_id', '=', $user_id);
        // });

        $info=$query->leftJoin('users', 'transaction_history_manual.user_id', '=', 'users.id')
        ->leftJoin('package', 'transaction_history_manual.package_id', '=', 'package.id')
        ->orderByRaw($order_by)->offset($start)->limit($limit)->get();


        $total_result=DB::table($table)->count();

          


        // $this->db->where($where_custom);
        // $info = $this->basic->get_data($table, $where='', $select, $join, $limit, $start, $order_by, $group_by='');
        // echo "<pre>"; print_r($info); exit;
        
        // $this->db->where($where_custom);
        // $total_rows_array = $this->basic->count_row($table, $where='', $count=$table.".id", $join, $group_by='');
        // $total_result = $total_rows_array[0]['total_rows'];

        $i = 0;
        $url =url('/');
        foreach ($info as $key => $value) {
            // Modifies transaction_history_manual.status
            $status = isset($info[$i]->status) ? $info[$i]->status : '2';
            if ('0' == $status) {
                $info[$i]->status = '<span class="text-warning"><i class="fa fa-spinner"></i> ' . __('Pending') . '</span>';
            } elseif ('1' == $status) {
                $info[$i]->status = '<span class="text-success"><i class="far fa-check-circle"></i> ' . __('Approved') . '</span>';
            } elseif ('2' == $status) {
                $info[$i]->status = '<span class="text-danger"><i class="far fa-check-circle"></i> ' . __('Rejected') . '</span>';
            }
            
            // Modifies transaction_history_manual.attachment column

            $file = url('public/storage/upload/manual_payment' .'/'. $info[$i]->filename);
            $info[$i]->attachment = $this->handle_attachment($info[$i]->id, $file);

            // Modifies users.name column
            if ('Admin' == Auth::user()->user_type) {
                $info[$i]->name = '<a href="' . url('/admin/edit_user' .'/'. $info[$i]->user_id) . '" target="_blank">' . $info[$i]->name . '</a>';
            }

            // Adds actions column for admin
            if (! isset($info[$i]->actions)) {
                $action_width = (2*47)+20;
                $is_disabled = ('1' == $status || '2' == $status) ? 'disabled' : '';
                
                if ('Admin' == Auth::user()->user_type) {
                    $approve_btn = '<a href="#" id="mp-approve-btn" class="btn btn-circle btn-outline-success ' . $is_disabled . '" data-id="' . $info[$i]->id . '"><i class="fas fa-check-circle"></i></a>';
                    $reject_btn = '<a href="#" id="mp-reject-btn" class="btn btn-circle btn-outline-danger ' . $is_disabled . '" data-id="' . $info[$i]->id . '"><i class="fas fa-times-circle"></i></a>';

                    $output = '<div class="dropdown d-inline dropright">';
                    $output .= '<button  class="btn btn-outline-primary dropdown-toggle no_caret" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    $output .= '<i class="fa fa-briefcase"></i>';
                    $output .= '</button>';

                    $output .= '<div class="dropdown-menu mini_dropdown text-center" style="width:' . $action_width . 'px !important">';
                    $output .= $approve_btn;
                    $output .= $reject_btn;
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '<script>$("[data-toggle=\'tooltip\']").tooltip();</script>';

                    $info[$i]->actions = $output;
                } elseif ('Member' == Auth::user()->user_type) {
                    if ('0' == $status) {
                        $info[$i]->actions = '<i class="fas fa-spinner text-warning" data-toggle="tooltip" title="' . __('In progress') . '"></i>';
                    } elseif ('1' == $status) {
                        $info[$i]->actions = '<i class="fas fa-check-circle text-success" data-toggle="tooltip" title="' . __('No action required') . '"></i>';
                    } elseif ('2' == $status) {
                        $info[$i]->actions = '<a href="#" id="manual-payment-resubmit" data-id="' . $info[$i]->id . '" data-toggle="tooltip" title="' . __('You can re-submit this payment.') . '">'. __('Re-submit') .'</a>';
                    }

                    $info[$i]->actions .= '<script>$("[data-toggle=\'tooltip\']").tooltip();</script>';
                }
            }

            $info[$i]->package = '<a target="_blank" href="'.url('/payment/edit_package'.'/'.$info[$i]->package_id).'">'.$info[$i]->package_name.'</a>';
            $info[$i]->price = $curency_icon.$info[$i]->price;
            $info[$i]->validity = $info[$i]->validity.' '.__('Days');

            // Modifies transaction_history_manual.created_at column
            $info[$i]->created_at = date("jS M y H:i:s",strtotime($info[$i]->created_at));

            $i++;
        }

        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = $info;

        echo json_encode($data);
    }

    public function transaction_log_manual_resubmit() 
    {
        if (! request()->ajax()) {
            $message = __('Bad request.');
            echo json_encode(['msg' => $message]);
            return;
        }

        $resubmit_data = isset($resubmit_data[0]) ? $resubmit_data[0] : '';
        $payment_instructions = isset($payment_instructions[0]) ? $payment_instructions[0] : '';

        // Prepares vars
        $user_id = session()->get('tlm_resubmit_user_id');
        $filename = session()->get('tlm_resubmit_filename');
        $package_price = session()->get('tlm_resubmit_package_price');
        $package_validity = session()->get('tlm_resubmit_package_validity');

        if ($user_id != Auth::user()->id) {
            $message = __('Bad request.');
            echo json_encode(['error' => $message]);
            return;
        }
    }

    public function transaction_log_manual_resubmit_data(Request $request) 
    {
        if (! request()->ajax()) {
            $message = __('Bad request.');
            echo json_encode(['msg' => $message]);
            return;
        }

        $rules = [
            'id' =>'required',
        ];

        $validate_data = Validator::make($request->all(),$rules);
        if ($validate_data->fails()) {
            $errors = $validate_data->errors();
            if($errors->has('id')) {
                $message = $errors->first('id');
            }

            return Response::json([
                'error'=> strip_tags($message)
            ]);
        }

        // Gets transaction ID
        $id = (int) $request->input('id');


        $select = [
            'transaction_history_manual.id as thm_id',
            'transaction_history_manual.filename',
            'transaction_history_manual.paid_amount',
            'transaction_history_manual.paid_currency',
            'transaction_history_manual.additional_info',
            'users.id as user_id',
            'package.id as package_id',
            'package.price as package_price',
            'package.validity as package_validity',            
        ];


        $resubmit_data = DB::table('transaction_history_manual')->select($select)
                                       ->leftJoin("users","transaction_manual_logs.buyer_user_id","=","users.id")
                                       ->leftJoin("package","transaction_manual_logs.package_id","=","package.id")
                                       ->where(['transaction_history_manual.id' => $id,'transaction_history_manual.status' => '2'])
                                       ->get() ;

        $payment_instructions = DB::table('settings_payments')
                                       ->select('manual_payment', 'manual_payment_instruction')
                                       ->get();
        if (1 != sizeof($resubmit_data)) {
            $message = __('Bad request.');
            echo json_encode(['error' => $message]);
            return;
        }

        $resubmit_data = isset($resubmit_data[0]) ? $resubmit_data[0] : '';
        $payment_instructions = isset($payment_instructions[0]) ? $payment_instructions[0] : '';

        // Prepares vars
        $user_id = $resubmit_data->user_id;
        $package_id = $resubmit_data->package_id;
        $package_price = $resubmit_data->package_price;
        $package_validity = $resubmit_data->package_validity;

        $filename = $resubmit_data->filename;
        $paid_amount = $resubmit_data->paid_amount;
        $paid_currency = $resubmit_data->paid_currency;
        $additional_info = $resubmit_data->additional_info;
        $manual_payment_status = isset($payment_instructions->manual_payment) ? $payment_instructions->manual_payment : '';
        $manual_payment_instruction = isset($payment_instructions->manual_payment_instruction) ? $payment_instructions->manual_payment_instruction : '';

        if ($user_id != Auth::user()->id) {
            $message = __('Bad request.');
            echo json_encode(['error' => $message]);
            return;
        }

        echo json_encode([
            'status' => 'ok',
            'package_id' => $package_id, 
            'paid_amount' => $paid_amount,
            'paid_currency' => $paid_currency,
            'additional_info' => $additional_info,
            'manual_payment_status' => $manual_payment_status,
            'manual_payment_instruction' => $manual_payment_instruction,
        ]);

        // $this->session->set_userdata('tlm_resubmit_user_id', $user_id);
        // $this->session->set_userdata('tlm_resubmit_filename', $filename);
        // $this->session->set_userdata('tlm_resubmit_package_price', $price);
        // $this->session->set_userdata('tlm_resubmit_package_validity', $validity);
    }

    public function manual_payment_download_file(Request $request) 
    {
        // Prevents out-of-memory issue
        if (ob_get_level()) {
            ob_end_clean();
        }
        // If it is GET request let it download file
        if ($request->isMethod('get')) {
            $filename = session()->get('manual_payment_download_file');

            if (! $filename) {
                $message = __('No file to download.');
                echo json_encode(['msg' => $message]);
            } else {
                if (!file_exists(storage_path("app/public/upload/manual_payment"))) {
                    mkdir(storage_path("app/public/upload/manual_payment"), 0777, true);
                }
                $file = storage_path('app/public/upload/manual_payment') . '/' . $filename;

                header('Expires: 0');
                header('Pragma: public');
                header('Cache-Control: must-revalidate');
                header('Content-Length: ' . filesize($file));
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                readfile($file);
                Session::forget('manual_payment_download_file');
                exit;
            }

        // If it is POST request, grabs the file
        } elseif ($request->isMethod('post')) {
            if (! request()->ajax()) {
                $message = __('Bad Request.');
                echo json_encode(['msg' => $message]);
                exit;
            }

            // Grabs transaction ID
            $id = (int) $request->input('file');

            // Checks file owner
            $select = ['id', 'user_id', 'filename'];
            $where = [];
            if ('Admin' == Auth::user()->user_type) {
                $where = 
                     [
                        'id' => $id,
                     ];
                
            } else {
                $where = 
                     [
                        'id' => $id,
                        'user_id' => Auth::user()->id,
                     ];
                
            }

            $result = DB::table('transaction_history_manual')->select($select)->where($where)->get()->toArray();
            if (1 != count($result)) {
                $message = __('You do not have permission to download this file.');
                echo json_encode(['error' => $message]);
                exit;
            }

            $filename = $result[0]->filename;
            session(['manual_payment_download_file'=>$filename]);

            echo json_encode(['status' => 'ok']);
        }
    }

    public function manual_payment_handle_actions(Request $request) 
    {
        if (! request()->ajax()) {
            $message = __('Bad Request.');
            echo json_encode(['msg' => $message]);
            exit;
        }

        $rules = [
            "id"=>"required|integer",
            "action_type"=>"required|in:mp-approve-btn,mp-reject-btn",
            "rejected_reason"=>"nullable",
        ];

        // $this->form_validation->set_rules('id', __('Transaction ID'), 'required|numeric');
        // $this->form_validation->set_rules('action_type', __('Action type'), 'trim|required|in_list[mp-approve-btn,mp-reject-btn]');
        // $this->form_validation->set_rules('rejected_reason', __('Rejection reason'), 'trim');

        $validate_data = Validator::make($request->all(),$rules);

        if($validate_data->fails()){
            $errors = $validate_data->errors();

            if($errors->has('id')) {
                $message = $errors->first('id');
            } else if($errors->has('action_type')) {
                $message = $errors->first('action_type');
            } else if($errors->has('rejected_reason')) {
                $message = $errors->first('rejected_reason');
            }

            return Response::json([
                'error'=> strip_tags($message)
            ]);
        }

        $id = $request->input('id');
        $action_type = $request->input('action_type');
        $rejected_reason = $request->input('rejected_reason');

        switch ($action_type) {
            case 'mp-approve-btn':
                $this->manual_payment_approve($id);
                return;

            case 'mp-reject-btn':
                $this->manual_payment_reject($id, $rejected_reason);
                return;

            default:
                $message = __('The action type was not valid.');
                return Response::json(['error' => $message]);
                exit;
        }
    }

    public function manual_payment_approve($transaction_id) 
    {
        set_email_config();
        if (! request()->ajax() 
            || 'Admin' != Auth::user()->user_type
        ) {
            $message = __('Bad Request.');
            echo json_encode(['msg' => $message]);
            exit;
        }

        $man_select = [
            'transaction_history_manual.id as thm_id',
            'transaction_history_manual.user_id',
            'transaction_history_manual.package_id',
            'transaction_history_manual.transaction_id',
            'transaction_history_manual.paid_amount',
            'transaction_history_manual.status',
            'transaction_history_manual.created_at',
            'users.name',
            'users.email',
            'package.price',
            'package.validity',
        ];

        $man_where = [
            'transaction_manual_logs.id' => $transaction_id,
        ];


        $manual_transaction = DB::table("transaction_manual_logs")
                                ->select($man_select)
                                ->leftJoin("users","transaction_manual_logs.buyer_user_id","=","users.id")
                                ->leftJoin("package","transaction_manual_logs.package_id","=","package.id")
                                ->where($man_where)
                                ->get();

        if (1 != sizeof($manual_transaction)) {
            $message = __('Bad request.');
            echo json_encode(['error' => $message]);
            return;
        }

        // Manual transaction info
        $manual_transaction = $manual_transaction[0];

        // Payment status
        $status = $manual_transaction->status;
        if ('1' == $status) {
            $message = __('The transaction had already been approved.');
            echo json_encode(['error'=>$message]);
            return;
        } elseif ('2' == $status) {
            $message = __('The transaction had been rejected and you can not approve it.');
            echo json_encode(['error'=>$message]);
            return;
        }
        
        // Prepares some vars
        $name = explode(' ', $manual_transaction->name);
        $first_name = isset($name[0]) ? $name[0] : '';
        $last_name = isset($name[1]) ? $name[1] : '';
        $name = $first_name . ' ' . $last_name;
        $email = $manual_transaction->email;
        $user_id = $manual_transaction->user_id;
        $package_id = $manual_transaction->package_id;
        $paid_amount = $manual_transaction->paid_amount;
        $transaction_id = $manual_transaction->transaction_id;

        // Prepares sql for 'transaction_history' table
        $prev_where = ['user_id' => $user_id];
        $prev_select = ['cycle_start_date', 'cycle_expired_date'];
        
        $prev_payment_info = DB::table("transaction_logs")
                                ->select($prev_select)
                                ->where($prev_where)
                                ->offset(0)
                                ->limit(1)
                                ->orderBy("ID","DESC")
                                ->get();
        // Previous payment info
        $prev_payment = isset($prev_payment_info[0]) ? $prev_payment_info[0] : [];

        // Prepares cycle start and end date
        $prev_cycle_expired_date = '';
        if (1 == sizeof($prev_payment_info)) {
            $prev_cycle_expired_date = $prev_payment->cycle_expired_date;
        }

        $validity_str = '+' . $manual_transaction->validity . ' day';
        if ('' == $prev_cycle_expired_date || strtotime($prev_cycle_expired_date) == strtotime(date('Y-m-d'))) {
            $cycle_start_date = date('Y-m-d');
            $cycle_expired_date = date("Y-m-d", strtotime($validity_str, strtotime($cycle_start_date)));
        } elseif (strtotime($prev_cycle_expired_date) < strtotime(date('Y-m-d'))) {
            $cycle_start_date = date('Y-m-d');
            $cycle_expired_date = date("Y-m-d", strtotime($validity_str, strtotime($cycle_start_date)));
        } elseif (strtotime($prev_cycle_expired_date) > strtotime(date('Y-m-d'))) {
            $cycle_start_date = date("Y-m-d",strtotime('+1 day', strtotime($prev_cycle_expired_date)));
            $cycle_expired_date = date("Y-m-d", strtotime($validity_str, strtotime($cycle_start_date)));
        }

        // Data for 'transaction_history' table
        $transaction_history_data = [
            'verify_status'     => '',
            'first_name'        => $first_name,
            'last_name'         => $last_name,
            'paypal_email'      => $email,
            'receiver_email'    => $email,
            'country'           => '',
            'paid_at'      => date('Y-m-d H:i:s', strtotime($manual_transaction->created_at)),
            'payment_method'      => 'manual',
            'transaction_id'    => $transaction_id,
            'user_id'           => $user_id,
            'package_id'        => $package_id,
            'cycle_start_date'  => $cycle_start_date,
            'cycle_expired_date'=> $cycle_expired_date,
            'paid_amount'       => $paid_amount,
        ];

        // Data form 'users' table
        $user_where = ['id' => $user_id];
        $user_data = [
            'expired_date' => $cycle_expired_date, 
            'package_id' => $package_id, 
            'bot_status' => '1'
        ];
        $has_error = false;
        try {
            DB::beginTransaction();

            DB::table("transaction_logs")->insert($transaction_history_data);
            DB::table("users")->where($user_where)->update($user_data);
            DB::table("transaction_manual_logs")->where("id",$manual_transaction->thm_id)->update(['status' => '1','updated_at' => date('Y-m-d H:i:s')]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $has_error = true;
        }

        if ($has_error) {
            echo json_encode(['status'=>'0','message'=> __('Something went wrong, please try again.')]);
            return;

        } else {
            echo json_encode(['status'=>'ok','message'=> __('Your transaction approved successfully.')]);
        }

        // affiliate Section
        // if($this->addon_exist('affiliate_system')) {
        //     $get_affiliate_id = DB::table('users')->where('id',$user_id)->pluck('affiliate_id')->get();
        //     $affiliate_id = isset($get_affiliate_id[0]->affiliate_id) ? $get_affiliate_id[0]->affiliate_id:0;
        //     if($affiliate_id != 0) {
        //         $this->affiliate_commission($affiliate_id,$user_id,'payment',$paid_amount);
        //     }
        // }


        
        // Prepares vars for sending emails to payer and payee
        $product_short_name = config('my_config.product_short_name');
        $from = config('my_config.institute_email');
        $mask = config('my_config.product_name');

        // $payment_confirmation_email_template = $this->basic->get_data('email_template_management',
        //     [
        //         'where' => [
        //             'template_type' => 'paypal_payment',
        //         ],
        //         'or_where' => [
        //             'template_type' => 'paypal_new_payment_made',
        //         ]
        //     ],
        //     [
        //         'subject',
        //         'message',
        //     ],$join='',$limit='',$start=NULL,$order_by='id asc'
        // );

        $payment_confirmation_email_template = DB::table('email_template_management')
                                                ->select('subject','message')
                                                ->where('template_type' ,'paypal_payment')
                                                ->orWhere('template_type' , 'paypal_new_payment_made')
                                                ->orderBy('id')->get();

        // Sends email to payer
        if (isset($payment_confirmation_email_template[0]) 
            && '' != $payment_confirmation_email_template[0]->subject 
            && '' != $payment_confirmation_email_template[0]->message
        ) {
            $to = $email;
            $url = url('/');
            $subject = $payment_confirmation_email_template[0]->subject;
            $message = str_replace(
                [
                    '#PRODUCT_SHORT_NAME#',
                    '#APP_SHORT_NAME#',
                    '#CYCLE_EXPIRED_DATE#',
                    '#SITE_URL#',
                    '#APP_NAME#',
                ], 
                [
                    $product_short_name,
                    $cycle_expired_date,
                    $url,
                    $mask,
                ],
                $payment_confirmation_email_template[0]->message
            );

            // Sends mail to payer
            // $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
             Mail::to($email)->send(new SimpleHtmlEmail($mask,$message,$subject));
        } else {
            $to = $email;
            $subject = 'Payment Confirmation';
            $message = "Congratulation,<br/> we have received your payment successfully. Now you are able to use {$product_short_name} system till {$cycle_expired_date}.<br/><br/>Thank you,<br/><a href=\"" . url('/') . "\">{$mask}</a> team";

            // Sends mail to payer
            // $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
             Mail::to($email)->send(new SimpleHtmlEmail($mask,$message,$subject));
        }

        // New payment email to payee (admin)
        if(isset($payment_confirmation_email_template[1]) 
            && '' != $payment_confirmation_email_template[1]->subject 
            && '' != $payment_confirmation_email_template[1]->message
        ) {
            $to = $from;
            $subject = $payment_confirmation_email_template[1]->subject;
            $message = str_replace('#PAID_USER_NAME#', $name, $payment_confirmation_email_template[1]->message);

            // Sends mail to payee (admin)
            // $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
             Mail::to($email)->send(new SimpleHtmlEmail($mask,$message,$subject));
        } else {
            $to = $from;
            $subject = 'New Payment Made';
            $message = "New payment has been made by {$name}";

            // Sends email to payee (admin)
            // $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
             Mail::to($email)->send(new SimpleHtmlEmail($mask,$message,$subject));
        }        
    }

    public function manual_payment_reject($id, $rejected_reason) 
    {
        if (! request()->ajax()
            || 'Admin' != Auth::user()->user_type
        ) {
            $message = __('Bad Request.');
            echo json_encode(['msg' => $message]);
            exit;
        }

        $man_select = [
            'transaction_history_manual.id as thm_id',
            'transaction_history_manual.user_id',
            'transaction_history_manual.package_id',
            'transaction_history_manual.transaction_id',
            'transaction_history_manual.status',
            'users.name',
            'users.email',
        ];

        $man_where = [  
                'transaction_history_manual.id' => $id,
                // 'transaction_history_manual.status' => '0',    
        ];

        // $manual_transaction = $this->basic->get_data('transaction_history_manual', $man_where, $man_select, $man_join, 1);
        $manual_transaction = DB::table('')->select($man_select)
                                            ->leftJoin("users","transaction_manual_logs.buyer_user_id","=","users.id")
                                            ->where($man_where)
                                            ->get();
        

        if (1 != sizeof($manual_transaction)) {
            $message = __('Bad request.');
            echo json_encode(['error' => $message]);
            return;
        }

        // Manual transaction info
        $manual_transaction = $manual_transaction[0];
        
        // Holds transaction status
        $status = $manual_transaction->status;
        $transaction_id = $manual_transaction->transaction_id;

        if ('1' == $status) {
            $message = __('The transaction had already been approved.');
            echo json_encode(['error' => $message]);
            return;
        } elseif ('2' == $status) {
            $message = __('The transaction had already been rejected.');
            echo json_encode(['error' => $message]);
            return;
        }

        if (empty($rejected_reason)) {
            $message = __('Please describe the reason of the rejection of this payment.');
            echo json_encode(['error' => $message]);
            exit;
        }

        // Prepares some vars
        $thm_id = $manual_transaction->thm_id;
        $email = $manual_transaction->email;

        $where = ['id' => $thm_id];
        $data = [
            'status' => '2',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if (DB::table("transaction_history_manual")->where($where)->update($data)) {
            $message = __('The transaction has been rejected.');
            echo json_encode(['status' => 'ok', 'message' => $message]);
        } else {
            $message = __('Something went wrong! Please try again later.');
            echo json_encode(['error' => $message]);
        }

        // Prepares vars for sending emails to payer and payee
        $product_short_name = config('my_config.product_short_name');
        $from = config('my_config.institute_email');
        $mask = config('my_config.product_name');

        // Sends email to payer
        $to = $email;
        $subject = 'Manual payment rejection';
        $message = "Transaction ID: {$transaction_id} has been rejected. Please check out the following reason:<br/><br/>{$rejected_reason}<br/><br/>If you are still want to use this {$product_short_name} system, please resubmit the payment again in accordance with the description above.<br/><br/>Thank you,<br/><a href=\"" . url('') . "\">{$mask}</a> team";

        // Sends mail to payer
        // $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
         Mail::to($email)->send(new SimpleHtmlEmail($mask,$message,$subject));
    
        $to = $from;
        // Sends email to payee (admin)
        // $this->_mail_sender($from, $to, $subject, $message, $mask, $html=1);
         Mail::to($email)->send(new SimpleHtmlEmail($mask,$message,$subject));
    }


    private function manual_payment_display_attachment($file) 
    {
        $output = '<div class="mp-display-img">';
        $output .= '<div class="mp-img-item btn btn-outline-info" data-image="' . $file . '" href="' . $file . '">';
        $output .= '<i class="fa fa-image"></i>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '<script>$(".mp-display-img").Chocolat({className: "mp-display-img", imageSelector: ".mp-img-item"});</script>';

        return $output;
    }

    private function handle_attachment($id, $file) 
    {
        $info = pathinfo($file);
        if (isset($info['extension']) && ! empty($info['extension'])) {
            switch (strtolower($info['extension'])) {
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                    return $this->manual_payment_display_attachment($file);
                case 'zip':
                case 'pdf':
                case 'txt':
                    return '<div data-id="' . $id . '" id="mp-download-file" class="btn btn-outline-info"><i class="fa fa-download"></i></div>';
            }
        }
    }


    public function package_manager()
    {
        if(Auth::user()->user_type != 'Admin') return redirect()->route('access_forbidden');
        
        $data['body']='subscription/package-list';
        // $data['page_title']=__("Package Manager");
        // $data['payment_config']=$this->basic->get_data('payment_config');
        $data['payment_config']=DB::table('settings_payments')->get();
        $data['payment_config'] = json_decode(json_encode($data['payment_config']));
        return $this->_viewcontroller($data);     

    }

    public function package_manager_data(Request $request)
    { 

        $search_value = $_POST['search']['value'];
        $display_columns = array("#",'id', 'package_name','price','validity','is_default');
        $search_columns = array( 'package_name','price','validity');

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 1;
        $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'id';
        $order = isset($_POST['order'][0]['dir']) ? strval($_POST['order'][0]['dir']) : 'desc';
        $order_by=$sort." ".$order;

        $table = "package";
        $query = DB::table($table);

        if ($search_value != '')
        {
            $query->where(function($query) use ($search_columns,$search_value){
                foreach ($search_columns as $key => $value) $query->orWhere($value, 'like',  "%$search_value%");
            });
        }
            

        
        $info = $query->where(['deleted'=> '0'])->orderByRaw($order_by)->offset($start)->limit($limit)->get();

        $query = DB::table($table)->get();
        $total_result=DB::table($table)->count();

        // $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join='',$group_by='');
        // $total_result=$total_rows_array[0]['total_rows'];

        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start);

        echo json_encode($data);
    }


    
    public function add_package()
    {       
        if(Auth::user()->user_type != 'Admin') return redirect()->route('access_forbidden');
        $data['body']='subscription/add-package';     
        $data['page_title']=__('Add Package');     
        $data['modules'] = DB::table('modules')->orderByRaw('module_name')->get();
        $data['modules']=json_decode(json_encode($data['modules']));

        $data['payment_config']=DB::table('settings_payments')->get();
        $data['validity_type'] = array('D' => __('Day'), 'W' => __('Week'), 'M' => __('Month'), 'Y' => __('Year'));
        return $this->_viewcontroller($data);     

    }


    public function add_package_action(Request $request) 
    {
        if(config('app.is_demo') == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }

        // if(session()->get('logged_in') == 1 && Auth::user()->user_type != 'Admin') 
        // return redirect()->route('login');

        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }




        if($_POST)
        {

            
            $rules = [
                'name' => 'required',
                'validity_amount' => 'required|integer',              
                'modules' => 'required',               
                'price' => 'required', 
            ];

            $validator = Validator::make($request->all(), $rules);
   
                
            if ($validator->fails())
            {
                return redirect()->back()
                ->withErrors($validator)
                ->withInput(); 
            }
            else
            {

                $validity_type_arr['D'] = 1;
                $validity_type_arr['W'] = 7;
                $validity_type_arr['M'] = 30;
                $validity_type_arr['Y'] = 365;

                $package_name=strip_tags($request->input('name'));
                $price=strip_tags($request->input('price'));
                $visible=$request->input('visible');
                $highlight=$request->input('highlight');

                if($visible=='') $visible='0';
                if($highlight=='') $highlight='0';

                $validity_amount=$request->input('validity_amount');
                $validity_type=$request->input('validity_type');
                $validity = $validity_amount * $validity_type_arr[$validity_type];
                $validity_extra_info = implode(',', array($validity_amount, $validity_type));
                // dd($request->all());
                $modules=array();
                if(count($request->input('modules'))>0)  
                {
                   $modules=$request->input('modules');                            
                }

                $bulk_limit=array();
                $monthly_limit=array();

                foreach ($modules as $value) 
                {
                    $monthly_field="monthly_".$value;
                   
                    $val=$request->input($monthly_field);
                    if($val=="") $val=0;
                    $monthly_limit[$value]=$val;
               

                    $bulk_field="bulk_".$value;
                    
                    $val=$request->input($bulk_field);
                    if($val=="") $val=0;
                    $bulk_limit[$value]=$val;                    
                }



                $modules_str=implode(',',$modules);                        
                               
                $data=array
                (
                    'package_name'=>$package_name,
                    'price'=>$price,
                    'validity'=>$validity,
                    'visible'=>$visible,
                    'highlight'=>$highlight,
                    'validity_extra_info'=>$validity_extra_info,
                    'module_ids'=>$modules_str,
                    'monthly_limit'=>json_encode($monthly_limit),
                    'bulk_limit'=>json_encode($bulk_limit)
                );
                
                // if($this->basic->insert_data('package',$data))                                      
                // session->set_flashdata('success_message',1);   
                // else    
                // session->set_flashdata('error_message',1); 
                
                if (DB::table('package')->insert($data)) {
                    session()->flash('success_message', 1);
                } else {
                    session()->flash('error_message', 1);
                }

                return redirect()->route('package_manager',);                 
                
            }
        }   
    }


    public function details_package($id=0)
    {        
        if(Auth::user()->user_type != 'Admin') return redirect()->route('access_forbidden');

        if($id==0)
        return redirect()->route('access_forbidden');

        // $data['body']='admin/payment/details_package';        
        // $data['page_title']=__("Package Details");        
        // $data['modules']=$this->basic->get_data('modules',$where='',$select='',$join='',$limit='',$start='',$order_by='module_name asc',$group_by='',$num_rows=0);
        $data['modules'] = DB::table('modules')->orderByRaw('module_name')->get();
        $data['modules'] = json_decode(json_encode($data['modules']));
        // $data['value']=$this->basic->get_data('package',$where=array("where"=>array("id"=>$id)));
        $data['value'] = DB::table('package')->where('id',$id)->get();
        $data['value'] = json_decode(json_encode($data['value']));
        // $data['payment_config']=$this->basic->get_data('payment_config');
        $payment_config = DB::table('settings_payments')->get();
        $payment_config = json_decode(json_encode($payment_config));
        $data['validity_type'] = array('D' => __('Days'), 'W' => __('Weeks'), 'M' => __('Months'), 'Y' => __('Years'));

        $validity_days = $data['value'][0]->validity;

        if ($validity_days % 365 == 0) {

            $data['validity_type_info'] = 'Y';
            $data['validity_amount'] = $validity_days / 365;
        }
        else if ($validity_days % 30 == 0) {

            $data['validity_type_info'] = 'M';
            $data['validity_amount'] = $validity_days / 30;
        }
        else if ($validity_days % 7 == 0) {

            $data['validity_type_info'] = 'W';
            $data['validity_amount'] = $validity_days / 7;
        }
        else {

            $data['validity_type_info'] = 'D';
            $data['validity_amount'] = $validity_days;
        }
        $data['body']='subscription.details-package';     
        // return view('subscription.details-package',$data);
        return $this->_viewcontroller($data);     

    }


    public function edit_package($id=0)
    {       
        if(Auth::user()->user_type != 'Admin') return redirect()->route('access_forbidden');

        if($id==0) 
        return redirect('access_forbidden');

        $data['body']='subscription/edit-package';     
        $data['page_title']=__('Edit Package');     
        $data['modules'] = DB::table('modules')->orderByRaw('module_name')->get();
        $data['value'] = DB::table('package')->where('id',$id)->get();
        $data['payment_config']=DB::table('settings_payments')->get();
        $data['validity_type'] = array('D' => __('Days'), 'W' => __('Weeks'), 'M' => __('Months'), 'Y' => __('Years'));

        $validity_days = $data['value'][0]->validity;

        if ($validity_days % 365 == 0) {

            $data['validity_type_info'] = 'Y';
            $data['validity_amount'] = $validity_days / 365;
        }
        else if ($validity_days % 30 == 0) {

            $data['validity_type_info'] = 'M';
            $data['validity_amount'] = $validity_days / 30;
        }
        else if ($validity_days % 7 == 0) {

            $data['validity_type_info'] = 'W';
            $data['validity_amount'] = $validity_days / 7;
        }
        else {

            $data['validity_type_info'] = 'D';
            $data['validity_amount'] = $validity_days;
        }

        // return view($data['body'],$data);
        return $this->_viewcontroller($data);     
    }


    public function edit_package_action(Request $request) 
    {
        if(config('app.is_demo') == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }

        // if(session()->get('logged_in') == 1 && Auth::user()->user_type != 'Admin') 
        // return redirect()->route('login');

        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }

        if($_POST)
        {
            $validity_type_arr['D'] = 1;
            $validity_type_arr['W'] = 7;
            $validity_type_arr['M'] = 30;
            $validity_type_arr['Y'] = 365;

            $id=$request->input("id");

            $rules = [
                'name' => 'required',
                'visible' => '',
                'highlight' => '',               
                'modules[]' => '',               
                'price' => 'required', 
            ];

            // $this->form_validation->set_rules('name', '<b>'.__("Package Name").'</b>', 'trim|required');
            // $this->form_validation->set_rules('visible', '<b>'.__("Available to Purchase").'</b>', 'trim');
            // $this->form_validation->set_rules('highlight', '<b>'.__("Highlighted Package").'</b>', 'trim');
            // $this->form_validation->set_rules('modules[]','<b>'.__("modules").'</b>','trim');   
            // $this->form_validation->set_rules('price', '<b>'.__("price").'</b>', 'trim|required');    
            
            if(($request->input("is_default")=="1" && $request->input("price")=="Trial") || $request->input("is_default")=="0")  
                $rules['validity_amount'] = 'required|integer';

            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails())
            {
                return redirect()->back()
                ->withErrors($validator)
                ->withInput();
            }
            else
            {

                $package_name=strip_tags($request->input('name'));
                $price=strip_tags($request->input('price'));
                $visible=$request->input('visible');
                $highlight=$request->input('highlight');

                if($visible=='') $visible='0';
                if($highlight=='') $highlight='0';

                // $validity=$request->input('validity');
                $validity_amount=$request->input('validity_amount');
                $validity_type=$request->input('validity_type');
                $validity = $validity_amount * $validity_type_arr[$validity_type];
                $validity_extra_info = implode(',', array($validity_amount, $validity_type));
                
                $modules=array();
                if(count($request->input('modules'))>0)  
                {
                   $modules=$request->input('modules');                            
                }

                $bulk_limit=array();
                $monthly_limit=array();

                foreach ($modules as $value) 
                {
                    $monthly_field="monthly_".$value;
                   
                    $val=$request->input($monthly_field);
                    if($val=="") $val=0;
                    $monthly_limit[$value]=$val;
               

                    $bulk_field="bulk_".$value;
                    
                    $val=$request->input($bulk_field);
                    if($val=="") $val=0;
                    $bulk_limit[$value]=$val;                    
                }


                $modules_str=implode(',',$modules);                        
                               
                if($request->input("is_default")=="1" && $request->input("price")=="0") 
                $validity="0"; 
                $data=array
                (
                    'package_name'=>$package_name,
                    'validity'=>$validity,
                    'visible'=>$visible,
                    'highlight'=>$highlight,
                    'validity_extra_info'=>$validity_extra_info,
                    'module_ids'=>$modules_str,
                    'price'=>$price,
                    'monthly_limit'=>json_encode($monthly_limit),
                    'bulk_limit'=>json_encode($bulk_limit)
                );
                
                // if($this->basic->update_data('package',array("id"=>$id),$data))                                      
                // session->set_flashdata('success_message',1);   
                // else    
                // session->set_flashdata('error_message',1);   
                if (DB::table('package')->where('id', $id)->update($data)) {
                    session()->flash('success_message', 1);
                } else {
                    session()->flash('error_message', 1);
                }


                // print_r($data); exit();
                
                return redirect()->route('package_manager');                 
                
            }
        }   
    }

    public function delete_package($id=0)
    {
        if(Auth::user()->user_type != 'Admin') {
             echo json_encode(array("status"=>"0","message"=>__("Access Forbiden")));
             exit();
        }

        if(config('app.is_demo') == '1')
        {
            echo json_encode(array("status"=>"0","message"=>"This feature is disabled in this demo.")); 
            exit();
        }        
        // if(session()->get('logged_in') == 1 && Auth::user()->user_type != 'Admin') exit();
        if($id==0) exit();

        // if($this->basic->update_data('package',array("id"=>$id),array("deleted"=>"1")))                                      
        // echo json_encode(array("status"=>"1","message"=>__("Package has been deleted successfully"))); 
        // else echo json_encode(array("status"=>"0","message"=>__("Something went wrong, please try again")));
        $data=["deleted"=>"1"];
        if(DB::table('package')->where('id',$id)->update($data))                                      
        echo json_encode(array("status"=>"1","message"=>__("Package has been deleted successfully"))); 
        else echo json_encode(array("status"=>"0","message"=>__("Something went wrong, please try again")));

    } 


    public function usage_history()
    {        
        if(Auth::user()->user_type != 'Member') 
        return redirect()->route('login');

        $current_month = date("n");
        $current_year = date("Y");

        // $info = $this->basic->get_data($table="modules", $where="", $select = "usage_log.*,modules.module_name,modules.id as module_id,limit_enabled,extra_text,bulk_limit_enabled",$join=array('usage_log'=>"usage_log.module_id=modules.id AND user_id =".session()->get("user_id")." AND usage_month=".$current_month." AND usage_year=".$current_year.",left"),$limit='',$start=NULL,$order_by='module_name asc');  
        $info = DB::table('modules')
            ->leftJoin('usage_log', function($join) use($current_month, $current_year) {
                $join->on('usage_log.module_id', '=', 'modules.id')
                     ->where('user_id', session()->get('user_id'))
                     ->where('usage_month', $current_month)
                     ->where('usage_year', $current_year);
            })
            ->select('usage_log.*', 'modules.module_name', 'modules.id as module_id', 'limit_enabled', 'extra_text', 'bulk_limit_enabled')
            ->orderBy('module_name')
            ->get();


        // $package_info=session()->get("package_info");
        $pid =Auth::user()->package_id;
        $package_info=DB::table('package')->where('id',$pid)->first();
        $package_ids= $package_info->module_ids;

        // module count of not monthly
        // $this->db->select('sum(usage_count) as usage_count,module_id');
        // $this->db->where('user_id', Auth::user()->id);
        // $this->db->group_by('module_id');
        // $not_monthy_info = $this->db->get('usage_log')->result_array();
        $not_monthy_info = DB::table('usage_log')
                            ->select(DB::raw('sum(usage_count) as usage_count, module_id'))
                            ->where('user_id', Auth::user()->id)
                            ->groupBy('module_id')
                            ->get();
        $not_monthy_module_info=array(); 
        foreach ($not_monthy_info as $key => $value) 
        {
            $not_monthy_module_info[$value->module_id]=$value->usage_count;
        }
        $data['not_monthy_module_info']=$not_monthy_module_info;

        $monthly_limit='';

        if(isset($package_info->monthly_limit))  $monthly_limit=$package_info->monthly_limit;
        $bulk_limit='';
        if(isset($package_info->bulk_limit))  $bulk_limit=$package_info->bulk_limit;
        $package_name="No Package";
        if(isset($package_info->package_name))  $package_name=$package_info->package_name;
        $validity="0";
        if(isset($package_info->validity))  $validity=$package_info->validity;
        $price="0";
        if(isset($package_info->price))  $price=$package_info->price;

        $data['info']=$info;
        $data['monthly_limit']=json_decode($monthly_limit,true);
        $data['bulk_limit']=json_decode($bulk_limit,true);
        $data['package_name']=$package_name;
        $data['validity']=$validity;
        $data['price']=$price;
        $data['module_access']=explode(',', $package_ids);


        // $config_data=$this->basic->get_data("payment_config");
        $config_data=DB::table('settings_payments')->get();
        $currency=isset($config_data[0]->currency)?$config_data[0]->currency:"USD";
        $currency_icons = $this->currency_icon();
        $data["currency"]=$currency;
        $data["curency_icon"]= isset($currency_icons[$currency])?$currency_icons[$currency]:"$";
        
        $data['body'] = 'member.usage-log';
        $data['page_title'] = __("Usage Log");
        $this->viewcontroller($data);
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
        return view($data['body'],$data);
    }


}
