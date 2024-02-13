<?php

use App\Http\Controllers\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//SEO- Tools
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Landing;
use App\Http\Controllers\Member;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\System\SettingsController;
use App\Http\Controllers\SEO_tools\VisitorController;
use App\Http\Controllers\SEO_tools\WebsiteController;
use App\Http\Controllers\SEO_tools\WidgetsController;
use App\Http\Controllers\System\SocialAppsController;
use App\Http\Controllers\System\CheckUpdateController;
use App\Http\Controllers\SEO_tools\RankIndexController;
use App\Http\Controllers\SEO_tools\UtilitiesController;
use App\Http\Controllers\System\AddOnManagerController;

//System
use App\Http\Controllers\System\ThemeManagerController;
use App\Http\Controllers\SEO_tools\IpAnalysisController;
use App\Http\Controllers\SEO_tools\UrlShortnerController;
use App\Http\Controllers\SEO_tools\CodeMinifierController;
use App\Http\Controllers\SEO_tools\LinkAnalysisController;
use App\Http\Controllers\SEO_tools\AnalysisToolsController;

use App\Http\Controllers\SEO_tools\SecurityToolsController;
use App\Http\Controllers\SEO_tools\DomainAnalysisController;
use App\Http\Controllers\SEO_tools\KeywordAnalysisController;
use App\Http\Controllers\SEO_tools\KeywordTrackingController;
use App\Http\Controllers\SubscriptionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

if(!file_exists(public_path("install.txt"))) {
    Route::get('/', [Landing::class,'index'])->name('home');
	Route::get('/dashboard', [DashboardController::class,'index'])->middleware(['auth'])->name('dashboard');
}
else {
	Route::get('/', [Landing::class,'install'])->name('home');
	Route::get('/dashboard', [Landing::class,'install'])->middleware(['auth'])->name('dashboard');
}


// Route::get('/', [Landing::class , 'index'])->name('home');
// Route::get('/dashboard', [DashboardController::class , 'index'])->middleware('auth')->name('dashboard');
Route::post('/dashboard/visitor_domain/switch',[DashboardController::class,'visior_domain_session'])->middleware(['auth'])->name('visior-domain-session');

Route::post('home/language_changer', [HomeController::class, 'language_changer'])->middleware('auth')->name('language_changer');


Route::post('read_text_file', [HomeController::class, 'read_text_file'])->middleware('auth')->name('read_text_file');
Route::post('read_after_delete', [HomeController::class, 'read_after_delete'])->middleware('auth')->name('read_after_delete');

Route::get('credential/check', [HomeController::class,'credential_check'])->middleware(['auth'])->name('credential-check');
Route::post('credential/check', [HomeController::class,'credential_check_action'])->middleware(['auth'])->name('credential-check-action');

// ...................................................................................................
// ................................................Payments routes....................................
// ....................................................................................................
Route::get('payment/package_manager', [PaymentController::class, 'package_manager'])->middleware('auth')->name('package_manager');
Route::post('payment/package_manager_data', [PaymentController::class, 'package_manager_data'])->middleware('auth')->name('package_manager_data');
Route::get('payment/add_package', [PaymentController::class, 'add_package'])->middleware('auth')->name('add_package');
Route::get('payment/accounts', [PaymentController::class, 'payment_settings'])->middleware('auth')->name('accounts');
Route::post('payment/accounts_action', [PaymentController::class, 'payment_settings_action'])->middleware('auth')->name('payment-settings-action');
Route::post('payment/edit_package_action', [PaymentController::class, 'edit_package_action'])->middleware('auth')->name('edit_package_action');
Route::post('/payment/delete_package/{id}', [PaymentController::class, 'delete_package'])->middleware('auth')->name('delete_package');
Route::get('/payment/details_package/{id}', [PaymentController::class, 'details_package'])->middleware('auth')->name('details_package');
Route::get('/payment/edit_package/{id}', [PaymentController::class, 'edit_package'])->middleware('auth')->name('edit_package');
Route::post('payment/add_package_action', [PaymentController::class, 'add_package_action'])->middleware('auth')->name('add_package_action');

Route::get('payment/earning_summary', [PaymentController::class, 'earning_summary'])->middleware('auth')->name('earning_summary');
Route::get('payment/usage_history', [PaymentController::class, 'usage_history'])->middleware('auth')->name('usage_history');
Route::get('payment/buy_package', [PaymentController::class, 'select_package'])->middleware('auth')->name('buy_package');
Route::get('payment/payment_button/{package}', [PaymentController::class, 'buy_package'])->middleware('auth')->name('payment_button');

Route::get('payment/transaction_log_manual', [PaymentController::class, 'transaction_log_manual'])->middleware('auth')->name('transaction_log_manual');
Route::get('payment/transaction_log', [PaymentController::class, 'transaction_log'])->middleware('auth')->name('transaction_log');
Route::post('payment/transaction_log_data', [PaymentController::class, 'transaction_log_data'])->middleware('auth')->name('transaction_log_data');
Route::post('payment/manual_payment_download_file', [PaymentController::class, 'manual_payment_download_file'])->middleware('auth')->name('manual_payment_download_file');
Route::post('payment/manual_payment_handle_actions', [PaymentController::class, 'manual_payment_handle_actions'])->middleware('auth')->name('manual_payment_handle_actions');
Route::post('payment/transaction_log_manual_resubmit_data', [PaymentController::class, 'transaction_log_manual_resubmit_data'])->middleware('auth')->name('transaction_log_manual_resubmit_data');
Route::post('payment/manual_payment_upload_file', [PaymentController::class, 'manual_payment_upload_file'])->middleware('auth')->name('manual_payment_upload_file');
Route::post('payment/manual_payment', [PaymentController::class, 'manual_payment'])->middleware('auth')->name('manual_payment');
Route::post('payment/manual_payment_delete_file', [PaymentController::class, 'manual_payment_delete_file'])->middleware('auth')->name('manual_payment_delete_file');
Route::post('payment/transaction_log_manual_data', [PaymentController::class, 'transaction_log_manual_data'])->middleware('auth')->name('transaction_log_manual_data');


Route::get('members/edit_profile',[Member::class,'edit_profile'])->middleware(['auth'])->name('edit_profile');
Route::post('members/edit_profile_action', [Member::class, 'edit_profile_action'])->middleware('auth')->name('edit_profile_action');
Route::post('members/user_delete_action', [Member::class, 'user_delete_action'])->middleware('auth')->name('user_delete_action');

