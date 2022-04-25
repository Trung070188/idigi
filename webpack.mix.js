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

let dirmap = s => {
    s = 'public' +s;
    return s;
};

var scripts = [

].map(dirmap);

var styles = [

].map(dirmap);

mix.scripts(scripts, 'public/assets/js/all.min.js');
mix.styles(styles, 'public/assets/css/all.min.css');
mix.js('resources/js/uploadFile', 'public/js/admin');

mix.js('resources/js/app.js', 'public/assets/js').vue({ version: 2 })
   .sass('resources/sass/app.scss', 'public/assets/css', [

   ]);
