<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Services\Payment\PaypalServiceInterface;
use Srmklive\PayPal\Services\PayPal as PayPalClient;


class Member extends HomeController
{
    /**
    * load index method. redirect to config
    * @access public
    * @return void
    */

    public function __construct(PaypalServiceInterface $paypal_service)
    {

        $this->set_global_userdata();
        $this->paypal = $paypal_service;
        $this->provider = new PayPalClient;
    }


    public function index()
    {
        return $this->edit_profile();
    }

 
    public function edit_profile()
    {      
        $data['body'] = "member/edit-profile";
        $data['page_title'] = __('Profile');
        // $data["profile_info"]=$this->basic->get_data("users",array("where"=>array("users.id"=>$this->session->userdata("user_id"))),"users.*,package_name",$join);
        $data["profile_info"] = DB::table('users')
                            ->where('users.id', Auth::user()->id)
                            ->leftJoin('package', 'users.package_id', '=', 'package.id')
                            ->select('users.*', 'package.package_name')
                            ->get();

        $data["time_zone_list"] = $this->_time_zone_list();
        // return view($data['body'],$data);
        return $this->_viewcontroller($data);     

    }

    public function edit_profile_action(Request $request)
    {
        if(config('app.is_demo') == '1' && Auth::user()->user_type == 'Admin')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>Permission denied</h2>"; 
            exit();
        }

        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }

        if ($_POST) 
        {
            $user_email = Auth::user()->email;
            $user_password = $request->password;
            $password_confirmation = $request->password_confirmation;
            $user_id = Auth::user()->id;
            $data['email'] = $request->email;
            $name=addslashes(strip_tags($request->input('name')));
            $address=addslashes(strip_tags($request->input('address')));
            $time_zone=addslashes(strip_tags($request->input('time_zone')));

            $id = $request->input('id');
            $rules = [
                'name' => 'required',
                'address' => 'nullable|sometimes|string',
                'time_zone' => 'nullable|sometimes|string',
                // 'logo'=>'nullable|sometimes|image|mimes:png,jpg,jpeg,webp'                          
            ];

            $logout=false;

            if($user_email != $data['email']){
                $rules['email'] = 'required|email|unique:users,email,' . $user_id;
                $logout = true;
            }
            if($user_password!=''){
                // $rules['password'] = ['required','confirmed'];
                $rules['password'] = ['required'];
                if($user_password == $password_confirmation) $logout = true;  
            }

            $validate_data = $request->validate($rules);

            if($request->file('logo')) {

                $file = $request->file('logo');
                $extension = $request->file('logo')->getClientOriginalExtension();
                $filename =Auth::user()->id.'.'.$extension;
                $upload_dir_subpath = 'public/profile';
    
                if(env('AWS_UPLOAD_ENABLED')){
                    try {
                        $upload2S3 = Storage::disk('s3')->putFileAs('profile', $file,$filename);
                        $validate_data['brand_logo'] = Storage::disk('s3')->url($upload2S3);
                    }
                    catch (\Exception $e){
                        $error_message = $e->getMessage();
                    }
                }
                else{
                    $request->file('logo')->storeAs(
                        $upload_dir_subpath, $filename
                    );
                    $validate_data['brand_logo'] = asset('storage/profile').'/'.$filename;
                }
            }

            if(empty($validate_data['address'])) $validate_data['address']='';

            if($user_password != '') $validate_data['password'] =  Hash::make($user_password);
            DB::table('users')->where('id',$user_id)->update($validate_data);

            if($logout) return redirect()->route('logout');
        

            session()->flash('success_message', 1);
            return redirect()->route('edit_profile');
            
        }
    }

    public function paypal_action(Request $request,$package_id=0,$buyer_user_id=0,$parent_user_id=0)
    {
        if(check_build_version() != 'double') abort(404);

        $payment_config = $this->get_payment_config_parent();
        $package_data = $this->get_package($package_id);
        $format_settings = ['currency'=>$payment_config->currency ?? 'USD','decimal_point'=>$payment_config->decimal_point ?? null,'thousand_comma'=>'0','currency_position'=>$payment_config->currency_position ?? 'left'];
        $payment_amount = (float) $package_data->price ?? 0;
        $price_raw_data = format_price($payment_amount,$format_settings,$package_data->discount_data,['return_raw_array'=>true]);
        $payment_amount = (float) $price_raw_data->sale_price_formatted ?? 0;
        $buyer_information = DB::table('users')->select('name','email')->where('id',$buyer_user_id)->first();
        $name = $buyer_information->name;
        $email = $buyer_information->email;
        $package_name = $package_data->package_name;
        $paypal_data = isset($payment_config->paypal) ? json_decode($payment_config->paypal) : [];
        $paypal_client_id = $paypal_data->paypal_client_id ?? '';
        $paypal_client_secret = $paypal_data->paypal_client_secret ?? '';
        $paypal_app_id = $paypal_data->paypal_app_id ?? '';
        $paypal_mode = $paypal_data->paypal_mode ?? 'sandbox';
        $paypal_payment_type = $paypal_data->paypal_payment_type ?? 'manual';
        $notify_url = get_domain_only(env('APP_URL'))=='sitespy.test' ? 'https://ezsoci.com/botsailor-test-ipn/paypal.php' : route('paypal-ipn',$paypal_mode);

        $this->paypal->paypal_client_id = $paypal_client_id;
        $this->paypal->paypal_client_secret = $paypal_client_secret;
        $this->paypal->paypal_app_id = $paypal_app_id;
        $this->paypal->notify_url = $notify_url;
        $this->paypal->name = $name;
        $this->paypal->email = $email;
        $this->paypal->mode = $paypal_mode;
        $provider = $this->provider;// call PypalClient
        $this->paypal->provider = $provider;
        $this->paypal->plan_id = $request->plan_id;
        $this->paypal->currency = $request->currency_code;
        $this->paypal->success_url = $request->return;
        $this->paypal->cancel_url = $request->cancel_return;
        $this->paypal->package_name = $request->package_name;
        $this->paypal->paypal_subscriber_url();
    }

    public function paypal_subscription_cancel()
    {
        if(check_build_version() != 'double') abort(404);
        $buyer_user_id = Auth::user()->id;
        $payment_config = $this->get_payment_config_parent();
        $paypal_data = isset($payment_config->paypal) ? json_decode($payment_config->paypal) : [];
        $paypal_client_id = $paypal_data->paypal_client_id ?? '';
        $paypal_client_secret = $paypal_data->paypal_client_secret ?? '';
        $paypal_app_id = $paypal_data->paypal_app_id ?? '';
        $paypal_mode = $paypal_data->paypal_mode ?? 'sandbox';

        $data = DB::table('users')->select('paypal_subscriber_id')->where('id',$buyer_user_id)->first();
        $subscription_id = $data->paypal_subscriber_id;

        $this->paypal->paypal_client_id = $paypal_client_id;
        $this->paypal->paypal_client_secret = $paypal_client_secret;
        $this->paypal->paypal_app_id = $paypal_app_id;
        $this->paypal->mode = $paypal_mode;
        $provider = $this->provider;// call PypalClient
        $this->paypal->provider = $provider;
        $this->paypal->subscription_id = $subscription_id;
        $response = $this->paypal->paypal_subscription_cancel();
        if($response == ''){
             DB::table('users')->where(['id'=>$buyer_user_id])->update(['subscription_enabled'=>'0','subscription_data'=>NULL,'paypal_subscriber_id'=>'','paypal_next_check_time'=>NULL]);
            return redirect()->back();
        }
        else dd($response);

    }



}
