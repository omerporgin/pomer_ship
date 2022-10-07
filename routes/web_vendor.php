<?php

use App\Http\Controllers\Vendor\PagesController as VendorPagesController;
use App\Http\Controllers\Vendor\ShippingPriceController;
use App\Http\Controllers\Vendor\API\OrderController;
use App\Http\Controllers\Vendor\API\PackageController;
use App\Http\Controllers\Vendor\API\PaymentAccountController;
use App\Http\Controllers\Vendor\API\EntegrationController;


Route::post('api/get_all_orders', [App\Http\Controllers\Vendor\OrderDownloadController::class, 'getAllOrders'])->name
('vendor_order_download');

Route::post('payment_installments', [App\Http\Controllers\Vendor\PaymentController::class, 'installments']);

Route::post('pay', [App\Http\Controllers\Vendor\PaymentController::class, 'pay'])->name('vendor_paytr_form');

Route::get('notifications', [App\Http\Controllers\Vendor\NotificationsController::class, 'notifications'])->name('vendor_notifications');

Route::get('messages', [App\Http\Controllers\Vendor\MesagesController::class, 'messages'])->name('vendor_messages');

Route::post('api_add_location', [App\Http\Controllers\LocationController::class, 'apiAddLocation'])->name('api_add_location');

Route::post('api_print_label', [App\Http\Controllers\Vendor\LabelController::class, 'print'])->name('api_print_label');

Route::any('api_upload_invoice', [App\Http\Controllers\Vendor\UploadInvoiceController::class, 'upload'])->name('api_upload_invoice');

Route::post('api_get_price', [App\Http\Controllers\Vendor\ShippingPriceController::class, 'getPrice'])->name('api_get_price');

/**
 * VENDOR RESOURCE SERVICE ROUTES
 *
 * Resource names for vendor must start with vendor...
 */

Route::resource('vendor_entegration_data', \App\Http\Controllers\Vendor\API\EntegrationDataController::class);

Route::resource('vendor_alert', \App\Http\Controllers\Vendor\API\NotificationController::class);

Route::resource('vendor_message', \App\Http\Controllers\Vendor\API\MessageController::class);

Route::get('', [VendorPagesController::class, 'index'])->name('vendor');

Route::get('order_status', [VendorPagesController::class, 'orderStatusses'])->name('vendor_order_status');
Route::get('vendor_entegration', [VendorPagesController::class, 'entegrations'])->name('vendor_entegrations');

Route::resource('vendor_orders', OrderController::class)->except(['index']);
Route::get('orders_ajax', [OrderController::class, 'indexAjax'])->name('vendor.orders.ajax');
Route::get('orders/{id?}', [OrderController::class, 'index'])->name('vendor_orders');

Route::resource('vendor_packages',PackageController::class)->except
(['index','show']);
Route::get('packages_ajax', [PackageController::class, 'indexAjax'])->name('vendor.packages.ajax');
Route::get('packages/{type}', [PackageController::class, 'index'])->name('vendor.packages');

Route::resource('vendor_payment_account', PaymentAccountController::class);
Route::get('payment_account_ajax', [PaymentAccountController::class, 'indexAjax'])->name('vendor.payment_account.ajax');

Route::get('shipping_prices', [ShippingPriceController::class, 'shippingPrices'])->name('vendor.shipping_prices');
Route::get('shipping_prices/download/{id}', [ShippingPriceController::class, 'downloadShippingPrices'])->name('admin_download_shipping_prices');

Route::post('documents', [\App\Http\Controllers\Vendor\DocumentController::class, 'createDocuments'])->name('vendor.create_document');

