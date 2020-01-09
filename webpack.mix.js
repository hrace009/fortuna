const path = require('path');
const mix = require('laravel-mix');
const webpack = require('webpack');
require('laravel-mix-versionhash');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;
const supportedLocales = ['en', 'pt'];

// Fonts
mix.styles([
    'resources/vendors/fontawesome.css',
    'resources/vendors/flaticon/css/flaticon.css',
    'resources/vendors/flaticon2/flaticon.css',
    'resources/vendors/line-awesome/css/line-awesome.css',
    'resources/vendors/metronic/css/styles.css',
], 'public/css/fonts.bundle.css');

// User
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.webpackConfig({
    plugins: [
        // new BundleAnalyzerPlugin()
        new webpack.ContextReplacementPlugin(
            /date\-fns[\/\\]/,
            new RegExp(`[/\\\\\](${supportedLocales.join('|')})[/\\\\\]`)
        )
    ],
    resolve: {
        extensions: ['.js', '.vue'],
        alias: {
            '~': path.join(__dirname, './resources/js'),
        },
    },
    output: {
        chunkFilename: 'js/[chunkhash].js',
        publicPath: '/',
    },
});

if (mix.inProduction()) {
    mix.versionHash()
} else {
    mix.sourceMaps();
}
