const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/index.js', 'public/js')
    .js('resources/js/bot-log.js', 'public/js')
    .js('resources/js/bot-test.js', 'public/js')
    .js('resources/js/bot-verify.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    
mix.copy('node_modules/font-awesome/fonts', 'public/fonts/font-awesome');
mix.copy('resources/data', 'public/data');
mix.copy('resources/images', 'public/images');
mix.setResourceRoot('../');