<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Validator;


class Announcement extends HomeController
{
    public $download_id;
    public function __construct()
    {
       $this->set_global_userdata(); 
    }

    public function full_list()
    {
        $data['body'] = 'announcement/list';
        $data['page_title'] = __("Announcement");     
        return $this->_viewcontroller($data);     

    }

    public function list_data(Request $request)
    {
        // $this->ajax_check();
        
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 10;
        $seen_type = $request->input('seen_type');
        
     
        $search = $request->input('search');
    
        $where_simple = array();
             
        if(Auth::user()->user_type != 'Admin') $where_simple["status"] = 'published';   
    
        if($seen_type=='1') // seen only
            $where_custom = "((user_id=".Auth::user()->id." AND is_seen='1') OR (user_id=0 AND FIND_IN_SET('".Auth::user()->id."', seen_by)))";
        else if($seen_type=='0') // unseeen
            $where_custom = "((user_id=".Auth::user()->id." AND is_seen='0') OR (user_id=0 AND NOT FIND_IN_SET('".Auth::user()->id."', seen_by)))";
        else // everything
            $where_custom = "((user_id=".Auth::user()->id.") OR (user_id=0))";
    
        if($search!='') 
            $where_custom .= " AND (title like '%".$search."%' OR description like '%".$search."%' OR created_at like '%".$search."%')";
                                 
        $where = $where_simple;
        // DB::whereRaw($where_custom);

        $table = "announcement";
        $info = DB::table($table)
                ->whereRaw($where_custom)
                ->where($where)
                ->limit($limit)
                ->offset($start)
                ->orderByDesc('id')
                ->get();
        // $sql =  $this->db->last_query();
        $info= json_decode(json_encode($info));

        $action_class='hidden';
        if(Auth::user()->user_type=='Admin') $action_class='';       
        $html='';
        for($i=0;$i<count($info);$i++)
        {
            $seen_class = '';
            if($info[$i]->user_id=="0")
            {
                $seen_by_array = explode(',', $info[$i]->seen_by);
                if(in_array(Auth::user()->id, $seen_by_array)) $seen_class = 'hidden';
            }
            else
            {
                if($info[$i]->is_seen=='1') $seen_class='hidden';    
            }

            if($info[$i]->status=='published') $info[$i]->status_formatted='<span class="badge badge-light"><i class="fa fa-check-circle green"></i> '.__("Published").'</span>';
            else $info[$i]->status_formatted='<span class="badge badge-light"><i class="fa fa-file orange"></i> '.__("Draft").'</span>';

            $view_url=url('/').("/announcement/details/".$info[$i]->id);
            $mark_seen_url=url('/').("/announcement/mark_seen/".$info[$i]->id);
            $action = "";
            $action .= '<a href="'.url('/').("/announcement/edit/".$info[$i]->id).'" class="dropdown-item has-icon"><i class="fas fa-edit"></i> '.__("Edit").'</a>';
        	$action .= '<div class="dropdown-divider"></div><a href="'.url('/').("/announcement/delete/".$info[$i]->id).'" class="dropdown-item has-icon text-danger delete_annoucement"><i class="fas fa-trash"></i> '.__("Delete").'</a>';
        	

        	$created_at = $info[$i]->created_at;
	        $announcement_single = '
	        <div class="activity">
				<div class="activity-icon bg-'.$info[$i]->color_class.' text-white shadow-'.$info[$i]->color_class.'">
				  <i class="'.$info[$i]->icon.'"></i>
				</div>
		        <div class="activity-detail" style="width:100%">
			      <div class="mb-2">
                    <div class="dropdown '.$action_class.'">
                      <a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h" style="font-size:25px"></i></a>
                      <div class="dropdown-menu">
                        <div class="dropdown-title">'.__("Options").'</div>                        
                        '.$action.'  
                      </div>
                    </div>
			        <span class="text-job gray"><i class="far fa-clock"></i> '.date_time_calculator($created_at,true).'</span>
			        <span class="bullet"></span>
			        <a class="text-job mark_seen '.$seen_class.'" href="'.$mark_seen_url.'">'.__("Mark Seen").'</a>
			        
			      </div>
			      <p><i class="far fa-eye"></i> <a href="'.$view_url.'">'.$info[$i]->title.'</a></p>
			    </div>
			</div>';
		    $html.=$announcement_single;
        }
        echo json_encode(array("html"=>$html,"found"=>count($info)));        
    }

