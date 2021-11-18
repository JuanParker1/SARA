angular.module('VariablesCtrl', [])
.controller('VariablesCtrl', ['$scope', '$rootScope', '$injector', '$filter', '$mdEditDialog', '$mdDialog',
	function($scope, $rootScope, $injector, $filter, $mdEditDialog, $mdDialog) {

		console.info('VariablesCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.VarSel = null;
		if(!('VariablesNav' in Rs.Storage) || !Rs.Storage.VariableSel) Rs.Storage.VariablesNav = true;
		Rs.mainTheme = 'Snow_White';
		Ctrl.VariablesCRUD = $injector.get('CRUD').config({ base_url: '/api/Variables' });
		Ctrl.filterVariables = '';

		Ctrl.Cancel = $mdDialog.cancel;

		Ctrl.tiposDatoVar = ['Numero','Porcentaje','Moneda','Millones'];

		Ctrl.agregators = Rs.agregators;

		Ctrl.getVariables = () => {

			if(Ctrl.variable_id) return Ctrl.prepVariableDiag();

			Promise.all([
				Rs.getProcesos(Ctrl),
				Rs.http('/api/Entidades/grids-get', {}, Ctrl, 'Grids')
			]).then(() => {

				Ctrl.VariablesCRUD.get().then(() => {

					Rs.getProcesosFS(Ctrl);

					if(Rs.Storage.VariableSel){
						var variable_sel_id = Rs.getIndex(Ctrl.VariablesCRUD.rows, Rs.Storage.VariableSel);
						Ctrl.openVariable(Ctrl.VariablesCRUD.rows[variable_sel_id]);
					};
				});

			});
		};

		Ctrl.prepVariableDiag = () => {
			Ctrl.Procesos = Ctrl.$parent.Procesos;
			Ctrl.ProcesosFS = Ctrl.$parent.ProcesosFS;
			Ctrl.Grids = Ctrl.$parent.Grids;
			Ctrl.VariablesCRUD = Ctrl.$parent.VariablesCRUD;

			var variable_sel_id = Rs.getIndex(Ctrl.VariablesCRUD.rows, Ctrl.variable_id);
			Ctrl.openVariable(Ctrl.VariablesCRUD.rows[variable_sel_id]);
		}

		Ctrl.openProceso = (P) => { Ctrl.ProcesoSelId = P.id; }

		Ctrl.getVariablesFiltered = () => {
			if(Ctrl.filterVariables.trim() == ''){
				return $filter('filter')(Ctrl.VariablesCRUD.rows, { proceso_id: Ctrl.ProcesoSelId }, true);
			}else{
				return $filter('filter')(Ctrl.VariablesCRUD.rows, Ctrl.filterVariables);
			}
		}

		Ctrl.getFs = () => {
			Ctrl.filterVariables = "";
			Ctrl.VariablesFS = Rs.FsGet(Ctrl.VariablesCRUD.rows,'Ruta','Variable');
		};

		Ctrl.getFolderVarData = (F) => {
			var Vars = Ctrl.VariablesCRUD.rows.filter((v) => {
				return v.Ruta.startsWith(F.route);
			}).map(v => v.id);
			Rs.getVariableData(Vars, null);
		};

		Ctrl.searchVariable = () => {
			if(Ctrl.filterVariables == ""){
				Ctrl.getFs();
			}else{
				Ctrl.VariablesFS = Rs.FsGet($filter('filter')(Ctrl.VariablesCRUD.rows, Ctrl.filterVariables),'Ruta','Variable',true);
			};
		};

		Ctrl.addVariable = () => {
			
			Ctrl.getFs();
			Rs.BasicDialog({
				Title: 'Crear Variable', Flex: 50,
				Fields: [
					{ Nombre: 'Nombre',  Value: '', 				Required: true, flex: 100 },
					{ Nombre: 'Proceso', Value: Ctrl.ProcesoSelId, Required: true, flex: 100, Type: 'list', List: Ctrl.Procesos, Item_Val: 'id', Item_Show: 'Proceso' },
					//{ Nombre: 'Ruta',    Value: '', flex: 70, Type: 'fsroute', List: Ctrl.VariablesFS },
					//{ Nombre: 'Crear Carpeta', Value: '', flex: 30, Type: 'string' },
				],
			}).then((r) => {
				if(!r) return;
				var f = Rs.prepFields(r.Fields);
				Ctrl.VariablesCRUD.add({
					//Ruta: Rs.FsCalcRoute(f.Ruta, f['Crear Carpeta']),
					Variable: f.Nombre,
					Filtros: [], proceso_id: f.Proceso
				}).then(() => {
					Ctrl.getFs();
				});
			});
		};

		Ctrl.openVariable = (V) => {
			Rs.http('/api/Variables/get-variable', { id: V.id }, Ctrl, 'VarSel').then(() => {
				//Rs.getVariableData([Ctrl.VarSel.id]);
				//
				Ctrl.ProcesoSelId = Ctrl.VarSel.proceso_id;
				Rs.Storage.VariableSel = Ctrl.VarSel.id;

				//Rs.viewVariableDiag(Ctrl.VarSel.id);
				//Ctrl.viewDistinctValues(Ctrl.VarSel.Filtros[0]);
			});
		};

		Ctrl.closeVariable = () => {
			Ctrl.VarSel = null;
			Rs.Storage.VariableSel = false;
		};

		Ctrl.updateVariable = () => {
			Ctrl.VariablesCRUD.update(Ctrl.VarSel).then(() => {
				Rs.showToast('Variable Actualizada', 'Success', 1000);
				Ctrl.openVariable(Ctrl.VarSel);
			});
		};

		Ctrl.addFiltro = () => {
			var col = angular.copy(Ctrl.newFiltro);
			Ctrl.VarSel.Filtros.push({
				columna_id: col.id,
				column_title: col.column_title,
				tipo_campo: col.tipo_campo,
				campo_id: col.campo.id,
				campo: col.campo,
				obs: '',
				Comparador: '=', Valor: null, Op1: null, Op2: null, Op3: null
			});
			Ctrl.newFiltro = null;
		};

		Ctrl.prepComparador = (R) => {
			if(Rs.inArray(R.Comparador, ['in','not_in'])){
				R.Valor = [];
			}else{
				R.Valor = null;
			}
		}

		Ctrl.pushFiltroOption = (R) => {
			var new_valor = angular.copy(R.newValor);
			if(!new_valor || new_valor.trim() == '') return;

			R.Valor.push(new_valor);
			R.newValor = null;
		}

		Ctrl.addFiltroOption = async (R) => {

			if(R.campo.Tipo == 'Lista'){
				var values = R.campo.Config.opciones.map(e => ({ Nombre: e.value }) );
			}else{
				var values = await Rs.http('api/Entidades/grid-get-distinct-values', { grid_id: Ctrl.VarSel.grid_id, campo_id: R.campo_id });
			}
				
			//filter values
			values = values.filter(e => {
				return !R.Valor.includes(e.Nombre);
			});

			Rs.TableDialog(values, {
				Title: 'Seleccionar Opciones',
				Columns: [
					{ Nombre: 'Nombre', Desc: 'Opción', numeric: false }
				],
				primaryId: 'Nombre', pluck: true
			}).then(newValues => {
				if(!newValues) return;
				R.Valor = R.Valor.concat(newValues);
				R.changed = true;
			});
		}

		Ctrl.removeFiltroOption = (R, kV) => {
			R.Valor.splice(kV, 1);
			R.changed = true;
		}

		Ctrl.editValor = (Periodo) => {
			var Valor = angular.isDefined(Ctrl.VarSel.valores[Periodo]) ? Ctrl.VarSel.valores[Periodo].Valor : null;
			

			Rs.BasicDialog({
				Title: 'Cambiar valor '+Periodo,
				Confirm: { Text: 'Cambiar' }, Flex: 20,
				Fields: [
					{ Nombre: 'Valor',  Value: Valor, Required: false, Regex: "\\d+" }
				], //          ^[0-9]+([.][0-9]{1,4})?$
			}).then((r) => {
				if(!r) return;
				newValor = (r.Fields[0].Value != "") ? r.Fields[0].Value : null;
				if(newValor == Valor) return;
				Rs.http('/api/Variables/update-valor', { variable_id: Ctrl.VarSel.id, Periodo: Periodo, Valor: newValor }).then(() => {
					Ctrl.openVariable(Ctrl.VarSel);
				});
			});
		};

		Ctrl.editValor2 = (event, Periodo) => {
			event.stopPropagation(); // in case autoselect is enabled

			var Valor = angular.isDefined(Ctrl.VarSel.valores[Periodo]) ? Ctrl.VarSel.valores[Periodo].Valor : null;
			if(Ctrl.VarSel.TipoDato == 'Porcentaje') Valor *= 100;
			
			return $mdEditDialog.small({
				modelValue:  Valor,
				targetEvent: event,
				placeholder: Periodo, title: Periodo,
				save: function (input) {
					var newValor = parseFloat(input.$modelValue);
					if(Number.isNaN(newValor)) newValor = null;
					if(Ctrl.VarSel.TipoDato == 'Porcentaje') newValor /= 100;
					if(newValor == Valor) return;

					return Rs.http('/api/Variables/update-valor', { variable_id: Ctrl.VarSel.id, Periodo: Periodo, Valor: newValor }).then(() => {
						Ctrl.openVariable(Ctrl.VarSel);
					});
				}
			});
		}

		Ctrl.copyVar = () => {
			Rs.BasicDialog({
				Title: 'Copiar Variable', Flex: 50, clickOutsideToClose: false,
				Confirm: { Text: 'Crear' },
				Fields: [
					{ Nombre: 'Nombre',  	    Value: Ctrl.VarSel.Variable + ' (copia)', Required: true, flex: 60 },
					{ Nombre: 'Proceso',        Value: Ctrl.VarSel.proceso_id,  Required: true, flex: 40, Type: 'list', List: Ctrl.Procesos, Item_Val: 'id', Item_Show: 'Proceso' },
					{ Nombre: 'Descripcion',  	Value: Ctrl.VarSel.Descripcion, Required: true },
					//{ Nombre: 'Ruta',       Value: Ctrl.VarSel.Ruta, flex: 70, Type: 'fsroute', List: Ctrl.VariablesFS },
					//{ Nombre: 'Crear Carpeta', Value: '', flex: 30, Type: 'string' },
				]
			}).then((r) => {
				if(!r) return;
				var f = Rs.prepFields(r.Fields);
				Ctrl.VariablesCRUD.add({
					//Ruta: 			Rs.FsCalcRoute(f.Ruta, f['Crear Carpeta']),
					proceso_id:     f.Proceso, 
					Variable: 		f.Nombre,
					Descripcion: 	f.Descripcion,
					TipoDato: 		Ctrl.VarSel.TipoDato,
					Decimales: 		Ctrl.VarSel.Decimales,
					Tipo: 			Ctrl.VarSel.Tipo,
					grid_id: 		Ctrl.VarSel.grid_id,
					ColPeriodo: 	Ctrl.VarSel.ColPeriodo,
					Agrupador: 		Ctrl.VarSel.Agrupador,
					Col: 			Ctrl.VarSel.Col,
					Filtros: 		Ctrl.VarSel.Filtros,
				}).then(() => { Ctrl.getFs(); });
			});
		};


		Ctrl.seleccionarEntidadGrid = () => {

			Rs.TableDialog(Ctrl.Grids, {
				Title: 'Seleccionar Grid', Flex: 60,
				primaryId: 'id', pluck: true,
				Columns: [
					{ Nombre: 'entidad.proceso.Proceso', Desc: 'Proceso', numeric: false },
					{ Nombre: 'entidad.Nombre', Desc: 'Entidad', numeric: false },
					{ Nombre: 'Titulo', 		Desc: 'Grid',    numeric: false }
				],
				selected: [], multiple: false, orderBy: 'Titulo',
			}).then(r => {
				if(!r) return;
				
				Ctrl.VarSel.grid_id = r[0];
				Ctrl.updateVariable();

			});

		}

		Ctrl.viewDistinctValues = (R) => {
			Rs.http('api/Entidades/grid-get-distinct-values', { grid_id: Ctrl.VarSel.grid_id, campo_id: R.campo_id }).then(values => {
				Rs.ListSelector(values, {
					searchPlaceholder: 'Buscar '+R.column_title,
					class: 'vh100'
				}).then(value_sel => {
					if(!value_sel) return;
					if(Rs.inArray(R.Comparador, ['in','not_in'])){
						R.Valor.push(value_sel.Nombre);
					}else{
						R.Valor = value_sel.Nombre;
					}
					
				});
			});
		}

		Rs.http('api/Main/get-configuracion', {}, Ctrl, 'Configuracion').then(() => {
			Ctrl.getVariables();
		});
		
	}
]);