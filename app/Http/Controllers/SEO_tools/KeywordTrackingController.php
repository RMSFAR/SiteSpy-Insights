<?php

namespace App\Http\Controllers\SEO_tools;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

class KeywordTrackingController extends HomeController
{

    public $download_id; 

    public function __construct()
    {
        $this->set_global_userdata(true,[],[],16);       
    }


    public function index()
    {

        $data['body'] = "seo-tools.keyword-tracking.index";
        return $this->_viewcontroller($data);
    }

    public function keyword_tracking_index()
    {
        
        $number_of_keyword = DB::table('keyword_position_set')
        ->where('user_id', Auth::user()->id)
        ->get();


        $data['number_of_keyword'] = count($number_of_keyword);
        $data['body'] = "seo-tools.keyword-tracking.keyword-tracking-list";
        return $this->_viewcontroller($data);
    }

    public function keyword_list_data(Request $request)
    {
        
        $searching = trim($request->input("searching"));
        $post_date_range = $request->input("post_date_range");
        $display_columns = ["#", "CHECKBOX", "id", "keyword", "website", "country", "language", "add_date", "actions"];
        $search_columns = ["keyword", "add_date"];

        $page = $request->input('page', 1);
        $start = $request->input('start', 0);
        $limit = $request->input('length', 10);
        $sort_index = $request->input('order.0.column', 2);
        $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'id';
        $order = $request->input('order.0.dir', 'desc');
        $order_by = "$sort $order";

        $where_simple = [];

        if ($post_date_range !== "") {
            // [$from_date, $to_date] = explode('|', $post_date_range);
            $exp = explode('|', $post_date_range);
            $from_date = isset($exp[0])?$exp[0]:"";
            $to_date   = isset($exp[1])?$exp[1]:"";

            if ($from_date !== "Invalid date" && $to_date !== "Invalid date") {
                $from_date = date('Y-m-d', strtotime($from_date));
                $to_date = date('Y-m-d', strtotime($to_date));
                // $where_simple[] = ['add_date', '>=', $from_date];
                // $where_simple[] = ['add_date', '<=', $to_date];
                $where_simple["Date_Format(add_date,'%Y-%m-%d') >="] = $from_date;
                $where_simple["Date_Format(add_date,'%Y-%m-%d') <="] = $to_date;
            }
        }

        if ($searching !== "") {
            // $where_simple[] = ['keyword', 'like', "%$searching%"];
            $where_simple['keyword like'] = "%".$searching."%";

        }

        // $where_simple[] = ['user_id', '=', Auth::user()->id];
        $where_simple['user_id'] = Auth::user()->id;
        $where  = array('where'=>$where_simple);
       
        
        $table = "keyword_position_set";
        // $info = DB::table($table)->where($where)->get();
        $info = DB::table($table)->where('user_id', Auth::user()->id)->get();
        // dd($info);
       
        
        for ($i = 0; $i < count($info); $i++) {
            $info[$i]->add_date = date("M j, y h:i A", strtotime($info[$i]->add_date));
            $info[$i]->actions = "<a href='#' title='" . __("Delete Keyword") . "' class='btn btn-circle btn-outline-danger delete_keyword' table_id=" . $info[$i]->id . "><i class='fa fa-trash-alt'></i></a>";
        }
        
        // $total_result = DB::table($table)->where($where_simple)->count();
        $total_result = DB::table($table)->where('user_id', Auth::user()->id)->count();
        
        $data = [
            'draw' => (int)$request->input('draw') + 1,
            'recordsTotal' => $total_result,
            'recordsFiltered' => $total_result,
            'data' => convertDataTableResult($info, $display_columns, $start),
        ];
        
        
        return response()->json($data);
    }

    public function keyword_tracking_settings_action(Request $request)
    {
        $responses = [];
        $status = $this->_check_usage($module_id=16, $req=1);
        if ($status == '2') {
            $responses['status'] = '2';
            $responses['msg'] = __('Sorry, your bulk limit is exceeded for this module.');

            return response()->json($responses);
        } else if ($status == '3') {
            $responses['status'] = '3';
            $responses['msg'] = __('Sorry, your monthly limit is exceeded for this module.');

            return response()->json($responses);
        }

        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }

