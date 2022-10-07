<?php

use App\Http\Controllers\Admin\ShippingPriceController;
use App\Http\Controllers\Admin\API\AdminPageController;
use App\Http\Controllers\Admin\PagesController as AdminPagesController;
use App\Http\Controllers\Admin\API\UsersController;
use App\Http\Controllers\Admin\API\OrdersController;
use App\Http\Controllers\Admin\API\LocationCountryController;
use App\Http\Controllers\Admin\API\LocationStateController;
use App\Http\Controllers\Admin\API\LocationCityController;
use App\Http\Controllers\Admin\API\BlogsController;
use App\Http\Controllers\Admin\API\UserGroupsController;
use App\Http\Controllers\Admin\API\PermissionsController;
use App\Http\Controllers\Admin\API\ShippingController;
use App\Http\Controllers\Admin\API\GtipController;
use App\Http\Controllers\Admin\API\CurrenciesController;
use App\Http\Controllers\Admin\API\LanguagesController;
use App\Http\Controllers\Admin\API\LocalizationController;
use App\Http\Controllers\Admin\ShippingZoneController;
use App\Http\Controllers\Admin\VendorSelect2Controller;
use App\Http\Controllers\Admin\API\UserGroupPricesController;

/**
 * ADMIN ROUTES
 */

Route::get('/', [AdminPagesController::class, 'index'])->name('admin');

Route::post('update_shipping_zone', [ShippingZoneController::class, 'updateShippingZone'])->name('admin_update_shipping_zone');

Route::post('update_shipping_prices', [ShippingPriceController::class, 'updateShippingPrices'])->name('admin_update_shipping_prices');

Route::get('shipping_prices/{id}', [ShippingPriceController::class, 'shippingPrices'])->name('admin_shipping_prices');
Route::get('update_shipping_prices/{shipping_id}', [ShippingPriceController::class, 'updateShippingPriceView'])->name('admin_shipping_prices_update');


/**
 * ADMIN SERVICES
 */
Route::post('language/scan', [LocalizationController::class, 'scan'])->name('language_scan');

Route::post('api/vendor_select2', [VendorSelect2Controller::class, 'getData'])->name('vendor_select2');

/**
 * ADMIN RESOURCE SERVICE ROUTES
 *
 * Resource names for admin must start with admin_...
 */

Route::resource('admin_user_group_prices', UserGroupPricesController::class);
Route::get('api_admin_user_group_prices_ajax', [UserGroupPricesController::class, 'indexAjax'])->name('api_admin_user_group_prices_ajax');

Route::resource('admin_user_groups', UserGroupsController::class);
Route::get('user_groups_ajax', [UserGroupsController::class, 'indexAjax'])->name('api_admin_user_groups_ajax');

Route::resource('user_group_prices', \App\Http\Controllers\Admin\API\UserGroupPricesController::class);

Route::resource('admin_blogs', BlogsController::class);

Route::get('blogs_ajax', [BlogsController::class, 'indexAjax'])->name('admin.blogs.ajax');
Route::post('blog_delete_image', [BlogsController::class, 'blogDeleteImage'])->name('admin.blogs.delete_image');

Route::resource('admin_users', UsersController::class)->except(['index']);
Route::get('users_ajax', [UsersController::class, 'indexAjax'])->name('admin.users.ajax');
Route::get('users/{type}', [UsersController::class, 'index'])->name('admin.users');
Route::get('user_gates', [AdminPagesController::class, 'userGates'])->name('admin_gates');

Route::resource('admin_permissions', PermissionsController::class);
Route::get('permissions_ajax', [PermissionsController::class, 'indexAjax'])->name('admin.permissions.ajax');

Route::resource('admin_orders', OrdersController::class)->except(['index']);
Route::get('orders/{id?}', [OrdersController::class, 'index'])->name('admin_orders');
Route::get('orders_ajax', [OrdersController::class, 'indexAjax'])->name('admin.orders_ajax');

Route::get('order_status', [AdminPagesController::class, 'orderStatusses'])->name('admin.order_statusses');

Route::resource('admin_location_country', LocationCountryController::class);
Route::get('location_country_ajax', [LocationCountryController::class, 'indexAjax'])->name('admin.location_country.ajax');

Route::resource('admin_location_state', LocationStateController::class);
Route::get('location_state_ajax', [LocationStateController::class, 'indexAjax'])->name('admin.location_state.ajax');

Route::resource('admin_location_city', LocationCityController::class);
Route::get('location_city_ajax', [LocationCityController::class, 'indexAjax'])->name('admin.location_city.ajax');

Route::resource('admin_shippings', ShippingController::class);
Route::get('shippings_ajax', [ShippingController::class, 'indexAjax'])->name('admin.shippings.ajax');

Route::resource('admin_gtip', GtipController::class);
Route::get('gtip_ajax', [GtipController::class, 'indexAjax'])->name('admin.gtip.ajax');

Route::resource('admin_currencies', CurrenciesController::class);
Route::get('currencies_ajax', [CurrenciesController::class, 'indexAjax'])->name('admin.currencies.ajax');

Route::resource('admin_languages', LanguagesController::class);
Route::get('languages_ajax', [LanguagesController::class, 'indexAjax'])->name('admin.languages.ajax');

Route::resource('admin_localization', LocalizationController::class);
Route::get('localization_ajax', [LocalizationController::class, 'indexAjax'])->name('admin.localization.ajax');
