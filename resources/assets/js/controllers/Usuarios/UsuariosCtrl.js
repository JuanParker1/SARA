angular.module('UsuariosCtrl', [])
.controller('UsuariosCtrl', ['$scope', '$rootScope', '$http', '$injector', '$mdDialog', 
	function($scope, $rootScope, $http, $injector, $mdDialog) {

		console.info('UsuariosCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Rs.mainTheme = 'Snow_White';

		Ctrl.Sections = {
			Usuarios: [ 'Usuarios' ],
			Perfiles: [ 'Perfiles' ],
			Retroalimentacion: [ 'Retroalimentación' ],
		};

		//Usuarios
		Ctrl.UsuariosCRUD = $injector.get('CRUD').config({ 
			base_url: '/api/Usuario/usuarios'
		});

		Ctrl.orderBy = 'Nombres';

		Ctrl.getUsuarios = () => {
			Ctrl.UsuariosCRUD.get().then(() => {
				
			});
		};

		if(Rs.State.route.length == 3){
			Rs.navTo('Home.Section.Subsection', { subsection: 'Usuarios' });
		};

		Ctrl.getUsuarios();

	}
]);