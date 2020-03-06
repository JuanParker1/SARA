angular.module('InicioCtrl', [])
.controller('InicioCtrl', ['$scope', '$rootScope', 
	function($scope, $rootScope) {

		console.info('InicioCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Rs.mainTheme = 'Snow_White';
		//Rs.mainTheme = 'Black';
		
		Ctrl.makeFavorite = (A,make) => {
			A.favorito = make;
			Rs.http('api/App/favorito', { usuario_id: Rs.Usuario.id, app_id: A.id, favorito: make });
		};

		var HoraDelDia = parseInt(moment().format('H'));
			 if(HoraDelDia < 7){ Rs.Saludo = 'Hola'; Rs.mainTheme = 'Black'; }
		else if(HoraDelDia >= 7 && HoraDelDia < 12){ Rs.Saludo = 'Buenos días'; }
		else if(HoraDelDia >= 12 && HoraDelDia < 18){ Rs.Saludo = 'Buenas tardes'; }
		else{ Rs.Saludo = 'Buenas noches'; Rs.mainTheme = 'Black'; }
	}
]);