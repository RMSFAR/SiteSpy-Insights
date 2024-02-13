<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\System\NativeAPIController;



// ................................Native API ....................................................
Route::get('native_api/index', [NativeAPIController::class, 'index'])->middleware('auth')->name('native_api');
Route::get('native_api/get_api_action', [NativeAPIController::class, 'get_api_action'])->middleware('auth')->name('get_api_action');
Route::get('native_api/get_content_overview_data', [NativeAPIController::class, 'get_content_overview_data'])->middleware('auth')->name('get_content_overview_data');
Route::get('native_api/get_overview_data', [NativeAPIController::class, 'get_overview_data'])->middleware('auth')->name('get_overview_data');
Route::get('native_api/facebook_ckeck', [NativeAPIController::class, 'facebook_ckeck'])->middleware('auth')->name('facebook_ckeck');
Route::get('native_api/xing_check', [NativeAPIController::class, 'xing_check'])->middleware('auth')->name('xing_check');
Route::get('native_api/reddit_check', [NativeAPIController::class, 'reddit_check'])->middleware('auth')->name('reddit_check');
Route::get('native_api/pinterest_check', [NativeAPIController::class, 'pinterest_check'])->middleware('auth')->name('pinterest_check');
Route::get('native_api/buffer_check', [NativeAPIController::class, 'buffer_check'])->middleware('auth')->name('buffer_check');
Route::get('native_api/pagestatus_check', [NativeAPIController::class, 'pagestatus_check'])->middleware('auth')->name('pagestatus_check');
Route::get('native_api/alexa_check', [NativeAPIController::class, 'alexa_check'])->middleware('auth')->name('alexa_check');
Route::get('native_api/similar_web_check', [NativeAPIController::class, 'similar_web_check'])->middleware('auth')->name('similar_web_check');
Route::get('native_api/bing_index_check', [NativeAPIController::class, 'bing_index_check'])->middleware('auth')->name('bing_index_check');
Route::get('native_api/yahoo_index_check', [NativeAPIController::class, 'yahoo_index_check'])->middleware('auth')->name('yahoo_index_check');
Route::get('native_api/link_analysis_check', [NativeAPIController::class, 'link_analysis_check'])->middleware('auth')->name('link_analysis_check');
Route::get('native_api/backlink_check', [NativeAPIController::class, 'backlink_check'])->middleware('auth')->name('backlink_check');
Route::get('native_api/google_malware_check', [NativeAPIController::class, 'google_malware_check'])->middleware('auth')->name('google_malware_check');
Route::get('native_api/macafee_malware_check', [NativeAPIController::class, 'macafee_malware_check'])->middleware('auth')->name('macafee_malware_check');
Route::get('native_api/norton_malware_check', [NativeAPIController::class, 'norton_malware_check'])->middleware('auth')->name('norton_malware_check');
Route::get('native_api/domain_ip_check', [NativeAPIController::class, 'domain_ip_check'])->middleware('auth')->name('domain_ip_check');
Route::get('native_api/sites_in_same_ip_check', [NativeAPIController::class, 'sites_in_same_ip_check'])->middleware('auth')->name('sites_in_same_ip_check');

