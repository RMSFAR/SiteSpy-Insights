<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Landing;
use Illuminate\Support\Facades\Route;


Route::any('/policy/privacy', [Landing::class,'policy_privacy'])->name('policy-privacy');
Route::any('/policy/terms', [Landing::class,'policy_terms'])->name('policy-terms');
Route::any('/policy/refund', [Landing::class,'policy_refund'])->name('policy-refund');
Route::any('/policy/gdpr', [Landing::class,'policy_gdpr'])->name('policy-gdpr');
Route::any('/home/allow_cookie', [Landing::class,'accept_cookie'])->name('accept-cookie');
Route::post('/installation-submit', [Landing::class,'installation_submit'])->name('installation-submit');
Route::get('/home/account_activation', [Landing::class,'account_activation'])->name('account-activation');
Route::post('/account_activation_action', [Landing::class,'account_activation_action'])->name('account-activation-action');


Route::post('/home/front_end_website_analysis', [HomeController::class,'front_end_website_analysis'])->name('home.front_end_website_analysis');
Route::post('/home/front_end_bulk_scan_progress_count', [HomeController::class,'front_end_bulk_scan_progress_count'])->name('home.front_end_bulk_scan_progress_count');
Route::get('home/frontend_domain_details_view/{id}', [HomeController::class, 'frontend_domain_details_view'])->name('home.analysis_report');
Route::post('/home/frontend_domain_details_view/ajax_get_social_network_data', [HomeController::class,'front_ajax_get_social_network_data'])->name('home.front_ajax_get_social_network_data');
Route::post('/home/frontend_domain_details_view/ajax_get_meta_tag_info_data', [HomeController::class,'front_ajax_get_meta_tag_info_data'])->name('home.front_ajax_get_meta_tag_info_data');
Route::post('/home/frontend_domain_details_view/ajax_get_alexa_info_data', [HomeController::class,'front_ajax_get_alexa_info_data'])->name('home.front_ajax_get_alexa_info_data');
Route::post('/home/frontend_domain_details_view/ajax_get_general_data', [HomeController::class,'front_ajax_get_general_data'])->name('home.front_ajax_get_general_data');
Route::get('home/download_analysis_report/{id}', [HomeController::class, 'frontend_download_pdf'])->name('frontend-download-pdf');


Route::group(config('translation.route_group_config') + ['namespace' => 'App\\Http\\Controllers'], function ($router) {
    $router->get(config('translation.ui_url'), 'Multilanguage@index')
        ->middleware(['auth'])->name('languages.index');

    $router->get(config('translation.ui_url').'/create', 'Multilanguage@create')
        ->middleware(['auth'])->name('languages.create');

    $router->get(config('translation.ui_url').'/edit/{language}', 'Multilanguage@edit')
        ->middleware(['auth'])->name('languages.edit');

    $router->get(config('translation.ui_url').'/delete/{locale?}', 'Multilanguage@delete')
        ->middleware(['auth'])->name('languages.delete');

    $router->post(config('translation.ui_url'), 'Multilanguage@store')
        ->middleware(['auth'])->name('languages.store');

    $router->get(config('translation.ui_url').'/download/{language}', 'Multilanguage@download_languages')
        ->middleware(['auth'])->name('languages.download');

    $router->get(config('translation.ui_url').'/{language}/translations', 'Multilanguage@translation_index')
        ->middleware(['auth'])->name('languages.translations.index');

    $router->post(config('translation.ui_url').'/{language}', 'Multilanguage@update_translation')
        ->middleware(['auth'])->name('languages.translations.update');

    $router->get(config('translation.ui_url').'/{language}/translations/create', 'Multilanguage@create_translation')
        ->middleware(['auth'])->name('languages.translations.create');


    $router->post(config('translation.ui_url').'/{language}/translations', 'Multilanguage@store_translation')
        ->middleware(['auth'])->name('languages.translations.store');

    $router->get(config('translation.ui_url').'/new-group/{locale?}/{group?}', 'Multilanguage@create_new_group')
        ->middleware(['auth'])->name('languages.translations.create-new-group');

    $router->get(config('translation.ui_url').'/run-command/{language}', 'Multilanguage@run_artisan')
        ->middleware(['auth'])->name('languages.translations.find-language');

    $router->get(config('translation.ui_url').'/compile/{language}', 'Multilanguage@compile_language')
        ->middleware(['auth'])->name('languages.translations.compile');
});