    public function add()
    {
        if(Auth::user()->user_type != 'Admin') return redirect()->route('login');
        $data['body'] = 'announcement/add';
        $data['page_title'] = __("Add Announcement");     
        return $this->_viewcontroller($data);     


    }

    public function add_action(Request $request)
    {
        if(config('app.is_demo') == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }

        if(Auth::user()->user_type != 'Admin') exit();
        if(!$_POST) exit();

        $rules = [
            'title' => 'required',
            'description' => 'required',              
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
            // $this->csrf_token_check();
            $title=strip_tags($request->input('title'));
            $description=strip_tags($request->input('description'));
            $status=$request->input('status');
            if($status=='') $status='draft';
            $created_at=date("Y-m-d H:i:s");

            // if($this->basic->insert_data('announcement',array('title'=>$title,'description'=>$description,'status'=>$status,'created_at'=>$created_at)))
            if(DB::table('announcement')->insert(['title'=>$title,'description'=>$description,'status'=>$status,'created_at'=>$created_at,'color_class'=>'primary','icon'=>'fas fa-bell' ]))
            session()->flash('success_message',1);    
            else session()->flash('error_message',1);

            return redirect()->route('announcement_full_list'); 
        }         
    }

    public function edit($id=0)
    {
        if($id==0) exit();
        if(Auth::user()->user_type != 'Admin') return redirect()->route('login');
        $data['body'] = 'announcement/edit';
        $data['page_title'] = __("Edit Announcement");  
        // $xdata=$this->basic->get_data("announcement",array('where'=>array('id'=>$id)));   
        $xdata=DB::table('announcement')->where('id',$id)->get();   
        if(!isset($xdata[0])) exit();
        $data['xdata']=$xdata[0];
        return $this->_viewcontroller($data);     


    }

    public function edit_action(Request $request)
    {
        if(config('app.is_demo') == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }

        if(Auth::user()->user_type != 'Admin') exit();
        if(!$_POST) exit();


        $rules = [
            'title' => 'required',
            'description' => 'required',              
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
            // $this->csrf_token_check();
            $id=$request->input('hidden_id');
            $title=strip_tags($request->input('title'));
            $description=strip_tags($request->input('description'));
            $status=$request->input('status');
            if($status=='') $status='draft';
            $created_at=date("Y-m-d H:i:s");

            // if($this->basic->update_data('announcement',array('id'=>$id),array('title'=>$title,'description'=>$description,'status'=>$status)))
            if(DB::table('announcement')->where('id',$id)->update(['title'=>$title,'description'=>$description,'status'=>$status,]))
            session()->flash('success_message',1);    
            else session()->flash('error_message',1);

            return redirect()->route('announcement_full_list'); 
        }
    }

    public function delete($id=0)
    {
        // $this->ajax_check();
        if(config('app.is_demo') == '1')
        {
            echo json_encode(array("status"=>"0","message"=>__("This feature is disabled in this demo."))); 
            exit();
        }
        
        if($id==0) exit();
        if(Auth::user()->user_type != 'Admin') exit();
        // if($this->basic->delete_data("announcement",array("id"=>$id)))
        if(DB::table('announcement')->where('id',$id)->delete())
        echo json_encode(array("status"=>"1","message"=>__("Announcement has been deleted successfully"))); 
        else echo json_encode(array("status"=>"0","message"=>__("Something went wrong, please try again")));

    }

