const mix = require('laravel-mix');

/**
 * CSS - For both admin and vendor css files
 */
mix.combine([
    'resources/admin_assets/vendor/fontawesome-free/css/all.min.css',
    'node_modules/datatables/media/css/jquery.dataTables.css',
    'node_modules/select2/dist/css/select2.min.css',
    'node_modules/trumbowyg/dist/ui/trumbowyg.min.css',
    'resources/js/admin/libraries/datetimepicker/tempusdominus-bootstrap-4.css', // Required for date time picker
    'resources/js/admin/libraries/jjsonviewer/css/jjsonviewer.css',
], 'public/css_pre/admin_pre.css');

/**
 * CSS - ADMIN
 */
mix.sass('resources/admin_assets/scss/sb-admin-2.scss', 'public/css_pre/sb-admin-2_precompile.css');

mix.combine([
    'public/css_pre/sb-admin-2_precompile.css',
    'public/css_pre/admin_pre.css',
    'resources/css/vendor_admin_together.css',
    'resources/css/admin.css',
], 'public/css/admin.css');

/**
 * CSS - VENDOR
 */
mix.sass('resources/admin_assets/scss/sb-vendor-2.scss', 'public/css_pre/sb-vendor-2_precompile.css');
mix.combine([
    'public/css_pre/sb-vendor-2_precompile.css',
    'public/css_pre/admin_pre.css',
    'resources/css/vendor_admin_together.css',
    'resources/css/vendor.css',
], 'public/css/vendor.css');

/**
 * CSS - APP
 */
mix.combine([
    'resources/admin_assets/vendor/fontawesome-free/css/all.min.css',
    'node_modules/bootstrap/dist/css/bootstrap.css',
    'resources/css/app_compiled.css',
    'resources/css/app.css',
], 'public/css/app.css');

/**
 * JS - ADMIN + VENDOR
 */
mix.js([
    'resources/admin_assets/vendor/jquery/jquery.js',
    'resources/admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js',
    //'resources/admin_assets/js/sb-admin-2.min.js', // admin.js içine taşındı. (JQuery tanıma poblemleri yüzünden.)
    'node_modules/lc-switch/lc_switch.min.js',
    'resources/js/admin.js',
    'resources/js/admin/libraries/pusher.min.js',
    'resources/js/admin/libraries/jjsonviewer/js/jjsonviewer.js',
], 'public/js/admin.js').sourceMaps();

/**
 * JS- APP
 */
mix.js([
    'resources/js/app.js',
], 'public/js/app.js').sourceMaps();

/**
 * MINIFY ALL FILES
 * Webpack minify code only in production mode.
 */
/*
mix.minify('public/css/app.css','public/css/app.css');
mix.minify('public/css/admin.css','public/css/admin.css');
mix.minify('public/css/vendor.css','public/css/vendor.css');
mix.minify('public/js/app.js','public/js/app.js');
mix.minify('public/js/admin.js','public/js/admin.js');
*/
if (mix.inProduction()) {
    mix.version();
}
