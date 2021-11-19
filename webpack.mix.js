const mix = require('laravel-mix');

mix.react('resources/js/app.js', 'public/assets/js')
    .sass('resources/scss/app.scss', 'public/assets/css');