require('dotenv').load();
var elixir = require('laravel-elixir');
var BrowserSync = require('laravel-elixir-browsersync2');

elixir(function(mix) {
    BrowserSync.init();

    mix.sass('app.scss')
        .browserify('app.js')
        .version([
            'css/app.css',
            'js/app.js',
        ])
        .BrowserSync({
            proxy: process.env.APP_URL,
            files: [
                'app/**/*',
                'public/assets/**/*',
                'resources/langs/**/*',
                'resources/views/**/*',
            ],
            reloadDelay: 1000
        });
});