Route::get('admin/user_manager',[SubscriptionController::class,'user_manager'])->middleware(['auth'])->name('user_manager');
Route::post('admin/user_manager_data',[SubscriptionController::class,'user_manager_data'])->middleware(['auth'])->name('user_manager_data');
Route::post('admin/send_email_member',[SubscriptionController::class,'send_email_member'])->middleware(['auth'])->name('send_email_member');
Route::post('admin/change_user_password_action',[SubscriptionController::class,'change_user_password_action'])->middleware(['auth'])->name('change_user_password_action');
Route::get('admin/login_log',[SubscriptionController::class,'login_log'])->middleware(['auth'])->name('login_log');
Route::post('home/user_delete_action/{id}',[HomeController::class,'user_delete_action'])->middleware(['auth'])->name('user_delete_action');
Route::post('admin/delete_user_log',[SubscriptionController::class,'delete_user_log'])->middleware(['auth'])->name('delete_user_log');
Route::get('admin/edit_user/{id}',[SubscriptionController::class,'edit_user'])->middleware(['auth'])->name('edit_user');
Route::post('admin/edit_user_action',[SubscriptionController::class,'edit_user_action'])->middleware(['auth'])->name('edit_user_action');
Route::get('admin/add_user',[SubscriptionController::class,'add_user'])->middleware(['auth'])->name('add_user');
Route::post('admin/add_user_action',[SubscriptionController::class,'add_user_action'])->middleware(['auth'])->name('add_user_action');


// ................................................Announcements....................................
Route::get('announcement/full_list',[Announcement::class,'full_list'])->middleware(['auth'])->name('announcement_full_list');
Route::post('announcement/list_data',[Announcement::class,'list_data'])->middleware(['auth'])->name('announcement_list_data');
Route::post('announcement/mark_seen_all',[Announcement::class,'mark_seen_all'])->middleware(['auth'])->name('announcement_mark_seen_all');
Route::get('announcement/add',[Announcement::class,'add'])->middleware(['auth'])->name('announcement_add');
Route::post('announcement/add_action',[Announcement::class,'add_action'])->middleware(['auth'])->name('announcement_add_action');
Route::post('announcement/edit_action',[Announcement::class,'edit_action'])->middleware(['auth'])->name('announcement_edit_action');
Route::post('announcement/delete/{id}',[Announcement::class,'delete'])->middleware(['auth'])->name('announcement_delete');
Route::get('announcement/edit/{id}',[Announcement::class,'edit'])->middleware(['auth'])->name('announcement_edit');
Route::post('announcement/mark_seen/{id}',[Announcement::class,'mark_seen'])->middleware(['auth'])->name('announcement_mark_seen');
Route::get('announcement/details/{id}',[Announcement::class,'details'])->middleware(['auth'])->name('announcement_details');




// ...................................................................................................
// ................................................SEO-TOOLS routes....................................
// ....................................................................................................




// ..................................................Analysis tools.................................


Route::get('menu_loader/analysis_tools', [AnalysisToolsController::class, 'index'])->middleware('auth')->name('analysis_tools');
Route::get('/social_network_analysis_list', [AnalysisToolsController::class, 'social_network_analysis_index'])->middleware('auth')->name('social_network_analysis_index');
Route::get('/social_network_analysis/new_analysis', [AnalysisToolsController::class, 'social_network_analysis'])->middleware('auth')->name('social_network_analysis');
Route::post('/social_network_analysis/social_download', [AnalysisToolsController::class, 'social_download'])->middleware('auth')->name('social_download');
Route::post('/social_network_analysis/social_delete', [AnalysisToolsController::class, 'social_delete'])->middleware('auth')->name('social_delete');
Route::post('/social_network_analysis/social_list_data', [AnalysisToolsController::class, 'social_list_data'])->middleware('auth')->name('social_list_data');
Route::post('/social_network_analysis/read_after_delete_csv_txt', [AnalysisToolsController::class, 'read_after_delete_csv_txt'])->middleware('auth')->name('social_read_after_delete_csv_txt');
Route::post('/social_network_analysis/read_text_csv_file_backlink', [AnalysisToolsController::class, 'read_text_csv_file_backlink'])->middleware('auth')->name('social_read_text_csv_file_backlink');
Route::post('/social_network_analysis/social_action', [AnalysisToolsController::class, 'social_action'])->middleware('auth')->name('social_action');



// ..................................................Visitor Analysis .................................
Route::get('visitor_analysis', [VisitorController::class, 'visitor_analysis'])->middleware('auth')->name('visitor_analysis');
Route::get('visitor_analysis/domain_details/{id}', [VisitorController::class, 'domain_details'])->middleware('auth')->name('domain_details');
Route::post('visitor_analysis/domain_list_visitor_data', [VisitorController::class, 'domain_list_visitor_data'])->middleware('auth')->name('domain_list_visitor_data');
Route::post('visitor_analysis/ajax_delete_last_30_days_data', [VisitorController::class, 'ajax_delete_last_30_days_data'])->middleware('auth')->name('ajax_delete_last_30_days_data');
Route::post('visitor_analysis/display_in_dashboard', [VisitorController::class, 'display_in_dashboard'])->middleware('auth')->name('display_in_dashboard');
Route::post('visitor_analysis/get_js_code', [VisitorController::class, 'get_js_code'])->middleware('auth')->name('get_js_code');
Route::post('visitor_analysis/ajax_delete_domain', [VisitorController::class, 'ajax_delete_domain'])->middleware('auth')->name('ajax_delete_domain');
Route::post('visitor_analysis/add_domain_action', [VisitorController::class, 'add_domain_action'])->middleware('auth')->name('add_domain_action');
Route::post('visitor_analysis/display_in_dashboard', [VisitorController::class, 'display_in_dashboard'])->middleware('auth')->name('display_in_dashboard');
Route::post('visitor_analysis/domain_details/ajax_get_traffic_source_data', [VisitorController::class, 'ajax_get_traffic_source_data'])->middleware('auth')->name('ajax_get_traffic_source_data');
Route::post('visitor_analysis/domain_details/ajax_get_individual_country_data', [VisitorController::class, 'ajax_get_individual_country_data'])->middleware('auth')->name('ajax_get_individual_country_data');
Route::post('visitor_analysis/domain_details/ajax_get_country_wise_report_data', [VisitorController::class, 'ajax_get_country_wise_report_data'])->middleware('auth')->name('ajax_get_country_wise_report_data');
Route::post('visitor_analysis/domain_details/ajax_get_os_report_data', [VisitorController::class, 'ajax_get_os_report_data'])->middleware('auth')->name('ajax_get_os_report_data');
Route::post('visitor_analysis/domain_details/ajax_get_browser_report_data', [VisitorController::class, 'ajax_get_browser_report_data'])->middleware('auth')->name('ajax_get_browser_report_data');
Route::post('visitor_analysis/domain_details/ajax_get_device_report_data', [VisitorController::class, 'ajax_get_device_report_data'])->middleware('auth')->name('ajax_get_device_report_data');
Route::post('visitor_analysis/domain_details/ajax_get_individual_device_data', [VisitorController::class, 'ajax_get_individual_device_data'])->middleware('auth')->name('ajax_get_individual_device_data');
Route::post('visitor_analysis/domain_details/ajax_get_individual_os_data', [VisitorController::class, 'ajax_get_individual_os_data'])->middleware('auth')->name('ajax_get_individual_os_data');
Route::post('visitor_analysis/domain_details/ajax_get_individual_browser_data', [VisitorController::class, 'ajax_get_individual_browser_data'])->middleware('auth')->name('ajax_get_individual_browser_data');
Route::post('visitor_analysis/domain_details/ajax_get_overview_data', [VisitorController::class, 'ajax_get_overview_data'])->middleware('auth')->name('ajax_get_overview_data');
Route::post('visitor_analysis/domain_details/ajax_get_visitor_type_data', [VisitorController::class, 'ajax_get_visitor_type_data'])->middleware('auth')->name('ajax_get_visitor_type_data');


