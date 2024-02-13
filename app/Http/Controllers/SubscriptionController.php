<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SimpleHtmlEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends HomeController
{
    public function user_manager()
    {
        $data['body']='user/user-list';
        $data['page_title']=__("User Manager");
        // return view($data['body'],$data);
        return $this->_viewcontroller($data);     
  
    }

    public function user_manager_data(Request $request)
    {           
        // $this->ajax_check();
        $search_value = $_POST['search']['value'];
        $display_columns = array("#","CHECKBOX",'user_id','avatar','name', 'email','package_name', 'status', 'user_type','expired_date', 'actions', 'add_date','last_login_at','last_login_ip');
        $search_columns = array('name', 'email','mobile','add_date','expired_date','last_login_ip');

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 2;
        $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'user_id';
        $order = isset($_POST['order'][0]['dir']) ? strval($_POST['order'][0]['dir']) : 'desc';
        $order_by=$sort." ".$order;


        $user_id = Auth::user()->id;
        $table = "users";
        $select= array("users.*","users.id as user_id","package.package_name");
        $query = DB::table($table)->select($select);
        if ($search_value != '')
        {
            $query->where(function($query) use ($search_columns,$search_value){
                foreach ($search_columns as $key => $value) $query->orWhere($value, 'like',  "%$search_value%");
            });
        }
            
        // $table="users";
        // $join = array('package'=>"package.id=users.package_id,left");
        // $select= array("users.*","users.id as user_id","package.package_name");
        // $info=$this->basic->get_data($table,$where,$select,$join,$limit,$start,$order_by,$group_by='');
        // $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        // $total_result=$total_rows_array[0]['total_rows'];

        $info = $query->leftJoin('package', 'package.id', '=', 'users.package_id')->orderByRaw($order_by)->offset($start)->limit($limit)->get();

        $query = DB::table($table)->get();
        $total_result=$query->count();

        $i=0;
        $base_url=url('/');
        foreach ($info as $key => $value) 
        {
            $status = $info[$i]->status;
            if($status=='1') $info[$i]->status = "<i title ='".__('Active')."'class='status-icon fas fa-toggle-on text-primary'></i>";
            else $info[$i]->status = "<i title ='".__('Inactive')."'class='status-icon fas fa-toggle-off gray'></i>";

            $last_login_at = $info[$i]->last_login_at;
            if($last_login_at=='0000-00-00 00:00:00') $info[$i]->last_login_at = __("Never");
            else $info[$i]->last_login_at = date("jS M y H:i",strtotime($info[$i]->last_login_at));

            $expired_date = $info[$i]->expired_date;
            if($expired_date=='0000-00-00 00:00:00' || $info[$i]->user_type=="Admin") $info[$i]->expired_date = "-";
            else $info[$i]->expired_date = date("jS M y",strtotime($info[$i]->expired_date));

            $info[$i]->add_date = date("jS M y",strtotime($info[$i]->add_date));

            if($info[$i]->package_name=="") $info[$i]->package_name = "-";
  
            $user_name = $info[$i]->name;
            $user_id = $info[$i]->id;
            $str="";   
            
            $str=$str."<a class='btn btn-circle btn-outline-warning' data-toggle='tooltip' title='".__('Edit')."' href='".$base_url.'/admin/edit_user/'.$info[$i]->user_id."'>".'<i class="fas fa-edit"></i>'."</a>";
            $str=$str."&nbsp;<a class='btn btn-circle btn-outline-dark change_password' href='' data-toggle='tooltip' title='".__('Change Password')."' data-id='".$user_id."' data-user='".htmlspecialchars($user_name)."'>".'<i class="fas fa-key"></i>'."</a>";
            $str=$str."&nbsp;<a csrf_token='".csrf_token()."' href='".$base_url.'/home/user_delete_action/'.$info[$i]->user_id."' class='are_you_sure_datatable btn btn-circle btn-outline-danger' data-toggle='tooltip' title='".__('Delete')."'>".'<i class="fa fa-trash"></i>'."</a>";

            // if($this->session->userdata('license_type') == 'double')
            //     $str=$str."&nbsp;<a target='_BLANK' href='".$base_url.'dashboard/index/'.$info[$i]->user_id"]."' class='btn btn-circle btn-outline-info' data-toggle='tooltip' title='".__('Activity')."'>".'<i class="fas fa-bolt"></i>'."</a>";
             
            // if($this->session->userdata('license_type') == 'double')
            // $info[$i]->actions"] = "<div style='min-width:208px'>".$str."</div>";
            // else $info[$i]->actions"] = "<div style='min-width:161px'>".$str."</div>";
            
            $info[$i]->actions = "<div style='min-width:161px'>".$str."</div>";
            $info[$i]->actions .= "<script>$('[data-toggle=\"tooltip\"]').tooltip();</script>";;

            // $logo=$info[$i]->brand_logo;

            // if($logo=="") $logo=asset("img/avatar/avatar-3.png");
            // else $logo=url('/').'member/'.$logo;

            $logo= isset($info[$i]->brand_logo) ? $info[$i]->brand_logo : ""; 
            if($logo=="") $logo=file_exists(asset("assets/img/avatar/avatar-3.png")) ? asset("assets/img/avatar/avatar-3.png") : asset("assets/img/avatar/avatar-3.png");
            else $logo=$logo;


            $info[$i]->avatar = "<img src='".$logo."' width='40px' height='40px' class='rounded-circle'>";

            if($info[$i]->user_type=='Admin') $tie="-circle orange";
            else $tie="-noicon blue";

            $info[$i]->name = "<span data-toggle='tooltip' title='".__($info[$i]->user_type)."'><i class='fas fa-user".$tie." text-warning'></i> ".$info[$i]->name." </span><script> $('[data-toggle=\"tooltip\"]').tooltip();</script>";
                
            if(config('app.is_demo')=='1')  $info[$i]->email ="******@*****.***";
            if(config('app.is_demo')=='1')  $info[$i]->last_login_ip ="XXXXXXXXX";

            $i++;
        }

        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="user_id");

        echo json_encode($data);
    }

    public function add_user()
    {       
        $data['body']='user/add-user';     
        $data['page_title']=__('Add User');     
        // $packages=$this->basic->get_data('package',$where='',$select='',$join='',$limit='',$start='',$order_by='package_name asc');
        $packages=DB::table('package')->orderByRaw('package_name')->get();
        $data['packages'] = format_data_dropdown($packages,"id","package_name",false);
        // return view($data['body'],$data);
        return $this->_viewcontroller($data);     

    }


    public function add_user_action(Request $request) 
    {
        if(config('app.is_demo') == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }

        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }

        if($_POST)
        {

            $rules = [
                'email' => 'required|email|unique:users,email',                            
                'password' => 'required', 
                'confirm_password' => 'required|same:password', 
                'user_type' => 'required', 
            ];

            // $this->form_validation->set_rules('name', '<b>'.__("Full Name").'</b>', 'trim');      
            // $this->form_validation->set_rules('email', '<b>'.__("Email").'</b>', 'trim|required|valid_email|is_unique[users.email]');      
            // $this->form_validation->set_rules('mobile', '<b>'.__("Mobile").'</b>', 'trim');      
            // $this->form_validation->set_rules('password', '<b>'.__("Password").'</b>', 'trim|required');      
            // $this->form_validation->set_rules('confirm_password', '<b>'.__("Confirm Password").'</b>', 'trim|required|matches[password]');      
            // $this->form_validation->set_rules('address', '<b>'.__("Address").'</b>', 'trim');      
            // $this->form_validation->set_rules('user_type', '<b>'.__("User Type").'</b>', 'trim|required');      
            // $this->form_validation->set_rules('status', '<b>'.__("Status").'</b>', 'trim');

            if($request->input("user_type")=="Member")     
            {
                // $this->form_validation->set_rules('package_id', '<b>'.__("Package").'</b>', 'trim|required');      
                // $this->form_validation->set_rules('expired_date', '<b>'.__("Expiry Date").'</b>', 'trim|required');
                $rules['package_id'] = 'required';
                $rules['expired_date'] = 'required';
            }
                
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails())
            {
                return redirect()->back()
                ->withErrors($validator)
                ->withInput();
            }
            else
            { 
                // $this->csrf_token_check();

                $name=strip_tags($request->input('name'));
                $email=strip_tags($request->input('email'));
                $mobile=strip_tags($request->input('mobile'));
                $password=Hash::make($request->input('password'));
                $confirm_password=$request->input('confirm_password');
                $address=strip_tags($request->input('address'));
                $user_type=$request->input('user_type');
                $status=$request->input('status');
                $package_id=$request->input('package_id');
                $expired_date=$request->input('expired_date');
                if($status=='') $status='0';
                                                       
                $data=array
                (
                    'name'=>$name,
                    'email'=>$email,
                    'mobile'=>$mobile,
                    'password'=>$password,
                    'address'=>$address,
                    'user_type'=>$user_type,
                    'status'=>$status,
                    'add_date' => date("Y-m-d H:i:s")
                    
                );

                if($user_type=='Member')
                {
                    $data["package_id"] = $package_id;
                    $data["expired_date"] = $expired_date;
                }
                else
                {
                    $data["package_id"] = 0;
                    $data["expired_date"] = '';
                }

                
                // if($this->basic->insert_data('users',$data)) $this->session->set_flashdata('success_message',1);   
                // else $this->session->set_flashdata('error_message',1); 
                
                if(DB::table('users')->insert($data)) session()->flash('success_message', 1);  
                else session()->flash('error_message', 1);    
                 
                return redirect()->route('user_manager');                 
                
            }
        }   
    }


    public function edit_user($id=0)
    {       
        if(config('app.is_demo') == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }

        $data['body']='user/edit-user';     
        $data['page_title']=__('Edit User');     
        // $packages=$this->basic->get_data('package',$where='',$select='',$join='',$limit='',$start='',$order_by='package_name asc');
        $packages=DB::table('package')->orderByRaw('package_name')->get();
        // $xdata=$this->basic->get_data('users',array("where"=>array("id"=>$id)));
        $xdata=DB::table('users')->where("id",$id)->get();
        if(!isset($xdata[0])) exit();
        $data['packages'] = format_data_dropdown($packages,"id","package_name",false);
        $data['xdata'] = $xdata[0];
        // return view($data['body'],$data);
        return $this->_viewcontroller($data);     

    }


    public function edit_user_action(Request $request) 
    {
        if(config('app.is_demo') == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }

        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }

        if($_POST)
        {
            $id = $request->input('id');

            $rules = [
                'email' => 'required|email|unique:users,email,'.$id,                            
                'user_type' => 'required', 
            ];

            if($request->input("user_type")=="Member")     
            {
                $rules['package_id'] = 'required';
                $rules['expired_date'] = 'required';
            }
                
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails())
            {
                return redirect()->back()
                ->withErrors($validator)
                ->withInput();
            }
            else
            {               
                // $this->csrf_token_check();

                $name=strip_tags($request->input('name'));
                $email=strip_tags($request->input('email'));
                $mobile=strip_tags($request->input('mobile'));                
                $address=strip_tags($request->input('address'));
                $user_type=$request->input('user_type');
                $status=$request->input('status');
                $package_id=$request->input('package_id');
                $expired_date=$request->input('expired_date');
                if($status=='') $status='0';
                                                       
                $data=array
                (
                    'name'=>$name,
                    'email'=>$email,
                    'mobile'=>$mobile,
                    'address'=>$address,
                    'user_type'=>$user_type,
                    'status'=>$status
                );
                if($user_type=='Member')
                {
                    $data["package_id"] = $package_id;
                    $data["expired_date"] = $expired_date;
                }
                else
                {
                    $data["package_id"] = 0;
                    $data["expired_date"] = '2099-03-06';
                }
                
                // if($this->basic->update_data('users',array("id"=>$id),$data)) $this->session->set_flashdata('success_message',1);   
                // else $this->session->set_flashdata('error_message',1);

                if(DB::table('users')->where("id",$id)->update($data)) session()->flash('success_message', 1);  
                else session()->flash('error_message',1);    
                 
                return redirect()->route('user_manager'); 
                                 
                
            }
        }   
    }
  

    public function login_log()
    {        
        if(config('app.is_demo') == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }

       $data['body'] = "user/login-log";
       $data['page_title'] = __('Login Log');
       $today = date("Y-m-d");
       $prev_day = date('Y-m-d', strtotime($today. ' - 30 days'))." 00:00:00";
        //    $data['info'] = $this->basic->get_data('user_login_info',array('where'=>array('login_time >='=>$prev_day)),$select='',$join='',$limit='',$start=NULL,$order_by='login_time DESC'); 
       $data['info'] = DB::table('user_login_info')->where('login_time', '>=', $prev_day)->orderBy('login_time', 'DESC')->get(); 
       // echo $this->db->last_query(); exit();
       return $this->_viewcontroller($data);     


    }

    public function delete_user_log()
    {       
        // $this->ajax_check();
        if(config('app.is_demo') == '1')
        {
            echo json_encode(array("status"=>"0","message"=>"This feature is disabled in this demo.")); 
            exit();
        }  

        $table_name = "user_login_info";
        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));
        $from_date = $from_date." 23:59:59";
        $where = array('login_time <' => $from_date);
        // if($this->basic->delete_data($table_name,$where))
        // echo json_encode(array("status"=>"1","message"=>__("Log has been deleted successfully"))); 
        // else echo json_encode(array("status"=>"0","message"=>__("Something went wrong, please try again")));
        if(DB::table('user_login_info')->where('login_time', '<', $from_date)->delete()) echo json_encode(array("status"=>"1","message"=>__("Log has been deleted successfully")));   
        else echo json_encode(array("status"=>"0","message"=>__("Something went wrong, please try again")));    
         

    }


    public function change_user_password_action(Request $request)
    {
        if(config('app.is_demo') == '1')
        {
            
                $response['status'] = 0;
                $response['message'] = "This feature is disabled in this demo.";
                echo json_encode($response);
                exit();
            
        }

        // $this->ajax_check();

        $id = $request->input('user_id');
        if ($_POST) 
        {

            $rules['password'] = 'required';
            $rules['confirm_password'] = 'required|same:password';
            // $this->form_validation->set_rules('password', '<b>'. __("password").'</b>', 'trim|required');
            // $this->form_validation->set_rules('confirm_password', '<b>'. __("confirm password").'</b>', 'trim|required|matches[password]');
        }
        // if ($this->form_validation->run() == false) 
        // {
        //    echo json_encode(array("status"=>"0","message"=>__("Something went wrong, please try again")));
        //    exit();
        // } 

        $validator = Validator::make($request->all(), $rules);
            
        if ($validator->fails())
        {
            echo json_encode(array("status"=>"0","message"=>__("Something went wrong, please try again")));
            // return redirect()->back()
            // ->withErrors($validator)
            // ->withInput();
        }

        else 
        {
            // $this->csrf_token_check();

            $new_password = $request->input('password');
            $new_confirm_password = $request->input('confirm_password');

            // $table_change_password = 'users';
            // $where_change_passwor = array('id' => $id);
            $data = array('password' => Hash::make($new_password));
            // $this->basic->update_data($table_change_password, $where_change_passwor, $data);
            DB::table('users')->where('id',$id)->update($data);

            $where['where'] = array('id' => $id);
            // $mail_info = $this->basic->get_data('users', $where);
            $mail_info = DB::table('users')->where('id',$id)->get();
            
            $name = $mail_info[0]->name;
            $to = $mail_info[0]->email;
            $password = $new_password;

            $mask = config('my_config.product_name');
            $from = config('my_config.institute_email');
            $url = url('/');


            // $email_template_info = $this->basic->get_data('email_template_management',array('where'=>array('template_type'=>'change_password')),array('subject','message'));
            $email_template_info = DB::table('email_template_management')->where('template_type', 'change_password')->select('subject', 'message')->get();
            
            if(isset($email_template_info[0]) && $email_template_info[0]->subject != '' && $email_template_info[0]->message != '') 
            {
                $subject = $email_template_info[0]->subject;
                $message = str_replace(array("#USERNAME#","#APP_URL#","#APP_NAME#","#NEW_PASSWORD#"),array($name,$url,$mask,$password),$email_template_info[0]->message);
            } 
            else 
            {
                $subject = 'Change Password Notification';
                $message = "Dear {$name},<br/> Your <a href='".$url."'>{$mask}</a> password has been changed. Your new password is: {$password}.<br/><br/> Thank you.";
            }
           
            // @$this->_mail_sender($from, $to, $subject, $message, $mask);
            echo json_encode(array("status"=>"1","message"=>__("Password has been changed successfully")));
        }
    }


    public function send_email_member(Request $request)
    {   
        if(config('app.is_demo') == '1')
        {
            echo "Notification sending is disabled in this demo.";
            exit();
        }
        if($_POST)
        {
            $subject= strip_tags($request->input('subject'));
            $message= $request->input('message');
            $user_ids=$request->input('user_ids');
            $count=0;
            $info = DB::table('users')->where('id',$user_ids)->get();
            
            set_email_config();

            foreach ($info as $member) {
                $email = $member->email;
                $data = [
                    'subject' => $subject,
                    'message' => $message,
                ];
                
                $mask = config('my_config.product_name');
                Mail::to($email)->send(new SimpleHtmlEmail($mask,$message,$subject));
        
                $count++;
            }
            echo "<b> $count / ".count($info)." : ".__("Email Sent Successfully")."</b>";
            exit();
           
        }   
    }


}