        $post = $request->input();
        foreach ($post as $key => $value) 
        {
            $$key = trim(strip_tags($value));
        }

        $data = [
            'keyword' => $keyword,
            'website' => $website,
            'language' => $language,
            'country' => $country,
            'user_id' => Auth::user()->id,
            'add_date' => date('Y-m-d H:i:s'),
            'deleted' => 0,
        ];
        if (!isset($data['last_scan_date'])){
            $data['last_scan_date']=date('Y-m-d H:i:s');
        }

        if (DB::table('keyword_position_set')->insert($data)) {
            $this->_insert_usage_log($module_id=16, $req=1);

            $responses['status'] = 1;
            $responses['msg'] = __('Keyword has been successfully added.');
            echo json_encode($responses);

        } else {
            $responses['status'] = 0;
            $responses['msg'] = __('Something went wrong, please try once again.');
            echo json_encode($responses); 
        }
    }
    public function delete_keyword_action(Request $request)
    {
        
        $table_id = $request->input("table_id");

        if(DB::table('keyword_position_set')->where(['id' => $table_id, 'user_id' => Auth::user()->id])->delete()) {
            DB::table('keyword_position_report')->where(["keyword_id"=>$table_id])->delete();
            echo "1";
        } else {
            echo "0";
        }
    }

    public function delete_selected_keyword_action(Request $request)
    {

        $selected_keyword_data = $request->input('info');

        if(!is_array($selected_keyword_data)) {
            $selected_keyword_data = array();
        }
    
        $implode_ids = implode(",",$selected_keyword_data);
        
    
        if(!empty($selected_keyword_data)) {
            $final_sql = "DELETE FROM keyword_position_set WHERE user_id={Auth::user()->id} AND id IN({$implode_ids})";
    
            foreach ($selected_keyword_data as $value) {
                DB::table('keyword_position_report')->where('keyword_id', $value)->delete();
            }
               
            $affected_rows = DB::delete($final_sql);

            if($affected_rows > 0) {
                echo "1";
            } else {
                echo "0";
            }
        }
    }

    public function keyword_position_report()
    {
        

        $keywords = DB::table('keyword_position_set')
                ->where('user_id', Auth::user()->id)
                ->get();
        
        $keywords_array = [];
        foreach($keywords as $value){
            
            $keywords_array[$value->id] = $value->keyword." | ".$value->website;
        }

        $data['keywords'] = $keywords_array;
        $data['body'] = "seo-tools.keyword-tracking.keyword-position-report";
        return $this->_viewcontroller($data);
    }

    public function keyword_position_report_data(Request $request)
    {


        $keyword = $request->input('keyword');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        
        $where = [
            ['keyword_id', '=', $keyword],
            ['date', '>=', date("Y-m-d",strtotime($from_date))],
            ['date', '<=', date("Y-m-d",strtotime($to_date))]
        ];


        $keyword_position = DB::table('keyword_position_report')
            ->leftJoin('keyword_position_set', 'keyword_position_report.keyword_id', '=', 'keyword_position_set.id')
            ->where($where)
            ->get();

        $str = '<div class="table-responsive">
                    <table class="table table-hover table-bordered text-left">
                        <thead>
                            <tr>
                                <th>'.__('Keyword').'</th>
                                <th>'.__('Website').'</th>
                                <th>'.__('Google Position').'</th>
                                <th>'.__('Bing Position').'</th>
                                <th>'.__('Yahoo Position').'</th>
                                <th>'.__('Date').'</th>
                            </tr>
                        </thead><tbody>';
        if(count($keyword_position) > 0) {
            foreach($keyword_position as $value){
                $str .= '<tr>
                            <td>'.$value->keyword.'</td>
                            <td>'.$value->website.'</td>
                            <td>'.$value->google_position.'</td>
                            <td>'.$value->bing_position.'</td>
                            <td>'.$value->yahoo_position.'</td>
                            <td>'.date("M j, Y", strtotime($value->date)).'</td>
                        </tr>';
            }
        } else {
            $str .= '<tr><td class="text-center" colspan="6">'.__('No Data found').'</td></tr>';
        }

        $str .= '</tbody></table></div>';

        echo $str;
    }
}
