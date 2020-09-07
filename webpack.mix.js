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

// mix.browserSync({
//     proxy: 'dev.nadias',
//     open: false,
//     notify: false,
//     files: [
//         'app/**/*.php',
//         'resources/views/**/*.php',
//         // 'packages/mixdinternet/frontend/src/**/*.php',
//         'public/js/**/*.js',
//         'public/css/**/*.css',
//         'config/**/*',
//         'routes/**/*'
//     ],
//     watchOptions: {
//         usePolling: true,
//         interval: 500
//     }
// });

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

if(mix.inProduction()){
    mix.version();
}

mix.browserSync('dev.nadias');