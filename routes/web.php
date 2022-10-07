<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\App\PagesController;
use App\Http\Controllers\App\PaymentController;
use App\Http\Controllers\App\BlogController;
use App\Http\Controllers\FriendlyUrlController;
use \App\Http\Controllers\SitemapController;

/**
 * This is a service used in both admin and vendor pages to trigger personal wep socket
 * runs on every page
 */
Route::get('api_get_notifications', function () {
    \App\NotificationService\NotificationService::runNotification();
});

Route::get('/', function () {

    return redirect()->route('login', ['lang' => 109]);
    // return redirect()->route('app.index', ['lang' => 109]);

})->name('app.index_without_lang');

/**
 *  App Routes
 */
Route::middleware(['MinifyHtml'])->group(function () {
    Route::group([
        'prefix' => '{lang}',
        'where' => [
            'lang' => '[0-9]+', // lang can only be an integer
        ],
        'middleware' => ['SelectLang'],
    ], function () {

        //Route::get('/', [PagesController::class, 'index'])->name('app.index');

        Route::get('/', function () {

            return redirect()->route('login', ['lang' => 109]);
            // return redirect()->route('app.index', ['lang' => 109]);

        })->name('app.index');

        Route::get('contact', [PagesController::class, 'contact'])->name('app.contact');

        Route::get('price', [PagesController::class, 'price'])->name('app.price');

        Route::get('blog/{id}', [BlogController::class, 'blog'])->name('app.blog');

        Route::get('blogs', [BlogController::class, 'blogs'])->name('app.blogs');

        Route::get('faq', [PagesController::class, 'faq'])->name('app.faq');

        Route::get('partners', [PagesController::class, 'partners'])->name('app.partners');

        Route::get('feature', [PagesController::class, 'feature'])->name('app.feature');

    });

    /**
     * Seo Friendly Urls - Only for blog
     */
    Route::fallback([FriendlyUrlController::class, 'index']);

});

Route::get('/sitemap', [SitemapController::class, 'sitemap'])->name('sitemap');

/**
 * Payment functions
 *
 * 1 - Paytr
 */
Route::get('payment/{id}/success', [PaymentController::class, 'success'])->name('payment.success');

Route::get('payment/{id}/fail', [PaymentController::class, 'fail'])->name('payment.fail');

Route::post('payment/{id}/callback', [PaymentController::class, 'callback'])->name('payment.callback');

require __DIR__ . '/auth.php';

use Laravel\Socialite\Facades\Socialite;

Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
});
