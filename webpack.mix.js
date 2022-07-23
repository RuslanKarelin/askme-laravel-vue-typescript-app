const mix = require('laravel-mix');

mix.webpackConfig({
    stats: {
        children: true,
    },});

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


mix.ts('resources/js/app/components/questions/on-main/main.ts', 'public/js/app/components/questions-on-main.js')
    .vue();
mix.ts('resources/js/app/components/answers/main.ts', 'public/js/app/components/answers-list.js')
    .vue();
mix.ts('resources/js/app/components/like/main.ts', 'public/js/app/components/like.js')
    .vue();