    public function details($id=0)
    {
        if($id==0) exit();
        $data['body'] = 'announcement/details';
        $data['page_title'] = __("Announcement Details");  
        // $xdata=$this->basic->get_data("announcement",array('where'=>array('id'=>$id)));  
        $xdata=DB::table('announcement')->where('id',$id)->get();   
 
        if(!isset($xdata[0])) exit();
        $data['xdata']=$xdata[0];

        if($xdata[0]->user_id!='0' && $xdata[0]->user_id!=Auth::user()->id && Auth::user()->user_type!="Admin") exit();

        if ($xdata[0]->user_id != '0') 
        {
            $update_data = 
            array
            (
                'is_seen' => '1',
                'last_seen_at' => date('Y-m-d H:i:s')
            );
            // $this->basic->update_data('announcement', array('id' => $id) ,$update_data);
            DB::table('announcement')->where('id',$id)->update($update_data);   

        }
        else 
        {
            $update_data = array('last_seen_at' => date('Y-m-d H:i:s'));
            
            $temp = explode(',', $xdata[0]->seen_by);
            array_push($temp, Auth::user()->id);
            $temp = array_unique($temp);
            $temp = implode(',', $temp);

            $update_data['seen_by'] = trim($temp,',');

            // $this->basic->update_data('announcement', array('id' => $id) ,$update_data);
            DB::table('announcement')->where('id',$id)->update($update_data);
        }

       
        return $this->_viewcontroller($data);     


    }


    public function mark_seen($id=0)
    {

        // $this->ajax_check();

        $user_id = auth()->id;

        // $info = $this->basic->get_data('announcement', array('where' => array('id' => $id)));
        $info =DB::table('announcement')->where('id',$id)->get();   
        ;
        if(!isset($info[0])) 
        {
            echo json_encode(array("status"=>"0","message"=>__("No data found")));
            exit();
        }
        $notification_info = $info[0];

        if ($notification_info->user_id != '0' && $notification_info->user_id !=$user_id)
        {
            echo json_encode(array("status"=>"0","message"=>__("Access denied")));
            exit();
        }

        if ($notification_info->user_id != '0') 
        {
            $data = 
            array
            (
                'is_seen' => '1',
                'last_seen_at' => date('Y-m-d H:i:s')
            );
            // $this->basic->update_data('announcement', array('id' => $id) ,$data);
            DB::table('announcement')->where('id',$id)->update($data);
        }
        else 
        {
            $data = array('last_seen_at' => date('Y-m-d H:i:s'));
            
            $temp = explode(',', $notification_info->seen_by);
            array_push($temp, $user_id);
            $temp = array_unique($temp);
            $temp = implode(',', $temp);

            $data['seen_by'] = trim($temp,',');

            // $this->basic->update_data('announcement', array('id' => $id) ,$data);
            DB::table('announcement')->where('id',$id)->update($data);
        }
        echo json_encode(array("status"=>"1","message"=>__("Announcement has been marked as seen sucessfully.")));

    }

    public function mark_seen_all()
    {
        $user_id = auth()->id();
        
        $where_custom = "(user_id={$user_id} AND is_seen='0') OR (user_id=0 AND NOT FIND_IN_SET('{$user_id}', seen_by))";
        
        $notification_info = DB::table('announcement')->whereRaw($where_custom)->get();
        
        $total = 0;
        
        foreach ($notification_info as $notification) {
            $update_data = [];
            
            if ($notification->user_id == '0') {
                $temp = explode(',', $notification->seen_by);
                array_push($temp, $user_id);
                $temp = array_unique($temp);
                $temp = implode(',', $temp);
                
                $update_data['seen_by'] = trim($temp, ',');
            } else {
                $update_data['is_seen'] = '1';
            }

            $update_data['last_seen_at'] = date('Y-m-d H:i:s');
            
            DB::table('announcement')->where('id', $notification->id)->update($update_data);
            
            $total++;
        }
        
        session()->flash('mark_seen_success',$total." ".__("Unseen announcements have been marked as seen."));    
        return response()->json(['status' => '1', 'message' => '']);
    }

}
