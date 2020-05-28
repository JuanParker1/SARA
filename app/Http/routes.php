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
Route::get('/a', 		  					'MainController@getApp');

Route::controller('/api/Main',       	'MainController');
Route::controller('/api/Usuario',       'UsuarioController');
Route::controller('/api/App',       	'AppsController');
Route::controller('/api/Bdds',     		'BddsController');
Route::controller('/api/Entidades',     'EntidadesController');
Route::controller('/api/Variables',     'VariablesController');
Route::controller('/api/Indicadores',   'IndicadoresController');
Route::controller('/api/Scorecards',   	'ScorecardsController');
Route::controller('/api/Procesos',   	'ProcesosController');
Route::controller('/api/ConsultasSQL',  'ConsultasSQLController');



Route::get('/phpinfo', function(){ phpinfo(); });

Route::get('/testconn', function(){ 

	//$db = connection();

	//$p = \DB::select('select * from `sara_scorecards_nodos` where `scorecard_id` = ? limit 1000', [10]);

	$p = \App\Models\ScorecardNodo::scorecard(10)->get();

	return $p;

});



// Avoid conflicts with AngularJS.
Blade::setContentTags('<%', '%>'); // For variables and all things Blade.
Blade::setEscapedContentTags('<%%', '%%>'); // For escaped data.