Route::any('script/client.js', [VisitorController::class, 'client'])->name('js_controller_client');
Route::any('script/get_ip', [VisitorController::class, 'get_ip'])->name('js_controller_get_ip');
Route::any('script/server_info', [VisitorController::class, 'server_info'])->name('js_controller_server_info');
Route::any('script/scroll_info', [VisitorController::class, 'scroll_info'])->name('js_controller_scroll_info');
Route::any('script/click_info', [VisitorController::class, 'click_info'])->name('js_controller_click_info');
Route::any('script/live_check_info', [VisitorController::class, 'live_check_info'])->name('js_controller_live_check_info');





// ..................................................Website Analysis .................................
Route::get('website_analysis', [WebsiteController::class, 'website_analysis'])->middleware('auth')->name('website_analysis');
Route::post('website_analysis/website_analysis_lists_data', [WebsiteController::class, 'website_analysis_lists_data'])->middleware('auth')->name('website_analysis_lists_data');
Route::post('website_analysis/delete_website_analysis_domain', [WebsiteController::class, 'delete_website_analysis_domain'])->middleware('auth')->name('delete_website_analysis_domain');
Route::post('website_analysis/ajax_delete_all_selected_domain', [WebsiteController::class, 'ajax_delete_all_selected_domain'])->middleware('auth')->name('ajax_delete_all_selected_domain');
Route::post('website_analysis/bulk_scan_progress_count', [WebsiteController::class, 'bulk_scan_progress_count'])->middleware('auth')->name('bulk_scan_progress_count');
Route::post('website_analysis/ajax_domain_analysis_action', [WebsiteController::class, 'ajax_domain_analysis_action'])->middleware('auth')->name('ajax_domain_analysis_action');
Route::post('website_analysis/analysis_report/ajax_get_meta_tag_info_data', [WebsiteController::class, 'ajax_get_meta_tag_info_data'])->middleware('auth')->name('ajax_get_meta_tag_info_data');
Route::post('website_analysis/analysis_report/ajax_get_social_network_data', [WebsiteController::class, 'ajax_get_social_network_data'])->middleware('auth')->name('ajax_get_social_network_data');
Route::post('website_analysis/analysis_report/ajax_get_alexa_info_data', [WebsiteController::class, 'ajax_get_alexa_info_data'])->middleware('auth')->name('ajax_get_alexa_info_data');
Route::post('website_analysis/analysis_report/ajax_get_general_data', [WebsiteController::class, 'ajax_get_general_data'])->middleware('auth')->name('ajax_get_general_data');
Route::get('website_analysis/analysis_report/{id}', [WebsiteController::class, 'analysis_report'])->middleware('auth')->name('analysis_report');
Route::get('website_analysis/download_analysis_report/{id}', [WebsiteController::class, 'download_analysis_report'])->middleware('auth')->name('download_analysis_report');





// ...........................................Rank Analysis .......................................
Route::get('/rank/moz_rank', [RankIndexController::class, 'moz_rank_index'])->middleware('auth')->name('moz_rank_index');
Route::get('/rank/moz_rank_analysis', [RankIndexController::class, 'moz_rank_analysis'])->middleware('auth')->name('moz_rank_analysis');
Route::post('/rank/moz_rank_action', [RankIndexController::class, 'moz_rank_action'])->middleware('auth')->name('moz_rank_action');
Route::post('/rank/moz_rank_delete', [RankIndexController::class, 'moz_rank_delete'])->middleware('auth')->name('moz_rank_delete');
Route::post('/rank/moz_rank_download', [RankIndexController::class, 'moz_rank_download'])->middleware('auth')->name('moz_rank_download');
Route::post('/rank/moz_rank_data', [RankIndexController::class, 'moz_rank_data'])->middleware('auth')->name('moz_rank_data');
Route::post('/rank/read_text_csv_file_backlink', [RankIndexController::class, 'read_text_csv_file_backlink'])->middleware('auth')->name('rank_read_text_csv_file_backlink');
Route::post('/rank/read_after_delete_csv_txt', [RankIndexController::class, 'read_after_delete_csv_txt'])->middleware('auth')->name('rank_read_after_delete_csv_txt');
Route::get('/search_engine_index', [RankIndexController::class, 'search_engine_index'])->middleware('auth')->name('search_engine_index');
Route::get('/search_engine', [RankIndexController::class, 'search_engine'])->middleware('auth')->name('search_engine');
Route::post('/search_engine/search_engine_index_data', [RankIndexController::class, 'search_engine_index_data'])->middleware('auth')->name('search_engine_index_data');
Route::post('/search_engine/search_engine_index_delete', [RankIndexController::class, 'search_engine_index_delete'])->middleware('auth')->name('search_engine_index_delete');
Route::post('/search_engine/search_engine_index_download', [RankIndexController::class, 'search_engine_index_download'])->middleware('auth')->name('search_engine_index_download');
Route::post('/search_engine/read_sengine_after_delete_csv_txt', [RankIndexController::class, 'read_sengine_after_delete_csv_txt'])->middleware('auth')->name('read_sengine_after_delete_csv_txt');
Route::post('/search_engine/read_sengine_text_csv_file_backlink', [RankIndexController::class, 'read_sengine_text_csv_file_backlink'])->middleware('auth')->name('read_sengine_text_csv_file_backlink');
Route::post('/search_engine/search_engine_index_action', [RankIndexController::class, 'search_engine_index_action'])->middleware('auth')->name('search_engine_index_action');

