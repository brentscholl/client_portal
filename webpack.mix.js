const mix = require("laravel-mix");
const tailwindcss = require('tailwindcss');

require("laravel-mix-tailwind");
require('laravel-mix-purgecss');

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

mix.js("resources/js/app.js", "public/js/app.js")
    .sass("resources/sass/app.scss", "public/css/app.css")
    .tailwind("./tailwind.config.js")
    .scripts([
        'public/js/app.js',
        'resources/js/tribute.js',
        // 'resources/js/moment.js',
        // 'resources/js/pikaday.js',
        // 'resources/js/popper.js',
        // 'resources/js/tippy.js',
    ], 'public/js/all.js')
    .sourceMaps();


// ***** UNCOMMENT THIS IF COMPILING EMAIL TEMPLATE *************
// mix.setPublicPath('resources/')
//     .sass("resources/sass/email-theme.scss", "./resources/views/vendor/mail/html/themes/theme.css")
//     .options({
//         processCssUrls: false,
//         postCss: [ tailwindcss('./tailwind.config.js') ],
//     })
//     .purgeCss({
//         content: [
//             path.join(__dirname, 'resources/views/vendor/mail/**/*.php'),
//             path.join(__dirname, 'resources/sass/email-theme.scss'),
//             path.join(__dirname, 'resources/sass/email-styles.scss'),
//         ],
//         defaultExtractor: content => content.match(/[\w-/:]+(?<!:)/g) || [],
//         enabled: true,
//     });

if (mix.inProduction()) {
    mix.version();
}
