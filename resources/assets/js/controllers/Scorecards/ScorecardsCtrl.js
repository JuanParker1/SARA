angular.module('ScorecardsCtrl', [])
.controller('ScorecardsCtrl', ['$scope', '$rootScope', '$injector', '$filter',
	function($scope, $rootScope, $injector, $filter) {

		console.info('ScorecardsCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.ScoSel = null;
		Ctrl.ScorecardsNav = true;
		Rs.mainTheme = 'Snow_White';
		Ctrl.ScorecardsCRUD  = $injector.get('CRUD').config({ base_url: '/api/Scorecards' });
		Ctrl.CardsCRUD 		 = $injector.get('CRUD').config({ base_url: '/api/Scorecards/cards' });
		Ctrl.NodosCRUD 		 = $injector.get('CRUD').config({ base_url: '/api/Scorecards/nodos', query_call: [['getRuta',null]] });
		Ctrl.IndicadoresCRUD = $injector.get('CRUD').config({ base_url: '/api/Indicadores' });
		Ctrl.VariablesCRUD 	 = $injector.get('CRUD').config({ base_url: '/api/Variables' });

		Ctrl.getScorecards = () => {
			Ctrl.ScorecardsCRUD.get().then(() => {
				Ctrl.openScorecard(Ctrl.ScorecardsCRUD.rows[0]);
				//Ctrl.getFs();
			});
		};

		Ctrl.getFs = () => {
			Ctrl.filterScorecards = "";
			Ctrl.NodosFS = Rs.FsGet(Ctrl.NodosCRUD.rows,'Ruta','Nodo',false,true);
			angular.forEach(Ctrl.NodosFS, (F) => {
				if(F.type == 'folder'){
					F.file = Ctrl.NodosCRUD.rows.filter(N => { return ( N.tipo == 'Nodo' && N.Ruta == F.route ) })[0];
				};
			});
		};

		Ctrl.addNodo = (NodoPadre) => {
			var Nodos = Ctrl.NodosCRUD.rows.filter(N => { return N.tipo == 'Nodo' });
			Rs.BasicDialog({
				Title: 'Crear Nodo', Flex: 50,
				Fields: [
					{ Nombre: 'Nombre',  Value: '',   			Required: true, flex: 60 },
					{ Nombre: 'Peso',    Value: 1,    			Required: true, flex: 10, Type: 'number' },
					{ Nombre: 'Padre',   Value: NodoPadre.id, 	Required: true, flex: 30, Type: 'list', List: Nodos, Item_Val: 'id', Item_Show: 'Nodo' },
				],
			}).then((r) => {
				if(!r) return;
				var f = Rs.prepFields(r.Fields);
				Ctrl.NodosCRUD.add({
					scorecard_id: Ctrl.ScoSel.id, Nodo: f.Nombre, padre_id: f.Padre, Indice: 0, tipo: 'Nodo', peso: f.Peso 
				}).then(() => {
					Ctrl.getFs();
				});
			});
		};

		Ctrl.openNodo = (Nodo) => {
			Ctrl.NodoSel = Nodo;
			Ctrl.NodoSel.indicadores = Ctrl.NodosCRUD.rows.filter(N => { return (N.tipo !== 'Nodo' && N.padre_id == Nodo.id) });
		};

		Ctrl.addIndicador = () => {
			Rs.BasicDialog({
				Title: 'Agregar Indicador', Flex: 50,
				Fields: [
					{ Nombre: 'Indicador', Value:null, Required: true, flex: 90, Type: 'autocomplete', 
					opts: {
						itemsFn: (text) => { return Ctrl.IndicadoresCRUD.rows; },
						itemDisplay: (item) => { return item.Indicador }, itemText: 'Indicador',
						minLength: 0, delay: 300, itemVal: false
					}},
					{ Nombre: 'Peso',    Value: 1,    			Required: true, flex: 10, Type: 'number' }
				],
			}).then(r => {
				if(!r) return;
				var f = Rs.prepFields(r.Fields);
				var Indice = Ctrl.NodoSel.indicadores.length;
				Ctrl.NodosCRUD.add({
					scorecard_id: Ctrl.ScoSel.id, Nodo: null, padre_id: Ctrl.NodoSel.id, Indice: Indice, tipo: 'Indicador', elemento_id: f.Indicador.id, peso: f.Peso 
				}).then(() => {
					Ctrl.openNodo(Ctrl.NodoSel);
					Ctrl.getFs();
				});
			});
		};

		Ctrl.searchScorecard = () => {
			if(Ctrl.filterScorecards == ""){
				Ctrl.getFs();
			}else{
				Ctrl.ScorecardsFS = Rs.FsGet($filter('filter')(Ctrl.ScorecardsCRUD.rows, Ctrl.filterScorecards),'Ruta','Scorecard',true);
			};
		};

		Ctrl.addScorecard = () => {
			Rs.BasicDialog({
				Title: 'Crear Scorecard', Flex: 50,
				Fields: [
					{ Nombre: 'Titulo',  		Value: '', Required: true },
					{ Nombre: 'Primer Nodo',    Value: '', Required: true },
				],
			}).then((r) => {
				if(!r) return;
				var f = Rs.prepFields(r.Fields);
				console.log(f);
				Ctrl.ScorecardsCRUD.add({ Titulo: f.Titulo, Secciones: [] }).then(r => {
					Ctrl.NodosCRUD.add({ scorecard_id: r.id, Nodo: r.Titulo, tipo: 'Nodo' }).then(n => {
						Ctrl.NodosCRUD.add({ scorecard_id: r.id, Nodo: f['Primer Nodo'], tipo: 'Nodo', padre_id: n.id }).then(() => {
							Rs.showToast('Scorecard Creado');
						});
					});
				});
			});
		};

		Ctrl.openScorecard = (V, Nodo) => {
			Ctrl.ScoSel = V;
			Ctrl.NodoSel = Rs.def(Nodo, null);
			Ctrl.NodosCRUD.setScope('scorecard', Ctrl.ScoSel.id).get().then(() => {
				Ctrl.getFs();
			});
		};

		Ctrl.updateScorecard = () => {

			if(Ctrl.ScoSel.changed){  
				Ctrl.ScorecardsCRUD.update(Ctrl.ScoSel); 
				Rs.showToast('Scorecard Actualizado', 'Success');
				Ctrl.ScoSel.changed = false;
			}

			if(Ctrl.NodoSel.changed){ 
				Ctrl.NodosCRUD.update(Ctrl.NodoSel).then(() => { Ctrl.openScorecard(Ctrl.ScoSel, Ctrl.NodoSel); }); 
				Rs.showToast('Nodo Actualizado', 'Success');
				Ctrl.NodoSel.changed = false; 
			}

			var IndicadoresChanged = Ctrl.NodoSel.indicadores.filter(i => { return (i.changed == true); });
			if(IndicadoresChanged.length > 0){
				Ctrl.NodosCRUD.updateMultiple(IndicadoresChanged).then(() => {
					Rs.showToast('Indicadores Actualizados', 'Success');
					angular.forEach(Ctrl.NodoSel.indicadores, I => {
						I.changed = false;
					});
				});
			}
			
			/*Ctrl.ScorecardsCRUD.update(Ctrl.ScoSel).then(() => {
				Rs.showToast('Scorecard Actualizada', 'Success');
				Ctrl.saveCards();
			});*/
		};

		Ctrl.delIndicador = (I) => {
			Ctrl.NodosCRUD.delete(I).then(() => {
				Ctrl.openNodo(Ctrl.NodoSel);
			});

		}


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


		Ctrl.getProcesos = () => {
			return Rs.http('api/Procesos', {}, Ctrl, 'Procesos');
		};


		Promise.all([Ctrl.IndicadoresCRUD.get(), Ctrl.VariablesCRUD.get(), Ctrl.getProcesos()]).then(values => { 
			Ctrl.getScorecards();
		});
		

		
	}
]);