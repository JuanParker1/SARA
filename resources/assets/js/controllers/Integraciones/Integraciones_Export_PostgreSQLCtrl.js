angular.module('Integraciones_Export_PostgreSQLCtrl', [])
.controller('Integraciones_Export_PostgreSQLCtrl', ['$scope', '$rootScope', '$http', '$injector', 
	function($scope, $rootScope, $http, $injector) {

		console.info('Integraciones_Export_PostgreSQLCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.EntidadesCRUD 		= $injector.get('CRUD').config({ base_url: '/api/Entidades', order_by: ['Nombre'] });

		Ctrl.filters = {
			Database: 'dbsara',
			DatabaseOp: 'create',
			Schema: 'public',
		};

		Ctrl.getEntidades = () => {
			Ctrl.EntidadesCRUD.setScope('Tipo', 'Tabla').get().then(() => {
				angular.forEach(Ctrl.EntidadesCRUD.rows, (E,k) => {
					E.selected 	 = ([241,242]).includes(E.id);
					E.estructura = true;
					E.datos 	 = true;
				});

				Ctrl.allSelected = true;
				Ctrl.allEstructura = true;
				Ctrl.allDatos = true;
			});
		};

		Ctrl.markAll = (attribute, value) => {
			angular.forEach(Ctrl.EntidadesCRUD.rows, E => {
				E[attribute] 	 = value;
			});
		};

		Ctrl.runExport = () => {

			let Entidades = Ctrl.EntidadesCRUD.rows.filter(E => {
				return ( E.selected && ( E.estructura || E.datos ) );
			});

			if(Entidades.length == 0) return Rs.showToast('No hay Elementos seleccionados', 'Error');

			Rs.CodeDialog('', {
				Title: `SQL para exportar a PostgreSQL`,
				Language: 'sql',
				Loading: true,
				onComplete: (scope, element) => {
					Rs.http('api/Integraciones/export-postgresql', { Entidades, filters: Ctrl.filters }).then(r => {
						scope.setCode(r.sql);
						scope.updateConfig({
							Loading: false
						});
					});
				}
			});
		};


		Ctrl.getEntidades();
		
	}
]);