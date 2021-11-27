const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js').vue();
mix.sass('resources/sass/app.scss', 'public/css');
mix.extract();

mix.webpackConfig({
    // For vue pug template
    module: {
        rules: [
            {
                test: /\.pug$/,
                oneOf: [
                    {
                        resourceQuery: /^\?vue/,
                        use: ['pug-plain-loader'],
                    },
                    {
                        use: ['raw-loader', 'pug-plain-loader'],
                    },
                ],
            },
        ],
    },
    output: {
        publicPath: '/',
        chunkFilename: 'js/[id].[chunkhash].js',
    },
    optimization: {
        providedExports: false,
        sideEffects: false,
        usedExports: false,
    },
});

if (mix.inProduction()) {
    mix.version();
}
