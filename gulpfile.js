var elixir = require('laravel-elixir');
elixir.config.sourcemaps = false;
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */
elixir(function (mix) {
  mix
    .less('App.less', 'resources/assets/less')
    .styles([
      '../less/App.css',
    ], 'public/css/all.min.css')
    .scripts([

      'MainCtrl.js',

      'controllers/**/*.js',

      'services/**/*.js',
      'filters/**/*.js',
      'directives/**/*.js',
      'components/**/*.js',
      
      '*.js',

    ], 'public/js/app.min.js')
    .version(['public/css/all.min.css', 'public/js/app.min.js']);
    
});