const { mix } = require('laravel-mix');

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

 mix.webpackConfig({
   resolve: {
      extensions: [".js", ".css", ".json"]
   }
 });

mix.autoload({
   'jquery': ['$', 'window.jQuery', 'jQuery'],
   'jquery-timepicker': 'jquery-timepicker'
});

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .styles([
      'resources/assets/styles/login-box.css',
      'resources/assets/styles/info-box.css',
      'resources/assets/styles/raffles.css'
   ], 'public/css/all.css')
   .extract([
      'jquery-ui',
      'jquery-validation',
      'moment'
   ], 'public/js/vendor.js')
   .copy('resources/assets/js/class/*.*', 'public/js/class')
   .version();