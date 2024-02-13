<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Validator;

class Simplesupport extends HomeController
{
    public $addon_data=array();
    /**
     * initialize addon 
     */
    // public function __construct()
    // {
    
    //     // getting addon information in array and storing to public variable
    //     // addon_name,unique_name,module_id,addon_uri,author,author_uri,version,description,controller_name,installed
    //     //------------------------------------------------------------------------------------------
    //     $addon_path=APPPATH."modules/".strtolower($this->router->fetch_class())."/controllers/".ucfirst($this->router->fetch_class()).".php"; // path of addon controller
    //     $this->addon_data=$this->get_addon_data($addon_path); 

    //     Auth::user()->id=session()->get('user_id'); // user_id of logged in user, we may need it
    //     $this->load->helper('text');
    //     $function_name=$this->uri->segment(2);
    //     if($function_name!="open_ticket") 
    //     {          
    //         if (session()->get('logged_in')!= 1) redirect('home/login', 'location');
    //     }if(session()->get('license_type') != 'double') redirect('home/access_forbidden', 'location');

    // }


    public function tickets()
    {
        if(check_build_version() != 'double') redirect()->route('access_forbidden');
        $data['body'] = 'support/tickets';
        $data['page_title'] = __("Tickets");    
        return $this->_viewcontroller($data);
    }

