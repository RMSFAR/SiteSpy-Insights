<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\System\CronJobController;




// ................................Cron Job ....................................................
Route::get('cron_job/index', [CronJobController::class, 'index'])->middleware('auth')->name('cron_job');
Route::get('cron_job/get_api_action', [CronJobController::class, 'get_api_action'])->middleware('auth')->name('get_api_action');
Route::get('cron_job/send_notification/{api_key}', [CronJobController::class, 'send_notification'])->middleware('auth')->name('send_notification');
Route::get('cron_job/auction_domain/{api_key}', [CronJobController::class, 'auction_domain'])->middleware('auth')->name('auction_domain');
Route::get('cron_job/get_keyword_position_data/{api_key}', [CronJobController::class, 'get_keyword_position_data'])->middleware('auth')->name('get_keyword_position_data');
Route::get('cron_job/delete_junk_files/{api_key}', [CronJobController::class, 'delete_junk_files'])->middleware('auth')->name('delete_junk_files');