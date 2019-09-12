angular.module('appRoutes', [])
.config(['$stateProvider', '$urlRouterProvider', '$httpProvider', 
	function($stateProvider, $urlRouterProvider, $httpProvider){
	
			$stateProvider
					.state('Login', {
						url: '/Login',
						templateUrl: '/Login',
						controller: 'LoginCtrl',
					})
					.state('Home', {
						url: '/Home',
						templateUrl: '/Home',
						controller: 'MainCtrl',
						resolve: {
							promiseObj:  function($rootScope, $localStorage, $http){

								var Rs = $rootScope;
								Rs.Storage = $localStorage;

								return $http.post('api/Usuario/check-token', { token: Rs.Storage.token });
							},
							promiseObj2:  function($http){
								return $http.post('api/Entidades/tipos-campo', {});
							},
							controller: function($rootScope, $localStorage, promiseObj,promiseObj2){
								var Rs = $rootScope;
								Rs.Usuario 		= promiseObj.data;
								Rs.TiposCampo 	= promiseObj2.data;
								$localStorage.token = Rs.Usuario.token;
							}
						},
					})
					.state('Home.Section', {
						url: '/:section',
						templateUrl: function (params) { return '/Home/'+params.section; },
					})
					.state('Home.Section.Subsection', {
						url: '/:subsection',
						templateUrl: function (params) { return '/Home/'+params.section+'/'+params.subsection; },
					});

			$urlRouterProvider.otherwise('/Home');
			

			$httpProvider.interceptors.push(['$q', '$localStorage', 
				function ($q, $localStorage) {
					return {
						request: function (config) {
							config.headers = config.headers || {};
							if ($localStorage.token) {
								config.headers.token = $localStorage.token;
							}
							//console.log(config);
							//config.url = '/app/public/index.php' + config.url;
							return config;
						},
						response: function (res) {
							return res || $q.when(res);
						},
						responseError: function(rejection) {
						  // do something on error

						  if ([400, 401].indexOf(rejection.status) !== -1) {
							// Handle unauthenticated user.
							//location.reload();
							
							//var r = confirm("Su sesión expiró, por favor ingrese nuevamente");
							location.replace("/#/Login");
						  }

						  return $q.reject(rejection);
						}

					};
				}
			]);
	}
]);