const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .js('resources/js/ruangan/create.js', 'public/js/ruangan')
   .postCss('resources/css/app.css', 'public/css')
   .postCss('resources/css/ruangan/create.css', 'public/css/ruangan'); 