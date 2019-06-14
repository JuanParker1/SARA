angular.module('LoginCtrl', [])
.controller('LoginCtrl', ['$scope', '$rootScope', '$http', '$localStorage', '$mdToast', '$state', 
	function($scope, $rootScope, $http, $localStorage, $mdToast, $state) {

		console.info('LoginCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
	
		delete $localStorage.token;

		Ctrl.User = '';
		Ctrl.Pass = '';

		Ctrl.ShowErr = function(Msg, Delay){
			var errTxt = '<md-toast class="md-toast-error"><span flex>' + Msg + '<span></md-toast>';
			$mdToast.show({
				template: errTxt,
				hideDelay: Delay
			});
		}

		Ctrl.Login = function(){
			$http.post('api/Usuario/login', { Email: Ctrl.User, Pass: Ctrl.Pass }).then(function(r){
				var token = r.data;
				$localStorage.token = token;
				$state.go('Home');
			}, function(r){
				Ctrl.ShowErr(r.data.Msg); 
				Ctrl.Pass = '';
			});
		};
	}
]);