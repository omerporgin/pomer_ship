<?php

use App\Http\Controllers\Common\API\OrderStatusesController;

//Route::any('create_form', [App\Http\Controllers\Common\FormController::class, 'formFactory']);

Route::resource('order_status', OrderStatusesController::class);
Route::get('order_status_ajax', [OrderStatusesController::class, 'indexAjax'])->name('order_status.ajax');