// ...................................Domain Analysis .......................................
Route::get('who_is/index', [DomainAnalysisController::class, 'who_is_index'])->middleware('auth')->name('who_is_index');
Route::get('who_is/who_is', [DomainAnalysisController::class, 'who_is'])->middleware('auth')->name('who_is_new');
Route::post('who_is/who_is_delete', [DomainAnalysisController::class, 'who_is_delete'])->middleware('auth')->name('who_is_delete');
Route::post('who_is/who_is_download', [DomainAnalysisController::class, 'who_is_download'])->middleware('auth')->name('who_is_download');
Route::post('who_is/who_is_list_data', [DomainAnalysisController::class, 'who_is_list_data'])->middleware('auth')->name('who_is_list_data');
Route::post('who_is/read_text_csv_file_backlink', [DomainAnalysisController::class, 'read_text_csv_file_backlink'])->middleware('auth')->name('whois_read_text_csv_file_backlink');
Route::post('who_is/read_after_delete_csv_txt', [DomainAnalysisController::class, 'read_after_delete_csv_txt'])->middleware('auth')->name('whois_read_after_delete_csv_txt');
Route::post('who_is/who_is_action', [DomainAnalysisController::class, 'who_is_action'])->middleware('auth')->name('who_is_action');
Route::get('expired_domain/index', [DomainAnalysisController::class, 'expired_domain_index'])->middleware('auth')->name('expired_domain_index');
Route::post('expired_domain/expired_domain_download', [DomainAnalysisController::class, 'expired_domain_download'])->middleware('auth')->name('expired_domain_download');
Route::post('expired_domain/expired_domain_data', [DomainAnalysisController::class, 'expired_domain_data'])->middleware('auth')->name('expired_domain_data');
Route::get('dns_info/index', [DomainAnalysisController::class, 'dns_info_index'])->middleware('auth')->name('dns_info_index');
Route::post('dns_info/dns_info_action', [DomainAnalysisController::class, 'dns_info_action'])->middleware('auth')->name('dns_info_action');
Route::get('server_info/index', [DomainAnalysisController::class, 'server_info_index'])->middleware('auth')->name('server_info_index');
Route::post('server_info/server_info_action', [DomainAnalysisController::class, 'server_info_action'])->middleware('auth')->name('server_info_action');


// ...................................Link Analysis .......................................
Route::get('/link_analysis/index', [LinkAnalysisController::class, 'link_analysis_index'])->middleware('auth')->name('link_analysis_index');
Route::get('/link_analysis/analysis_new', [LinkAnalysisController::class, 'link_analysis'])->middleware('auth')->name('link_analysis_new');
Route::post('/link_analysis/link_analysis_action', [LinkAnalysisController::class, 'link_analysis_action'])->middleware('auth')->name('link_analysis_action');
Route::post('/link_analysis/link_analysis_delete', [LinkAnalysisController::class, 'link_analysis_delete'])->middleware('auth')->name('link_analysis_delete');
Route::post('/link_analysis/link_analysis_download', [LinkAnalysisController::class, 'link_analysis_download'])->middleware('auth')->name('link_analysis_download');
Route::post('/link_analysis/link_analysis_data', [LinkAnalysisController::class, 'link_analysis_data'])->middleware('auth')->name('link_analysis_data');
Route::get('/page_status/index', [LinkAnalysisController::class, 'page_status_index'])->middleware('auth')->name('page_status_index');
Route::get('/page_status/analysis_new', [LinkAnalysisController::class, 'page_status'])->middleware('auth')->name('page_status_new');
Route::post('/link_analysis/read_text_csv_file_backlink', [LinkAnalysisController::class, 'read_text_csv_file_backlink'])->middleware('auth')->name('link_analysis_read_text_csv_file_backlink');
Route::post('/link_analysis/read_after_delete_csv_txt', [LinkAnalysisController::class, 'read_after_delete_csv_txt'])->middleware('auth')->name('link_analysis_read_after_delete_csv_txt');
Route::post('/link_analysis/page_status_action', [LinkAnalysisController::class, 'page_status_action'])->middleware('auth')->name('page_status_action');
Route::post('/link_analysis/page_status_delete', [LinkAnalysisController::class, 'page_status_delete'])->middleware('auth')->name('page_status_delete');
Route::post('/link_analysis/page_status_list_data', [LinkAnalysisController::class, 'page_status_list_data'])->middleware('auth')->name('page_status_list_data');
Route::post('/link_analysis/page_status_download', [LinkAnalysisController::class, 'page_status_download'])->middleware('auth')->name('page_status_download');


