<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Simplesupport;




Route::get('simplesupport/tickets', [Simplesupport::class, 'tickets'])->middleware('auth')->name('simplesupport');
Route::post('simplesupport/ticket_data', [Simplesupport::class, 'ticket_data'])->middleware('auth')->name('ticket_data');
Route::get('simplesupport/reply/{id}', [Simplesupport::class, 'reply'])->middleware('auth')->name('ticket_reply');
Route::post('simplesupport/reply_action', [Simplesupport::class, 'reply_action'])->middleware('auth')->name('ticket_reply_action');
Route::get('simplesupport/support_category_manager', [Simplesupport::class, 'support_category_manager'])->middleware('auth')->name('support_category_manager');
Route::get('simplesupport/add_category', [Simplesupport::class, 'add_category'])->middleware('auth')->name('add_category');
Route::post('simplesupport/add_category_action', [Simplesupport::class, 'add_category_action'])->middleware('auth')->name('add_category_action');
Route::get('simplesupport/edit_category/{id}', [Simplesupport::class, 'edit_category'])->middleware('auth')->name('edit_category');
Route::post('simplesupport/edit_category_action', [Simplesupport::class, 'edit_category_action'])->middleware('auth')->name('edit_category_action');
Route::get('simplesupport/open_ticket', [Simplesupport::class, 'open_ticket'])->middleware('auth')->name('open_ticket');
Route::post('simplesupport/open_ticket_action', [Simplesupport::class, 'open_ticket_action'])->middleware('auth')->name('open_ticket_action');
Route::post('simplesupport/delete_ticket', [Simplesupport::class, 'delete_ticket'])->middleware('auth')->name('delete_ticket');
Route::post('simplesupport/ticket_action', [Simplesupport::class, 'ticket_action'])->middleware('auth')->name('ticket_action');
Route::post('simplesupport/delete_category', [Simplesupport::class, 'delete_category'])->middleware('auth')->name('delete_category');