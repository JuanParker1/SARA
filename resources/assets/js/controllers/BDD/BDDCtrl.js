angular.module('BDDCtrl', [])
.controller('BDDCtrl', ['$scope', '$rootScope', '$injector',
	function($scope, $rootScope, $injector) {

		console.info('BDDCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Rs.mainTheme = 'Snow_White';
		Ctrl.BDDSidenav = true;
		Ctrl.BDDFavSidenav = false;

		Ctrl.BDDsCRUD = $injector.get('CRUD').config({ base_url: '/api/Bdds' });

		Ctrl.BDDsCRUD.get().then(() => {
			if(Ctrl.BDDsCRUD.rows.length > 0){
				Ctrl.openBDD(Ctrl.BDDsCRUD.rows[0]);
			};
		});

		Ctrl.openBDD = (B) => {
			Ctrl.BDDSel = B;
			Ctrl.getFavoritos();
			//Ctrl.executeQuery(); //REmove
		};

		Ctrl.TiposBDD = {
			ODBC_DB2:     { Op1: 'DSN', Op2: 'Servidor', 		Op3: 'Base de Datos', Op4: false, Op5: false },
			ODBC_MySQL:   { Op1: 'DSN', Op2: 'Servidor', 		Op3: 'Base de Datos', Op4: false, Op5: false },
			MySQL:  	  { Op1: false, Op2: 'Servidor', 		Op3: 'Base de Datos', Op4: false, Op5: false },
			SQLite: 	  { Op1: false, Op2: 'Ruta al Archivo', Op3: 'Base de Datos', Op4: false, Op5: false },
		};

		Ctrl.addBDD = () => {
			Rs.BasicDialog({
				Title: 'Crear Conexión a Base de Datos'
			}).then((r) => {
				var Nombre = r.Fields[0].Value.trim();
				if(Rs.found(Nombre, Ctrl.BDDsCRUD.rows, 'Nombre')) return;

				Ctrl.BDDsCRUD.add({
					Nombre: Nombre,
					Tipo: 'ODBC'
				});
			});
		};

		Ctrl.updateBDD = () => {
			Ctrl.BDDsCRUD.update(Ctrl.BDDSel).then(() => {
				Rs.showToast('Actualizado', 'Success', 5000, 'bottom right');
			});
		};

		Ctrl.removeBDD = () => {
			Rs.confirmDelete({
				Title: '¿Borrar la Conexión a la Base de Datos "'+Ctrl.BDDSel.Nombre+'"?'
			}).then((del) => {
				if(!del) return;
				Ctrl.BDDsCRUD.delete(Ctrl.BDDSel).then(() => {
					Ctrl.BDDSel = null;
				});
			});
		};

		Ctrl.testBDD = () => {
			Rs.http('/api/Bdds/probar', { BDD: Ctrl.BDDSel }).then((r) => {
				Rs.showToast('Conexión Exitosa', 'Success', 5000, 'bottom right');
			});
		};

		//Panel de Consultas SQL
		Ctrl.SQLQuery = "";
		Ctrl.executingQuery = false;
		Ctrl.QueryRows = null;
		Ctrl.executeQuery = () => {
			if(Ctrl.SQLQuery == "" || Ctrl.executingQuery) return;
			Ctrl.executingQuery = true;

			Rs.http('/api/Bdds/query', { BDD: Ctrl.BDDSel, Query: Ctrl.SQLQuery }).then((r) => {
				Ctrl.QueryRows = r;
			}).finally(() => {
				Ctrl.executingQuery = false;
			});
		};



		//Panel de Favoritos
		Ctrl.FavsCRUD = $injector.get('CRUD').config({ 
			base_url: '/api/Bdds/favoritos',
			query_scopes: [
				[ 'mine', true ]
			]
		});

		Ctrl.getFavoritos = () => {
			Ctrl.FavsCRUD.setScope('bddid', Ctrl.BDDSel.id);
			Ctrl.FavsCRUD.get();
		};

		Ctrl.useFav = (F) => {
			if(Ctrl.executingQuery) return;

			Ctrl.SQLQuery = F.Consulta;

			if(F.EjecutarAutom == 'S'){
				Ctrl.executeQuery();
			};
		};

		Ctrl.addFav = () => {
			Ctrl.FavsCRUD.dialog({
				Consulta: angular.copy(Ctrl.SQLQuery),
				EjecutarAutom: 'N',
				bdd_id: Ctrl.BDDSel.id,
				usuario_id: Rs.Usuario.id
			}, {
				title: 'Crear Favorito',
				only: [ 'Nombre', 'Consulta', 'EjecutarAutom' ]
			}).then((R) => {
				if(!R) return;
				Ctrl.FavsCRUD.add(R);
			});
		};

		Ctrl.editFav = (F) => {
			Ctrl.FavsCRUD.dialog(angular.copy(F), {
				title: 'Favorito: ' + F.Nombre,
				only: [ 'Nombre', 'Consulta', 'EjecutarAutom' ]
			}).then((R) => {
				if(!R) return;
				if(R == 'DELETE') return Ctrl.FavsCRUD.delete(F);
				Ctrl.FavsCRUD.update(R);
			});
		};

	}
]);