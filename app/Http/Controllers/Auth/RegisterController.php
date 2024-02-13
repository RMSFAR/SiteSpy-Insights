<?php

namespace App\Http\Controllers\Auth;

use Rules\Password;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\SimpleHtmlEmail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends HomeController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function create()
    {
        $signup_form = config('my_config.enable_signup_form');

        if($signup_form == '0')
        {
            return redirect()->route('login');
        }
        $data['num1']=$this->_random_number_generator(1);
        $data['num2']=$this->_random_number_generator(1);
        $captcha= $data['num1']+ $data['num2'];
        session(["sign_up_captcha" => $captcha]);

        $data["page_title"] = __("Sign Up");

        return view('auth.register',$data);
    }

    public function store(Request $request)
    {

        $captcha = $request->input('captcha', TRUE);
        if($captcha!=session("sign_up_captcha"))
        {
            session("sign_up_captcha_error",__("invalid captcha"));
            return $this->create();

        }

        $name = strip_tags($request->input('name', TRUE));
        $email = $request->input('email', TRUE);
        // $mobile = $request->input('mobile', TRUE);
        $password = $request->input('password', TRUE);

        // $this->db->trans_start();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed'],
            'captcha' => ['required'],
        ]);

        $default_package=DB::table("package")->where("is_default","1")->get();
        $expiry_date='';
        $package_id='';
        $to_date='';
        $validity='';
        if(isset($default_package))
        {
            
            $validity=$default_package[0]->validity;
            $package_id=$default_package[0]->id;

            $to_date=date("Y-m-d H:i:s");
        
            $expiry_date=date("Y-m-d H:i:s",strtotime('+'.$validity.' day',strtotime($to_date)));
        }

        $code = $this->_random_number_generator();
        $data = array(
            'name' => $name,
            'email' => $email,
            // 'mobile' => $mobile,
            'password' => Hash::make($password),
            'address' => '',
            'user_type' => 'Member',
            'status' => '0',
            'activation_code' => $code,
            'expired_date'=>$expiry_date,
            'package_id'=>(int)$package_id
            );

        // $user=DB::table('users')->insert($data);

        if (DB::table('users')->insert($data)) {

            $mail_service_id = config('my_config.mail_service_id');
            $system_short_name= config('my_config.product_short_name');
            $mailchimp_list_tag="Sign up - {$system_short_name}";
            
            $email_template_info = DB::table("email_template_management")->where('template_type',"signup_activation")->select('subject','message')->get();

            $url = url('')."/home/account_activation";
            $url_final = "<a href='".$url."' target='_BLANK'>".$url."</a>";

            $productname = config('my_config.product_name');

            if(isset($email_template_info[0]) && $email_template_info[0]->subject != '' && $email_template_info[0]->message != '')
            {
                $subject = str_replace('#APP_NAME#',$productname,$email_template_info[0]->subject);
                $message = str_replace(array("#APP_NAME#","#ACTIVATION_URL#","#ACCOUNT_ACTIVATION_CODE#"),array($productname,$url_final,$code),$email_template_info[0]->message);
                // echo "Database Has data"; exit();

            } else
            {
                $subject = $productname." | Account activation";
                $message = "<p>".__("to activate your account please perform the following steps")."</p>
                            <ol>
                                <li>".__("go to this url").":".$url_final."</li>
                                <li>".__("enter this code").":".$code."</li>
                                <li>".__("activate your account")."</li>
                            </ol>";
            }

            $from = config('my_config.institute_email');
            $to = $email;
            $mask = config("my_config.product_name");
            $html = 1;

            // $this->_mail_sender($from, $to, $subject, $message, $mask, $html);
            set_email_config();
            Mail::to($email)->send(new SimpleHtmlEmail($mask,$message,$subject));

            // Auth::login($user);

            session(['reg_success' => 1]);

            return redirect()->route('register');

        }

        // event(new Registered($user));

        // $credentials = [
        //     'email' => $email,
        //     'password' => $password
        // ];

        // if (Auth::attempt($credentials)) {
        //     return redirect(route('dashboard'));
        // } else {
        //     return $this->create();
        // }

    }

}
