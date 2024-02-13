<?php

namespace App\Http\Controllers\SEO_tools;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Services\Custom\WebCommonReportServiceInterface;

class VisitorController extends HomeController
{

    public function __construct(WebCommonReportServiceInterface $web_common_repport)
    {

        $this->web_repport= $web_common_repport;        

    }


    public function visitor_analysis()
    {
        $this->set_global_userdata(true,[],[],1);

        $data['body'] = "seo-tools.analysis-tools.visitor.visitor-analysis";
        return $this->_viewcontroller($data);     
    }
    
    public function domain_list_visitor_data(Request $request)
    {

        $domain_name   = trim($request->input("domain_name"));

        $display_columns = ['#','id','domain_name','domain_code','js_code','actions'];

        $database_columns = ['#','id','domain_name','domain_code','js_code','actions'];
        $search_columns = ["domain_name", "add_date"];

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 1;
        $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : "id";
        $order = isset($_POST['order'][0]['dir']) ? strval($_POST['order'][0]['dir']) : 'desc';
        $order_by=$sort." ".$order;

        $table = "visitor_analysis_domain_list";
        $select= ["visitor_analysis_domain_list.domain_name",'visitor_analysis_domain_list.user_id',"visitor_analysis_domain_list.id","visitor_analysis_domain_list.domain_code","visitor_analysis_domain_list.js_code","visitor_analysis_domain_list.add_date","visitor_analysis_domain_list.dashboard"];
        $query = DB::table($table)->select($select);
        $user_id = Auth::user()->id;

        if ($domain_name != '')
        {
            $query->where(function($query) use ($search_columns,$domain_name){
                foreach ($search_columns as $key => $value) $query->orWhere($value, 'like',  "%$domain_name%");
            });
        }
        $query->where(function($query) use ($user_id){
          $query->orWhere('visitor_analysis_domain_list.user_id', '=', $user_id);
        });
       

        $info = $query->orderByRaw($order_by)->offset($start)->limit($limit)->get();

        $total_result = DB::table($table)->where('user_id', Auth::user()->id)->count();
        


        for($i=0;$i<count($info);$i++) 
        {
            $info[$i]->js_code = "not shown in the grid";
            
            $action_width = (5*47)+20;
            $info[$i]->actions = '<div class="dropdown d-inline dropright">
            <button class="btn btn-outline-primary dropdown-toggle no_caret" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-briefcase"></i></button>
            <div class="dropdown-menu mini_dropdown text-center" style="width:'.$action_width.'px !important">';
            $info[$i]->actions .= "<a href='#' class='btn btn-circle btn-outline-info get_js_code' data-toggle='tooltip' data-original-title='".__('Get JS Code')."' table_id='".$info[$i]->id."'><i class='fas fa-code'></i></a>&nbsp;<a class='btn btn-circle btn-outline-success' data-toggle='tooltip' data-original-title='".__('View Details')."' href='".url('visitor_analysis/domain_details').'/'.$info[$i]->id."'><i class='fas fa-eye'></i></a>&nbsp;<a href='#' class='btn btn-circle btn-outline-danger delete_template' data-toggle='tooltip' data-original-title='".__('Delete Domain')."' table_id='".$info[$i]->id."'><i class='fa fa-trash'></i></a>";
            if ($info[$i]->dashboard == '1') {
            
                    $info[$i]->actions .= "&nbsp;<a href='#' table_id='".$info[$i]->id."' data-toggle='tooltip' data-original-title='".__('Remove from dashboard')."' data-placement='top' dashboard='0'  class='btn btn-circle btn-primary show_in_dashboard'><i class='fas fa-check'></i></a>";
            }
            else{
                    $info[$i]->actions .= "&nbsp;<a href='#' table_id='".$info[$i]->id."' data-toggle='tooltip' data-original-title='".__('Show on your dashboard')."' data-placement='top' dashboard='1' class='btn btn-circle btn-outline-primary show_in_dashboard'><i class='fas fa-check'></i></a>";
            }
            $info[$i]->actions .= "&nbsp;<a href='#' class='btn btn-circle btn-outline-danger delete_30_days_data' data-toggle='tooltip' data-original-title='".__('Delete data except last 30 days')."' table_id='".$info[$i]->id."'><i class='fas fa-eraser'></i></a>";
            $info[$i]->actions .= "</div></div><script>$('[data-toggle=\"tooltip\"]').tooltip();</script>";
        }

 
        
        
        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns, $start, $primary_key="id");


