<?php

namespace App\Http\Controllers\SEO_tools;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

class WidgetsController extends HomeController
{  

    public function __construct()
    {
        $this->set_global_userdata(true,[],[],1);
    }


    public function index(Request $request)
    {
        $user_id = Auth::user()->id;

        $domain_list_id = $request->input('domain_list_id');

        $domain_name = DB::table('visitor_analysis_domain_list')->where('user_id',$user_id)->select('id','domain_name','domain_code')->get();
        $domain_name = json_decode(json_encode($domain_name));
        $data['domain_name_array'] = $domain_name;

        $data['body'] = "seo-tools.widgets.index";
        $data['user_id'] = Auth::user()->id;
        if($domain_list_id !='' && DB::table('visitor_analysis_domain_list')->where('id', $domain_list_id)->where('user_id', $user_id)->exists()) {
           return redirect("native_widgets/get_widget/".$domain_list_id);
        }
        else {
        return $this->_viewcontroller($data);
        }
    }


    public function public_content_overview_data($domain_code='')
    {        
        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));
        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";
        $data = [];
        $data['from_date'] = date("d-M-y",strtotime($from_date));
        $data['to_date'] = date("d-M-y",strtotime($to_date));
        $info = DB::table('visitor_analysis_domain_list')->where('domain_code',$domain_code)->select('id')->get();
        $info = json_decode(json_encode($info));
        if(!empty($info))
        {
            $domain_list_id = $info[0]->id;

            $where = [
                ['date_time','>=', $from_date],
                ['date_time','<=', $to_date],
                ['domain_list_id','=', $domain_list_id],
            ];
            
            $select = [
                DB::raw('count(id) as total_view'),
                'visit_url',
            ];
            
            $content_overview_data = DB::table('visitor_analysis_domain_list_data')
                ->where($where)
                ->select($select)
                ->groupBy('visit_url')
                ->orderByDesc('total_view')
                ->get();
            $content_overview_data =json_decode(json_encode($content_overview_data)) ; 
            $total_view = 0;
            foreach($content_overview_data as $value){
                $total_view = $total_view+$value->total_view;
            }

            $data['total_view'] = $total_view;
            $data['content_overview_data'] = $content_overview_data;
            $data['data_found'] = 'yes';
        }
        else
            $data['data_found'] = 'no';
        
        return view("seo-tools.widgets.widget-for-content-overview", $data);

    }

    public function public_traffic_source_data($domain_code='')
    {
        $info = DB::table('visitor_analysis_domain_list')->where('domain_code',$domain_code)->select('id')->get();
        $info = json_decode(json_encode($info));

        if(!empty($info))
        {
            $domain_list_id = $info[0]->id;
            $to_date = date("Y-m-d");
            $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

            $to_date = $to_date." 23:59:59";
            $from_date = $from_date." 00:00:00";


            $where = [
                ['date_time','>=', $from_date],
                ['date_time','<=', $to_date],
                ['domain_list_id','=', $domain_list_id],
            ];
            $table = "visitor_analysis_domain_list_data";
            $total_page_view = DB::table($table)->where($where)->get();
            $total_unique_visitor = DB::table($table)->where($where)->get();
            $select = [
                DB::raw('count(id) as session_number'),
                'last_scroll_time',
                'last_engagement_time',
            ];
            $total_unique_session = DB::table($table)
                 ->select($select)
                 ->where($where)
                 ->groupBy('session_value', 'last_scroll_time', 'last_engagement_time')
                 ->get();
            $total_unique_session = json_decode(json_encode($total_unique_session));
            
            $bounce = 0;
            $no_bounce = 0;
            foreach($total_unique_session as $value){
                if($value->session_number > 1)
                    $no_bounce++;
                if($value->session_number == 1){
                    if($value->last_scroll_time=="0000-00-00 00:00:00" && $value->last_engagement_time=="0000-00-00 00:00:00")
                        $bounce++;
                    else
                        $no_bounce++;
                }
            }
            $bounce_no_bounce = $bounce+$no_bounce;
    		if($bounce_no_bounce == 0) $bounce_rate = 0;
    		else 
            	$bounce_rate = number_format($bounce*100/$bounce_no_bounce, 2);

            // code for average stay time
            //"if(status='1',count(book_info.id),0) as available_book"
            $select = array(
                "date_time as stay_from",
                "last_engagement_time",
                "last_scroll_time"
                );
            $stay_time_info = DB::table($table)->where($where)->select($select)->get();
            $stay_time_info = json_decode(json_encode($stay_time_info));
            
            $total_stay_time = 0;
            if(!empty($stay_time_info)) {
                foreach($stay_time_info as $value){
                    $total_stay_time_individual = 0;
                    if($value->last_scroll_time=='0000-00-00 00:00:00' && $value->last_engagement_time=='0000-00-00 00:00:00')
                        $total_stay_time = $total_stay_time + $total_stay_time_individual;
                    else if ($value->last_scroll_time=='0000-00-00 00:00:00' && $value->last_engagement_time!='0000-00-00 00:00:00'){
                        $total_stay_time_individual = strtotime($value->last_engagement_time) - strtotime($value->stay_from);
                        $total_stay_time = $total_stay_time + $total_stay_time_individual;
                    }
                    else if ($value->last_scroll_time!='0000-00-00 00:00:00' && $value->last_engagement_time=='0000-00-00 00:00:00'){
                       $total_stay_time_individual = strtotime($value->last_scroll_time) - strtotime($value->stay_from);
                       $total_stay_time = $total_stay_time + $total_stay_time_individual;
                    }
                    else {
                        if($value->last_scroll_time>$value->last_engagement_time){
                           $total_stay_time_individual = strtotime($value->last_scroll_time) - strtotime($value->stay_from);
                           $total_stay_time = $total_stay_time + $total_stay_time_individual;
                        }
                        else{
                           $total_stay_time_individual = strtotime($value->last_engagement_time) - strtotime($value->stay_from);  
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

            $data['total_page_view'] = number_format(count($total_page_view));
            $data['total_unique_visitro'] = number_format(count($total_unique_visitor));
            if(count($total_unique_visitor) == 0)
                $data['average_visit'] = number_format(count($total_page_view));
            else
                $data['average_visit'] = number_format(count($total_page_view)/count($total_unique_visitor), 2);

            $data['average_stay_time'] = $hours.":".$minutes.":".$seconds;
            $data['bounce_rate'] = $bounce_rate;
            $data['data_found'] = 'yes';
        }
        else
            $data['data_found'] = 'no';

        return view("seo-tools.widgets.widget-for-overview", $data);

    }

	
	public function public_country_report_data($domain_code='')
	{
        $info = DB::table('visitor_analysis_domain_list')->where('domain_code',$domain_code)->select('id')->get();
        $info = json_decode(json_encode($info));
       
        if(!empty($info))
        {
            $domain_list_id = $info[0]->id;
            $to_date = date("Y-m-d");
            $from_date = date("Y-m-d",strtotime("$to_date-30 days"));        

            $to_date = $to_date." 23:59:59";
            $from_date = $from_date." 00:00:00";
            
            $where = [
                ['date_time','>=', $from_date],
                ['date_time','<=', $to_date],
                ['domain_list_id','=', $domain_list_id],
            ];

            $table = "visitor_analysis_domain_list_data";
            $select = ['country', DB::raw("GROUP_CONCAT(is_new SEPARATOR ',') as new_user")];
            $country_name = DB::table($table)->where($where)->select($select)->groupBy('country')->get();
            $country_name = json_decode(json_encode($country_name));
       
            $i = 0;
            $country_report = array();
            $a = array('Country','New Visitor');
            $country_report[$i] = $a;
            foreach($country_name as $value){
                $new_users = array();
                $i++;
                $new_users = explode(',', $value->new_user);
                $new_users = array_filter($new_users);
                $new_users = array_values($new_users);
                $new_users = count($new_users);
                $temp = array();
                $temp[] = $value->country;
                $temp[] = $new_users;
                $country_report[$i] = $temp;
            }
            $data['country_graph_data'] = json_encode($country_report);
            $data['data_found'] = 'yes';
        }
        else
            $data['data_found'] = 'no';

        return view("seo-tools.widgets.widget-for-country-report", $data);

	}


}