// ...................................Ip Analysis .......................................
Route::get('ip/index', [IpAnalysisController::class, 'index'])->middleware('auth')->name('ip_analysis');
Route::get('ip/domain_info', [IpAnalysisController::class, 'domain_info_index'])->middleware('auth')->name('domain_info_index');
Route::get('ip/domain/analysis_new', [IpAnalysisController::class, 'domain_info_new'])->middleware('auth')->name('domain_info_new');
Route::get('ip/site_this_ip', [IpAnalysisController::class, 'site_this_ip'])->middleware('auth')->name('site_this_ip');
Route::get('ip/sites_same_ip', [IpAnalysisController::class, 'site_this_ip_new'])->middleware('auth')->name('site_this_ip_new');
Route::get('ip/ipv6_check', [IpAnalysisController::class, 'ipv6_check'])->middleware('auth')->name('ipv6_check');
Route::get('ip/check_ipv6', [IpAnalysisController::class, 'ipv6_check_new'])->middleware('auth')->name('ipv6_check_new');
Route::get('ip/ip_canonical_check', [IpAnalysisController::class, 'ip_canonical_check'])->middleware('auth')->name('ip_canonical_check');
Route::get('ip/ip_traceout', [IpAnalysisController::class, 'ip_traceout'])->middleware('auth')->name('ip_traceout');
Route::post('ip/ip_canonical_action', [IpAnalysisController::class, 'ip_canonical_action'])->middleware('auth')->name('ip_canonical_action');
Route::post('ip/traceout_check_data', [IpAnalysisController::class, 'traceout_check_data'])->middleware('auth')->name('traceout_check_data');
Route::post('ip/read_text_csv_file_backlink', [IpAnalysisController::class, 'read_text_csv_file_backlink'])->middleware('auth')->name('ip_read_text_csv_file_backlink');
Route::post('ip/ipv6_check_action', [IpAnalysisController::class, 'ipv6_check_action'])->middleware('auth')->name('ipv6_check_action');
Route::post('ip/read_after_delete_csv_txt', [IpAnalysisController::class, 'read_after_delete_csv_txt'])->middleware('auth')->name('ip_read_after_delete_csv_txt');
Route::post('ip/ipv6_check_delete', [IpAnalysisController::class, 'ipv6_check_delete'])->middleware('auth')->name('ipv6_check_delete');
Route::post('ip/ipv6_check_download', [IpAnalysisController::class, 'ipv6_check_download'])->middleware('auth')->name('ipv6_check_download');
Route::post('ip/ipv6_check_data', [IpAnalysisController::class, 'ipv6_check_data'])->middleware('auth')->name('ipv6_check_data');
Route::post('ip/site_this_ip_data', [IpAnalysisController::class, 'site_this_ip_data'])->middleware('auth')->name('site_this_ip_data');
Route::post('ip/site_this_ip_download', [IpAnalysisController::class, 'site_this_ip_download'])->middleware('auth')->name('site_this_ip_download');
Route::post('ip/site_this_ip_delete', [IpAnalysisController::class, 'site_this_ip_delete'])->middleware('auth')->name('site_this_ip_delete');
Route::post('ip/site_this_ip_action', [IpAnalysisController::class, 'site_this_ip_action'])->middleware('auth')->name('site_this_ip_action');
Route::post('ip/domain_info_action', [IpAnalysisController::class, 'domain_info_action'])->middleware('auth')->name('domain_info_action');
Route::post('ip/domain_info_delete', [IpAnalysisController::class, 'domain_info_delete'])->middleware('auth')->name('domain_info_delete');
Route::post('ip/domain_info_download', [IpAnalysisController::class, 'domain_info_download'])->middleware('auth')->name('domain_info_download');
Route::post('ip/domain_info_data', [IpAnalysisController::class, 'domain_info_data'])->middleware('auth')->name('domain_info_data');


// ...................................Keyword Analysis .......................................
Route::get('keyword/keyword_analyzer', [KeywordAnalysisController::class, 'keyword_analyzer'])->middleware('auth')->name('keyword_analyzer');
Route::post('keyword/keyword_analyzer_data', [KeywordAnalysisController::class, 'keyword_analyzer_data'])->middleware('auth')->name('keyword_analyzer_data');
Route::get('keyword/index', [KeywordAnalysisController::class, 'index'])->middleware('auth')->name('keyword_index');
Route::get('keyword/position_keyword', [KeywordAnalysisController::class, 'position_keyword_new'])->middleware('auth')->name('position_keyword_new');
Route::post('keyword/keyword_position_action', [KeywordAnalysisController::class, 'keyword_position_action'])->middleware('auth')->name('keyword_position_action');
Route::post('keyword/keyword_position_delete', [KeywordAnalysisController::class, 'keyword_position_delete'])->middleware('auth')->name('keyword_position_delete');
Route::post('keyword/keyword_position_download', [KeywordAnalysisController::class, 'keyword_position_download'])->middleware('auth')->name('keyword_position_download');
Route::post('keyword/keyword_position_data', [KeywordAnalysisController::class, 'keyword_position_data'])->middleware('auth')->name('keyword_position_data');
Route::get('keyword/keyword_suggestion', [KeywordAnalysisController::class, 'keyword_suggestion'])->middleware('auth')->name('keyword_suggestion');
Route::post('keyword/keyword_suggestion_delete', [KeywordAnalysisController::class, 'keyword_suggestion_delete'])->middleware('auth')->name('keyword_suggestion_delete');
Route::post('keyword/keyword_suggestion_download', [KeywordAnalysisController::class, 'keyword_suggestion_download'])->middleware('auth')->name('keyword_suggestion_download');
Route::post('keyword/keyword_suggestion_data', [KeywordAnalysisController::class, 'keyword_suggestion_data'])->middleware('auth')->name('keyword_suggestion_data');
Route::post('keyword/keyword_suggestion_action', [KeywordAnalysisController::class, 'keyword_suggestion_action'])->middleware('auth')->name('keyword_suggestion_action');
Route::get('keyword/auto_keyword', [KeywordAnalysisController::class, 'keyword_suggestion_new'])->middleware('auth')->name('keyword_suggestion_new');



