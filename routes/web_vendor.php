<?php

use App\Http\Controllers\Vendor\{
    PagesController as VendorPagesController,
    ShippingPriceController,
    NotificationsController,
    MesagesController,
    UploadInvoiceController,
    LabelController,
};
use App\Http\Controllers\Vendor\Order\{
    OrderController,
    OrderDataTableController,
    OrderDownloadController,
    DocumentController
};
use App\Http\Controllers\Vendor\Payment\{
    PaymentController,
    PaymentDataTableController,
    PaymentAccountController
};
use App\Http\Controllers\Vendor\Package\{
    PackageController,
    PackageDataTableController,
};
use \App\Http\Controllers\Vendor\API\{
    EntegrationController,
    EntegrationDataController,
    NotificationController,
    MessageController,
};
use App\Http\Controllers\LocationController;

Route::get('', [VendorPagesController::class, 'index'])->name('vendor');

Route::get('notifications', [NotificationsController::class, 'notifications'])->name('vendor_notifications');

Route::get('messages', [MesagesController::class, 'messages'])->name('vendor_messages');

Route::post('api_add_city', [LocationController::class, 'apiAddCity'])->name('api.add_city');
Route::post('api_add_state', [LocationController::class, 'apiAddState'])->name('api.add_state');

Route::post('api_print_label', [ LabelController::class, 'print'])->name('api_print_label');

Route::any('api_upload_invoice', [ UploadInvoiceController::class, 'upload'])->name('api_upload_invoice');

Route::post('api_get_price', [ ShippingPriceController::class, 'getPrice'])->name('api_get_price');

Route::get('order_status', [VendorPagesController::class, 'orderStatusses'])->name('vendor_order_status');
Route::get('vendor_entegration', [VendorPagesController::class, 'entegrations'])->name('vendor_entegrations');

# Orders
Route::resource('vendor_orders', OrderController::class)->except(['index']);
Route::get('orders_ajax', [OrderDataTableController::class, 'index'])->name('vendor.orders.ajax');
Route::post('api/get_all_orders', [OrderDownloadController::class, 'getAllOrders'])->name('vendor_order_download');
Route::get('orders/{id?}', [OrderController::class, 'index'])->name('vendor_orders'); // ? order.show olmalı
Route::post('documents', [DocumentController::class, 'createDocuments'])->name('vendor.create_document');

# Package
Route::resource('vendor_packages', PackageController::class)->except(['index', 'show']);
Route::get('packages_ajax', [PackageDataTableController::class, 'index'])->name('vendor.packages.ajax');
Route::get('packages/{type}', [PackageController::class, 'index'])->name('vendor.packages');

# Payment
Route::post('payment_installments', [PaymentController::class, 'installments']);
Route::post('pay', [PaymentController::class, 'pay'])->name('vendor_paytr_form');
Route::resource('vendor_payment_account', PaymentAccountController::class);
Route::get('payment_account_ajax', [PaymentDataTableController::class, 'index'])->name('vendor.payment_account.ajax');

Route::get('shipping_prices', [ShippingPriceController::class, 'shippingPrices'])->name('vendor.shipping_prices');
Route::get('shipping_prices/download/{id}', [ShippingPriceController::class, 'downloadShippingPrices'])->name('admin_download_shipping_prices');

/**
 * VENDOR RESOURCE SERVICE ROUTES
 *
 * Resource names for vendor must start with vendor...
 */

Route::resource('vendor_entegration_data', EntegrationDataController::class);

Route::resource('vendor_alert', NotificationController::class);

Route::resource('vendor_message', MessageController::class);
