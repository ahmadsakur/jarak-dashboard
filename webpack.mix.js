require("dotenv").config(); // Load environment variables
const mix = require("laravel-mix");
const Dotenv = require("dotenv-webpack");

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
            new Dotenv(), // Load environment variables into webpack
        ],
    });