    public function ticket_data(Request $request)
    {
        // $this->ajax_check();
        
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 10;
        $ticket_status = $request->input("ticket_status");
        $order_by = "id DESC";
     
        $search = $request->input('search');

        $users = DB::table('users')->get();
        $user_assoc = array();
        $admin_users = array();
        foreach ($users as $key => $value) {
            $user_assoc[$value->id] = $value;
            if($value->user_type=="Admin") array_push($admin_users, $value->id);
        }

        $query = DB::table('fb_simple_support_desk')
        ->leftJoin('fb_support_category', 'fb_simple_support_desk.support_category', '=', 'fb_support_category.id')
        ->select('fb_simple_support_desk.*', 'fb_support_category.category_name');


        if (Auth::user()->user_type == 'Member') {
            $query->where('fb_simple_support_desk.user_id',Auth::user()->id);
        } else {
            $query->where('fb_simple_support_desk.user_id', '>', 0);
        }

        if (Auth::user()->user_type == 'Admin') {
            if ($ticket_status == 'hidden') {               
                $query->where('fb_simple_support_desk.display', '0');
            } elseif ($ticket_status != '') {
                $query->where('fb_simple_support_desk.display', '1');
            }
        }

        if ($ticket_status !== '' && $ticket_status !== 'hidden') {
            $query->where('fb_simple_support_desk.ticket_status', $ticket_status);
        }
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('fb_simple_support_desk.id', 'like', '%' . $search . '%')
                    ->orWhere('fb_simple_support_desk.ticket_title', 'like', '%' . $search . '%')
                    ->orWhere('fb_support_category.category_name', 'like', '%' . $search . '%');
            });
        }

        $info = $query->orderByDesc('id')
            ->offset($start)
            ->limit($limit)
            ->get();
        // $info=DB::table('fb_simple_support_desk')->get();
        // echo $sql =  $this->db->last_query();  

        $html='';
        for($i=0;$i<count($info);$i++)
        {  
            $id = $info[$i]->id;
            $view_url= url('/')."/simplesupport/reply/".$id;
            $resolve_url=url("/simplesupport/action/resolve/".$id);
            $close_url=url("/simplesupport/action/close/".$id);

            $logo = isset($user_assoc[$info[$i]->user_id]->brand_logo)?$user_assoc[$info[$i]->user_id]->brand_logo:"";
            if($logo=="") $logo=asset("img/avatar/avatar-1.png");
            else $logo=$logo;

            $ticket_owner_name = isset($user_assoc[$info[$i]->user_id]->name)?$user_assoc[$info[$i]->user_id]->name:"";
            if(Auth::user()->user_type=="Admin") $ticket_owner_name = "<a href='".url('/admin/edit_user/'.$info[$i]->user_id)."'>".$ticket_owner_name."</a>";

            $action = ""; 

            if($info[$i]->ticket_status != '1')
            $action .= '<a  table_id="'.$id.'" href="" class="dropdown-item has-icon ticket_action"  data-type="open"><i class="far fa-comment"></i> '.__("Re-open").'</a>';

            if($info[$i]->ticket_status != '3')
            $action .= '<a  table_id="'.$id.'" href="" class="dropdown-item has-icon ticket_action"  data-type="resolve"><i class="fas fa-paper-plane"></i> '.__("Resolve").'</a>';

            if($info[$i]->ticket_status != '2')
            $action .= '<a  table_id="'.$id.'" href="" class="dropdown-item has-icon ticket_action"  data-type="close"><i class="fas fa-ban"></i> '.__("Close").'</a>';          

            if($info[$i]->display == '1' && Auth::user()->user_type=="Admin")
            $action .= '<a  table_id="'.$id.'" href="" class="dropdown-item has-icon ticket_action"  data-type="hide"><i class="fas fa-eye-slash"></i> '.__("Hide").'</a>';

            $action .= '<div class="dropdown-divider"></div>';
            $action .= '<a  table_id="'.$id.'" class="dropdown-item has-icon text-danger delete_ticket"><i class="fas fa-trash"></i> '.__("Delete").'</a>';
           
    
            $icon = "";
            if($info[$i]->display=="0")  $icon = "fas fa-eye-slash";
            else if($info[$i]->ticket_status=='2') $icon = "text-danger fas fa-ban";
            else if($info[$i]->ticket_status=='3')  $icon = "text-primary far fa-paper-plane";
            else $icon = "text-warning fas fa-ticket-alt";
            
            if($info[$i]->last_replied_by==0)
            $reply_details = '<span class="badge badge-danger float-md-right"><i class="far fa-clock"></i> '.__("Reply Pending").'</span>';
            else if(in_array($info[$i]->last_replied_by,$admin_users))
            $reply_details = '<span class="badge badge-light float-md-right"><i class="fas fa-headset"></i> '.__("Agent Replied").' '.date_time_calculator($info[$i]->last_replied_at,true).'</span>';
            else 
            $reply_details = '<span class="badge badge-warning float-md-right"><i class="fas fa-user"></i> '.__("Client Replied").' '.date_time_calculator($info[$i]->last_replied_at,true).'</span>';
            
            $ticket_open_time = $info[$i]->ticket_open_time;
            $ticket_single = '
            <div class="activity">
                <div class="activity-icon bg-light" text-white shadow-light">
                  <i class="'.$icon.'"></i>
                </div>
                <div class="activity-detail h-100" style="width:100%">            
                  <div class="row align-items-center h-100">
                    <div class="col-3 col-md-1 mx-auto">
                        <img src="'.$logo.'" class="rounded-circle" style="max-width:50px">
                    </div>
                    <div class="col-9 col-md-11">
                        <div class="mb-2">
                          <div class="dropdown">
                            <a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h" style="font-size:25px"></i></a>
                            <div class="dropdown-menu">
                              <div class="dropdown-title">'.__("Options").'</div>                        
                              '.$action.'  
                            </div>
                          </div>
                          <span class="text-job"><i class="far fa-clock"></i> '.date_time_calculator($ticket_open_time,true).'</span>
                          <span class="bullet"></span>
                          '.$ticket_owner_name.'
                          '.$reply_details.'
                        </div>
                        <p class="text-justify"><a href="'.$view_url.'"><b>#'.$info[$i]->id.' : </b>'.$info[$i]->ticket_title.'</a></p>
                    </div>

                  </div>
                </div>
            </div>';
            $html.=$ticket_single;
        }
        echo json_encode(array("html"=>$html,"found"=>count($info)));        
    }



    public function open_ticket()
    {
        if(check_build_version() != 'double') redirect()->route('access_forbidden');
        $data['body'] = 'support/open-ticket';
        $data['page_title'] = __('Open Ticket');
        $data["support_category"]=DB::table('fb_support_category')->get();
        return $this->_viewcontroller($data);
    }


    public function open_ticket_action(Request $request)
    {
        if($_POST)
        {
            
            // $this->csrf_token_check();
            $post=$_POST;
            foreach ($post as $key => $value) 
            {
                $$key=$request->input($key,true);
            }
        }
        // if(session()->get('license_type') != 'double') exit;

        $data['ticket_title'] = strip_tags($ticket_title);
        $data['ticket_text'] = $ticket_text;
        $data['user_id'] = Auth::user()->id;
        $data['support_category']= $support_category;
        $data['ticket_open_time']= date("Y-m-d H:i:s");
        // if($this->basic->insert_data('fb_simple_support_desk',$data))
        if(DB::table('fb_simple_support_desk')->insert($data))
        {
    		// $ticket_id = $this->db->insert_id();
    		$ticket_id = DB::getPdo()->lastInsertId();;
    		// $user_email = session()->get("user_login_email");
    		$user_email = Auth::user()->email;
    		$ticket_url = url('/').'/simplesupport/reply/'.$ticket_id;
    		$subject = config('my_config.product_name')." | "."support ticket";
    		$message = "<p>Hi Admin. <br><br> 
    		<p>
    		The customer open a new ticket. <br>
    		".word_limiter($ticket_text,30)."
    		<br><br>If you want to reply this ticket, (go to ticket ID <a href='{$ticket_url}'>{$ticket_id})</a>. <br>
    		</p> 

    		<br> <br> Thanks<br><a href='".url('/')."'>".Auth::user()->name."</a>";
    		$from = $user_email;
    	    $to = config('my_config.institute_email');
    		$subject = $subject;
    		$mask = $subject;
    		$html = 1;
    		$this->_mail_sender($from, $to, $subject, $message, $mask, $html);
    		
    		if(Auth::user()->user_type=="Member")
    		{
    			$message = "<p>Hi ".Auth::user()->name.". <br><br> 
    			<p>
    			Thanks for contacting us. We have received your request (ticket ID <a href='{$ticket_url}'>{$ticket_id})</a>. <br>
    			A support representative will be reviewing your request and will send you a personal response.(usually within 24 hours). </p>

    			<br> <br> Thanks<br><a href='".url()."'>".config("my_config.company")."</a> Team";
    			$from = config('my_config.institute_email');
    			$to   = $user_email;
    			$subject = $subject;
    			$mask = $subject;
    			$html = 1;
    			$this->_mail_sender($from, $to, $subject, $message, $mask, $html);
    		}
    		session()->flash('success_message', 1);
    		return redirect()->route('simplesupport');
        }
       

        
      

    }

    public function delete_ticket(Request $request)
    {
        
      if(config('app.is_demo') == '1')
      {
          echo json_encode(array("status"=>"0","message"=>"This feature is disabled in this demo.")); 
          exit();
      } 
	    $id = $request->input('id');

        if(Auth::user()->user_type=="Admin"){
        DB::table('fb_simple_support_desk')->where('id',$id)->delete();
        }
        else DB::table('fb_simple_support_desk')->where('id',$id)->where('user_id',Auth::user()->id)->delete();
	
        DB::table('fb_support_desk_reply')->where('reply_id',$id)->delete();

        echo json_encode(array("status"=>"1","message"=>__("Ticket has been deleted successfully")));
    	
    }    

    public function ticket_action(Request $request)
    {
        // $this->ajax_check();

        $id = $request->input('id');
        $action = $request->input('action');

        $update_data=array();
        $message = "Operation successful";

        if($action=="open") 
        {
            $update_data=array("ticket_status"=>"1","display"=>"1","last_action_at"=>date("Y-m-d H:i:s"));
            $message = "Ticket has been re-opened successfully";
        }
        if($action=="resolve") 
        {
            $update_data=array("ticket_status"=>"3","display"=>"1","last_action_at"=>date("Y-m-d H:i:s"));
            $message = "Ticket has been resolved successfully";
        }
        if($action=="close")
        {
            $update_data=array("ticket_status"=>"2","display"=>"1","last_action_at"=>date("Y-m-d H:i:s"));
            $message = "Ticket has been closed successfully";
        }
        if($action=="hide" && Auth::user()->user_type=="Admin") 
        {
            $update_data=array("display"=>"0","last_action_at"=>date("Y-m-d H:i:s"));
            $message = "Ticket has been hidden successfully";
        }

        if(Auth::user()->user_type=="Admin")
        DB::table('fb_simple_support_desk')->where('id',$id)->update($update_data);
        else DB::table('fb_simple_support_desk')->where('id',$id)->where('user_id',Auth::user()->id)->update($update_data);

        
        echo json_encode(array("status"=>"1","message"=>__($message)));
    }

   
    public function reply($id=0)
    {
        if(check_build_version() != 'double') redirect()->route('access_forbidden');
        if($id==0) exit();
        // if(session()->get('license_type') != 'double') exit;
        $data['body'] = 'support/ticket-reply';
        if(Auth::user()->user_type=="Admin"){
        $where = [
            'fb_simple_support_desk.id' => $id
        ];
        } 
    
        else {
            $where = [
            'fb_simple_support_desk.id' => $id,
            'fb_simple_support_desk.user_id' => auth()->user()->id
            ];
        }
        $table = "fb_simple_support_desk";
        // $info = $this->basic->get_data($table, $where, $select='fb_simple_support_desk.*,fb_support_category.category_name', $join);
        $info = DB::table($table)
        ->select('fb_simple_support_desk.*', 'fb_support_category.category_name')
        ->leftJoin('fb_support_category', 'fb_simple_support_desk.support_category', '=', 'fb_support_category.id')
        ->where($where)
        ->get();
        if(!isset($info[0])) exit();

        $data['ticket_info']=$info;

        $user=$info[0]->user_id;
        // $user_info = $this->basic->get_data('users',array('where'=>array('id'=>$user)));
        $user_info = DB::table('users')->where('id',$user)->get();
        $data['user_info']=$user_info;
        $table = "fb_support_desk_reply";
        $ticket_replied = DB::table($table)
            ->leftJoin('users', 'fb_support_desk_reply.user_id', '=', 'users.id')
            ->where('reply_id', $id)
            ->get();
        $data['ticket_replied'] = $ticket_replied;
        $data['page_title'] = "#".$id." : ".$info[0]->ticket_title;
        
        return $this->_viewcontroller($data);
    }

    public function reply_action(Request $request)
    {
       if($_POST)
       {
           
        //    $this->csrf_token_check();
           $post=$_POST;
           foreach ($post as $key => $value) 
           {
               $$key=$request->input($key,true);
           }
       }
       
       $data['ticket_reply_text']  = $ticket_reply_text;
       $data['user_id'] = Auth::user()->id;
       $data['reply_id'] = $id; // ticket id
       $data['ticket_reply_time'] = date("Y-m-d H:i:s");
       
       if(DB::table('fb_support_desk_reply')->insert($data))
       {
       		if(Auth::user()->user_type=="Member")
       		{
       			$id= $id; 
       			$url = url('')."/simplesupport/reply/".$id;
       			$url_final="<a href='".$url."' target='_BLANK'>".$url."</a>";
       			$message = "<p>"."The customer has responded to the ticket"."</p>
       			            </br>
       			            </br>
       			            <p>".'Hi'." ".'Admin'.", </p>
       			            </br>
       			            </br>
       			            <p>".word_limiter($data['ticket_reply_text'],50)." </p>
       			            </br>
       			            </br>
       			            <p>"."Go to this url".":".$url_final."</p>";


       			$from = session()->get("user_login_email");
       			$to = config('my_config.institute_email');
       			$subject = config('my_config.product_name')." | "."support ticket";
       			$mask = $subject;
       			$html = 1;
       			$this->_mail_sender($from, $to, $subject, $message, $mask, $html);
       			$update_ticket = array("last_replied_at"=>date("Y-m-d H:i:s"),"last_replied_by"=>Auth::user()->id,"ticket_status"=>"1","display"=>"1");
                DB::table('fb_simple_support_desk')->where('id',$id)->update($update_ticket);
       			session()->set_flashdata('success_message', 1);
       			redirect('simplesupport/reply/'.$id.'', 'location'); 
       		}
       		else
       		{
       			$id= $id; 
       			$url = url('/')."/simplesupport/reply/".$id;
       			$url_final="<a href='".$url."' target='_BLANK'>".$url."</a>";
       			$message = "<p>"."Admin has responded to your ticket"."</p>
       			            </br>
       			            </br>
       			            <p>".'Hi'." ".'Customer'.", </p>
       			            </br>
       			            </br>
       			            <p>".word_limiter($data['ticket_reply_text'],50)." </p>
       			            </br>
       			            </br>
       			            <p>"."Go to this url".":".$url_final."</p>";

                $where = ['fb_simple_support_desk.id' => $id];
				$data_support_desk= DB::table('fb_simple_support_desk')->where($where)->get();
				
				$userid=$data_support_desk[0]->user_id;
                $where = ['users.id' => $userid];
				$table = "users";
                $from = config('my_config.institute_email'); 
				$select =array("users.email");
				$tomail = DB::table($table)->where($where)->select($select)->get();
				if(isset($tomail[0]->email))
				$to = $tomail[0]->email; 
				$subject = config('my_config.product_name')." | "."Support ticket";
				$mask = $subject;
				$html = 1;
				$this->_mail_sender($from, $to, $subject, $message, $mask, $html);
                $update_ticket = array("last_replied_at"=>date("Y-m-d H:i:s"),"last_replied_by"=>Auth::user()->id);
                DB::table('fb_simple_support_desk')->where('id',$id)->update($fb_simple_support_desk);
				session()->set_flashdata('success_message', 1);
				return redirect('simplesupport/reply/'.$id.'', 'location');              
       		}
       }


       // if(Auth::user()->user_type!="Admin") $update_ticket = array("last_replied_at"=>date("Y-m-d H:i:s"),"last_replied_by"=>Auth::user()->id,"ticket_status"=>"1","display"=>"1");
       // else $update_ticket = array("last_replied_at"=>date("Y-m-d H:i:s"),"last_replied_by"=>Auth::user()->id);

       // $this->basic->update_data('fb_simple_support_desk',array("id"=>$id),$update_ticket);

       // session()->set_flashdata('success_message', 1);
       // redirect('simplesupport/reply/'.$id.'', 'location');

    }


    public function support_category_manager()
    {
     if (Auth::user()->user_type != 'Admin') return redirect()->route('login');
     $data['body'] = 'support/support-category';
     $data['page_title'] = __('Support Category');
     $data['category_data'] = DB::table('fb_support_category')->get();
     return $this->_viewcontroller($data);

    }

    public function add_category()
    {       
        if (Auth::user()->user_type != 'Admin') return redirect()->route('login');
        $data['body'] = 'support/add-category';
        $data['page_title'] = __('Add Category');
        return $this->_viewcontroller($data);
    }
    
    public function add_category_action(Request $request)
    {
       if (Auth::user()->user_type != 'Admin') return redirect()->route('login');
       if(config('app.is_demo') == '1')
       {
           echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
           exit();
       }

       if($_SERVER['REQUEST_METHOD'] === 'GET') 
       return redirect()->route('access_forbidden');

       if($_POST)
       {
            $validator = Validator::make($request->all(), [
                'category_name' => 'required',
            ]);
        
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator);
            }
           else
           {               
               $category_name=$request->input('category_name');               
                                                      
               $data=array
               (
                   'category_name'=>$category_name,
                   'deleted'=>'0'
               );
               
               if(DB::table('fb_support_category')->update($data)) session()->flash('success_message',1);   
               else session()->flash('error_message',1);     
               
               return redirect()->route('support_category_manager');                 
               
           }
       }  

    }

    public function edit_category($id=0)
    {       
        if (Auth::user()->user_type != 'Admin') return redirect()->route('login');
   
        $data['body']='support/edit-category';     
        $data['page_title']=__('Edit Category');     
        $xdata=DB::table('fb_support_category')->where('id',$id)->get();
        if(!isset($xdata[0])) exit();
        $data['xdata'] = $xdata[0];
        return $this->_viewcontroller($data);
    }


    public function edit_category_action(Request $request) 
    {
        if (Auth::user()->user_type != 'Admin') return redirect()->route('login');
        if(config('app.is_demo') == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] === 'GET') 
        return redirect()->route('access_forbidden');

        if($_POST)
        {
            $id = $request->input('id');
            $validator = Validator::make($request->all(), [
                'category_name' => 'required',
            ]);
        
            if ($validator->fails()) {
                return $this->edit_category($id)
                    ->withErrors($validator)
                    ->withInput();
            }
            else
            {               
                $category_name=$request->input('category_name');               
                                                       
                $data=array
                (
                    'category_name'=>$category_name
                );
                
                if(DB::table('fb_support_category')->where('id',$id)->update($data)) session()->flash('success_message',1);   
                else session()->flash('error_message',1);     
                
                return redirect()->route('support_category_manager');                 
                
            }
        }   
    }   


    public function delete_category($id=0)
    {
        // $this->ajax_check();
        if(config('app.is_demo') == '1')
        {
            echo json_encode(array("status"=>"0","message"=>"This feature is disabled in this demo.")); 
            exit();
        }
        if (Auth::user()->user_type != 'Admin') exit(); 
        if(DB::table('fb_support_category')->where('id',$id)->update(['deleted'=>'1']))
        echo json_encode(array("status"=>"1","message"=>__("Category has been deleted successfully"))); 
        else echo json_encode(array("status"=>"0","message"=>__("Something went wrong, please try again")));
      
    }
}
