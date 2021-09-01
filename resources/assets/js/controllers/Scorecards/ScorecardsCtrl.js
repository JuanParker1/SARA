angular.module('ScorecardsCtrl', [])
.controller('ScorecardsCtrl', ['$scope', '$rootScope', '$injector', '$filter', '$timeout', '$mdDialog', 
	function($scope, $rootScope, $injector, $filter, $timeout, $mdDialog) {

		console.info('ScorecardsCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.ScoSel = null;
		Ctrl.ScorecardsNav = true;
		Rs.mainTheme = 'Snow_White';
		Ctrl.ScorecardsCRUD  = $injector.get('CRUD').config({ base_url: '/api/Scorecards' });
		Ctrl.NodosCRUD 		 = $injector.get('CRUD').config({ base_url: '/api/Scorecards/nodos', query_call_arr: [['getElementos',null],['getRutas',null]] });
		Ctrl.IndicadoresCRUD = $injector.get('CRUD').config({ base_url: '/api/Indicadores' });
		Ctrl.VariablesCRUD 	 = $injector.get('CRUD').config({ base_url: '/api/Variables' });
		Ctrl.NodosSelected = [];
		Ctrl.newNodoName = '';

		Ctrl.getScorecards = () => {
			Ctrl.ScorecardsCRUD.get().then(() => {
				
				if(Rs.Storage.ScorecardSel){
					var scorecard_sel_id = Rs.getIndex(Ctrl.ScorecardsCRUD.rows, Rs.Storage.ScorecardSel);
					Ctrl.openScorecard(Ctrl.ScorecardsCRUD.rows[scorecard_sel_id]);
				};
				//Ctrl.getFs();
			});
		};

		Ctrl.getFs = () => {
			Ctrl.filterScorecards = "";

			let Nodos = angular.copy(Ctrl.NodosCRUD.rows).filter(N => N.tipo == 'Nodo');
			let NodoPrincipal = Nodos.find(N => N.padre_id == null);

			angular.forEach(Nodos, N => {
				if(N.padre_id == null){ N.Ruta = NodoPrincipal.Nodo }
				else{ N.Ruta = NodoPrincipal.Nodo +"\\"+ N.Ruta; }
			});

			Ctrl.NodosFS = Rs.FsGet(Nodos,'Ruta','Nodo',false,true,false);
			angular.forEach(Ctrl.NodosFS, (F) => {
				if(F.type == 'folder'){
					F.file = Nodos.find(N => { return (N.Ruta.trim() == F.route.trim()) });
					//F.file = Ctrl.NodosCRUD.rows.filter(N => { return ( N.tipo == 'Nodo' && N.Ruta == F.route ) })[0];
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
					scorecard_id: Ctrl.ScoSel.id, Nodo: f.Nombre, padre_id: f.Padre, Indice: Ctrl.NodoSel.subnodos.length, tipo: 'Nodo', peso: f.Peso 
				}).then(() => {
					Ctrl.openNodo(Ctrl.NodoSel);
				});
			});
		};

		Ctrl.addNewNodo = () => {
			if(Ctrl.newNodoName.trim() == '') return;
			Ctrl.NodosCRUD.add({
				scorecard_id: Ctrl.ScoSel.id, Nodo: Ctrl.newNodoName, padre_id: Ctrl.NodoSel.id, Indice: (Ctrl.NodoSel.subnodos.length + 1), tipo: 'Nodo', peso: 1 
			}).then(() => {
				Ctrl.getFs();
				Ctrl.openNodo(Ctrl.NodoSel);
			});
		}

		Ctrl.deleteScorecardNodo = () => {
			if(Ctrl.NodoSel.indicadores.length > 0 || Ctrl.NodoSel.subnodos.length > 0 ) return Rs.showToast('Solo se pueden eliminar nodos vacíos', 'Error');
			Ctrl.NodosCRUD.delete(Ctrl.NodoSel).then(() => {
				Ctrl.openScorecard(Ctrl.ScoSel);
			});
		}

		Ctrl.openNodo = (Nodo) => {
			
			Ctrl.NodoSel = Nodo;
			Ctrl.NodoSel.indicadores = $filter('orderBy')(Ctrl.NodosCRUD.rows.filter(N => { return (N.tipo !== 'Nodo' && N.padre_id == Nodo.id) }), 'Indice');
			Ctrl.NodoSel.subnodos    = Ctrl.NodosCRUD.rows.filter(N => { return (N.tipo ==  'Nodo'      && N.padre_id == Nodo.id) });
			Ctrl.NodosSelected = [];
			Ctrl.newNodoName = '';
			//Rs.viewScorecardDiag(Ctrl.ScoSel.id); //FIX

		};

		Ctrl.addIndicador = () => {

			var indicadores_ids = Ctrl.NodosCRUD.rows.filter(n => n.tipo == 'Indicador').map(n => n.elemento_id);
			var Indicadores = Ctrl.IndicadoresCRUD.rows.filter(i => !Rs.inArray(i.id, indicadores_ids) );

			//return console.log(indicadores_ids);

			Rs.TableDialog(Indicadores, {
				Title: 'Seleccionar Indicadores', Flex: 60, 
				Columns: [
					{ Nombre: 'proceso.Proceso',  Desc: 'Nodo',       numeric: false, orderBy: 'Ruta' },
					{ Nombre: 'Indicador', 	 	  Desc: 'Indicador',  numeric: false, orderBy: 'Indicador' },
					{ Nombre: 'proceso.Tipo',     Desc: 'Tipo Nodo',  numeric: false, orderBy: false },
					{ Nombre: 'updated_at',       Desc: 'Actualizado',  numeric: false, orderBy: 'updated_at' },
				],
				orderBy: 'Ruta', select: 'Row.id'
			}).then(Selected => {
				if(!Selected || Selected.length == 0 ) return;
				var Indice = Ctrl.NodoSel.indicadores.length + 1;
				Selected = Selected.map(indicador_id => {
					return {
						scorecard_id: Ctrl.ScoSel.id, 
						Nodo: null, padre_id: Ctrl.NodoSel.id, 
						Indice: Indice++, tipo: 'Indicador', elemento_id: indicador_id, peso: 1 
					}
				});

				Ctrl.NodosCRUD.addMultiple(Selected).then(() => {
					Ctrl.openNodo(Ctrl.NodoSel);
				});
			});
		};

		Ctrl.addVariable = () => {
			Rs.BasicDialog({
				Title: 'Agregar Variable', Flex: 50,
				Fields: [
					{ Nombre: 'Variable', Value:null, Required: true, flex: 90, Type: 'autocomplete', 
					opts: {
						itemsFn: (text) => { return $filter('filter')(Ctrl.VariablesCRUD.rows, { Variable: text }); },
						itemDisplay: (item) => { return item.Variable }, itemText: 'Variable',
						minLength: 0, delay: 300, itemVal: false
					}},
					{ Nombre: 'Peso',    Value: 1,    			Required: true, flex: 10, Type: 'number' }
				],
			}).then(r => {
				if(!r) return;

				var f = Rs.prepFields(r.Fields);
				var Indice = Ctrl.NodoSel.indicadores.length;
				Ctrl.NodosCRUD.add({
					scorecard_id: Ctrl.ScoSel.id, Nodo: null, padre_id: Ctrl.NodoSel.id, Indice: Indice, tipo: 'Variable', elemento_id: f.Variable.id, peso: f.Peso 
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
			Rs.Storage.ScorecardSel = V.id;
			Ctrl.NodoSel = Rs.def(Nodo, null);
			Ctrl.NodosCRUD.setScope('scorecard', Ctrl.ScoSel.id).get().then(() => {
				if(!Nodo) Nodo = Ctrl.NodosCRUD.rows[0];
				Ctrl.openNodo(Nodo);
				Ctrl.getFs();

				//Ctrl.copyUrlDatos() //FIX
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
			var SubnodosChanged    = Ctrl.NodoSel.subnodos.filter(i => {    return (i.changed == true); });
			var Changed = IndicadoresChanged.concat(SubnodosChanged);
			if(Changed.length > 0){
				Ctrl.NodosCRUD.updateMultiple(Changed).then(() => {
					Rs.showToast('Indicadores Actualizados', 'Success');
					angular.forEach(Ctrl.NodoSel.indicadores, I => {
						I.changed = false;
					});

					angular.forEach(Ctrl.NodoSel.subnodos, I => {
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

		//Nuevo multiple delete
		Ctrl.deleteNodosInd = () => {
			if(Ctrl.NodosSelected.length == 0) return;
			return Rs.confirmDelete({
				Title: '¿Eliminar estos '+Ctrl.NodosSelected.length+' Indicadores/Variables ?',
			}).then(d => {
				if(!d) return;
				Ctrl.NodosCRUD.ops.selected = angular.copy(Ctrl.NodosSelected);
				Ctrl.NodosCRUD.deleteMultiple().then(() => {
					return Ctrl.reindexarNodo(Ctrl.NodoSel);
				});
			})
		}

		Ctrl.reindexarNodo = (Nodo) => {
			return Rs.http('api/Scorecards/reindexar', { Nodo: Nodo }).then(() => {
				Ctrl.openScorecard(Ctrl.ScoSel, Ctrl.NodoSel);
			});
		}

		Ctrl.moveNodosInd = () => {
			return $mdDialog.show({
				controller: 'Scorecards_NodoSelectorCtrl',
				templateUrl: 'Frag/Scorecards.Scorecards_NodoSelector',
				locals: { NodosFS: angular.copy(Ctrl.NodosFS) },
				clickOutsideToClose: true,
				fullscreen: true,
				multiple: true,
			}).then(N => {
				if(!N) return;
				Rs.http('api/Scorecards/move-inds', { Inds: Ctrl.NodosSelected, nodo_destino_id: N.id }).then(() => {
					Ctrl.openScorecard(Ctrl.ScoSel, Ctrl.NodoSel);
				});

				//console.log(N);
			});
		}

		Ctrl.eraseCacheNodosInd = () => {
			Rs.http('api/Scorecards/erase-cache', { Inds: Ctrl.NodosSelected }).then(() => {
				//Ctrl.openScorecard(Ctrl.ScoSel, Ctrl.NodoSel);
				Rs.showToast('Caché Borrada', 'Success');
			});
		}


		Ctrl.getProcesos = () => {
			return Rs.http('api/Procesos', {}, Ctrl, 'Procesos');
		};


		Promise.all([Ctrl.IndicadoresCRUD.get(), Ctrl.VariablesCRUD.get(), Ctrl.getProcesos()]).then(values => { 
			Ctrl.getScorecards();
		});
		

		//Reordenar Indicadores
		Ctrl.dragListener2 = {
			accept: function (sourceItemHandleScope, destSortableScope) { return true; },
			orderChanged: () => {
				angular.forEach(Ctrl.NodoSel.indicadores, (C,index) => {
					if(C.Indice !== index){
						C.Indice = index;
						C.changed = true;
					};
				});
			}
		};



		//URL de Datos
		Ctrl.copyUrlDatos = async () => {

			if(Ctrl.ScoSel.config.data_code == ''){
				Ctrl.ScoSel.config.data_code = Rs.makeUid(5);
				await Ctrl.ScorecardsCRUD.update(Ctrl.ScoSel);
			}

			let Url = Rs.Usuario.url + 'scorecard_data/' + Ctrl.ScoSel.config.data_code + '/' + Rs.AnioActual;

			let Res = await Rs.Confirm({
				Titulo: 'Url para acceder a los datos en formato JSON',
				Detail: Url,
				hasCancel: false,
				Buttons: [
					{ Text: 'Cambiar Código', Class: 'md-raised', Value: 'CHANGE_CODE' },
					{ Text: 'Ok', Class: 'md-raised md-primary', Value: true }
				],
			});

			if(Res == 'CHANGE_CODE'){
				Ctrl.ScoSel.config.data_code = Rs.makeUid(5);
				await Ctrl.ScorecardsCRUD.update(Ctrl.ScoSel);
				Ctrl.copyUrlDatos();
			}
		}

	}
]);