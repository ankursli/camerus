let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

const ImageminPlugin = require('imagemin-webpack-plugin').default;
const CopyWebpackPlugin = require('copy-webpack-plugin');
const imageminMozjpeg = require('imagemin-mozjpeg');

const version = 'theme-2023-11-07-003';

mix.setPublicPath('./');

mix.webpackConfig({
    plugins: [
        new CopyWebpackPlugin([{
            from: 'assets/images',
            to: 'dist/images', // Laravel mix will place this in 'public/img'
        }]),
        new CopyWebpackPlugin([{
            from: 'assets/fonts',
            to: 'dist/fonts', // Laravel mix will place this in 'public/img'
        }]),
        new ImageminPlugin({
            test: /\.(jpe?g|png|gif|svg)$/i,
            plugins: [
                imageminMozjpeg({
                    quality: 80,
                })
            ]
        })
    ]
})
    .js('assets/js/theme.js', 'dist/js/' + version + '.min.js')
    .js('assets/js/admin.js', 'dist/js/admin.min.js')
    .sass('assets/sass/style.scss', 'dist/css/' + version + '.css')
    .sass('assets/sass/admin.scss', 'dist/css/admin.css').options({processCssUrls: false})
    //.sass('assets/sass/main.css', 'dist/css/main.css')
    .sass('assets/sass/woocommerce.scss', 'dist/css/woocommerce-' + version + '.css');