// ...........................................URL SHORTNER ...........................................
Route::get('menu_loader/url_shortner', [UrlShortnerController::class, 'index'])->middleware('auth')->name('url_shortner');
Route::get('url_shortner/rebrandly_shortner', [UrlShortnerController::class, 'rebrandly_shortener_index'])->middleware('auth')->name('rebrandly_shortener_index');
Route::get('url_shortner/bitly_shortner', [UrlShortnerController::class, 'bitly_shortener_index'])->middleware('auth')->name('bitly_shortener_index');
Route::get('url_shortner/bitly', [UrlShortnerController::class, 'bitly'])->middleware('auth')->name('bitly');
Route::post('url_shortner/short_url_delete', [UrlShortnerController::class, 'short_url_delete'])->middleware('auth')->name('short_url_delete');
Route::post('url_shortner/short_url_download', [UrlShortnerController::class, 'short_url_download'])->middleware('auth')->name('short_url_download');
Route::post('url_shortner/url_shortener_data', [UrlShortnerController::class, 'url_shortener_data'])->middleware('auth')->name('url_shortener_data');
Route::post('url_shortner/url_shortener_action', [UrlShortnerController::class, 'url_shortener_action'])->middleware('auth')->name('url_shortener_action');
Route::post('url_shortner/read_text_csv_file_backlink', [UrlShortnerController::class, 'read_text_csv_file_backlink'])->middleware('auth')->name('url_short_read_text_csv_file_backlink');
Route::post('url_shortner/read_after_delete_csv_txt', [UrlShortnerController::class, 'read_after_delete_csv_txt'])->middleware('auth')->name('url_short_read_after_delete_csv_txt');
Route::get('url_shortener/url_analytics/{id}', [UrlShortnerController::class, 'url_analytics'])->middleware('auth')->name('url_analytics');
Route::get('url_shortener/rebrandly_url_analytics/{id}', [UrlShortnerController::class, 'rebrandly_url_analytics'])->middleware('auth')->name('rebrandly_url_analytics');
Route::get('url_shortner/rebrandly', [UrlShortnerController::class, 'rebrandly'])->middleware('auth')->name('rebrandly');
Route::post('url_shortener/rebrandly_shortener_action', [UrlShortnerController::class, 'rebrandly_shortener_action'])->middleware('auth')->name('rebrandly_shortener_action');
Route::post('url_shortener/rebrandly_short_url_delete', [UrlShortnerController::class, 'rebrandly_short_url_delete'])->middleware('auth')->name('rebrandly_short_url_delete');
Route::post('url_shortener/rebrandly_short_url_download', [UrlShortnerController::class, 'rebrandly_short_url_download'])->middleware('auth')->name('rebrandly_short_url_download');
Route::post('url_shortener/rebrandly_shortener_data', [UrlShortnerController::class, 'rebrandly_shortener_data'])->middleware('auth')->name('rebrandly_shortener_data');
Route::post('url_shortener/rebrandly_shortener_action', [UrlShortnerController::class, 'rebrandly_shortener_action'])->middleware('auth')->name('rebrandly_shortener_action');


//  ...........................................KEYWORD TRACKING ............................................
Route::get('menu_loader/keyword_position_tracking', [KeywordTrackingController::class, 'index'])->middleware('auth')->name('keyword_tracking');
Route::get('keyword_tracking/list', [KeywordTrackingController::class, 'keyword_tracking_index'])->middleware('auth')->name('keyword_tracking_index');
Route::get('keyword_tracking/keyword_position_report', [KeywordTrackingController::class, 'keyword_position_report'])->middleware('auth')->name('keyword_position_report');
Route::post('keyword_position_tracking/keyword_position_report_data', [KeywordTrackingController::class, 'keyword_position_report_data'])->middleware('auth')->name('keyword_position_report_data');
Route::post('keyword_position_tracking/keyword_tracking_settings_action', [KeywordTrackingController::class, 'keyword_tracking_settings_action'])->middleware('auth')->name('keyword_tracking_settings_action');
Route::post('keyword_position_tracking/keyword_list_data', [KeywordTrackingController::class, 'keyword_list_data'])->middleware('auth')->name('keyword_list_data');
Route::post('keyword_position_tracking/delete_keyword_action', [KeywordTrackingController::class, 'delete_keyword_action'])->middleware('auth')->name('delete_keyword_action');
Route::post('keyword_position_tracking/delete_selected_keyword_action', [KeywordTrackingController::class, 'delete_selected_keyword_action'])->middleware('auth')->name('delete_selected_keyword_action');


// ........................................... SECURITY TOOLS ................................................
Route::get('menu_loader/security_tools', [SecurityToolsController::class, 'index'])->middleware('auth')->name('security_tools');
Route::get('security_tools/virus_index', [SecurityToolsController::class, 'virus_index'])->middleware('auth')->name('virus_index');
Route::get('security_tools/virus_scan', [SecurityToolsController::class, 'virus_scan'])->middleware('auth')->name('virus_scan');
Route::post('security_tools/virus_total_scan_action', [SecurityToolsController::class, 'virus_total_scan_action'])->middleware('auth')->name('virus_total_scan_action');
Route::post('security_tools/virus_total_scan_download', [SecurityToolsController::class, 'virus_total_scan_download'])->middleware('auth')->name('virus_total_scan_download');
Route::post('security_tools/virus_total_scan_data', [SecurityToolsController::class, 'virus_total_scan_data'])->middleware('auth')->name('virus_total_scan_data');
Route::post('security_tools/virus_total_report', [SecurityToolsController::class, 'virus_total_report'])->middleware('auth')->name('virus_total_report');
Route::post('security_tools/virus_total_scan_delete', [SecurityToolsController::class, 'virus_total_scan_delete'])->middleware('auth')->name('virus_total_scan_delete');
Route::get('security_tools/malware_index', [SecurityToolsController::class, 'malware_index'])->middleware('auth')->name('malware_index');
Route::get('security_tools/malware_scan', [SecurityToolsController::class, 'malware_scan'])->middleware('auth')->name('malware_scan');
Route::post('security_tools/scan_action', [SecurityToolsController::class, 'scan_action'])->middleware('auth')->name('scan_action');
Route::post('security_tools/read_after_delete_csv_txt', [SecurityToolsController::class, 'read_after_delete_csv_txt'])->middleware('auth')->name('read_after_delete_csv_txt');
Route::post('security_tools/read_text_csv_file_antivirus', [SecurityToolsController::class, 'read_text_csv_file_antivirus'])->middleware('auth')->name('read_text_csv_file_antivirus');
Route::post('security_tools/scan_data', [SecurityToolsController::class, 'scan_data'])->middleware('auth')->name('scan_data');
Route::post('security_tools/scan_download', [SecurityToolsController::class, 'scan_download'])->middleware('auth')->name('scan_download');
Route::post('security_tools/scan_delete', [SecurityToolsController::class, 'scan_delete'])->middleware('auth')->name('scan_delete');


