angular.module('ScorecardsCtrl', [])
.controller('ScorecardsCtrl', ['$scope', '$rootScope', '$injector', '$filter',
	function($scope, $rootScope, $injector, $filter) {

		console.info('ScorecardsCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.ScoSel = null;
		Ctrl.ScorecardsNav = true;

		Ctrl.ScorecardsCRUD  = $injector.get('CRUD').config({ base_url: '/api/Indicadores/scorecards' });
		Ctrl.CardsCRUD 		 = $injector.get('CRUD').config({ base_url: '/api/Indicadores/scorecards-cards' });
		Ctrl.IndicadoresCRUD = $injector.get('CRUD').config({ base_url: '/api/Indicadores' });
		Ctrl.VariablesCRUD 	 = $injector.get('CRUD').config({ base_url: '/api/Variables' });

		Ctrl.getScorecards = () => {
			Ctrl.ScorecardsCRUD.get().then(() => {
				Ctrl.openScorecard(Ctrl.ScorecardsCRUD.rows[0]);
				Ctrl.getFs();
			});
		};

		Ctrl.getFs = () => {
			Ctrl.filterScorecards = "";
			Ctrl.ScorecardsFS = Rs.FsGet(Ctrl.ScorecardsCRUD.rows,'Ruta','Titulo');
		};

		Ctrl.getFolderVarData = (F) => {
			var Vars = Ctrl.ScorecardsCRUD.rows.filter((v) => {
				return v.Ruta.startsWith(F.route);
			}).map(v => v.id);
			Rs.getScorecardData(Vars);
		};

		Ctrl.searchScorecard = () => {
			if(Ctrl.filterScorecards == ""){
				Ctrl.getFs();
			}else{
				Ctrl.ScorecardsFS = Rs.FsGet($filter('filter')(Ctrl.ScorecardsCRUD.rows, Ctrl.filterScorecards),'Ruta','Scorecard',true);
			};
		};

		Ctrl.addScorecard = () => {
			Ctrl.getFs();
			Rs.BasicDialog({
				Title: 'Crear Scorecard', Flex: 50,
				Fields: [
					{ Nombre: 'Titulo',  Value: '', Required: true },
					{ Nombre: 'Ruta',    Value: '', flex: 70, Type: 'fsroute', List: Ctrl.ScorecardsFS },
					{ Nombre: 'Crear Carpeta', Value: '', flex: 30, Type: 'string' },
				],
			}).then((r) => {
				if(!r) return;
				var f = Rs.prepFields(r.Fields);
				Ctrl.ScorecardsCRUD.add({
					Ruta: Rs.FsCalcRoute(f.Ruta, f['Crear Carpeta']),
					Titulo: f.Titulo,
					Secciones: []
				}).then(() => {
					Ctrl.getFs();
				});
			});
		};

		Ctrl.openScorecard = (V) => {
			Ctrl.ScoSel = V;
			Ctrl.CardsCRUD.setScope('scorecard', Ctrl.ScoSel.id).get();
			//Rs.viewScorecardDiag(V.id);
		};

		Ctrl.updateScorecard = () => {
			Ctrl.ScorecardsCRUD.update(Ctrl.ScoSel).then(() => {
				Rs.showToast('Scorecard Actualizada', 'Success');
				Ctrl.saveCards();
			});
		};


		//Cards
		Ctrl.addCard = () => {
			Rs.BasicDialog({
				Title: 'Agregar Tarjeta', Flex: 50,
				Fields: [
					{ Nombre: 'Indicador', Value: null, Type: 'list', List: Ctrl.IndicadoresCRUD.rows, Item_Val: 'id', Item_Show: 'Indicador' },
				],
			}).then((r) => {
				if(!r) return;
				var f = Rs.prepFields(r.Fields);
				var Indice = Ctrl.CardsCRUD.rows.length;
				var seccion_id = (Indice == 0) ? null : Ctrl.CardsCRUD.rows[Indice-1].seccion_id;
				Ctrl.CardsCRUD.add({
					Indice: Indice,
					scorecard_id: Ctrl.ScoSel.id,
					seccion_id: seccion_id,
					tipo: 'Indicador', elemento_id: f.Indicador
				});
			});
		};

		Ctrl.saveCards = () => {
			var Updatees = $filter('filter')(Ctrl.CardsCRUD.rows, { changed: true });
			if(Updatees.length == 0) return;
			Ctrl.CardsCRUD.updateMultiple(Updatees);
			angular.forEach(Ctrl.CardsCRUD.rows, C => {
				C.changed = false;
			});
		};

		Ctrl.delCard = (C) => {
			Ctrl.CardsCRUD.delete(C);
		};





		Promise.all([Ctrl.IndicadoresCRUD.get(), Ctrl.VariablesCRUD.get()]).then(values => { 
			Ctrl.getScorecards();
		});
		

		
	}
]);