        echo json_encode($data);

    }

    public function add_domain_action(Request $request)
    {
        $given_domain_name = strip_tags(strtolower($request->input('domain_name')));
        $domain_name = get_domain_only($given_domain_name);
        // $domain_exist = $this->basic->get_data('visitor_analysis_domain_list',$where,'id');
        $domain_exist = DB::table('visitor_analysis_domain_list')->where(["user_id" => Auth::user()->id])->where(['domain_name' => $domain_name])->get();
        $domain_exist=json_decode(json_encode($domain_exist));
        if(!empty($domain_exist))
        {
            echo '<div class="alert alert-danger text-center"><i class="fas fa-times-circle"></i> '.__('You have already added this domain for tracking.').'</div>';
            exit;
        }

        //************************************************//
        $status=$this->_check_usage($module_id=1,$req=1);
        if($status=="2") 
        {
            echo '<div class="alert alert-danger text-center"><i class="fas fa-times-circle"></i> '.__("Module limit is over.").'</div>';
            exit();
        }
        else if($status=="3") 
        {
            echo '<div class="alert alert-danger text-center"><i class="fas fa-times-circle"></i> '.__("Module limit is over.").'</div>';
            exit();
        }
        //************************************************//

        DB::beginTransaction();

        $random_num = random_number_generator().time()."-".Auth::user()->id;
        $js_code = '<script id="xvas-22-domain-name" xvas-22-data-name="'.$random_num.'" type="text/javascript" src="'.route('js_controller_client').'"></script>';
        $js_code=htmlspecialchars($js_code);

        $data = [
            'user_id' => Auth::user()->id,
            'domain_name' => $domain_name,
            'domain_code' => $random_num,
            'js_code' => $js_code,
            'add_date' => date("Y-m-d")
        ];

        // $this->basic->insert_data('visitor_analysis_domain_list',$data);
        DB::table('visitor_analysis_domain_list')->insert($data);


        //******************************//
        // insert data to useges log table
        $this->_insert_usage_log($module_id=1,$req=1);   
        //******************************//
        

        if(DB::commit())
        {
            echo '<div class="alert alert-danger text-center"><i class="fas fa-times-circle"></i> '.__("Something went wrong, please try again.").'</div>';

        } 

            else 
            {
                $str = '
                <div class="card">
                    <div class="card-header">
                      <h4><i class="fas fa-copy"></i> '.__('Confirmation').'</h4>
                    </div>
                    <div class="card-body">
                      <p>'.__('Domain has been added successfully for visitor analysis. Please copy the below code and paste it to your website that you want to track.').'</p>
                      <pre class="language-javascript">
                        <code class="dlanguage-javascript copy_code">
                '.$js_code.'</code>
                      </pre>
                    </div>
                </div>
                ';
                $str .='
                    <script>
                        $(document).ready(function() {
                            Prism.highlightAll();
                            $(".toolbar-item").find("a").addClass("copy");
    
                            $(document).on("click", ".copy", function(event) {
                                event.preventDefault();
    
                                $(this).html("'.__('Copied!').'");
                                var that = $(this);
                                
                                var text = $(this).prev("code").text();
                                var temp = $("<input>");
                                $("body").append(temp);
                                temp.val(text).select();
                                document.execCommand("copy");
                                temp.remove();
    
                                setTimeout(function(){
                                  $(that).html("'.__('Copy').'");
                                }, 2000); 
    
                            });
                        });
                    </script>
                    ';
                echo $str;

            }
    }

    public function ajax_delete_domain(Request $request)
    {
        if(config('app.is_demo') == '1')
        {
            echo "error"; 
            exit();
        }

        $id = $request->input('table_id');
        // $info = $this->basic->get_data('visitor_analysis_domain_list',array('where'=>array('id'=>$id,'user_id'=>Auth::user()->id)));
        $info = DB::table('visitor_analysis_domain_list')->where('id',$id)->where('user_id',Auth::user()->id)->get();
        if(empty($info))
        {
            echo 'no_match';
            exit;
        }

        // $this->db->trans_start();
        DB::beginTransaction();

        // $this->basic->delete_data('visitor_analysis_domain_list',$where=array('id'=>$id));
        DB::table('visitor_analysis_domain_list')->where('id',$id)->delete();
        // $this->basic->delete_data('visitor_analysis_domain_list_data',$where=array('domain_list_id'=>$id));
        DB::table('visitor_analysis_domain_list_data')->where('domain_list_id',$id)->delete();

        $this->_delete_usage_log($module_id=1,$request=1);

        // $this->db->trans_complete();
        // if($this->db->trans_status() === false) {
        //     echo 'error';
        // } else {
        //     echo 'success';
        // }

        if(DB::commit()) {
            echo 'error';
        } else {
            DB::rollBack();
            echo 'success';
        }
    }

    public function ajax_delete_last_30_days_data(Request $request)
    {
        $id = $request->input('table_id');
        // $info = $this->basic->get_data('visitor_analysis_domain_list',array('where'=>array('id'=>$id,'user_id'=>Auth::user()->id)));
        $info = DB::table('visitor_analysis_domain_list')->where(['id'=>$id])->where(['user_id'=>Auth::user()->id])->get();
        if(empty($info))
        {
            echo 'no_match';
            exit;
        }

        // $this->db->trans_start();
        DB::beginTransaction();
        $to_date = date("Y-m-d H:i:s");
        $from_date = date("Y-m-d H:i:s",strtotime("$to_date-30 days"));
        DB::table('visitor_analysis_domain_list_data')->where('domain_list_id',$id)->where('date_time','<',$from_date)->delete();
        // $this->db->or_where('last_scroll_time <',$from_date);
        // $this->db->or_where('last_engagement_time <',$from_date);
        // $this->db->delete('visitor_analysis_domain_list_data');

        // $this->db->trans_complete();
        // if($this->db->trans_status() === false) {
        //     echo 'error';
        // } else {
        //     echo 'success';
        // }
        if(DB::commit()) {
            echo 'error';
        } else {
            echo 'success';
        }
    }

    public function get_js_code(Request $request)
    {
        $id = $request->input('table_id');
        // $info = $this->basic->get_data('visitor_analysis_domain_list',array('where'=>array('id'=>$id,'user_id'=>Auth::user()->id)));
        $info = DB::table('visitor_analysis_domain_list')->where(['id'=>$id,'user_id'=>Auth::user()->id])->get();
        if(empty($info))
        {
            $error_message = '
                        <div class="card" id="nodata">
                          <div class="card-body">
                            <div class="empty-state">
                              <img class="img-fluid" style="height: 200px" src="'.asset('assets/img/drawkit/drawkit-nature-man-colour.svg').'" alt="image">
                              <h2 class="mt-0">'.__("We could not find any data.").'</h2>
                            </div>
                          </div>
                        </div>';
            echo $error_message;
        }
        else
        {
            $js_code = $info[0]->js_code;
            $content='<div class="row">
                    <div class="col-12">';
            $content .= '
                        <div class="card">
                          <div class="card-header">
                            <h4><i class="fas fa-copy"></i> '.__("Copy the below code for further use.").'</h4>
                          </div>
                          <div class="card-body">
                            <pre class="language-javascript">
                                <code class="dlanguage-javascript copy_code">
        '.$js_code.'
                                </code>
                            </pre>
                          </div>
                        </div>';
            $content .='</div>
                </div>
                <script>
                    $(document).ready(function() {
                        Prism.highlightAll();
                        $(".toolbar-item").find("a").addClass("copy");

                        $(document).on("click", ".copy", function(event) {
                            event.preventDefault();

                            $(this).html("'.__('Copied!').'");
                            var that = $(this);
                            
                            var text = $(this).prev("code").text();
                            var temp = $("<input>");
                            $("body").append(temp);
                            temp.val(text).select();
                            document.execCommand("copy");
                            temp.remove();

                            setTimeout(function(){
                              $(that).html("'.__('Copy').'");
                            }, 2000); 

                        });
                    });
                </script>
                ';
            echo $content;
        }
    }

    public function domain_details($id=0)
    {
        $data['id'] = $id;
        // $info = $this->basic->get_data('visitor_analysis_domain_list',['where'=>['id'=>$id,'user_id'=>Auth::user()->id]]);
        $info = DB::table('visitor_analysis_domain_list')->where(['id'=>$id,'user_id'=>Auth::user()->id])->get();
        $info= json_decode(json_encode($info));
        if(!empty($info)){
            $data['body'] = "seo-tools.analysis-tools.visitor.domain-details";
            return $this->_viewcontroller($data); 
        }
        else
        {
            $data['body'] = "seo-tools.analysis-tools.visitor.no-data";
            return $this->_viewcontroller($data); 
        }
    }

    public function ajax_get_individual_browser_data(Request $request)
    {
        $domain_id = $request->input('domain_id');
        $date_range = $request->input('date_range');
        $browser_name = $request->input('browser_name');
        

        $from_and_to_date = [];
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";

        $table = "visitor_analysis_domain_list_data";
        $select = ['session_value','date_time','is_new','browser_version','browser_name'];
        // $all_data = $this->basic->get_data($table,$where,$select);
        $all_data = DB::table($table)->select($select)->where( ["domain_list_id" => $domain_id])->get();

        $browser_daily_session = [];
        $browser_versions = [];
        foreach ($all_data as $key => $single_row)
        {
            if($single_row->browser_name == $browser_name)
            {
                $formated_date = date("Y-m-d",strtotime($single_row->date_time));
                $browser_daily_session[$formated_date]['date'] = $formated_date;
                if(isset($browser_daily_session[$formated_date]['sessions']))
                    $browser_daily_session[$formated_date]['sessions'] = $browser_daily_session[$formated_date]['sessions'].','.$single_row->session_value;
                else
                    $browser_daily_session[$formated_date]['sessions'] = $single_row->session_value;

                $browser_versions[$single_row->browser_version]['browser_version'] = $single_row->browser_version;
                $browser_versions[$single_row->browser_version]['browser_name'] = $single_row->browser_name;
                if(isset($browser_versions[$single_row->browser_version]['sessions']))
                    $browser_versions[$single_row->browser_version]['sessions'] = $browser_versions[$single_row->browser_version]['sessions'].','.$single_row->session_value;
                else
                    $browser_versions[$single_row->browser_version]['sessions'] = $single_row->session_value;

                if(isset($browser_versions[$single_row->browser_version]['new_user']))
                    $browser_versions[$single_row->browser_version]['new_user'] = $browser_versions[$single_row->browser_version]['new_user'].','.$single_row->is_new;
                else
                    $browser_versions[$single_row->browser_version]['new_user'] = $single_row->is_new;
            }    
        }

        foreach($browser_daily_session as $value){
            $sessions = [];
            $sessions = explode(',', $value['sessions']);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);
            $report[$value['date']]['sessions'] = $sessions;
        }

        $dDiff = strtotime($to_date) - strtotime($from_date);
        $no_of_days = floor($dDiff/(60*60*24));
        $line_char_data = [];

        for($i=0;$i<=$no_of_days+1;$i++){
            $day_count = date('Y-m-d', strtotime($from_date. " + $i days"));
            if(isset($report[$day_count])){
                $line_char_data[$i]['session'] = $report[$day_count]['sessions'];
            } else {
                $line_char_data[$i]['session'] = 0;               
            }
            $line_char_data[$i]['date'] = date('d M Y', strtotime($from_date. " + $i days"));
        }

        $info['browser_daily_session_dates'] = array_column($line_char_data, 'date');
        $info['browser_daily_session_values'] = array_column($line_char_data, 'session');
        $max1 = (!empty($info['browser_daily_session_values'])) ? max($info['browser_daily_session_values']) : 0;
        $steps = round($max1/7);
        if($steps==0) $steps = 1;
        $info['browser_daily_session_steps'] = $steps;
        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));

        $browser_version_str = "<table class='table table-sm'>
                                <thead>
                                    <tr style='background:#0073B7;color:white'>
                                        <th>SL</th>
                                        <th>".__('Browser Name')."</th>
                                        <th>".__('Sessions')."</th>
                                        <th>".__('New Users')."</th>
                                    </tr>
                                </thead>
                                <tbody>
                                ";        
        $i = 0;
        foreach($browser_versions as $value){
            $new_users = [];
            $sessions = [];
            $i++;
            $new_users = explode(',', $value['new_user']);
            $new_users = array_filter($new_users);
            $new_users = array_values($new_users);
            $new_users = count($new_users);

            $sessions = explode(',', $value['sessions']);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);

            $browser_version_str .= "<tr><td>".$i."</td><td>".$value['browser_name']."</td><td>".$sessions."</td><td>".$new_users."</td></tr>";

        }
        $browser_version_str .= "</tbody></table>";

        $info['browser_version'] = $browser_version_str;

        echo json_encode($info);
    }


    public function ajax_get_individual_device_data(Request $request)
    {
        $domain_id = $request->input('domain_id');
        $date_range = $request->input('date_range');
        $device_name = $request->input('device_name');

        $from_and_to_date = [];
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";

        $table = "visitor_analysis_domain_list_data";
         

        $select = ['session_value','date_time','device'];
        // $all_data = $this->basic->get_data($table,$where,$select);
        $all_data = DB::table($table)->select($select)->where(["domain_list_id" => $domain_id])->orwhere("date_time" ,">=", $from_date)->orwhere("date_time", "<=", $to_date)->get();
        $browser_daily_session = [];
        foreach ($all_data as $key => $single_row) 
        {
            if($single_row->device == $device_name)
            {
                $formated_date = date("Y-m-d",strtotime($single_row->date_time));
                $browser_daily_session[$formated_date]['date'] = $formated_date;
                if(isset($browser_daily_session[$formated_date]['sessions']))
                    $browser_daily_session[$formated_date]['sessions'] = $browser_daily_session[$formated_date]['sessions'].','.$single_row->session_value;
                else
                    $browser_daily_session[$formated_date]['sessions'] = $single_row->session_value;
            }
        }

        foreach($browser_daily_session as $value){
            $sessions = [];
            $sessions = explode(',', $value['sessions']);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);
            $report[$value['date']]['sessions'] = $sessions;
        }

        $dDiff = strtotime($to_date) - strtotime($from_date);
        $no_of_days = floor($dDiff/(60*60*24));
        $line_char_data = [];

        for($i=0;$i<=$no_of_days+1;$i++){
            $day_count = date('Y-m-d', strtotime($from_date. " + $i days"));
            if(isset($report[$day_count])){
                $line_char_data[$i]['session'] = $report[$day_count]['sessions'];
            } else {
                $line_char_data[$i]['session'] = 0;               
            }
            $line_char_data[$i]['date'] = date('d M Y', strtotime($from_date. " + $i days"));
        }

        $info['device_daily_session_dates'] = array_column($line_char_data, 'date');
        $info['device_daily_session_values'] = array_column($line_char_data, 'session');
        $max1 = (!empty($info['device_daily_session_values'])) ? max($info['device_daily_session_values']) : 0;
        $steps = round($max1/7);
        if($steps==0) $steps = 1;
        $info['device_daily_session_steps'] = $steps;
        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));

        echo json_encode($info);
    }

    public function ajax_get_os_report_data(Request $request)
    {
        $domain_id = $request->input('domain_id');
        $date_range = $request->input('date_range');

        $table = "visitor_analysis_domain_list_data";
        $from_and_to_date = [];
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";

        // $where = array();
        // $where['where'] = array('id' => $domain_id);
        // $domain_info = $this->basic->get_data('visitor_analysis_domain_list',$where,$select="");
        $domain_info = DB::table('visitor_analysis_domain_list')->where(['id'=> $domain_id])->get();
        

        
        $info['domain_name'] = $domain_info[0]->domain_name;
        

        $where =[ 
            [ "date_time >=" => $from_date],
             ["date_time <=" => $to_date],
             ["domain_list_id" => $domain_id]
         
         ];

        // $select = ["GROUP_CONCAT(session_value SEPARATOR ',') as sessions","GROUP_CONCAT(is_new SEPARATOR ',') as new_user","os"];
        $select = [
            DB::raw("GROUP_CONCAT(session_value SEPARATOR ',') as sessions"),
            DB::raw("GROUP_CONCAT(is_new SEPARATOR ',') as new_user"),
            'os'
        ];
        
        // $os_report = $this->basic->get_data($table,$where,$select,$join='',$limit='',$start=NULL,$order_by='',$group_by='os');
        $os_report = DB::table($table)->select($select)->where(["domain_list_id" => $domain_id])->orwhere("date_time" ,">=", $from_date)->orwhere("date_time", "<=", $to_date)->groupBy('os')->get();
        

        $os_report_str = "<table class='table table-sm'>
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>".__('OS Name')."</th>
                                        <th>".__('Sessions')."</th>
                                        <th>".__('New Users')."</th>
                                        <th>".__('Action')."</th>
                                    </tr>
                                </thead>
                                <tbody>
                                ";    
        $os_list = [
            'android' => asset('assets/img/os/android.png'),
            'ipad' => asset('assets/img/os/ipad.png'),
            'iphone' => asset('assets/img/os/iphone.png'),
            'linux' => asset('assets/img/os/linux.png'),
            'mac os x' => asset('assets/img/os/mac.png'),
            'search bot' => asset('assets/img/os/search-bot.png'),
            'windows' => asset('assets/img/os/windows.png'),
        ]; 

        $i = 0;
        foreach($os_report as $value){
            $new_users = [];
            $sessions = [];
            $i++;
            $new_users = explode(',', $value->new_user);
            $new_users = array_filter($new_users);
            $new_users = array_values($new_users);
            $new_users = count($new_users);

            $sessions = explode(',', $value->sessions);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);

            $os_name = strtolower($value->os);
            $os_img_path = isset($os_list[$os_name]) ? $os_list[$os_name] : asset("assets/img/browser/other.png");
            $image = '<img style="height: 15px; width: 20px; margin-top: -3px;" src="'.$os_img_path.'" alt=" "> &nbsp;';

            $os_report_str .= "<tr><td>".$i."</td><td>".$image.$value->os."</td><td>".$sessions."</td><td>".$new_users."</td><td><button class='os_name btn btn-outline-info btn-circle' title='".__('Session Details')."' data='".$value->os."'><i class='fas fa-binoculars'></i></button></td></tr>";

        }
        $os_report_str .= "</tbody></table>";
        $info['os_report_name'] = $os_report_str;
        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));

        echo json_encode($info);

    }

    public function ajax_get_individual_country_data(Request $request)
    {
        $domain_id = $request->input('domain_id');
        $date_range = $request->input('date_range');
        $country_name = $request->input('country_name');

        $from_and_to_date = [];
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";

        $table = "visitor_analysis_domain_list_data";

        $where =[ 
            [ "date_time >=" => $from_date],
             ["date_time <=" => $to_date],
             ["domain_list_id" => $domain_id]
         
         ];
        $select = ['session_value','date_time','city','is_new','country'];
        // $all_data = $this->basic->get_data($table,$where,$select);
        $all_data = DB::table($table)->select($select)->where($where)->get();
        foreach ($all_data as $key => $single_row) 
        {
            if($single_row->country == $country_name)
            {
                $formated_date = date("Y-m-d",strtotime($single_row->date_time));
                $country_daily_session[$single_row->city]['date'] = $formated_date;
                if(isset($country_daily_session[$single_row->city]['sessions']))
                    $country_daily_session[$single_row->city]['sessions'] = $country_daily_session[$single_row->city]['sessions'].','.$single_row->session_value;
                else
                    $country_daily_session[$single_row->city]['sessions'] = $single_row->session_value;

                $country_city[$single_row->city]['country'] = $single_row->country;
                $country_city[$single_row->city]['city'] = $single_row->city;
                if(isset($country_city[$single_row->city]['sessions']))
                    $country_city[$single_row->city]['sessions'] = $country_city[$single_row->city]['sessions'].','.$single_row->session_value;
                else
                    $country_city[$single_row->city]['sessions'] = $single_row->session_value;

                if(isset($country_city[$single_row->city]['new_user']))
                    $country_city[$single_row->city]['new_user'] = $country_city[$single_row->city]['new_user'].','.$single_row->is_new;
                else
                    $country_city[$single_row->city]['new_user'] = $single_row->is_new;
            }
        }

        foreach($country_daily_session as $value){
            $sessions = [];
            $sessions = explode(',', $value['sessions']);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);
            $report[$value['date']]['sessions'] = $sessions;
        }

        $dDiff = strtotime($to_date) - strtotime($from_date);
        $no_of_days = floor($dDiff/(60*60*24));
        $line_char_data = [];

        for($i=0;$i<=$no_of_days+1;$i++){
            $day_count = date('Y-m-d', strtotime($from_date. " + $i days"));
            if(isset($report[$day_count])){
                $line_char_data[$i]['session'] = $report[$day_count]['sessions'];
            } else {
                $line_char_data[$i]['session'] = 0;               
            }
            $line_char_data[$i]['date'] = date('d M Y', strtotime($from_date. " + $i days"));
        }

        $info['country_daily_session_dates'] = array_column($line_char_data,'date');
        $info['country_daily_session_values'] = array_column($line_char_data,'session');
        $max1 = (!empty($info['country_daily_session_values'])) ? max($info['country_daily_session_values']) : 0;
        $steps = round($max1/7);
        if($steps==0) $steps = 1;
        $info['country_daily_session_steps'] = $steps;
        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));

        $country_city_str = "<table class='table table-sm'>
                                    <thead>
                                        <tr>
                                            <th>City Name</th>
                                            <th>Sessions</th>
                                            <th>New Users</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                ";
        // $country_list_individual = $this->get_country_names();       
        $i = 0;
        foreach($country_city as $value){
            $new_users =[];
            $sessions =[];
            $i++;
            $new_users = explode(',', $value['new_user']);
            $new_users = array_filter($new_users);
            $new_users = array_values($new_users);
            $new_users = count($new_users);

            $sessions = explode(',', $value['sessions']);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);

            // $s_country = array_search(trim(strtoupper($value["country"])), $country_list_individual); 
            // $image_link = base_url()."assets/images/flags/".$s_country.".png";

            $country_city_str .= "<tr><td>".$value['city']."</td><td>".$sessions."</td><td>".$new_users."</td></tr>";

        }
        $country_city_str .= "</tbody></table>";

        $info['country_city_str'] = $country_city_str;

        echo json_encode($info);
    }

    public function ajax_get_traffic_source_data(Request $request)
    {
        $domain_id = $request->input('domain_id');
        $date_range = $request->input('date_range');

        
        $from_and_to_date = array();
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";

        $table = "visitor_analysis_domain_list";
        // $where = [];
        // $where['where'] = array('id' => $domain_id);
        // $domain_info = $this->basic->get_data('visitor_analysis_domain_list',$where,$select="");
        $domain_info = DB::table('visitor_analysis_domain_list')->where(['id'=>$domain_id])->get();

        $table = "visitor_analysis_domain_list_data";
        $info['domain_name'] = $domain_info[0]->domain_name;

        $select = ['date_time','session_value','referrer','visit_url'];

        $traffic_source_info = [];
        $daily_traffic_source_info = [];
        // $all_data = $this->basic->get_data($table,$where,$select);
        $all_data = DB::table($table)->select($select)->where(['domain_list_id'=>$domain_id])->get();

        foreach ($all_data as $key => $single_row)
        {
            $formated_date = date("Y-m-d",strtotime($single_row->date_time));

            $traffic_source_info[$single_row->session_value]['date_test'] = $formated_date;
            $traffic_source_info[$single_row->session_value]['session_value'] = $single_row->session_value;
            if(isset($traffic_source_info[$single_row->session_value]['referrer']))
                $traffic_source_info[$single_row->session_value]['referrer'] = $traffic_source_info[$single_row->session_value]['referrer'].",".$single_row->referrer;
            else
                $traffic_source_info[$single_row->session_value]['referrer'] = $single_row->referrer;

            if(isset($traffic_source_info[$single_row->session_value]['visit_url_str']))
                $traffic_source_info[$single_row->session_value]['visit_url_str'] = $traffic_source_info[$single_row->session_value]['visit_url_str'].",".$single_row->visit_url;
            else
                $traffic_source_info[$single_row->session_value]['visit_url_str'] = $single_row->visit_url;

            if(strtotime($single_row->date_time)>=strtotime($from_date) && strtotime($single_row->date_time)<=strtotime($to_date))
            {
                $daily_traffic_source_info[$single_row->session_value]['date_test'] = $formated_date;
                $daily_traffic_source_info[$single_row->session_value]['session_value'] = $single_row->session_value;
                if(isset($daily_traffic_source_info[$single_row->session_value]['referrer']))
                    $daily_traffic_source_info[$single_row->session_value]['referrer'] = $daily_traffic_source_info[$single_row->session_value]['referrer'].",".$single_row->referrer;
                else
                    $daily_traffic_source_info[$single_row->session_value]['referrer'] = $single_row->referrer;

                if(isset($daily_traffic_source_info[$single_row->session_value]['visit_url_str']))
                    $daily_traffic_source_info[$single_row->session_value]['visit_url_str'] = $daily_traffic_source_info[$single_row->session_value]['visit_url_str'].",".$single_row->visit_url;
                else
                    $daily_traffic_source_info[$single_row->session_value]['visit_url_str'] = $single_row->visit_url;
            }
        }

        
        $search_engine_array = array('Baidu','Bing','DuckDuckGo','Ecosia','Exalead','Gigablast','Google','Munax','Qwant','Sogou','Soso.com','Yahoo','Yandex','Youdao','FAROO','YaCy','DeeperWeb','Dogpile','Excite','HotBot','Info.com','Mamma','Metacrawler','Mobissimo','Otalo','Skyscanner','WebCrawler','Accoona','Ansearch','Biglobe','Daum','Egerin','Leit.is','Maktoob','Miner.hu','Najdi.si','Naver','Onkosh','Rambler','Rediff','SAPO','Search.ch','Sesam','Seznam','Walla!','Yandex.ru','ZipLocal');
        $social_network_array = array('Twitter','Facebook','Xing','Renren','plus.Google','Disqus','Linkedin Pulse','Snapchat','Tumblr','Pintarest','Twoo','MyMFB','Instagram','Vine','WhatsApp','vk.com','Meetup','Secret','Medium','Youtube','Reddit');


        $search_link_count = 0;
        $social_link_count = 0;
        $referrer_link_count = 0;
        $direct_link_count = 0;

        $k = 0;
        $referrer_info = [];
        $search_engine_info = [];
        $social_network_info = [];
        $referrer_name = [];

        foreach($traffic_source_info as $value){
            $referrer_array = [];
            if($value['referrer'] != ''){
                $referrer_array = explode(',', $value['referrer']);
                $visit_url = explode(',', $value['visit_url_str']);           
            }

            if(empty($referrer_array)){
                $direct_link_count++;

                if(isset($referrer_info['direct_link']))
                    $referrer_info['direct_link']++;
                else
                   $referrer_info['direct_link'] = 1;
            }
            else{
                $first_part_of_domain_array = [];
                $first_index_of_referrer = get_domain_only($referrer_array[0]);
                $first_index_of_url = get_domain_only($visit_url[0]);
                /** creating referrer info array with count **/
                for($i=0;$i<count($referrer_array);$i++){                    
                    
                    if($first_index_of_referrer != $first_index_of_url && $referrer_array[0] != ''){
                        if(isset($referrer_info[$referrer_array[$i]]))
                            $referrer_info[$referrer_array[$i]]++;
                        else 
                            $referrer_info[$referrer_array[$i]] = 1;
                    }
                    $only_domain_name = get_domain_only($referrer_array[$i]);
                    $first_part_of_domain_array[] = $only_domain_name; 
                    
                } // end of for loop

                if($first_index_of_referrer == $first_index_of_url){
                    $direct_link_count++;
                    if(isset($referrer_info['direct_link']))
                        $referrer_info['direct_link']++;
                    else
                       $referrer_info['direct_link'] = 1;
                }
                if($referrer_array[0] == ''){
                    $direct_link_count++;
                    if(isset($referrer_info['direct_link']))
                        $referrer_info['direct_link']++;
                    else
                       $referrer_info['direct_link'] = 1;
                } 


                $count_search_engine = [];
                $count_social_network = [];
                /** for social network and search engine array creation and counter **/
                for($i=0;$i<count($first_part_of_domain_array);$i++){

                    for($j=0;$j<count($search_engine_array);$j++){
                        $occurance_search_engine = stripos($first_part_of_domain_array[$i], $search_engine_array[$j]);
                        if($occurance_search_engine !== FALSE){
                            if(isset($search_engine_info[$search_engine_array[$j]])){
                                $search_engine_info[$search_engine_array[$j]]++;
                                $count_search_engine[] = $search_engine_array[$j];
                            }
                            else{
                                $search_engine_info[$search_engine_array[$j]] = 1;
                                $count_search_engine[] = $search_engine_array[$j];
                            }
                        }
                    } // end of for loop
                    
                    for($k=0;$k<count($social_network_array);$k++){
                        $occurance_social_network = stripos($first_part_of_domain_array[$i], $social_network_array[$k]);
                        if($occurance_social_network !== FALSE){
                            if(isset($social_network_info[$social_network_array[$k]])){
                                $social_network_info[$social_network_array[$k]]++;
                                $count_social_network[] = $social_network_array[$k];
                            }
                            else{
                                $social_network_info[$social_network_array[$k]] = 1;
                                $count_social_network[] = $social_network_array[$k];
                            }
                        }
                    } // end of for loop

                } // end of for loop

                if(!empty($count_search_engine)){
                    $search_link_count = $search_link_count + count($count_search_engine);
                }
                if(!empty($count_social_network)){
                    $social_link_count = $social_link_count + count($count_social_network);
                }
                if(empty($count_search_engine) && empty($count_social_network)){
                    if($first_index_of_referrer != $first_index_of_url && $first_index_of_referrer != '')
                        $referrer_link_count = $referrer_link_count + count($first_part_of_domain_array);
                }

            }

        }

        // for top five referrer section
        $total_referrers = $direct_link_count+$search_link_count+$social_link_count+$referrer_link_count;
        $top_referrer = asort($referrer_info);
        $top_referrer = array_reverse($referrer_info);
        $top_referrer_keys = array_keys($top_referrer);
        $top_referrer_values = array_values($top_referrer);
        $no_of_top_referrer = 0;
        if(count($top_referrer)>5) $no_of_top_referrer = 5;
        else $no_of_top_referrer = count($top_referrer);

        $color_array = array("#44B3C2", "#F1A94E", "#E45641", "#5D4C46", "#7B8D8E");
        $top_five_referrer = [];
        for($i=0;$i<$no_of_top_referrer;$i++){
            $top_five_referrer[$i]['value'] = number_format($top_referrer_values[$i]*100/$total_referrers,2);
            $top_five_referrer[$i]['color'] = $color_array[$i];
            $top_five_referrer[$i]['highlight'] = $color_array[$i];
            if($top_referrer_keys[$i] == 'direct_link')
                $link_name = "Direct Link";
            else $link_name = $top_referrer_keys[$i];
            $top_five_referrer[$i]['label'] = $link_name;
        }
        $info['top_referrer_present_value'] = array_column($top_five_referrer, 'value');
        $info['top_referrer_present_label'] = array_column($top_five_referrer, 'label');
        // $info['top_referrer_data'] = $top_five_referrer;
        //end of top five referrer section

        //section for search engine info
        $search_engine_info_keys = array_keys($search_engine_info);
        $search_engine_info_values = array_values($search_engine_info);
        $search_engine_color = array("#44B3C2","#F1A94E","#E45641","#5D4C46","#7B8D8E","#F2EDD8","#BCCF3D","#BCCF3D","#82683B","#B6A754","#D79C8C");
        $j = 0;
        $search_engine_result = array();
        $search_engine_names = array();
        for($i=0;$i<count($search_engine_info);$i++){
            $search_engine_result[$i]['value'] = $search_engine_info_values[$i];
            $search_engine_result[$i]['color'] = $search_engine_color[$j];
            $search_engine_result[$i]['highlight'] = $search_engine_color[$j];
            $search_engine_result[$i]['label'] = $search_engine_info_keys[$i];

            // $search_engine_names[$i]['name'] = $search_engine_info_keys[$i];
            array_push($search_engine_names, $search_engine_info_keys[$i]);
            $j++;
            if($j == 10) $j=0;
        }

        $info['search_engine_colors'] = array_column($search_engine_result, 'color');
        $info['search_engine_labels'] = array_column($search_engine_result, 'label');
        $info['search_engine_values'] = array_column($search_engine_result, 'value');
        // end of search engine info

        
        //social network info
        $social_network_info_keys = array_keys($social_network_info);
        $social_network_info_values = array_values($social_network_info);
        $social_network_color = array_reverse(array("#000066","#FFFFCC","#CCCCFF","#990066","#003399","#CCFFCC","#0099CC","#FF0080","#800080","#D79C8C"));
        $j = 0;
        $social_network_result = array();
        $social_network_names = array();
        for($i=0;$i<count($social_network_info);$i++){
            $social_network_result[$i]['value'] = $social_network_info_values[$i];
            $social_network_result[$i]['color'] = $social_network_color[$j];
            $social_network_result[$i]['highlight'] = $social_network_color[$j];
            $social_network_result[$i]['label'] = $social_network_info_keys[$i];

            $social_network_names[$i]['name'] = $social_network_info_keys[$i];
            $social_network_names[$i]['color'] = $social_network_color[$j];
            $j++;
            if($j == 10) $j=0;
        }

        $info['social_network_colors'] = array_column($social_network_result, 'color');
        $info['social_network_labels'] = array_column($social_network_result, 'label');
        $info['social_network_values'] = array_column($social_network_result, 'value');

        // end of social network info

        $day_wise_search_link_count = 0;
        $day_wise_social_link_count = 0;
        $day_wise_referrer_link_count = 0;
        $day_wise_direct_link_count = 0;

        //for daily report section
        $visit_url = array();
        foreach($daily_traffic_source_info as $value){
            $referrer_array = array();
            if(isset($value['referrer'])){
                $referrer_array = explode(',', $value['referrer']);
                $empty_referrer_array = array_filter($referrer_array);
                $empty_referrer_array = array_values($empty_referrer_array);

                $visit_url = explode(',', $value['visit_url_str']);
            }

            if(empty($empty_referrer_array)){

                $day_wise_direct_link_count++;
                if(isset($daily_report[$value['date_test']]['direct_link_count']))
                    $daily_report[$value['date_test']]['direct_link_count'] = $daily_report[$value['date_test']]['direct_link_count'] + $day_wise_direct_link_count;
                else
                    $daily_report[$value['date_test']]['direct_link_count'] = $day_wise_direct_link_count;
                $day_wise_direct_link_count = 0;

            }
            else{
                $first_part_of_domain_array = array();
                for($i=0;$i<count($referrer_array);$i++){
                    $only_domain_name = get_domain_only($referrer_array[$i]);
                    $first_part_of_domain_array[] = $only_domain_name;  
                }

                $first_index_of_referrer = get_domain_only($referrer_array[0]);
                $first_index_of_url = get_domain_only($visit_url[0]);
                if($first_index_of_referrer == $first_index_of_url){
                    $day_wise_direct_link_count++;
                    if(isset($daily_report[$value['date_test']]['direct_link_count']))
                        $daily_report[$value['date_test']]['direct_link_count'] = $daily_report[$value['date_test']]['direct_link_count'] + $day_wise_direct_link_count;
                    else
                       $daily_report[$value['date_test']]['direct_link_count'] = $day_wise_direct_link_count;
                   $day_wise_direct_link_count = 0;
                }
                if($referrer_array[0] == ''){
                    $day_wise_direct_link_count++;
                    if(isset($daily_report[$value['date_test']]['direct_link_count']))
                        $daily_report[$value['date_test']]['direct_link_count'] = $daily_report[$value['date_test']]['direct_link_count'] + $day_wise_direct_link_count;
                    else
                       $daily_report[$value['date_test']]['direct_link_count'] = $day_wise_direct_link_count;
                   $day_wise_direct_link_count = 0;
                }

                $count_search_engine = array();
                $count_social_network = array();

                for($i=0;$i<count($first_part_of_domain_array);$i++){

                    for($j=0;$j<count($search_engine_array);$j++){
                        $occurance_search_engine = stripos($first_part_of_domain_array[$i], $search_engine_array[$j]);
                        if($occurance_search_engine !== FALSE){
                            $count_search_engine[] = $search_engine_array[$j];
                        }
                    }
                    
                    for($k=0;$k<count($social_network_array);$k++){
                        $occurance_social_network = stripos($first_part_of_domain_array[$i], $social_network_array[$k]);
                        if($occurance_social_network !== FALSE){
                            $count_social_network[] = $social_network_array[$k];
                        }
                    }

                }                

                if(!empty($count_search_engine)){
                    $day_wise_search_link_count = $day_wise_search_link_count + count($count_search_engine);
                    if(isset($daily_report[$value['date_test']]['search_link_count']))
                        $daily_report[$value['date_test']]['search_link_count'] = $daily_report[$value['date_test']]['search_link_count'] + $day_wise_search_link_count;
                    else
                        $daily_report[$value['date_test']]['search_link_count'] = $day_wise_search_link_count;
                    $day_wise_search_link_count = 0;
                }
                if(!empty($count_social_network)){
                    $day_wise_social_link_count = $day_wise_social_link_count + count($count_social_network);
                    if(isset($daily_report[$value['date_test']]['social_link_count']))
                        $daily_report[$value['date_test']]['social_link_count'] = $daily_report[$value['date_test']]['social_link_count'] + $day_wise_social_link_count;
                    else
                        $daily_report[$value['date_test']]['social_link_count'] = $day_wise_social_link_count;
                    $day_wise_social_link_count = 0;
                }
                if(empty($count_search_engine) && empty($count_social_network)) {
                    if($first_index_of_referrer != $first_index_of_url && $first_index_of_referrer != ''){

                        $day_wise_referrer_link_count = $day_wise_referrer_link_count + count($first_part_of_domain_array);
                        if(isset($daily_report[$value['date_test']]['referrer_link_count']))
                            $daily_report[$value['date_test']]['referrer_link_count'] = $daily_report[$value['date_test']]['referrer_link_count'] + $day_wise_referrer_link_count;
                        else
                            $daily_report[$value['date_test']]['referrer_link_count'] = $day_wise_referrer_link_count;
                        $day_wise_referrer_link_count = 0;
                    }
                }

            }
        }

        $dDiff = strtotime($to_date) - strtotime($from_date);
        $no_of_days = floor($dDiff/(60*60*24));
        $line_char_data = array();
        for($i=0;$i<=$no_of_days+1;$i++){
            $day_count = date('Y-m-d', strtotime($from_date. " + $i days"));
            if(isset($daily_report[$day_count])){
                if(isset($daily_report[$day_count]['direct_link_count']))
                    $line_char_data[$i]['direct_link'] = $daily_report[$day_count]['direct_link_count'];
                else
                    $line_char_data[$i]['direct_link'] = 0;

                if(isset($daily_report[$day_count]['search_link_count']))
                    $line_char_data[$i]['search_link'] = $daily_report[$day_count]['search_link_count'];
                else
                    $line_char_data[$i]['search_link'] = 0;

                if(isset($daily_report[$day_count]['social_link_count']))
                    $line_char_data[$i]['social_link'] = $daily_report[$day_count]['social_link_count'];
                else
                    $line_char_data[$i]['social_link'] = 0;

                if(isset($daily_report[$day_count]['referrer_link_count']))
                    $line_char_data[$i]['referrer_link'] = $daily_report[$day_count]['referrer_link_count'];
                else
                    $line_char_data[$i]['referrer_link'] = 0;
            } else {
                $line_char_data[$i]['direct_link'] = 0;
                $line_char_data[$i]['search_link'] = 0;
                $line_char_data[$i]['social_link'] = 0;
                $line_char_data[$i]['referrer_link'] = 0;
            }
            $line_char_data[$i]['date'] = date('d M Y', strtotime($from_date. " + $i days"));
        }

        // $info['line_chart_data'] = $line_char_data;
        $info['traffic_line_chart_dates'] = array_column($line_char_data, 'date');
        $info['traffic_direct_link'] = array_column($line_char_data, 'direct_link');
        $info['traffic_search_link'] = array_column($line_char_data, 'search_link');
        $info['traffic_social_link'] = array_column($line_char_data, 'social_link');
        $info['traffic_referrer_link'] = array_column($line_char_data, 'referrer_link');
        $max1 = (!empty($info['traffic_direct_link'])) ? max($info['traffic_direct_link']) : 0;
        $max2 = (!empty($info['traffic_search_link'])) ? max($info['traffic_search_link']) : 0;
        $max3 = (!empty($info['traffic_social_link'])) ? max($info['traffic_social_link']) : 0;
        $max4 = (!empty($info['traffic_referrer_link'])) ? max($info['traffic_referrer_link']) : 0;
        $steps = round(max(array($max1,$max2,$max3,$max4))/7);
        if($steps==0) $steps = 1;
        $info['traffic_daily_line_step_count'] = $steps;
        // end of daily report section

        $info['traffic_bar_direct_link_count'] = $direct_link_count;
        $info['traffic_bar_search_link_count'] = $search_link_count;
        $info['traffic_bar_social_link_count'] = $social_link_count;
        $info['traffic_bar_referrer_link_count'] = $referrer_link_count;
        $total_traffic_different_source = array($direct_link_count,$search_link_count,$social_link_count,$referrer_link_count);
        $max1 = (!empty($total_traffic_different_source)) ? max($total_traffic_different_source) : 0;
        $steps = round($max1/7);
        if($steps==0) $steps = 1;
        $info['traffic_bar_step_count'] = $steps;

        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));

        echo json_encode($info);
    }

    public function ajax_get_individual_os_data(Request $request)
    {
        $domain_id = $request->input('domain_id');
        $post_date_range = $request->input('date_range');
        $os_name = $request->input('os_name');

        $table = "visitor_analysis_domain_list_data";

        $from_date = $to_date = "";
        if($post_date_range!="")
        {
            $exp = explode('|', $post_date_range);
            $from_date = isset($exp[0])?$exp[0]:"";
            $to_date   = isset($exp[1])?$exp[1]:"";
  
        }

        $query = DB::table($table);
        if($from_date!='') $query->where("add_date", ">=", $from_date);
        if($to_date!='') $query->where("add_date", "<=", $to_date); 

 
        $where =[ 
            [ "date_time >=" => $from_date],
             ["date_time <=" => $to_date],
             ["domain_list_id" => $domain_id]
         
         ];
        $select = ['session_value','date_time','os'];
        // $all_data = $this->basic->get_data($table,$where,$select);
        $all_data =$query->select($select)->where(["domain_list_id" => $domain_id])->orwhere("date_time" ,">=", $from_date)->orwhere("date_time", "<=", $to_date)->get();
        
        $browser_daily_session = [];
        foreach ($all_data as $key => $single_row) 
        {
            if($single_row->os == $os_name)
            {
                $formated_date = date("Y-m-d",strtotime($single_row->date_time));
                $browser_daily_session[$formated_date]['os'] = $single_row->os;
                $browser_daily_session[$formated_date]['date'] = $formated_date;
                if(isset($browser_daily_session[$formated_date]['sessions']))
                    $browser_daily_session[$formated_date]['sessions'] = $browser_daily_session[$formated_date]['sessions'].','.$single_row->session_value;
                else
                    $browser_daily_session[$formated_date]['sessions'] = $single_row->session_value;
            }
        }

        foreach($browser_daily_session as $value)
        {
            $sessions = [];
            $sessions = explode(',', $value['sessions']);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);
            $report[$value['date']]['sessions'] = $sessions;
        }

        $dDiff = strtotime($to_date) - strtotime($from_date);
        $no_of_days = floor($dDiff/(60*60*24));
        $line_char_data = array();

        for($i=0;$i<=$no_of_days+1;$i++){
            $day_count = date('Y-m-d', strtotime($from_date. " + $i days"));
            if(isset($report[$day_count])){
                $line_char_data[$i]['session'] = $report[$day_count]['sessions'];
            } else {
                $line_char_data[$i]['session'] = 0;               
            }
            $line_char_data[$i]['date'] = date('d M Y', strtotime($from_date. " + $i days"));
        }

        $info['os_daily_session_dates'] = array_column($line_char_data, 'date');
        $info['os_daily_session_values'] = array_column($line_char_data, 'session');
        $max1 = (!empty($info['os_daily_session_values'])) ? max($info['os_daily_session_values']) : 0;
        $steps = round($max1/7);
        if($steps==0) $steps = 1;
        $info['os_daily_session_steps'] = $steps;
        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));

        echo json_encode($info);
    }
    
    public function ajax_get_overview_data(Request $request)
    {
        $domain_id = $request->input('domain_id');
        $date_range = $request->input('date_range');
        $from_and_to_date = array();
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";


        // $domain_info = $this->basic->get_data('visitor_analysis_domain_list',$where,$select="");
        $domain_info = DB::table('visitor_analysis_domain_list')->where(['id'=>$domain_id, 'user_id'=>Auth::user()->id])->get();
        $table = "visitor_analysis_domain_list_data";
        // this domain name will be placed for all the pages of visitor analysis tab
        $info['domain_name'] = $domain_info[0]->domain_name;

        $where =[ 
            [ 'date_time' ,'>=', $from_date],
             ["date_time", "<=", $to_date],
             ["domain_list_id" => $domain_id]
         
         ];
        $select = ['cookie_value','session_value','last_scroll_time','last_engagement_time','date_time','is_new'];

        // $all_data = $this->basic->get_data($table,$where,$select);
        $all_data = DB::table($table)->select($select)->where('date_time' ,'>=', $from_date)->where("date_time", "<=", $to_date)->where(["domain_list_id" => $domain_id])->get();
        $total_page_view = $all_data;
        $total_unique_visitor = [];
        $all_unique_cookies = [];
        $total_unique_session = [];
        $all_unique_sessions = [];
        $stay_time_info = [];
        $day_wise_visitor = [];
        $all_unique_dates = [];
        foreach($all_data as $key=>$single_row)
        {

            if(isset($all_unique_cookies[$single_row->cookie_value]))
            {
                $all_unique_cookies[$single_row->cookie_value] = $all_unique_cookies[$single_row->cookie_value] + 1;
                $total_unique_visitor[$single_row->cookie_value] = $all_unique_cookies[$single_row->cookie_value];
            }
            else
            {
                $all_unique_cookies[$single_row->cookie_value] = 1;
                $total_unique_visitor[$single_row->cookie_value] = 1;
            }

            if(isset($all_unique_sessions[$single_row->session_value]))
            {
                $all_unique_sessions[$single_row->session_value] = $all_unique_sessions[$single_row->session_value] + 1;
                $total_unique_session[$single_row->session_value]['session_number'] = $all_unique_sessions[$single_row->session_value];
            }
            else
            {
                $all_unique_sessions[$single_row->session_value] = 1;
                $total_unique_session[$single_row->session_value]['session_number'] = 1;
            }
            $total_unique_session[$single_row->session_value]['last_scroll_time'] = $single_row->last_scroll_time;
            $total_unique_session[$single_row->session_value]['last_engagement_time'] = $single_row->last_engagement_time;

            $stay_time_info[$key]['stay_from'] = $single_row->date_time;
            $stay_time_info[$key]['last_engagement_time'] = $single_row->last_engagement_time;
            $stay_time_info[$key]['last_scroll_time'] = $single_row->last_scroll_time;

            if($single_row->is_new == 1)
            {
                $date = date("Y-m-d",strtotime($single_row->date_time));
                $day_wise_visitor[$date]['date'] = $date;
                if(isset($all_unique_dates[$date]))
                {
                    $all_unique_dates[$date] = $all_unique_dates[$date] + 1;
                    $day_wise_visitor[$date]['number_of_user'] = $all_unique_dates[$date];
                }
                else
                {
                    $all_unique_dates[$date] = 1;
                    $day_wise_visitor[$date]['number_of_user'] = 1;
                }
            }
        }


        $bounce = 0;
        $no_bounce = 0;
        foreach($total_unique_session as $value){
            if($value['session_number'] > 1)
                $no_bounce++;
            if($value['session_number'] == 1){
                if($value['last_scroll_time']=="0000-00-00 00:00:00" && $value['last_engagement_time']=="0000-00-00 00:00:00")
                    $bounce++;
                else
                    $no_bounce++;
            }
        }
        $bounce_no_bounce = $bounce+$no_bounce;
        if($bounce_no_bounce == 0)
            $bounce_rate = 0;
        else
            $bounce_rate = number_format($bounce*100/$bounce_no_bounce, 2);

        $total_stay_time = 0;
        if(!empty($stay_time_info)) {
            foreach($stay_time_info as $value){
                $total_stay_time_individual = 0;
                if($value['last_scroll_time']=='0000-00-00 00:00:00' && $value['last_engagement_time']=='0000-00-00 00:00:00')
                    $total_stay_time = $total_stay_time + $total_stay_time_individual;
                else if ($value['last_scroll_time']=='0000-00-00 00:00:00' && $value['last_engagement_time']!='0000-00-00 00:00:00'){
                    $total_stay_time_individual = strtotime($value['last_engagement_time']) - strtotime($value['stay_from']);
                    $total_stay_time = $total_stay_time + $total_stay_time_individual;
                }
                else if ($value['last_scroll_time']!='0000-00-00 00:00:00' && $value['last_engagement_time']=='0000-00-00 00:00:00'){
                   $total_stay_time_individual = strtotime($value['last_scroll_time']) - strtotime($value['stay_from']);
                   $total_stay_time = $total_stay_time + $total_stay_time_individual;
                }
                else {
                    if($value['last_scroll_time']>$value['last_engagement_time']){
                       $total_stay_time_individual = strtotime($value['last_scroll_time']) - strtotime($value['stay_from']);
                       $total_stay_time = $total_stay_time + $total_stay_time_individual;
                    }
                    else{
                       $total_stay_time_individual = strtotime($value['last_engagement_time']) - strtotime($value['stay_from']);  
                       $total_stay_time = $total_stay_time + $total_stay_time_individual;
                    }
                }
            }
        }


        $average_stay_time = 0;
        if($total_stay_time != 0)
            $average_stay_time = $total_stay_time/count($total_unique_session);

        $hours = 0;
        $minutes = 0;
        $seconds = 0;

        $hours = floor($average_stay_time / 3600);
        $minutes = floor(($average_stay_time / 60) % 60);
        $seconds = $average_stay_time % 60;        

        // end of average stay time

        // code for line chart
        $day_count = date('Y-m-d', strtotime($from_date. " + 1 days"));

        foreach ($day_wise_visitor as $value){
            $day_wise_info[$value['date']] = $value['number_of_user'];
        }

        $dDiff = strtotime($to_date) - strtotime($from_date);
        $no_of_days = floor($dDiff/(60*60*24));
        $line_char_data = array();
        for($i=0;$i<=$no_of_days+1;$i++){
            $day_count = date('Y-m-d', strtotime($from_date. " + $i days"));
            if(isset($day_wise_info[$day_count])){
                $line_char_data[$i]['user'] = $day_wise_info[$day_count];
            } else {
                $line_char_data[$i]['user'] = 0;
            }
            $line_char_data[$i]['date'] = date('d M Y', strtotime($from_date. " + $i days"));
        }
        // end of code for line chart

        $info['line_chart_dates'] = array_column($line_char_data, 'date');
        $info['line_chart_values'] = array_column($line_char_data, 'user');

        $max1 = (!empty($info['line_chart_values'])) ? max($info['line_chart_values']) : 0;
        $steps = round($max1/7);
        if($steps==0) $steps = 1;
        $info['step_count'] = $steps;

        $info['total_page_view'] = number_format(count($total_page_view));
        $info['total_unique_visitor'] = number_format(count($total_unique_visitor));
        $info['total_unique_session'] = number_format(count($total_unique_session));
        if(count($total_unique_visitor) == 0)
            $info['average_visit'] = number_format(count($total_page_view));
        else
            $info['average_visit'] = number_format(count($total_page_view)/count($total_unique_visitor), 2);

        $info['average_stay_time'] = $hours.":".$minutes.":".$seconds;
        $info['bounce_rate'] = $bounce_rate." %";
        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));

        echo json_encode($info);
    }


    public function ajax_get_visitor_type_data(Request $request)
    {
        $domain_id = $request->input('domain_id');
        $date_range = $request->input('date_range');
        $from_and_to_date = [];
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";

        $where = array();
        $where['where'] = array('id' => $domain_id);
        // $domain_info = $this->basic->get_data('visitor_analysis_domain_list',$where,$select="");
        $domain_info = DB::table('visitor_analysis_domain_list')->where(['id' => $domain_id])->first();
        $table = "visitor_analysis_domain_list_data";
        $info['domain_name'] = $domain_info->domain_name;

        $select = ['is_new','date_time','cookie_value','session_value','visit_url','id'];
        // $all_data = $this->basic->get_data($table,$where,$select);
        $all_data = DB::table($table)->select($select)->where(["domain_list_id" => $domain_id])->get();
        $total_new_returning = [];
        $daily_total_new_returning = [];
        $content_overview_data = [];
        foreach ($all_data as $key => $single_row) {
            $cookie_and_session = $single_row->cookie_value."-".$single_row->session_value;
            if(isset($total_new_returning[$cookie_and_session]['new_vs_returning']))
                $total_new_returning[$cookie_and_session]['new_vs_returning'] = $total_new_returning[$cookie_and_session]['new_vs_returning'].",".$single_row->is_new;
            else
                $total_new_returning[$cookie_and_session]['new_vs_returning'] = $single_row->is_new;

            if(strtotime($single_row->date_time)>=strtotime($from_date) && strtotime($single_row->date_time)<=strtotime($to_date))
            {
                $formated_date = date("Y-m-d",strtotime($single_row->date_time));
                $session_cookie_date = $single_row->session_value."-".$single_row->cookie_value."-".$formated_date;
                $daily_total_new_returning[$session_cookie_date]['date'] = $formated_date;
                if(isset($daily_total_new_returning[$session_cookie_date]['new_vs_returning']))
                    $daily_total_new_returning[$session_cookie_date]['new_vs_returning'] = $daily_total_new_returning[$session_cookie_date]['new_vs_returning'].','.$single_row->is_new;
                else
                    $daily_total_new_returning[$session_cookie_date]['new_vs_returning'] = $single_row->is_new;

                if(isset($content_overview_data[$single_row->visit_url]))
                {
                    $content_overview_data[$single_row->visit_url]['visit_url'] = $single_row->visit_url;
                    $content_overview_data[$single_row->visit_url]['total_view'] = $content_overview_data[$single_row->visit_url]['total_view'] + 1;
                }
                else
                {
                    $content_overview_data[$single_row->visit_url]['visit_url'] = $single_row->visit_url;
                    $content_overview_data[$single_row->visit_url]['total_view'] = 1;
                }
            }
        }


        $new_or_returning = array();
        $new_user = 0;
        $returning_user = 0;
        foreach($total_new_returning as $value){
            $new_or_returning = explode(',', $value['new_vs_returning']);
            if(in_array(1, $new_or_returning)) $new_user++;
            else $returning_user++;
        }

        $info['total_new_returning_labels'] = array(__('New Users'),__('Returning Users'));
        $info['total_new_returning_values'] = array($new_user,$returning_user);

        
        $daily_report = array();
        $new_or_returning = array();
        $new_user = 0;
        $returning_user = 0;
        $i = 0;
        foreach($daily_total_new_returning as $value){
            $daily_report[$value['date']]['date'] = $value['date'];

            $new_or_returning = explode(',', $value['new_vs_returning']);                
            if(in_array(1, $new_or_returning)){
                if(isset($daily_report[$value['date']]['new_user'])){
                    $daily_report[$value['date']]['new_user']=$daily_report[$value['date']]['new_user']+1;
                }
                else{
                   $daily_report[$value['date']]['new_user'] = 1; 
                }
            } 
            else {
                if(isset($daily_report[$value['date']]['returning_user']))
                    $daily_report[$value['date']]['returning_user']=$daily_report[$value['date']]['returning_user']+1;
                else{
                   $daily_report[$value['date']]['returning_user'] = 1;
                }
            }
        }

        $dDiff = strtotime($to_date) - strtotime($from_date);
        $no_of_days = floor($dDiff/(60*60*24));
        $line_char_data = array();

        for($i=0;$i<=$no_of_days+1;$i++){
            $day_count = date('Y-m-d', strtotime($from_date. " + $i days"));
            if(isset($daily_report[$day_count])){
                if(isset($daily_report[$day_count]['new_user']))
                    $line_char_data[$i]['new_user'] = $daily_report[$day_count]['new_user'];
                else
                    $line_char_data[$i]['new_user'] = 0;

                if(isset($daily_report[$day_count]['returning_user']))
                    $line_char_data[$i]['returning_user'] = $daily_report[$day_count]['returning_user'];
                else
                    $line_char_data[$i]['returning_user'] = 0;

            } else {
                $line_char_data[$i]['new_user'] = 0;
                $line_char_data[$i]['returning_user'] = 0;                
            }
            $line_char_data[$i]['date'] = date('d M Y', strtotime($from_date. " + $i days"));
        }

        $info['new_vs_returning_dates'] = array_column($line_char_data, 'date');
        $info['new_vs_returning_new_user'] = array_column($line_char_data, 'new_user');
        $info['new_vs_returning_returning_user'] = array_column($line_char_data, 'returning_user');
        $max1 = (!empty($info['new_vs_returning_new_user'])) ? max($info['new_vs_returning_new_user']) : 0;
        $max2 = (!empty($info['new_vs_returning_returning_user'])) ? max($info['new_vs_returning_returning_user']) : 0;
        $steps = round(max(array($max1,$max2))/7);
        if($steps==0) $steps = 1;
        $info['new_vs_returning_step_count'] = $steps;


        $total_view = 0;
        foreach($content_overview_data as $value){
            $total_view = $total_view+$value['total_view'];
        }

        $top_url = '';
        $i = 0;
        foreach($content_overview_data as $value){
            $percentage = number_format($value['total_view']*100/$total_view, 2);
            $i++;
            $top_url .= $i.". ".$value['visit_url']." <span class='float-right'><b>".$percentage." %</b></span>";
            $top_url .= 
            '<div class="progress">                                         
              <div class="progress-bar progress-bar-striped " role="progressbar" aria-valuenow="'.$percentage.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$percentage.'%">
              </div>
            </div>';
            if($i==10) break;
        }

        $info['progress_bar_data'] = $top_url;
        

        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));

        echo json_encode($info);
    }


    public function ajax_get_country_wise_report_data(Request $request)
    {
        $domain_id = $request->input('domain_id');
        $date_range = $request->input('date_range');
        $from_and_to_date = [];
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";

        // $domain_info = $this->basic->get_data('visitor_analysis_domain_list',$where,$select="");
        $domain_info = DB::table('visitor_analysis_domain_list')->where(['id' => $domain_id])->get();
        $table = "visitor_analysis_domain_list_data";
        $info['domain_name'] = $domain_info[0]->domain_name;


        $select = ['country','is_new','session_value','date_time'];
        // $all_data = $this->basic->get_data($table,$where,$select);
        $all_data = DB::table($table)->select($select)->where(["domain_list_id" => $domain_id])->get();
        $country_name = [];
        $browser_report = [];
        foreach ($all_data as $key => $single_row)
        {
            $country_name[$single_row->country]['country'] = $single_row->country;
            if(isset($country_name[$single_row->country]['new_user']))  
                $country_name[$single_row->country]['new_user'] = $country_name[$single_row->country]['new_user'].','.$single_row->is_new;
            else
                $country_name[$single_row->country]['new_user'] = $single_row->is_new;

            if(strtotime($single_row->date_time)>=strtotime($from_date) && strtotime($single_row->date_time)<=strtotime($to_date))
            {
                $browser_report[$single_row->country]['country'] = $single_row->country;
                if(isset($browser_report[$single_row->country]['new_user']))  
                    $browser_report[$single_row->country]['new_user'] = $browser_report[$single_row->country]['new_user'].','.$single_row->is_new;
                else
                    $browser_report[$single_row->country]['new_user'] = $single_row->is_new;

                if(isset($browser_report[$single_row->country]['sessions']))  
                    $browser_report[$single_row->country]['sessions'] = $browser_report[$single_row->country]['sessions'].','.$single_row->session_value;
                else
                    $browser_report[$single_row->country]['sessions'] = $single_row->session_value;
            }  
        }

        ksort($browser_report);


        $i = 0;
        $country_report = array();
        $a = array('Country','New Visitor');
        $country_report[$i] = $a;
        foreach($country_name as $value){
            $new_users = array();
            $i++;
            $new_users = explode(',', $value['new_user']);
            $new_users = array_filter($new_users);
            $new_users = array_values($new_users);
            $new_users = count($new_users);
            $temp = array();
            $temp[] = $value['country'];
            $temp[] = $new_users;
            $country_report[$i] = $temp;
        }

        $info['country_graph_data'] = $country_report;

        $country_report_str = "<table class='table table-sm'>
                                    <thead>
                                        <tr>
                                            <th>".__('Country Name')."</th>
                                            <th>".__('Sessions')."</th>
                                            <th>".__('New Users')."</th>
                                            <th>".__('Action')."</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                ";
        $country_list = get_country_names();       
        $i = 0;
        foreach($browser_report as $value){
            $new_users = array();
            $sessions = array();
            $i++;
            $new_users = explode(',', $value['new_user']);
            $new_users = array_filter($new_users);
            $new_users = array_values($new_users);
            $new_users = count($new_users);

            $sessions = explode(',', $value['sessions']);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);


            // $s_country = array_search(trim($value["country"]), $country_list); 
            $s_country = in_array(trim($value["country"]), $country_list) ? array_search($value["country"],$country_list):""; 
            $image_link = asset("assets/img/flags/".$s_country.".png");

            $image = '<img style="height: 15px; width: 20px; margin-top: -3px;" src="'.$image_link.'" alt=" "> &nbsp;';
            if($value['country'] == '' || !isset($value['country'])){
                $image = '';
                $value['country'] = "Unknown";
            }

            $country_report_str .= "<tr><td>".$image.$value['country']."</td><td>".$sessions."</td><td>".$new_users."</td><td><button class='country_wise_name btn btn-outline-info btn-circle' title='".__('Session Details')."' data='".$value['country']."'><i class='fas fa-binoculars'></i></button></td></tr>";

        }
        $country_report_str .= "</tbody></table>";
        $info['country_wise_table_data'] = $country_report_str;

        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));

        echo json_encode($info);
    }


    public function ajax_get_browser_report_data(Request $request)
    {
        $domain_id = $request->input('domain_id');
        $date_range = $request->input('date_range');

        $from_and_to_date = array();
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";

        // $domain_info = $this->basic->get_data('visitor_analysis_domain_list',$where,$select="");
        $domain_info = DB::table('visitor_analysis_domain_list')->where(['id' => $domain_id])->get();
        $table = "visitor_analysis_domain_list_data";
        $info['domain_name'] = $domain_info[0]->domain_name;
        $where =[ 
            [ "date_time >=" => $from_date],
             ["date_time <=" => $to_date],
             ["domain_list_id" => $domain_id]
         
         ];

        // $select = ["GROUP_CONCAT(session_value SEPARATOR ',') as sessions","GROUP_CONCAT(is_new SEPARATOR ',') as new_user","browser_name"];
        $select = [
            DB::raw("GROUP_CONCAT(session_value SEPARATOR ',') as sessions"),
            DB::raw("GROUP_CONCAT(is_new SEPARATOR ',') as new_user"),
            'browser_name'
        ];
        // $browser_report = $this->basic->get_data($table,$where,$select,$join='',$limit='',$start=NULL,$order_by='',$group_by='browser_name');
        $browser_report = DB::table($table)->select($select)->where(["domain_list_id" => $domain_id])->orwhere("date_time" ,">=", $from_date)->orwhere("date_time", "<=", $to_date)->groupBy('browser_name')->get();

        $browser_report_str = "<table class='table table-sm'>
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>".__('Browser Name')."</th>
                                            <th>".__('Sessions')."</th>
                                            <th>".__('New Users')."</th>
                                            <th>".__('Action')."</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                ";        
        $i = 0;

        $browser_list = [
            'chrome' => asset('assets/img/browser/chrome.png'),
            'firefox' => asset('assets/img/browser/firefox.png'),
            'safari' => asset('assets/img/browser/safari.png'),
            'opera' => asset('assets/img/browser/opera.png'),
            'ie' => asset('assets/img/browser/ie.png'),
            'edge' => asset('assets/img/browser/edge.png'),
        ]; 

        foreach($browser_report as $value){
            $new_users = array();
            $sessions = array();
            $i++;
            $new_users = explode(',', $value->new_user);
            $new_users = array_filter($new_users);
            $new_users = array_values($new_users);
            $new_users = count($new_users);

            $sessions = explode(',', $value->sessions);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);

            $browser_name = strtolower($value->browser_name);
            $browser_img_path = isset($browser_list[$browser_name]) ? $browser_list[$browser_name] : asset("assets/img/browser/other.png");

            $image = '<img style="height: 15px; width: 20px; margin-top: -3px;" src="'.$browser_img_path.'" alt=" "> &nbsp;';

            $browser_report_str .= "<tr><td>".$i."</td><td>".$image.$value->browser_name."</td><td>".$sessions."</td><td>".$new_users."</td><td><button class='browser_name btn btn-outline-info btn-circle' title='".__('Session Details')."' data='".$value->browser_name."'><i class='fas fa-binoculars'></i></button></td></tr>";

        }
        $browser_report_str .= "</tbody></table>";

        $info['browser_report_name'] = $browser_report_str;
        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));


        echo json_encode($info);
    }


    public function ajax_get_device_report_data(Request $request)
    {
        $domain_id = $request->input('domain_id');
        $date_range = $request->input('date_range');

        $from_and_to_date = array();
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";


        // $domain_info = $this->basic->get_data('visitor_analysis_domain_list',$where,$select="");
        $domain_info = DB::table('visitor_analysis_domain_list')->where(['id' => $domain_id])->get();
        $table = "visitor_analysis_domain_list_data";
        $info['domain_name'] = $domain_info[0]->domain_name;


        $where =[ 
            [ "date_time >=" => $from_date],
             ["date_time <=" => $to_date],
             ["domain_list_id" => $domain_id]
         
         ];

        // $select = array("GROUP_CONCAT(session_value SEPARATOR ',') as sessions","GROUP_CONCAT(is_new SEPARATOR ',') as new_user","device");
        $select = [
            DB::raw("GROUP_CONCAT(session_value SEPARATOR ',') as sessions"),
            DB::raw("GROUP_CONCAT(is_new SEPARATOR ',') as new_user"),
            'device'
        ];
        // $select = ['country','is_new','session_value','date_time'];
        // $device_report = $this->basic->get_data($table,$where,$select,$join='',$limit='',$start=NULL,$order_by='',$group_by='device');
        $device_report = DB::table($table)->select($select)->where(["domain_list_id" => $domain_id])->orwhere("date_time" ,">=", $from_date)->orwhere("date_time", "<=", $to_date)->groupBy('device')->get();


        $device_report_str = "<table class='table table-sm'>
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>".__('Device Name')."</th>
                                        <th>".__('Sessions')."</th>
                                        <th>".__('New Users')."</th>
                                        <th>".__('Action')."</th>
                                    </tr>
                                </thead>
                                <tbody>
                                ";  
        $device_list = [
            'mobile' => asset('assets/img/os/iphone.png'),
            'desktop' => asset('assets/img/os/windows.png'),
        ]; 

        $i = 0;
        foreach($device_report as $value){
            $new_users = [];
            $sessions = [];
            $i++;
            $new_users = explode(',', $value->new_user);
            $new_users = array_filter($new_users);
            $new_users = array_values($new_users);
            $new_users = count($new_users);

            $sessions = explode(',', $value->sessions);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);

            $device_name = strtolower($value->device);
            $devise_img_path = isset($device_list[$device_name]) ? $device_list[$device_name] : asset("img/browser/other.png");
            $image = '<img style="height: 15px; width: 20px; margin-top: -3px;" src="'.$devise_img_path.'" alt=" "> &nbsp;';

            $device_report_str .= "<tr><td>".$i."</td><td>".$image.$value->device."</td><td>".$sessions."</td><td>".$new_users."</td><td><button class='device_name btn btn-outline-info btn-circle' title='".__('Session Details')."' data='".$value->device."'><i class='fas fa-binoculars'></i></button></td></tr>";

        }
        $device_report_str .= "</tbody></table>";

        $info['device_report_name'] = $device_report_str;
        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));

        echo json_encode($info);
    }

    public function display_in_dashboard(Request $request)
    {
        if(config('app.is_demo') == '1')
        {
            $response['status'] = 'exist';
            $response['message'] = __("This feature is disabled in this demo.");
            echo json_encode($response);
            exit;
        }


        $id = $request->input('table_id');
        $response = [];
        // $get_data = $this->basic->get_data('visitor_analysis_domain_list',array('where'=>array('user_id'=>Auth::user()->id,'id'=>$id)));
        $get_data = DB::table('visitor_analysis_domain_list')->where('id', $id)->where('user_id', Auth::user()->id)->get();
        // $get_data = json_decode(json_encode($get_data));
        if ($get_data[0]->dashboard == '1') {
        //    $this->basic->update_data("visitor_analysis_domain_list",array("user_id"=>Auth::user()->id,"id"=>$id),array("id"=>$id));
           DB::table('visitor_analysis_domain_list')->where(["user_id"=>Auth::user()->id])->where(["id"=>$id])->update(["dashboard"=>'0']);
           $response['status'] = 'remove';
           $response['message'] = __("This domain has successfully been removed from your dashboard.");
           echo json_encode($response);
           exit;
        }
        else {
            // $count_row=$this->basic->count_row("visitor_analysis_domain_list",array("where"=>array("user_id"=>Auth::user()->id,"dashboard"=>'1')));
            $count_row = DB::table('visitor_analysis_domain_list')->where('user_id', Auth::user()->id)->where('dashboard', 1)->count();
            $count=isset($count_row[0]->total_rows) ? $count_row[0]->total_rows : 0;

            if($count>=3) {   
                $response['status'] = 'exist';
                $response['message'] = __("You can not add more domain as you have already 3 domains on dashboard.");
                echo json_encode($response);
                exit;
            }
            else {   
                $response['status'] = 'not_exist';
                $response['message'] = __("This domain has successfully been added to your dashboard.");
                // $this->basic->update_data("visitor_analysis_domain_list",array("user_id"=>Auth::user()->id,"id"=>$id),array("dashboard"=>'1'));
                DB::table('visitor_analysis_domain_list')->where(["user_id"=>Auth::user()->id])->where(["id"=>$id])->update(['dashboard' => '1']);

                echo json_encode($response);
                exit;
            }

        }



    }

      function get_ip()
    {
        $ip[0]=$this->real_ip();
        echo $_GET['callback']."(".json_encode($ip).")";
    }

    public function server_info()
    {
        // header('Access-Control-Allow-Origin: *');
        $time=date("Y-m-d H:i:s");
       
        $ip=$this->real_ip();
        $website_code=$_POST['website_code'];
        $browser_name=$_POST['browser_name'];
        $browser_version=$_POST['browser_version'];
        $device=$_POST['device'];
        $mobile_desktop=$_POST['mobile_desktop'];
        $referrer=$_POST['referrer'];
        $current_url=$_POST['current_url'];
        $only_domain = get_domain_only($current_url);
        $cookie_value=$_POST['cookie_value'];
        $is_new=$_POST['is_new'];
        $session_value=$_POST['session_value'];
        $browser_rawdata=$_POST['browser_rawdata'];
        
        
        $domain_info = DB::table('visitor_analysis_domain_list')->where('domain_code',$website_code)->select('id','domain_name','user_id')->first();
        $domain_list_id = $domain_info->id;
        $domain_name = $domain_info->domain_name;
        $user_id = $domain_info->user_id;

        
        /**Get Country code and country name***/
        
        if($ip){
            
            /** Check ip is already in table or not, if in table then don't call for api ***/
            
            $where = array('ip'=>$ip,'domain_list_id'=>$domain_list_id);
            $select=array('country','city','org','latitude','longitude','postal','cookie_value','session_value');

            $existing_ip_info = DB::table('visitor_analysis_domain_list_data')
                            ->where('ip',$ip)->where('domain_list_id',$domain_list_id)
                            ->select($select)->limit(1)->offset(0)->get();
            
            if(isset($existing_ip_info[0]->country) && $existing_ip_info[0]->country !=''){
            
                $user_country=isset($existing_ip_info[0]->country) ? $existing_ip_info[0]->country: "";
                $user_city=isset($existing_ip_info[0]->city)? $existing_ip_info[0]->city: "";
                $user_org=isset($existing_ip_info[0]->org) ? $existing_ip_info[0]->org:"";
                $user_latitude=isset($existing_ip_info[0]->latitude) ? $existing_ip_info[0]->latitude :"";
                $user_longitude=isset($existing_ip_info[0]->longitude) ? $existing_ip_info[0]->longitude : "";
                $user_postal=isset($existing_ip_info[0]->postal) ? $existing_ip_info[0]->postal : "";
            }
            
            else{
                    $ip_info= $this->web_repport->ip_information($ip);
                    
                    $user_country=isset($ip_info['country']) ? $ip_info['country']: "";
                    $user_city=isset($ip_info['city'])? $ip_info['city']: "";
                    $user_org=isset($ip_info['org'])?$ip_info['org']:"";
                    $user_latitude=isset($ip_info['latitude'])?$ip_info['latitude']:"";
                    $user_longitude=isset($ip_info['longitude'])?$ip_info['longitude']:"";
                    $user_postal=isset($ip_info['postal'])?$ip_info['postal']:"";
            }
            
         }
         
        if(!isset($user_country))
            $user_country="";
        
        if(!isset($country_code))
            $country_code="";       
                
        // $browser_rawdata=result_encode($browser_rawdata);

        $where = array('cookie_value'=>$cookie_value,'domain_list_id'=>$domain_list_id);
        $select=array('cookie_value','session_value');
        // $existing_cookie_info= $this->basic->get_data('visitor_analysis_domain_list_data',$where,$select,'', $limit = '1', $start = '0');
        $existing_cookie_info = DB::table('visitor_analysis_domain_list_data')->where($where)->select($select)->limit(1)->offset(0)->get();

        if(isset($existing_cookie_info[0]->cookie_value)){
            $is_new = 0;
        }
        else
            $is_new = 1;

        if(strtolower($only_domain) == strtolower($domain_name)) {
            $insert_data = [
                'domain_list_id' => $domain_list_id,
				'user_id' => $user_id,
                'domain_code' => $website_code,
                'ip' => $ip,
                'country' => trim($user_country),
                'city' => trim($user_city),
                'org' => $user_org,
                'latitude' => $user_latitude,
                'longitude' => $user_longitude,
                'postal' => $user_postal,
                'os' => $device,
                'device' => trim($mobile_desktop),
                'browser_name' => trim($browser_name),
                'browser_version' => $browser_version,
                'date_time' => $time,
                'referrer' => $referrer,
                'visit_url' => $current_url,
                'cookie_value' => trim($cookie_value),
                'is_new' => $is_new,
                'session_value' => trim($session_value),
                'browser_rawdata' => $browser_rawdata
            ];
            DB::table('visitor_analysis_domain_list_data')->insert($insert_data);
        }
    }

    public function scroll_info()
    {
        // header('Access-Control-Allow-Origin: *');
        $time=date("Y-m-d H:i:s");     
        $ip=$this->real_ip();
        $website_code=$_POST['website_code'];
        $current_url=$_POST['current_url'];
        $only_domain = get_domain_only($current_url);
        $cookie_value=$_POST['cookie_value'];
        $session_value=$_POST['session_value'];

        $domain_info = DB::table('visitor_analysis_domain_list')->where('domain_code',$website_code)->select('id','domain_name')->first();
        $domain_list_id = $domain_info->id;
        $domain_name = $domain_info->domain_name;
        
        $q="Update `visitor_analysis_domain_list_data` set  last_scroll_time='$time' WHERE domain_list_id='$domain_list_id' and visit_url='$current_url' and cookie_value='$cookie_value' and session_value='$session_value' order by id desc limit 1";
        $update_data=[
            'last_scroll_time' =>$time,
        ];
        if(strtolower($only_domain) == strtolower($domain_name)){
            DB::table('visitor_analysis_domain_list_data')->where('domain_list_id',$domain_list_id)
            ->where('visit_url',$current_url)
            ->where('cookie_value',$cookie_value)
            ->where('session_value',$session_value)
            ->orderBy('id')
            ->limit(1)
            ->update($update_data);
        }
    }

    public function click_info()
    {
        // header('Access-Control-Allow-Origin: *');
        $time=date("Y-m-d H:i:s");
       
        $ip=$this->real_ip();
        $website_code=$_POST['website_code'];
        $current_url=$_POST['current_url'];
        $only_domain = get_domain_only($current_url);
        $cookie_value=$_POST['cookie_value'];
        $session_value=$_POST['session_value'];

        // $where['where'] = array('domain_code'=>$website_code);
        // $domain_info = $this->basic->get_data('visitor_analysis_domain_list',$where,$select=array('id','domain_name'));
        $domain_info = DB::table('visitor_analysis_domain_list')->where('domain_code',$website_code)->select('id','domain_name')->first();
        $domain_list_id = $domain_info->id;
        $domain_name = $domain_info->domain_name;
        
        $q="Update `visitor_analysis_domain_list_data` set  last_engagement_time='$time' WHERE domain_list_id='$domain_list_id' and visit_url='$current_url' and cookie_value='$cookie_value' and session_value='$session_value' order by id desc limit 1";
        $update_data=[
            'last_engagement_time' =>$time,
        ];
        if(strtolower($only_domain) == strtolower($domain_name)){
            DB::table('visitor_analysis_domain_list_data')->where('domain_list_id',$domain_list_id)
            ->where('visit_url',$current_url)
            ->where('cookie_value',$cookie_value)
            ->where('session_value',$session_value)
            ->orderBy('id')
            ->limit(1)
            ->update($update_data);
        }
    }

    public function live_check_info()
    {
        // header('Access-Control-Allow-Origin: *');
        $time=date("Y-m-d H:i:s");
       
        $ip=$this->real_ip();
        $website_code=$_POST['website_code'];
        $current_url=$_POST['current_url'];
        $only_domain = get_domain_only($current_url);
        $cookie_value=$_POST['cookie_value'];
        $session_value=$_POST['session_value'];

        // $where['where'] = array('domain_code'=>$website_code);
        // $domain_info = $this->basic->get_data('visitor_analysis_domain_list',$where,$select=array('id','domain_name'));
        $domain_info = DB::table('visitor_analysis_domain_list')->where('domain_code',$website_code)->select('id','domain_name')->first();
        $domain_list_id = $domain_info->id;
        $domain_name = $domain_info->domain_name;
        
        $q="Update `visitor_analysis_domain_list_data` set  is_live_time='$time' WHERE domain_list_id='$domain_list_id' and visit_url='$current_url' and cookie_value='$cookie_value' and session_value='$session_value' order by id desc limit 1";
        $update_data=[
            'is_live_time' =>$time,
        ];
        if(strtolower($only_domain) == strtolower($domain_name)){
            DB::table('visitor_analysis_domain_list_data')->where('domain_list_id',$domain_list_id)
                    ->where('visit_url',$current_url)
                    ->where('cookie_value',$cookie_value)
                    ->where('session_value',$session_value)
                    ->orderBy('id')
                    ->limit(1)
                    ->update($update_data);
        }
    }

    public function client()
    {
        $get_live_data = 0;
        $csrf_token = csrf_token();

        // header('Access-Control-Allow-Origin: *');
        // header('Content-Type: application/javascript');
        $content = "
            var ip_link='".route('js_controller_get_ip')."';
            var server_link='".route('js_controller_server_info')."';
            var scroll_server_link='".route('js_controller_scroll_info')."';
            var click_server_link='".route('js_controller_live_check_info')."';
            var live_check_link='".route('js_controller_live_check_info')."';
            var browser_js_link='".asset('assets/js/useragent.js')."';


            var hmsas_22_csrf_token = '".$csrf_token."';



            function document_height(){
                var body = document.body,
                html = document.documentElement;
                var height = Math.max( body.scrollHeight, body.offsetHeight, 
                                   html.clientHeight, html.scrollHeight, html.offsetHeight );
                return height;
            }

            function getScrollTop(){
                if(typeof pageYOffset!= 'undefined'){
                    //most browsers except IE before #9
                    return pageYOffset;
                }
                else{
                    var B= document.body; //IE 'quirks'
                    var D= document.documentElement; //IE with doctype
                    D= (D.clientHeight)? D: B;
                    return D.scrollTop;
                }
            }


            function ajax_dolphin(link,data){
                  xhr = new XMLHttpRequest();
                  xhr.open('POST',link);
                  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                  xhr.setRequestHeader('X-CSRF-TOKEN', hmsas_22_csrf_token);
                  xhr.send(data);
            }


            function get_browser_info(){
                        var ua=navigator.userAgent,tem,M=ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || []; 
                        if(/trident/i.test(M[1])){
                            tem=/\brv[ :]+(\d+)/g.exec(ua) || []; 
                            return {name:'IE',version:(tem[1]||'')};
                            }   
                        if(M[1]==='Chrome'){
                            tem=ua.match(/\bOPR\/(\d+)/)
                            if(tem!=null)   {return {name:'Opera', version:tem[1]};}
                            }   
                        M=M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
                        if((tem=ua.match(/version\/(\d+)/i))!=null) {M.splice(1,1,tem[1]);}
                        return {
                          name: M[0],
                          version: M[1]
                        };
             }
             
             /** Creating Cookie function **/
             function createCookie(name,value,days) {
                if (days) {
                    var date = new Date();
                    date.setTime(date.getTime()+(days*24*60*60*1000));
                    var expires = '; expires='+date.toGMTString();
                }
                else var expires = '';
                document.cookie = name+'='+value+expires+'; path=/';
            }

            /***Read Cookie function**/
            function readCookie(name) {
                var nameEQ = name + '=';
                var ca = document.cookie.split(';');
                for(var i=0;i < ca.length;i++) {
                    var c = ca[i];
                    while (c.charAt(0)==' ') c = c.substring(1,c.length);
                    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
                }
                return null;
            }

            /** Delete Cookie Function **/
            function eraseCookie(name) {
                createCookie(name,'',-1);
            }


            function time_difference(from_time,to_time){
                var differenceTravel = to_time.getTime() - from_time.getTime();
                var seconds = Math.floor((differenceTravel) / (1000));
                return seconds;
                
            }
             
            function ajax_call()
            {

                    
                /**Load browser plugin***/
                var y = document.createElement('script');
                y.src = browser_js_link;
                document.getElementsByTagName('head')[0].appendChild(y);

                /**after browser plugin loaded**/
                y.onload=function()
                {

                    var ip;
                    var device;
                    var mobile_desktop;
                    
                    device=jscd.os;
                    if(jscd.mobile){
                        mobile_desktop='Mobile';
                    }
                    else{
                        mobile_desktop='Desktop';
                    }
                    
                    var browser_info=get_browser_info();
                    var browser_name=browser_info.name;
                    var browser_version=browser_info.version;
                    
                    var browser_rawdata = JSON.stringify(navigator.userAgent);
                    // var website_code = document.getElementById('xvas-22-domain-name').getAttribute('xvas-22-data-name');
                    var website_code = document.querySelector('script#xvas-22-domain-name').getAttribute('xvas-22-data-name');
                    
                    /**Get referer Address**/
                    var referrer = document.referrer;
                    
                    /* Get Current url */
                    var current_url = window.location.href;
                        
                    /** Get cookie value , if it is already set or not */
                    var cookie_value=readCookie('xvas_22_dolphin');
                    var extra_value= new Date().getTime();
                    
                    /**if new visitor set the cookie value a random number***/
                    if(cookie_value=='' || cookie_value==null || cookie_value === undefined){
                        var is_new=1;
                        var random_cookie_value=Math.floor(Math.random()*999999);
                        random_cookie_value=random_cookie_value+extra_value.toString();
                        createCookie('xvas_22_dolphin',random_cookie_value,1);
                        cookie_value=random_cookie_value;
                    }
                    else{
                        createCookie('xvas_22_dolphin',cookie_value,1);
                        var is_new=0;
                    }
                    
                    
                    var session_value=sessionStorage.xvas_22_dolphin_session;
                    
                    if(session_value=='' || session_value==null || session_value === undefined){
                        var random_session_value=Math.floor(Math.random()*999999);
                        random_session_value=random_session_value+extra_value.toString();
                        sessionStorage.xvas_22_dolphin_session=random_session_value;
                        session_value=random_session_value;
                    }
                        
                    /**if it is a new session then create session***/
                    var data='website_code='+website_code+'&browser_name='+browser_name+'&browser_version='+browser_version+'&device='+device+'&mobile_desktop='+mobile_desktop+'&referrer='+referrer+'&current_url='+current_url+'&cookie_value='+cookie_value+'&is_new='+is_new+'&session_value='+session_value+'&browser_rawdata='+browser_rawdata;

                    ajax_dolphin(server_link,data);
                                            
                    
                    /* Scrolling detection, if it is scrolling more than 50%  and after 5 seceond of last scroll then enter the time ***/
                    var last_scroll_time;
                    var scroll_track=0;
                    var time_dif=0;
                    
                    window.onscroll =   function(){
                        
                        var  wintop = getScrollTop();
                        var  docheight = document_height();
                        var  winheight = window.innerHeight;
                         
                        var  scrolltrigger = 0.50;
                         
                        if ((wintop/(docheight-winheight)) > scrolltrigger) {
                         
                            scroll_track++;
                            var to_time=new Date();
                            
                            if(scroll_track>1){
                                time_dif=time_difference(last_scroll_time,to_time);
                            }
                            
                            if(scroll_track==1 || time_dif>5){
                                last_scroll_time=new Date();
                                
                                var data='website_code='+website_code+'&current_url='+current_url+'&cookie_value='+cookie_value+'&session_value='+session_value;
                                ajax_dolphin(scroll_server_link,data);
                                
                            }
                        }
                    };      
                    
                    
                    
                    /*** track each engagement record. Enagagment is calculated by click function****/
                    var last_click_time;
                    var click_track=0;
                    var click_time_dif=0;
                    
                    document.onclick = function(){
                            click_track++;
                            var to_time=new Date();
                            
                            if(click_track>1){
                                click_time_dif=time_difference(last_click_time,to_time);
                            }
                            
                            if(click_track==1 || click_time_dif>5){
                                last_click_time=new Date();
                                var data='website_code='+website_code+'&current_url='+current_url+'&cookie_value='+cookie_value+'&session_value='+session_value;
                                ajax_dolphin(click_server_link,data);
                                
                            }   
                    };";
        if($get_live_data == 1)

            $content .= "setInterval(function(){
                            var data='website_code='+website_code+'&current_url='+current_url+'&cookie_value='+cookie_value+'&session_value='+session_value;
                            ajax_dolphin(live_check_link,data);
                            },5000);";

                    
        $content .= "       
                }
            }

            function init(){
                ajax_call();
            }

            init();
        ";

        echo $content;
    }

    public function useragent(){
        // header('Access-Control-Allow-Origin: *');
        // header('Content-Type: application/javascript');
        $script = "
                
            /**
             * JavaScript Client Detection
             * (C) viazenetti GmbH (Christian Ludwig)
             */
            
            (function (window) {
                {
                    var unknown = '-';

                    // screen
                    var screenSize = '';
                    if (screen.width) {
                        width = (screen.width) ? screen.width : '';
                        height = (screen.height) ? screen.height : '';
                        screenSize += '' + width + ' x ' + height;
                    }

                    // browser
                    var nVer = navigator.appVersion;
                    var nAgt = navigator.userAgent;
                    var browser = navigator.appName;
                    var version = '' + parseFloat(navigator.appVersion);
                    var majorVersion = parseInt(navigator.appVersion, 10);
                    var nameOffset, verOffset, ix;

                    // Opera
                    if ((verOffset = nAgt.indexOf('Opera')) != -1) {
                        browser = 'Opera';
                        version = nAgt.substring(verOffset + 6);
                        if ((verOffset = nAgt.indexOf('Version')) != -1) {
                            version = nAgt.substring(verOffset + 8);
                        }
                    }
                    // Opera Next
                    if ((verOffset = nAgt.indexOf('OPR')) != -1) {
                        browser = 'Opera';
                        version = nAgt.substring(verOffset + 4);
                    }
                    // MSIE
                    else if ((verOffset = nAgt.indexOf('MSIE')) != -1) {
                        browser = 'Microsoft Internet Explorer';
                        version = nAgt.substring(verOffset + 5);
                    }
                    // Chrome
                    else if ((verOffset = nAgt.indexOf('Chrome')) != -1) {
                        browser = 'Chrome';
                        version = nAgt.substring(verOffset + 7);
                    }
                    // Safari
                    else if ((verOffset = nAgt.indexOf('Safari')) != -1) {
                        browser = 'Safari';
                        version = nAgt.substring(verOffset + 7);
                        if ((verOffset = nAgt.indexOf('Version')) != -1) {
                            version = nAgt.substring(verOffset + 8);
                        }
                    }
                    // Firefox
                    else if ((verOffset = nAgt.indexOf('Firefox')) != -1) {
                        browser = 'Firefox';
                        version = nAgt.substring(verOffset + 8);
                    }
                    // MSIE 11+
                    else if (nAgt.indexOf('Trident/') != -1) {
                        browser = 'Microsoft Internet Explorer';
                        version = nAgt.substring(nAgt.indexOf('rv:') + 3);
                    }
                    // Other browsers
                    else if ((nameOffset = nAgt.lastIndexOf(' ') + 1) < (verOffset = nAgt.lastIndexOf('/'))) {
                        browser = nAgt.substring(nameOffset, verOffset);
                        version = nAgt.substring(verOffset + 1);
                        if (browser.toLowerCase() == browser.toUpperCase()) {
                            browser = navigator.appName;
                        }
                    }
                    // trim the version string
                    if ((ix = version.indexOf(';')) != -1) version = version.substring(0, ix);
                    if ((ix = version.indexOf(' ')) != -1) version = version.substring(0, ix);
                    if ((ix = version.indexOf(')')) != -1) version = version.substring(0, ix);

                    majorVersion = parseInt('' + version, 10);
                    if (isNaN(majorVersion)) {
                        version = '' + parseFloat(navigator.appVersion);
                        majorVersion = parseInt(navigator.appVersion, 10);
                    }

                    // mobile version
                    var mobile = /Mobile|mini|Fennec|BlackBerry|Android|iP(ad|od|hone)/.test(nVer);

                    // cookie
                    var cookieEnabled = (navigator.cookieEnabled) ? true : false;

                    if (typeof navigator.cookieEnabled == 'undefined' && !cookieEnabled) {
                        document.cookie = 'testcookie';
                        cookieEnabled = (document.cookie.indexOf('testcookie') != -1) ? true : false;
                    }

                    // system
                    var os = unknown;
                    var clientStrings = [
                        {s:'Windows 10', r:/(Windows 10.0|Windows NT 10.0)/},
                        {s:'Windows 8.1', r:/(Windows 8.1|Windows NT 6.3)/},
                        {s:'Windows 8', r:/(Windows 8|Windows NT 6.2)/},
                        {s:'Windows 7', r:/(Windows 7|Windows NT 6.1)/},
                        {s:'Windows Vista', r:/Windows NT 6.0/},
                        {s:'Windows Server 2003', r:/Windows NT 5.2/},
                        {s:'Windows XP', r:/(Windows NT 5.1|Windows XP)/},
                        {s:'Windows 2000', r:/(Windows NT 5.0|Windows 2000)/},
                        {s:'Windows ME', r:/(Win 9x 4.90|Windows ME)/},
                        {s:'Windows 98', r:/(Windows 98|Win98)/},
                        {s:'Windows 95', r:/(Windows 95|Win95|Windows_95)/},
                        {s:'Windows NT 4.0', r:/(Windows NT 4.0|WinNT4.0|WinNT|Windows NT)/},
                        {s:'Windows CE', r:/Windows CE/},
                        {s:'Windows 3.11', r:/Win16/},
                        {s:'Android', r:/Android/},
                        {s:'Open BSD', r:/OpenBSD/},
                        {s:'Sun OS', r:/SunOS/},
                        {s:'Linux', r:/(Linux|X11)/},
                        {s:'iPhone', r:/(iPhone)/},
                        {s:'iPad', r:/(iPad)/},
                        {s:'iPod', r:/(iPod)/},
                        {s:'Mac OS X', r:/Mac OS X/},
                        {s:'Mac OS', r:/(MacPPC|MacIntel|Mac_PowerPC|Macintosh)/},
                        {s:'QNX', r:/QNX/},
                        {s:'UNIX', r:/UNIX/},
                        {s:'BeOS', r:/BeOS/},
                        {s:'OS/2', r:/OS\/2/},
                        {s:'Search Bot', r:/(nuhk|Googlebot|Yammybot|Openbot|Slurp|MSNBot|Ask Jeeves\/Teoma|ia_archiver)/}
                    ];
                    for (var id in clientStrings) {
                        var cs = clientStrings[id];
                        if (cs.r.test(nAgt)) {
                            os = cs.s;
                            break;
                        }
                    }

                    var osVersion = unknown;

                    if (/Windows/.test(os)) {
                        osVersion = /Windows (.*)/.exec(os)[1];
                        os = 'Windows';
                    }

                    switch (os) {
                        case 'Mac OS X':
                            osVersion = /Mac OS X (10[\.\_\d]+)/.exec(nAgt)[1];
                            break;

                        case 'Android':
                            osVersion = /Android ([\.\_\d]+)/.exec(nAgt)[1];
                            break;

                        case 'iOS':
                            osVersion = /OS (\d+)_(\d+)_?(\d+)?/.exec(nVer);
                            osVersion = osVersion[1] + '.' + osVersion[2] + '.' + (osVersion[3] | 0);
                            break;
                    }

                    // flash (you'll need to include swfobject)
                    / script src='//ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js' /
                    var flashVersion = 'no check';
                    if (typeof swfobject != 'undefined') {
                        var fv = swfobject.getFlashPlayerVersion();
                        if (fv.major > 0) {
                            flashVersion = fv.major + '.' + fv.minor + ' r' + fv.release;
                        }
                        else  {
                            flashVersion = unknown;
                        }
                    }
                }

                window.jscd = {
                    screen: screenSize,
                    browser: browser,
                    browserVersion: version,
                    browserMajorVersion: majorVersion,
                    mobile: mobile,
                    os: os,
                    osVersion: osVersion,
                    cookies: cookieEnabled,
                    flashVersion: flashVersion
                };
            }(this));


                ";

        echo $script;
        

    }

}
