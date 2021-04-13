angular.module('IndicadoresCtrl', [])
.controller('IndicadoresCtrl', ['$scope', '$rootScope', '$injector', '$filter', '$mdDialog', '$http',
	function($scope, $rootScope, $injector, $filter, $mdDialog, $http) {

		console.info('IndicadoresCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.IndSel = null;
		Ctrl.IndicadoresNav = true;
		Rs.mainTheme = 'Snow_White';
		Ctrl.tiposDatoInd = ['Numero','Porcentaje','Moneda','Millones'];
		Ctrl.OpsUsar = [
			{id: 'Cump', desc: 'Cumplimiento (1/0)'},
			{id: 'PorcCump', desc: '% de Cumplimiento'},
			{id: 'Valor', desc: 'Valor del Indicador'},
		];
		Ctrl.filterIndicadores = '';

		Ctrl.VariablesCRUD = $injector.get('CRUD').config({ base_url: '/api/Variables', order_by: ['Variable'] });
		Ctrl.IndicadoresCRUD = $injector.get('CRUD').config({ base_url: '/api/Indicadores', offline: ['Indicadores', 'updated_at', (60*24*7)] });
		Ctrl.IndicadoresVarsCRUD = $injector.get('CRUD').config({ base_url: '/api/Indicadores/variables' });
		Ctrl.MetasCRUD = $injector.get('CRUD').config({ base_url: '/api/Indicadores/metas' });
		Ctrl.NodosCRUD = $injector.get('CRUD').config({ base_url: '/api/Scorecards/nodos', add_append: 'refresh', order_by: ['padre_id'], query_call_arr: [ ['getFullRuta', null] ] });
		Ctrl.IndicadoresLoaded = false;

		Ctrl.getIndicadores = () => {
			//return Ctrl.addIndicador(false); //FIX
			
			Ctrl.IndicadoresCRUD.get().then(() => {
				//Ctrl.getFs();

				console.timeEnd('Obtener Indicadores');
				Ctrl.IndicadoresLoaded = true;

				console.time('Obtener Variables');
				Ctrl.VariablesCRUD.get().then(() => {
					console.timeEnd('Obtener Variables');
				});

				if(Rs.Storage.IndicadorSel){
					var indicador_sel_id = Rs.getIndex(Ctrl.IndicadoresCRUD.rows, Rs.Storage.IndicadorSel);
					Ctrl.openIndicador(Ctrl.IndicadoresCRUD.rows[indicador_sel_id]); //FIX
				};

				

				Ctrl.NodosCRUD.get().then(() => {
					Ctrl.NodosFS = Rs.FsGet(Ctrl.NodosCRUD.rows,'Ruta','Nodo',false,true,false);
					angular.forEach(Ctrl.NodosFS, (P) => {
						if(P.type == 'folder'){
							P.file = Ctrl.NodosCRUD.rows.find(p => { return (p.Ruta == P.route && p.tipo == 'Nodo'); });
						}
					});
				});

				//Ctrl.addToTablero(); //FIX

			});
			
		};

		Ctrl.openProceso = (P) => { Ctrl.ProcesoSelId = P.id; }

		Ctrl.getIndicadoresFiltered = () => {
			if(Ctrl.filterIndicadores.trim() == ''){
				return $filter('filter')(Ctrl.IndicadoresCRUD.rows, { proceso_id: Ctrl.ProcesoSelId }, true);
			}else{
				return $filter('filter')(Ctrl.IndicadoresCRUD.rows, Ctrl.filterIndicadores);
			}
		}

		Ctrl.getFs = () => {
			Ctrl.filterIndicadores = "";
			Ctrl.IndicadoresFS = Rs.FsGet(Ctrl.IndicadoresCRUD.rows,'Ruta','Indicador');
		};

		Ctrl.getFolderVarData = (F) => {
			var Vars = Ctrl.IndicadoresCRUD.rows.filter((v) => {
				return v.Ruta.startsWith(F.route);
			}).map(v => v.id);
			Rs.getIndicadorData(Vars);
		};

		Ctrl.searchIndicador = () => {
			if(Ctrl.filterIndicadores == ""){
				Ctrl.getFs();
			}else{
				Ctrl.IndicadoresFS = Rs.FsGet($filter('filter')(Ctrl.IndicadoresCRUD.rows, Ctrl.filterIndicadores),'Ruta','Indicador',true);
			};
		};

		Ctrl.addIndicador = (route) => {
			if(route){
				var route = route.split('\\').slice(0, -1).join('\\');
				proceso_id = Rs.def(Ctrl.Procesos.filter(e => e.Ruta == route).pop().id, null);
			}else{
				proceso_id = null;
			};

			$mdDialog.show({
				controller: 'Indicadores_AddDiagCtrl',
				templateUrl: 'Frag/Indicadores.Indicadores_AddDiag',
				locals: { proceso_id: proceso_id, tiposDatoInd : Ctrl.tiposDatoInd, Procesos: Ctrl.Procesos },
				clickOutsideToClose: false, fullscreen: false, multiple: true
			}).then(newInd => {
				if(!newInd) return;
				Rs.http('api/Indicadores/add-indicador', { newInd: newInd }).then(() => {
					Rs.showToast('Indicador Agregado', 'Success');
					Ctrl.getIndicadores();
				});
			});

			console.log(proceso_id);
		};

		Ctrl.openIndicador = (V) => {
			Ctrl.IndSel = V;
			Rs.Storage.IndicadorSel = Ctrl.IndSel.id;
			Ctrl.ProcesoSelId = Ctrl.IndSel.proceso_id;
			
			Promise.all([
				Ctrl.IndicadoresVarsCRUD.setScope('indicador', Ctrl.IndSel.id).get(),
				Ctrl.MetasCRUD.setScope('indicador', Ctrl.IndSel.id).get()
			]).then(() => {
				
				//Ctrl.openComponente(Ctrl.IndicadoresVarsCRUD.rows[0]);
				//Ctrl.searchComponente();
				
			});

			

			//Rs.viewIndicadorDiag(Ctrl.IndSel.id); //FIX
		};

		Ctrl.updateIndicador = () => {
			Ctrl.IndicadoresCRUD.update(Ctrl.IndSel).then(() => {
				Rs.showToast('Indicador Actualizada', 'Success');
				Ctrl.saveVariables();
				//Ctrl.openIndicador(Ctrl.IndSel);
			});
		};



		
			

		Ctrl.reloadIndicador = () => {
			Promise.all([
				Ctrl.VariablesCRUD.get(),
				Ctrl.IndicadoresCRUD.get()
			]).then( () => {
				Ctrl.openIndicador(Ctrl.IndSel);
			});
		}


		Ctrl.addVariable = () => {

			Ctrl.VariablesCRUD.dialog({
				proceso_id: Ctrl.ProcesoSelId,
				Filtros: []
			}, {
				title: 'Nueva Variable',
				only: ['Variable']
			}).then(r => {
				Ctrl.VariablesCRUD.add(r);
			});

		};

		Ctrl.searchComponente = () => {

			var Componentes = Ctrl.VariablesCRUD.rows.map(r => {
				return {
					id: 'Var_' + r.id,
					Tipo: 'Variable', variable_id: r.id, Titulo: r.Variable,
					Ruta: '1_' + r.Ruta,
					Nodo: r.proceso.Proceso, 
				};
			});

			Componentes = Componentes.concat(Ctrl.IndicadoresCRUD.rows.map(r => {
				return {
					id: 'Ind_' + r.id,
					Tipo: 'Indicador', variable_id: r.id, Titulo: r.Indicador,
					Ruta: '2_' + r.Ruta,
					Nodo: r.proceso.Proceso, 
				};
			}));

			return Rs.TableDialog(Componentes, {
				Title: 'Seleccionar Componente', Flex: 60, 
				Columns: [
					{ Nombre: 'Tipo',  		Desc: 'Tipo',       numeric: false,  orderBy: 'Tipo' },
					{ Nombre: 'Nodo',  		Desc: 'Nodo',       numeric: false,  orderBy: 'Ruta' },
					{ Nombre: 'Titulo', 	Desc: 'Titulo',     numeric: false,  orderBy: 'Titulo' }
				],
				orderBy: 'Ruta', select: 'Row', multiple: false, pluck: false
			}).then(Selected => {
				if(!Selected || Selected.length == 0) return;
				var newComp = Selected[0];
				delete newComp.id;
				Ctrl.addComponente(newComp);
			});
		}

		Ctrl.delVariable = (Var) => {
			Ctrl.IndicadoresVarsCRUD.delete(Var).then(() => {
				angular.forEach(Ctrl.IndicadoresVarsCRUD.rows, (V,i) => {
					var Letra = String.fromCharCode(97 + i);
					if(Letra !== V.Letra){
						V.Letra = Letra;
						V.changed = true;
					};
				});
				Ctrl.saveVariables();
			});
		};

		Ctrl.saveVariables = () => {
			var Updatees = $filter('filter')(Ctrl.IndicadoresVarsCRUD.rows, { changed: true });
			if(Updatees.length == 0) return;
			Ctrl.IndicadoresVarsCRUD.updateMultiple(Updatees);
			angular.forEach(Ctrl.IndicadoresVarsCRUD.rows, IV => {
				IV.changed = false;
			});
		};

		Ctrl.addComponente = (newComp) => {
			newComp.indicador_id = Ctrl.IndSel.id;
			newComp.Letra = String.fromCharCode(97 + Ctrl.IndicadoresVarsCRUD.rows.length);

			Ctrl.IndicadoresVarsCRUD.add(newComp);
		}

		Ctrl.openComponente = (C) => {
			if(C.Tipo == 'Variable'){
				var newCtrl = Ctrl.$new();
				newCtrl.variable_id = C.variable_id;
				Rs.viewVariableEditorDiag(newCtrl);
			}

			if(C.Tipo == 'Indicador'){
				var indsel = Ctrl.IndicadoresCRUD.rows.filter(i => i.id == C.variable_id )[0];
				Ctrl.openIndicador(indsel);
			}
		}

		Ctrl.deleteComponente = (Comp) => {
			Ctrl.IndicadoresVarsCRUD.delete(Comp);
		}

		Ctrl.convertIndicador = (V) => {

			if(Number.isInteger(V)) V = Ctrl.VariablesCRUD.rows.filter(va => (va.id == V) )[0];

			Rs.Confirm({
				Titulo: '¿Convertir la variable en un indicador?',
				Detail: 'Se creará un indicador llamado: "' +V.Variable+ '", y se cambiará la asignación en cualquier indicador asociado',
			}).then(c => {
				if(!c) return;

				$http.post('/api/Variables/convertir-en-indicador', { Variable: V }).then(() => {
					Ctrl.reloadIndicador();
				});

			});

		}

		Ctrl.deleteVariable = (V) => {
			Rs.confirmDelete({
				Title: '¿Eliminar la variable: "' +V.Variable+ '"?',
				Detail: '',
			}).then(d => {
				if(!d) return;

				$http.post('/api/Variables/delete-variable', { Variable: V }).then(() => {
					Ctrl.reloadIndicador();
				});
			});
		}

		//Metas
		Ctrl.addMeta = () => {
			var PeriodoDef = Rs.AnioActual+'-01-15';
			var f = [
				{ Nombre: 'Periodo',  Type: 'period', Value: PeriodoDef, Required: true, flex: 50 }
			];
			if(Ctrl.IndSel.Sentido == 'RAN'){
				f.push({Nombre: 'Límite Inferior',  Value: '', Type: 'string', Required: true, flex: 100 });
				f.push({Nombre: 'Límite Superior',  Value: '', Type: 'string', Required: true, flex: 100 });
			}else{
				f.push({Nombre: 'Meta',  			Value: '', Type: 'string', Required: true, flex: 50 });
			};

			Rs.BasicDialog({
				Title: 'Crear Meta',
				Fields: f
			}).then(f => {
				if(!f) return;
				var m = {
					indicador_id: Ctrl.IndSel.id,
					PeriodoDesde: moment(f.Fields[0].Value).format('YYYYMM'),
					Meta: f.Fields[1].Value,
					Meta2: ((Ctrl.IndSel.Sentido == 'RAN') ? f.Fields[2].Value : null),
				};

				if($filter('filter')(Ctrl.MetasCRUD.rows, { PeriodoDesde: m.PeriodoDesde }).length > 0) return Rs.showToast('Periodo ya existe', 'Error');
				
				Ctrl.MetasCRUD.add(m);
			});
		};

		Ctrl.editMeta = (M) => {
			var only = ['PeriodoDesde', 'Meta'];
			if(Ctrl.IndSel.Sentido == 'RAN') only.push('Meta2');

			Ctrl.MetasCRUD.dialog(M, {
				title: 'Editar Meta',
				only: only
			}).then(Meta => {
				if(!Meta) return;
				if(Meta == 'DELETE') return Ctrl.MetasCRUD.delete(M);
				Ctrl.MetasCRUD.update(Meta);
			});
		}

		Ctrl.formatPeriodo = (date) => {
        	var m = moment(date);
      		return m.isValid() ? m.format('YYYYMM') : '';
        };

		Ctrl.delMeta = (Meta) => {
			Ctrl.MetasCRUD.delete(Meta);
		};

		
		//Tablero
		Ctrl.addToTablero = () => {

			return $mdDialog.show({
				controller: 'Scorecards_NodoSelectorCtrl',
				templateUrl: 'Frag/Scorecards.Scorecards_NodoSelector',
				locals: { NodosFS: angular.copy(Ctrl.NodosFS) },
				clickOutsideToClose: true,
				fullscreen: true,
				multiple: true,
			}).then(Sel => {
				if(!Sel) return;

				var nodos_ind = Ctrl.NodosCRUD.rows.filter(N => { return N.tipo != 'Nodo' && N.Ruta == Sel.Ruta });
				var Indice = nodos_ind.length;
				Ctrl.NodosCRUD.add({
					scorecard_id: Sel.scorecard_id, Nodo: null, padre_id: Sel.id, Indice: Indice, tipo: 'Indicador', elemento_id: Ctrl.IndSel.id, peso: 1
				});
			});
		}

		Ctrl.deleteToTablero = (N) => {
			Rs.confirmDelete({
				Title: '¿Eliminar del Nodo "' +N.Ruta+ '"?',
				Detail: 'Esta acción no se puede deshacer',
			}).then(d => {
				if(d) return Ctrl.NodosCRUD.delete(N);
			});
			
		}

		console.time('Resolver Promesas');
		
		Promise.all([
			Rs.getProcesos(Ctrl)
			//,Ctrl.VariablesCRUD.get()
		]).then(() => {
			
			console.timeEnd('Resolver Promesas');
			console.time('Obtener Indicadores');
			Rs.getProcesosFS(Ctrl);
			Ctrl.getIndicadores();
		});


	}
]);