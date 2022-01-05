const mix = require('laravel-mix');
require('laravel-mix-workbox');

mix
    .js('resources/js/app.js', 'public/js')
    .react('resources/js/appreact.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .generateSW();
