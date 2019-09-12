<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 			  				'MainController@getBase');
Route::get('/Login', 		  				'MainController@getLogin');
Route::get('/Test', 		  				'MainController@getTest');
Route::get('/Autologin', 		  			'MainController@getAutologin');
Route::get('/Home', 		  				'MainController@getHome');
Route::get('/Home/{section}', 				'MainController@getSection');
Route::get('/Home/{section}/{subsection}',  'MainController@getSubsection');
Route::get('/Frag/{fragment}',  			'MainController@getFragment');
Route::post('/file',  						'MainController@getFile');
Route::post('/phpinfo',  					function(){ echo phpinfo(); });

Route::controller('/api/Main',       	'MainController');
Route::controller('/api/Usuario',       'UsuarioController');
Route::controller('/api/App',       	'AppsController');
Route::controller('/api/Bdds',     		'BddsController');
Route::controller('/api/Entidades',     'EntidadesController');
Route::controller('/api/Variables',     'VariablesController');



Route::get('/phpinfo', function(){
	phpinfo();
});



// Avoid conflicts with AngularJS.
Blade::setContentTags('<%', '%>'); // For variables and all things Blade.
Blade::setEscapedContentTags('<%%', '%%>'); // For escaped data.