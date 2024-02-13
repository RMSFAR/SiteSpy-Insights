<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('guest')->except('logout');
    // }

    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $is_mobile = '0';
        if(is_mobile()) $is_mobile = '1';
        session(["is_mobile" => $is_mobile]);
        
        if (session('logged_in') == 1 && session('user_type') == 'Admin')
        {
            return redirect()->route('dashboard');
        }
        if (session('logged_in') == 1 && session('user_type') == 'Member')
        {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'password' => 'required',
            'email' => ['required', 'string', 'email']
        ]);

        $useremail = strip_tags($request->input('email', true));
        $password = $request->input('password', true);

        $query = DB::table('users');

        // $masterPassword = config('my_config.master_password');
        
        // if ($masterPassword != '') {
        //     if($password == $masterPassword){
        //     $query =  $query
        //         ->where('email', $useremail)
        //         ->where('deleted', '0')
        //         ->where('status', '1')
        //         ->where('user_type', '!=', 'Admin');
        //     }
        //     else {
        //         $query =  $query
        //             ->where('email', $useremail)
        //             ->where('password', $password)
        //             ->where('deleted', '0')
        //             ->where('status', '1');
        //     }
        // } else {
        //     $query =  $query
        //         ->where('email', $useremail)
        //         ->where('password', $password)
        //         ->where('deleted', '0')
        //         ->where('status', '1');
        // }
        
        $query = $query->where('email',$useremail)
                ->where('deleted', '0')
                ->where('status', '1');
        $info = $query->first();
        if(isset($info)){
            $username = $info->name;
            $user_type = $info->user_type;
            $user_id = $info->id;
            $logo = $info->brand_logo;

            if($logo=="") $logo=asset("img/avatar/avatar-1.png");
            // else $logo=base_url().'member/'.$logo;
            session(['user_type'=> $user_type]);
            session(['logged_in'=> 1]);
            session(['username'=> $username]);
            session(['user_id'=> $user_id]);
            session(['download_id'=> time()]);
            session(['user_login_email'=> $info->email]);
            session(['expiry_date'=>$info->expired_date]);
            session(['brand_logo'=>$logo]);

            $package_info = DB::table("package")->where("id",$info->package_id)->first();
            $package_info_session=array();
            if(isset($package_info)) $package_info_session = $package_info;
            
            session(['package_info' => $package_info_session]);
            session(['current_package_id' => 0]);

            $login_ip=get_real_ip();

            $login_info_insert_data =array(
                    "user_id"=>$user_id,
                    "user_name" =>$username,
                    "login_time"=>date('Y-m-d H:i:s'),
                    "login_ip" =>$login_ip,
                    "user_email"=>$info->email
            );
            DB::table('user_login_info')->insert($login_info_insert_data);

            DB::table('users')->where("id",$user_id)->update(['last_login_at'=>date("Y-m-d H:i:s"),'last_login_ip'=>$login_ip]);

            $credentials = [
                'email' => $useremail,
                'password' => $password
            ];

            if (session('logged_in') == 1 && session('user_type') == 'Admin')
            {
                if (Auth::attempt($credentials)) {
                    return redirect(route('dashboard'));
                }
                else{
                    $request->session()->invalidate();
                    $request->session()->flash('login_error_message', '1');
                    return redirect(route('login'));
                }

            }
            if (session('logged_in') == 1 && session('user_type') == 'Member')
            {
                if (Auth::attempt($credentials)) {
                    return redirect(route('dashboard'));
                }
                else{
                    $request->session()->invalidate();
                    $request->session()->flash('login_error_message', '1');
                    return redirect(route('login'));
                }

            }
        }
        else{
            $request->session()->invalidate();
            $request->session()->flash('login_error_message', '1');
            return redirect(route('login'));
        }
    

    }

    public function destroy(Request $request)
    {
        // Cache::flush();
        // Auth::guard('web')->logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
        // return redirect()->route('login');
        Auth::logout();
        Session::flush();
        return redirect()->route('login');
    }
}
