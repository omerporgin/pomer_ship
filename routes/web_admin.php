<?php

use App\Http\Controllers\Admin\Dashboard\{
    DashboardController
};
use App\Http\Controllers\Admin\Blog\{
    BlogController,
    BlogDataTableController
};
use App\Http\Controllers\Admin\Currency\{
    CurrencyController,
    CurrencyDataTableController
};
use App\Http\Controllers\Admin\Gtip\{
    GtipController,
    GtipDataTableController
};
use App\Http\Controllers\Admin\Language\{
    LanguageController,
    LanguageDataTableController,
};
use App\Http\Controllers\Admin\Localization\{
    LocalizationController,
    LocalizationDataTableController,
    ScanVariablesStatusController
};
use App\Http\Controllers\Admin\Location\{
    LocationCountryController,
    LocationCountryDataTableController,
    LocationStateController,
    LocationStateDataTableController,
    LocationCityController,
    LocationCityDataTableController,
};
use App\Http\Controllers\Admin\Order\{
    OrderController,
    OrderDataTableController,
    OrderStatusController,
};
use App\Http\Controllers\Admin\Shipping\{
    ShippingController,
    ShippingDataTableController,
    ShippingPriceController,
    ShippingZoneController,
    UserGroupPriceController,
    UserGroupPricesDataTableController,
    UserGroupController,
    UserGroupDataTableController,
    GetShippingServiceController
};
use App\Http\Controllers\Admin\User\{
    UserController,
    UserDataTableController,
    PermissionController,
    PermissionDataTableController,
    UserGatesController,
};

use App\Http\Controllers\Admin\VendorSelect2Controller;


# Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('admin');

# Blog
Route::resource('admin_blogs', BlogController::class);
Route::get('blogs_ajax', [BlogDataTableController::class, 'index'])->name('admin.blogs.ajax');
Route::post('blog_delete_image', [BlogController::class, 'blogDeleteImage'])->name('admin.blogs.delete_image');

# Currency
Route::resource('admin_currencies', CurrencyController::class);
Route::get('currencies_ajax', [CurrencyDataTableController::class, 'index'])->name('admin.currencies.ajax');

# Gtip
Route::resource('admin_gtip', GtipController::class);
Route::get('gtip_ajax', [GtipDataTableController::class, 'index'])->name('admin.gtip.ajax');

#Language
Route::resource('admin_languages', LanguageController::class);
Route::get('languages_ajax', [LanguageDataTableController::class, 'index'])->name('admin.languages.ajax');
Route::post('language/scan', [ScanVariablesStatusController::class, 'scan'])->name('language_scan');

# Localization
Route::resource('admin_localization', LocalizationController::class);
Route::get('localization_ajax', [LocalizationDataTableController::class, 'index'])->name('admin.localization.ajax');

# Location
Route::resource('admin_location_country', LocationCountryController::class);
Route::get('location_country_ajax', [LocationCountryDataTableController::class, 'index'])->name('admin.location_country.ajax');
Route::resource('admin_location_state', LocationStateController::class);
Route::get('location_state_ajax', [LocationStateDataTableController::class, 'index'])->name('admin.location_state.ajax');
Route::resource('admin_location_city', LocationCityController::class);
Route::get('location_city_ajax', [LocationCityDataTableController::class, 'index'])->name('admin.location_city.ajax');

# Order
Route::resource('admin_orders', OrderController::class)->except(['index']);
Route::get('orders/{id?}', [OrderController::class, 'index'])->name('admin_orders');
Route::get('orders_ajax', [OrderDataTableController::class, 'index'])->name('admin.orders_ajax');

Route::get('order_status', [OrderStatusController::class, 'index'])->name('admin.order_statusses');

# Shipping
Route::resource('admin_shippings', ShippingController::class);
Route::get('shippings_ajax', [ShippingDataTableController::class, 'index'])->name('admin.shippings.ajax');
Route::post('update_shipping_zone', [ShippingZoneController::class, 'updateShippingZone'])->name('admin_update_shipping_zone');
Route::post('update_shipping_prices', [ShippingPriceController::class, 'updateShippingPrices'])->name('admin_update_shipping_prices');
Route::get('shipping_prices/{id}', [ShippingPriceController::class, 'shippingPrices'])->name('admin_shipping_prices');
Route::get('update_shipping_prices/{shipping_id}', [ShippingPriceController::class, 'updateShippingPriceView'])->name('admin_shipping_prices_update');
Route::resource('admin_user_group_prices', UserGroupPriceController::class);
// Route::resource('user_group_prices', UserGroupPriceController::class); // Emin değilim yukarıdaki route ile aynı
Route::get('api_admin_user_group_prices_ajax', [UserGroupPricesDataTableController::class, 'index'])->name('api_admin_user_group_prices_ajax');
Route::resource('admin_user_groups', UserGroupController::class);
Route::get('user_groups_ajax', [UserGroupDataTableController::class, 'index'])->name('api_admin_user_groups_ajax');

Route::post('get_shipping_services', [GetShippingServiceController::class, '__invoke'])->name('api.get_shipping_services');

# User
Route::resource('admin_users', UserController::class)->except(['index']);
Route::get('users_ajax', [UserDataTableController::class, 'index'])->name('admin.users.ajax');
Route::get('users/{type}', [UserController::class, 'index'])->name('admin.users');
Route::get('user_gates', [UserGatesController::class, 'index'])->name('admin_gates');

Route::resource('admin_permissions', PermissionController::class);
Route::get('permissions_ajax', [PermissionDataTableController::class, 'index'])->name('admin.permissions.ajax');

/**
 * ?
 */
Route::post('api/vendor_select2', [VendorSelect2Controller::class, 'getData'])->name('vendor_select2');
