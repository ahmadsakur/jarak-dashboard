require("dotenv").config(); // Load environment variables
const mix = require("laravel-mix");
const webpack = require('webpack');

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

mix.js(
    ["resources/js/app.js", "resources/js/bootstrap.js"],
    "public/assets/js/app.js"
)
    .sass(
        "resources/scss/argon-dashboard.scss",
        "public/assets/css/argon-dashboard.css",
        [
            //
        ]
    )
    .disableNotifications()
    .webpackConfig({
        plugins: [
            new webpack.DefinePlugin({
                "process.env": {
                    PUSHER_APP_KEY: JSON.stringify(process.env.MIX_PUSHER_APP_KEY),
                },
            }),
        ],
    });