// ............................................CODE MINIFIER ............................................
Route::get('menu_loader/code_minifier', [CodeMinifierController::class, 'index'])->middleware('auth')->name('code_minifier');
Route::get('code_minifier/js_minifier', [CodeMinifierController::class, 'js_index'])->middleware('auth')->name('js_minifier');
Route::get('code_minifier/css_minifier', [CodeMinifierController::class, 'css_index'])->middleware('auth')->name('css_minifier');
Route::get('code_minifier/html_minifier', [CodeMinifierController::class, 'html_index'])->middleware('auth')->name('html_minifier');
Route::post('code_minifier/html_minifier_textarea', [CodeMinifierController::class, 'html_minifier_textarea'])->middleware('auth')->name('html_minifier_textarea');
Route::post('code_minifier/read_text_file_html', [CodeMinifierController::class, 'read_text_file_html'])->middleware('auth')->name('read_text_file_html');
Route::post('code_minifier/read_after_delete_html', [CodeMinifierController::class, 'read_after_delete_html'])->middleware('auth')->name('read_after_delete_html');
Route::post('code_minifier/css_minifier_textarea', [CodeMinifierController::class, 'css_minifier_textarea'])->middleware('auth')->name('css_minifier_textarea');
Route::post('code_minifier/read_text_file_css', [CodeMinifierController::class, 'read_text_file_css'])->middleware('auth')->name('read_text_file_css');
Route::post('code_minifier/read_after_delete_css', [CodeMinifierController::class, 'read_after_delete_css'])->middleware('auth')->name('read_after_delete_css');
Route::post('code_minifier/js_minifier_textarea', [CodeMinifierController::class, 'js_minifier_textarea'])->middleware('auth')->name('js_minifier_textarea');
Route::post('code_minifier/read_text_file_js', [CodeMinifierController::class, 'read_text_file_js'])->middleware('auth')->name('read_text_file_js');
Route::post('code_minifier/read_after_delete_js', [CodeMinifierController::class, 'read_after_delete_js'])->middleware('auth')->name('read_after_delete_js');


// ..............................................WIDGETS .......................................................
Route::any('native_widgets/get_widget', [WidgetsController::class, 'index'])->middleware('auth')->name('widgets');
Route::get('native_widgets/get_widget/{id}', [WidgetsController::class, 'index'])->middleware('auth');
Route::get('native_widgets/public_traffic_source_data/{id}', [WidgetsController::class, 'public_traffic_source_data'])->middleware('auth')->name('public_traffic_source_data');
Route::get('native_widgets/public_country_report_data/{id}', [WidgetsController::class, 'public_country_report_data'])->middleware('auth')->name('public_country_report_data');
Route::get('native_widgets/public_content_overview_data/{id}', [WidgetsController::class, 'public_content_overview_data'])->middleware('auth')->name('public_content_overview_data');


//  ..............................................................................................................
// ..............................................UTILITIES .........................................................
// ..................................................................................................................
Route::get('menu_loader/utlities', [UtilitiesController::class, 'index'])->middleware('auth')->name('utilities');
Route::get('tools/email_encoder_decoder', [UtilitiesController::class, 'email_encoder_decoder'])->middleware('auth')->name('email_encoder_decoder');
Route::post('tools/email_encoder_action', [UtilitiesController::class, 'email_encoder_action'])->middleware('auth')->name('email_encoder_action');
Route::post('tools/email_decoder_action', [UtilitiesController::class, 'email_decoder_action'])->middleware('auth')->name('email_decoder_action');
Route::get('tools/meta_tag_list', [UtilitiesController::class, 'meta_tag_list'])->middleware('auth')->name('meta_tag_list');
Route::post('tools/meta_tag_action', [UtilitiesController::class, 'meta_tag_action'])->middleware('auth')->name('meta_tag_action');
Route::get('tools/plagarism_check_list', [UtilitiesController::class, 'plagarism_check_list'])->middleware('auth')->name('plagarism_check_list');
Route::get('tools/word_count', [UtilitiesController::class, 'word_count'])->middleware('auth')->name('word_count');
Route::post('tools/word_count_action', [UtilitiesController::class, 'word_count_action'])->middleware('auth')->name('word_count_action');
Route::post('tools/plagarism_check_action', [UtilitiesController::class, 'plagarism_check_action'])->middleware('auth')->name('plagarism_check_action');
Route::get('tools/valid_email_check', [UtilitiesController::class, 'valid_email_check'])->middleware('auth')->name('valid_email_check');
Route::post('tools/email_unique_maker', [UtilitiesController::class, 'email_unique_maker'])->middleware('auth')->name('email_unique_maker');
Route::post('tools/email_validator', [UtilitiesController::class, 'email_validator'])->middleware('auth')->name('email_validator');
Route::get('tools/duplicate_email_filter_list', [UtilitiesController::class, 'duplicate_email_filter_list'])->middleware('auth')->name('duplicate_email_filter_list');
Route::get('tools/url_encode_list', [UtilitiesController::class, 'url_encode_list'])->middleware('auth')->name('url_encode_list');
Route::post('tools/url_encode_action', [UtilitiesController::class, 'url_encode_action'])->middleware('auth')->name('url_encode_action');
Route::post('tools/url_decode_action', [UtilitiesController::class, 'url_decode_action'])->middleware('auth')->name('url_decode_action');
Route::get('tools/url_canonical_check', [UtilitiesController::class, 'url_canonical_check'])->middleware('auth')->name('url_canonical_check');
Route::post('tools/url_canonical_action', [UtilitiesController::class, 'url_canonical_action'])->middleware('auth')->name('url_canonical_action');
Route::get('tools/gzip_check', [UtilitiesController::class, 'gzip_check'])->middleware('auth')->name('gzip_check');
Route::post('tools/gzip_check_action', [UtilitiesController::class, 'gzip_check_action'])->middleware('auth')->name('gzip_check_action');
Route::get('tools/base64_encode_list', [UtilitiesController::class, 'base64_encode_list'])->middleware('auth')->name('base64_encode_list');
Route::post('tools/base64_encode_action', [UtilitiesController::class, 'base64_encode_action'])->middleware('auth')->name('base64_encode_action');
Route::post('tools/base64_decode_action', [UtilitiesController::class, 'base64_decode_action'])->middleware('auth')->name('base64_decode_action');
Route::get('tools/robot_code_generator', [UtilitiesController::class, 'robot_code_generator'])->middleware('auth')->name('robot_code_generator');
Route::post('tools/robot_code_generator_action', [UtilitiesController::class, 'robot_code_generator_action'])->middleware('auth')->name('robot_code_generator_action');
Route::get('tools/sitemap_generator', [UtilitiesController::class, 'sitemap_generator'])->middleware('auth')->name('sitemap_generator');
Route::post('tools/sitemap_generator_action', [UtilitiesController::class, 'sitemap_generator_action'])->middleware('auth')->name('sitemap_generator_action');
Route::get('download-sitemap', [UtilitiesController::class, 'downloadSitemap'])->middleware('auth')->name('download.sitemap');
Route::get('tools/comparision', [UtilitiesController::class, 'comparision'])->middleware('auth')->name('comparision');
Route::post('tools/comparison_action', [UtilitiesController::class, 'comparison_action'])->middleware('auth')->name('comparison_action');






