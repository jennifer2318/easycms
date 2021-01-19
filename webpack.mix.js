const mix = require('laravel-mix');

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

mix.config.babelConfig =
{
    "plugins": [
        ["@babel/plugin-proposal-decorators", { "legacy": true },

            //Minify
            "@babel/plugin-transform-minify-booleans",
            "@babel/plugin-minify-builtins",
            "@babel/plugin-minify-dead-code-elimination", { "optimizeRawSize": true },
            "@babel/plugin-minify-mangle-names",
            "@babel/plugin-minify-numeric-literals",
            "@babel/plugin-minify-simplify",
            "@babel/plugin-transform-simplify-comparison-operators",
            "@babel/plugin-minify-type-constructors",
            "@babel/plugin-transform-member-expression-literals",

            //Experimental
            "@babel/plugin-proposal-nullish-coalescing-operator",
        ],
        // ["@babel/plugin-transform-minify-booleans"],
        // ["minify-builtins"],
        // ["minify-dead-code-elimination", { "optimizeRawSize": true }],
        // ["minify-mangle-names"],
        // ["minify-numeric-literals"],
        // ["minify-simplify"],
        // ["transform-simplify-comparison-operators"],
    ]
};

mix.react('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');
