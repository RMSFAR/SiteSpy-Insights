<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SocialAppsController extends HomeController
{
    public $is_demo=0;
    public $user_id=1;

    public function __construct()
    {
       $this->set_global_userdata(false);  
    }

    public function index()
    {
        $data['body'] = 'system.social-apps.index';
        return $this->_viewcontroller($data);      
    }
    public function add_facebook_settings()
    {
        if (Auth::user()->user_type != 'Admin')
        return redirect()->route('login');

        $data['facebook_settings'] = array();
        $appsData = DB::table('facebook_rx_config')->first();
        if(config('app.is_demo') == '1')
        {
            if(!empty($appsData)){
            $appsData->app_id = 'XXXXXXXXXXX';
            $appsData->app_secret = 'XXXXXXXXXXX';
            }
        }
        $data['app_data'] = isset($appsData) ? $appsData : "";
        $data['body'] = 'system.social-apps.add-facebook-settings';
        return $this->_viewcontroller($data);      
    }
    public function facebook_settings_update_action(Request $request)
    {
        if(config('app.is_demo') == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }
        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }

        $request->validate([
            'api_id' => 'required',
            'api_secret' => 'required',
        ]);

        $app_name = $request->input('app_name') ?? '';
        $app_id = $request->input('api_id');
        $app_secret = $request->input('api_secret');
        $status = $request->input('status') ?? '0';
        


        $data = [
            'id' => 1,
            'user_id'=>1,
            'app_name' => $app_name,
            'app_id' => $app_id,
            'app_secret' => $app_secret,
            'status' => $status,
        ];

        $facebook_settings = DB::table('facebook_rx_config')->get();

        if (count($facebook_settings) > 0 ) {
            DB::table('facebook_rx_config')->update($data);
        }
        else
        {
            DB::table('facebook_rx_config')->insert($data);
        }
        $request->session()->flash('success_message', '1');
        return redirect()->back()->with('success', 'FB settings updated successfully.');

    }
    public function google_settings()
    {

        if (Auth::user()->user_type != 'Admin')
        return redirect()->route('login');

        $google_settings = DB::table('login_config')->first();

        if (!isset($google_settings)) $google_settings = array();
        else $google_settings = $google_settings;

        if(config('app.is_demo') == '1')
        {
            if(!empty($google_settings)){
                $google_settings->api_key = 'XXXXXXXXXXX';
                $google_settings->google_client_secret = 'XXXXXXXXXXX';
            }
        }
        $data['google_settings'] = $google_settings;

        $data['body'] = 'system.social-apps.google-settings';
        return $this->_viewcontroller($data);      
    }

    public function google_settings_action(Request $request)
    {
        if(config('app.is_demo') == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }
        $request->validate([
            'api_key' => 'required',
            'google_client_id' => 'required',
            'google_client_secret' => 'required',
        ]);

        $app_name = $request->input('app_name');
        $api_key = $request->input('api_key');
        $google_client_id = $request->input('google_client_id');
        $google_client_secret = $request->input('google_client_secret');
        $status = $request->input('status') ?? '0';
        $deleted = $request->input('deleted') ?? '0';


        $data = [

            'app_name' => $app_name,
            'api_key' => $api_key,
            'google_client_id' => $google_client_id,
            'google_client_secret' => $google_client_secret,
            'status' => $status,
            'deleted' => $deleted,
        ];

        $settings = DB::table('login_config')->get();

        if (count($settings) > 0 ) {
            DB::table('login_config')->update($data);
        }
        else
        {
            DB::table('login_config')->insert($data);
        }
        $request->session()->flash('success_message', '1');
        return redirect()->back()->with('success', 'Google settings updated successfully.');
        
    }

    public function connectivity_settings()
    {
        $data['config_data'] = DB::table('config')->where("user_id",Auth::user()->id)->get();
        $data['config_data'] = json_decode(json_encode($data['config_data']));
        $data['is_demo'] = config('app.is_demo');

        $data['body'] = 'system.social-apps.connectivity-settings';
        return $this->_viewcontroller($data);       
    }

    public function connectivity_settings_action(Request $request)
    {
    	if(config('app.is_demo') == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }

        // if ($this->session->userdata('user_type') != 'Admin')
        //     redirect('home/login_page', 'location');

        // if ($request->session()->userdata('user_type') !="Admin") {
        //     return redirect()->route('access_forbidden');
        // }
        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }


        $google_safety_api = $request->input('google_safety_api') ?? '';
        $moz_access_id = $request->input('moz_access_id') ?? '';
        $moz_secret_key = $request->input('moz_secret_key') ?? '';
        $virus_total_api = $request->input('virus_total_api') ?? '';
        $bitly_access_token = $request->input('bitly_access_token') ?? '';
        $rebrandly_api_key = $request->input('rebrandly_api_key') ?? '';

        $mobile_ready_api_key = $request->input('mobile_ready_api_key') ?? '';
        $facebook_app_id = $request->input('facebook_app_id') ?? '';
        $facebook_app_secret = $request->input('facebook_app_secret') ?? '';


        $update_data = [

            "google_safety_api"=>$google_safety_api,
            "moz_access_id"=>$moz_access_id,
            "moz_secret_key"=>$moz_secret_key,
            "virus_total_api"=>$virus_total_api,
            "bitly_access_token"=>$bitly_access_token,
            "rebrandly_api_key"=>$rebrandly_api_key
        ];
        $insert_data = [
            "google_safety_api"=>$google_safety_api,
            "moz_access_id"=>$moz_access_id,
            "moz_secret_key"=>$moz_secret_key,
            "virus_total_api"=>$virus_total_api,
            "bitly_access_token"=>$bitly_access_token,
            "rebrandly_api_key"=>$rebrandly_api_key,
            "mobile_ready_api_key"=>$mobile_ready_api_key,
            "facebook_app_id"=>$facebook_app_id,
            "facebook_app_secret"=>$facebook_app_secret,
            "user_id"=>Auth::user()->id
        ];

        if(Auth::user()->user_type == 'Admin' && config('my_config.use_admin_app') == 'yes')
        {
            $update_data['access'] = 'all_users';
            $insert_data['access'] = 'all_users';
        }


        $settings = DB::table('config')->get();

        if (count($settings) > 0 ) {
            DB::table('config')->update($update_data);
        }
        else
        {
            DB::table('config')->insert($insert_data);
        }
        $request->session()->flash('success_message', '1');
        return redirect()->back()->with('success', 'connectivity settings updated successfully.');   
    }

    public function proxy_settings()
    {
        $data['body'] = 'system.social-apps.proxy-settings';
        return $this->_viewcontroller($data);     
    }

    // public function proxy_settings_data(Request $request)
    // {

    //     $proxy_keyword  = trim($request->input("proxy_keyword"));

    //     $display_columns = ["#",'id','proxy','port','admin_permission','username','password','actions'];
    //     $search_columns = ["proxy","port","username","password"];

    //     $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    //     $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
    //     $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
    //     $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 1;
    //     $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'id';
    //     $order = isset($_POST['order'][0]['dir']) ? strval($_POST['order'][0]['dir']) : 'desc';
    //     $order_by=$sort." ".$order;

    //     $where_custom="user_id = ".Auth::user()->id;

    //     if ($proxy_keyword != '') {

    //         foreach ($search_columns as $key => $value) 
    //             $temp[] = $value." LIKE "."'%$proxy_keyword%'";

    //         $imp = implode(" OR ", $temp);
    //         $where_custom .=" AND (".$imp.") ";
    //     }

    //     // $table = "config_proxy";
    //     // $this->db->where($where_custom);
    //     // $info = $this->basic->get_data($table,$where='',$select='',$join='',$limit,$start,$order_by,$group_by='');
    //     $info = DB::table('config_proxy')->get();
    //     // dd($info);

    //     // $this->db->where($where_custom);
    //     // $total_rows_array = $this->basic->count_row($table,$where='',$count="id",$join,$group_by='');
    //     $total_rows_array = DB::table('config_proxy')->count('id');
    //     $total_result = $total_rows_array[0]['total_rows'];



    //     for ($i=0; $i < count($info) ; $i++) 
    //     { 

            
    //             if($info[$i]['admin_permission'] == 'everyone') {

    //                 $info[$i]['admin_permission'] = "<div class='badge badge-primary pointer text-center'>".ucwords($info[$i]['admin_permission'])."</div>";

    //             } 
    //             else {

    //                 $info[$i]['admin_permission'] = "<div class='badge badge-warning pointer text-center'>".ucwords($info[$i]['admin_permission'])."</div>";

    //             }


    //         $info[$i]['actions'] = "<div><a class='btn btn-outline-warning btn-circle edit_proxy' href='#' table_id='".$info[$i]['id']."'><i class='fas fa-edit'></i></a>&nbsp;&nbsp;<a class='btn btn-outline-danger btn-circle delete_proxy' href='#' table_id='".$info[$i]['id']."'><i class='fas fa-trash-alt'></i></a></div>";
    //     }

    //     $data['draw'] = (int)$_POST['draw'] + 1;
    //     $data['recordsTotal'] = $total_result;
    //     $data['recordsFiltered'] = $total_result;
    //     $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

    //     echo json_encode($data);
    // }
    
    public function proxy_settings_data(Request $request)
{

    $userId = '1';

    $proxyKeyword = trim($request->input('proxy_keyword'));

    $display_columns = ['#', 'id', 'proxy', 'port', 'admin_permission', 'username', 'password', 'actions'];
    $searchColumns = ['proxy', 'port', 'username', 'password'];

    $page = $request->input('page', 1);
    $start = $request->input('start', 0);
    $limit = $request->input('length', 10);
    $sortIndex = $request->input('order.0.column', 1);
    $sort = $display_columns[$sortIndex] ?? 'id';
    $order = $request->input('order.0.dir', 'desc');
    $orderBy = $sort . ' ' . $order;

    $whereCustom = "user_id = " . Auth::user()->id;

    if ($proxyKeyword) {
        $temp = [];
        foreach ($searchColumns as $key => $value) {
            $temp[] = $value . " LIKE '%" . $proxyKeyword . "%'";
        }

        $imp = implode(' OR ', $temp);
        $whereCustom .= " AND (" . $imp . ") ";
    }
    $info = DB::table('config_proxy')
        ->whereRaw($whereCustom)
        ->offset($start)
        ->limit($limit)
        ->orderBy($sort, $order)
        ->get();

    $total_result = DB::table('config_proxy')
        ->whereRaw($whereCustom)
        ->count();

        // dd($info);

    foreach ($info as $i => $row) {
        // if (auth()->user()->user_type === 'Admin') {
            if ($row->admin_permission === 'everyone') {
                $info[$i]->admin_permission = "<div class='badge badge-primary pointer text-center'>" . ucwords($row->admin_permission) . "</div>";
            } else {
                $info[$i]->admin_permission = "<div class='badge badge-warning pointer text-center'>" . ucwords($row->admin_permission) . "</div>";
            }
        // }
        //  else {
        //     unset($display_columns[4]);
        // }

        $info[$i]->actions = "<div><a class='btn btn-outline-warning btn-circle edit_proxy' href='#' table_id='".$info[$i]->id."'><i class='fas fa-edit'></i></a>&nbsp;&nbsp;<a class='btn btn-outline-danger btn-circle delete_proxy' href='#' table_id='".$info[$i]->id."'><i class='fas fa-trash-alt'></i></a></div>";
        
    }
    
    $data['draw'] = (int)$_POST['draw'] + 1;
    $data['recordsTotal'] = $total_result;
    $data['recordsFiltered'] = $total_result;
    $data['data'] = array_format_datatable_data($info, $display_columns ,$start);

    echo json_encode($data);

}
    public function insert_proxy(Request $request)
    {
        if(!$_POST) exit();
        

        $result = [];
        $insert_proxy_data = [];
        // $user_type = session()->userdata("user_type");

        $insert_proxy_data['proxy'] = trim(strip_tags($request->input("proxy",true)));
        $insert_proxy_data['port'] = trim(strip_tags($request->input("proxy_port",true)));
        $insert_proxy_data['username'] = trim(strip_tags($request->input("proxy_username",true)));
        $insert_proxy_data['password'] = trim(strip_tags($request->input("proxy_password",true)));
        $insert_proxy_data['admin_permission'] = $request->input("permission",true);
        $insert_proxy_data['user_id'] = Auth::user()->id;

        

        // if($user_type == "Member") {
        //     $insert_proxy_data ['admin_permission'] = "only me";
        // }
        if(DB::table('config_proxy')->insert($insert_proxy_data)) {

            $result['status'] = "1";
            $result['message'] = __("Proxy information have been added successfully.");

        } else {

            $result['status'] = "0";
            $result['message'] = __("something went wrong, please try once again.");
        }

        echo json_encode($result); 
    }

    public function ajax_update_proxy_info(Request $request)
    {

        $table_id = $request->input("table_id");
        if($table_id == "" || $table_id == "0") exit;

        // $get_proxy_data = $this->basic->get_data("config_proxy",array("where"=>array("id"=>$table_id,"user_id"=>Auth::user()->id)));
        $get_proxy_data = DB::table('config_proxy')->where([
            ['id', '=', $table_id],
            ['user_id', '=', Auth::user()->id]
            ])->get();

        $form_html = '
            <div class="row">
                <div class="col-12">
                    <form action="#" method="POST" id="update_proxy_form">
                        <input type="hidden" name="table_id" value="'.$get_proxy_data[0]->id.'">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>'.__('Proxy').'</label>
                                    <input type="text" class="form-control" id="updated_proxy" name="proxy" value="'.$get_proxy_data[0]->proxy.'">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>'.__('Proxy Port').'</label>
                                    <input type="text" class="form-control" id="updated_proxy_port" name="proxy_port" value="'.$get_proxy_data[0]->port.'">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>'.__('Proxy Username').'</label>
                                    <input type="text" class="form-control" id="updated_proxy_username" name="proxy_username" value="'.$get_proxy_data[0]->username.'">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>'.__('Proxy Password').'</label>
                                    <input type="text" class="form-control" id="updated_proxy_password" name="proxy_password" value="'.$get_proxy_data[0]->password.'">
                                </div>
                            </div>';

                            // if(session()->userdata("user_type") == "Admin") {
                                $permission = $get_proxy_data[0]->admin_permission;

                                if($permission == "everyone") $everyone = "checked";
                                else $everyone = "";

                                if($permission == "only me") $onlyme = "checked";
                                else $onlyme = "";

                                $form_html .='
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>'.__('Proxy Permission').'</label>
                                            <div class="custom-switches-stacked mt-2">
                                                <div class="row">   
                                                    <div class="col-6">
                                                        <label class="custom-switch">
                                                            <input type="radio" name="permission" value="everyone" class="permission custom-switch-input" '.$everyone.'>
                                                            <span class="custom-switch-indicator"></span>
                                                            <span class="custom-switch-description">'.__('Everyone').'</span>
                                                        </label>
                                                    </div>                        
                                                    <div class="col-6">
                                                        <label class="custom-switch">
                                                            <input type="radio" name="permission" value="only me" class="permission custom-switch-input" '.$onlyme.'>
                                                            <span class="custom-switch-indicator"></span>
                                                            <span class="custom-switch-description">'.__('Only me').'</span>
                                                        </label>
                                                    </div>
                                                </div>                                  
                                            </div>
                                        </div> 
                                    </div>';
                            // }

                            $form_html .= '
                                        </div>
                                    </form>
                                </div>
                            </div>';
        echo $form_html;
    }

    public function update_proxy_settings(Request $request)
    {
        if(!$_POST) exit();
        

        $table_id = trim($request->input("table_id"));

        $result = [];
        $update_proxy_data = [];
        // $user_type = session()->userdata("user_type");

        $update_proxy_data['proxy'] = trim(strip_tags($request->input("proxy")));
        $update_proxy_data['port'] = trim(strip_tags($request->input("proxy_port")));
        $update_proxy_data['username'] = trim(strip_tags($request->input("proxy_username")));
        $update_proxy_data['password'] = trim(strip_tags($request->input("proxy_password")));
        $update_proxy_data['admin_permission'] = $request->input("permission");
        $update_proxy_data['user_id'] = Auth::user()->id;

        // if($user_type == "Member") {
        //     $update_proxy_data ['admin_permission'] = "only me";
        // }

        // $table = "config_proxy";
        // if($this->basic->update_data($table,["id"=>$table_id,"user_id"=>Auth::user()->id],$update_proxy_data)) {
            
        if(DB::table('config_proxy')->where(['id' => $table_id, 'user_id' => Auth::user()->id])->update($update_proxy_data)) {

            $result['status'] = "1";
            $result['message'] = __("Proxy information have been updated successfully.");

        } else {

            $result['status'] = "0";
            $result['message'] = __("something went wrong, please try once again.");
        }

        echo json_encode($result); 
    }

    public function delete_proxy(Request $request)
    {

        $table_id = $request->input("table_id");
        if($table_id == "" || $table_id == "0") exit;

        // if($this->basic->delete_data("config_proxy",array("id"=>$table_id,"user_id"=>Auth::user()->id))) {
        // DB::table('config_proxy')->where(['id' => $table_id, 'user_id' => Auth::user()->id])->delete();
        if(DB::table('config_proxy')->where(['id' => $table_id, 'user_id' => Auth::user()->id])->delete()) {
            echo "1";
        } else {
            echo "0";
        }
    }
}