//...................................................................................
// ........................ SYSTEMS ROUTES............................................
// .....................................................................................


// ...........................SETTING.................................................
Route::get('admin/settings', [SettingsController::class, 'index'])->middleware('auth')->name('settings');
Route::get('admin/general_settings', [SettingsController::class, 'general_settings'])->middleware('auth')->name('general_settings');
Route::post('admin/general_settings_action', [SettingsController::class, 'general_settings_action'])->middleware('auth')->name('general_settings_action');
Route::get('admin/front_end_settings', [SettingsController::class, 'front_end_settings'])->middleware('auth')->name('front_end_settings');
Route::post('admin/frontend_settings_action', [SettingsController::class, 'frontend_settings_action'])->middleware('auth')->name('frontend_settings_action');
Route::get('admin/smtp_settings', [SettingsController::class, 'smtp_settings'])->middleware('auth')->name('smtp_settings');
Route::post('admin/smtp_settings_action', [SettingsController::class, 'smtp_settings_action'])->middleware('auth')->name('smtp_settings_action');
Route::post('admin/send_test_email', [SettingsController::class, 'send_test_email'])->middleware('auth')->name('send_test_email');
Route::get('admin/email_templete_settings', [SettingsController::class, 'email_templete_settings'])->middleware('auth')->name('email_templete_settings');
Route::post('admin/email_template_settings_action', [SettingsController::class, 'email_template_settings_action'])->middleware('auth')->name('email_template_settings_action');
Route::get('admin/analytics_settings', [SettingsController::class, 'analytics_settings'])->middleware('auth')->name('analytics_settings');
Route::post('admin/analytics_settings_action', [SettingsController::class, 'analytics_settings_action'])->middleware('auth')->name('analytics_settings_action');
Route::get('admin/advertisement_settings', [SettingsController::class, 'advertisement_settings'])->middleware('auth')->name('advertisement_settings');
Route::post('admin/advertisement_settings', [SettingsController::class, 'advertisement_settings_action'])->middleware('auth')->name('advertisement_settings_action');


// .............................Social APPS ..................................................
Route::get('social_apps/index', [SocialAppsController::class, 'index'])->middleware('auth')->name('social_apps');
Route::get('social_apps/add_facebook_settings', [SocialAppsController::class, 'add_facebook_settings'])->middleware('auth')->name('add_facebook_settings');
Route::post('social_apps/facebook_settings_update_action', [SocialAppsController::class, 'facebook_settings_update_action'])->middleware('auth')->name('facebook_settings_update_action');
Route::get('social_apps/google_settings', [SocialAppsController::class, 'google_settings'])->middleware('auth')->name('google_settings');
Route::post('social_apps/google_settings_action', [SocialAppsController::class, 'google_settings_action'])->middleware('auth')->name('google_settings_action');
Route::get('social_apps/connectivity_settings', [SocialAppsController::class, 'connectivity_settings'])->middleware('auth')->name('connectivity_settings');
Route::post('social_apps/connectivity_settings_action', [SocialAppsController::class, 'connectivity_settings_action'])->middleware('auth')->name('connectivity_settings_action');
Route::get('social_apps/proxy_settings', [SocialAppsController::class, 'proxy_settings'])->middleware('auth')->name('proxy_settings');
Route::post('social_apps/proxy_settings_data', [SocialAppsController::class, 'proxy_settings_data'])->middleware('auth')->name('proxy_settings_data');
Route::post('social_apps/update_proxy_settings', [SocialAppsController::class, 'update_proxy_settings'])->middleware('auth')->name('update_proxy_settings');
Route::post('social_apps/delete_proxy', [SocialAppsController::class, 'delete_proxy'])->middleware('auth')->name('delete_proxy');
Route::post('social_apps/insert_proxy', [SocialAppsController::class, 'insert_proxy'])->middleware('auth')->name('insert_proxy');
Route::post('social_apps/ajax_update_proxy_info', [SocialAppsController::class, 'ajax_update_proxy_info'])->middleware('auth')->name('ajax_update_proxy_info');


// ..................................Add On Manager ...................................................
Route::get('/addons/lists', [AddOnManagerController::class, 'index'])->middleware('auth')->name('add_on_manager');
Route::get('/addons/upload', [AddOnManagerController::class, 'upload'])->middleware('auth')->name('addons_upload');
Route::get('/addons/upload_addon_zip', [AddOnManagerController::class, 'upload_addon_zip'])->middleware('auth')->name('upload_addon_zip');


// ................................Theme Manager ....................................................
Route::get('/themes/lists', [ThemeManagerController::class, 'index'])->middleware('auth')->name('theme_manager');
Route::get('/themes/upload', [ThemeManagerController::class, 'upload'])->middleware('auth')->name('themes_upload');
Route::get('/themes/upload_addon_zip', [ThemeManagerController::class, 'upload_addon_zip'])->middleware('auth')->name('theme_upload_addon_zip');
Route::post('/themes/active_deactive_theme', [ThemeManagerController::class, 'active_deactive_theme'])->middleware('auth')->name('active_deactive_theme');
Route::post('/themes/delete_theme', [ThemeManagerController::class, 'delete_theme'])->middleware('auth')->name('delete_theme');



Route::get('update_system/index', [CheckUpdateController::class, 'index'])->middleware('auth')->name('check_update');
Route::post('update_system/initialize_update', [CheckUpdateController::class, 'initialize_update'])->middleware('auth')->name('initialize-update');
Route::post('update_system/addon_initialize_update', [CheckUpdateController::class, 'addon_initialize_update'])->middleware('auth')->name('addon-initialize-update');

Route::get('/test', [WidgetsController::class, 'test'])->name('test');


Route::get('access_forbidden', [WidgetsController::class, 'test'])->name('access_forbidden');




Route::get('/storage/{extra}', function ($extra) {
	return redirect("/public/storage/$extra");
	})->where('extra', '.*');




Auth::routes();



require __DIR__.'/cron.php';
require __DIR__.'/native-api.php';
require __DIR__.'/support.php';
require __DIR__.'/landing.php';
require __DIR__.'/auth.php';
if(check_build_version() == 'double'){
	require __DIR__.'/webhook.php';
}


