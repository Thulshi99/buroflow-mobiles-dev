const mix = require('laravel-mix')

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ])
    .version()

if (mix.inProduction()) {
    mix.version();
}
if (!mix.inProduction()) {
    mix.browserSync({
        host: 'localhost',
        proxy: 'localhost:8000',
        port: 8000,
        open: false
    });
}
