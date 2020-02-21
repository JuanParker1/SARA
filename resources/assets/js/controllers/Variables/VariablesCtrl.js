angular.module('VariablesCtrl', [])
.controller('VariablesCtrl', ['$scope', '$rootScope', '$injector', '$filter',
	function($scope, $rootScope, $injector, $filter) {

		console.info('VariablesCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.VarSel = null;
		Ctrl.VariablesNav = true;

		Ctrl.VariablesCRUD = $injector.get('CRUD').config({ base_url: '/api/Variables' });
		Ctrl.Grids = Rs.http('/api/Entidades/grids-get', {}, Ctrl, 'Grids');

		Ctrl.tiposDatoVar = ['Numero','Porcentaje','Moneda'];

		Ctrl.agregators = [
			{ id: 'count', 			Nombre: 'Contar' },
			{ id: 'countdistinct',  Nombre: 'Contar Distintos' },
			{ id: 'sum',  			Nombre: 'Suma' },
			{ id: 'avg',  			Nombre: 'Promedio' },
			{ id: 'min',  			Nombre: 'Mínimo' },
			{ id: 'max',  			Nombre: 'Máximo' },
		];

		Ctrl.getVariables = () => {

			Rs.http('api/Procesos', {}, Ctrl, 'Procesos');

			Ctrl.VariablesCRUD.get().then(() => {

				
				Ctrl.getFs();

				if(Rs.Storage.VariableSel){
					var variable_sel_id = Rs.getIndex(Ctrl.VariablesCRUD.rows, Rs.Storage.VariableSel);
					Ctrl.openVariable(Ctrl.VariablesCRUD.rows[variable_sel_id]);
				};
			});
		};

		Ctrl.getFs = () => {
			Ctrl.filterVariables = "";
			Ctrl.VariablesFS = Rs.FsGet(Ctrl.VariablesCRUD.rows,'Ruta','Variable');
		};

		Ctrl.getFolderVarData = (F) => {
			var Vars = Ctrl.VariablesCRUD.rows.filter((v) => {
				return v.Ruta.startsWith(F.route);
			}).map(v => v.id);
			Rs.getVariableData(Vars);
		};

		Ctrl.searchVariable = () => {
			if(Ctrl.filterVariables == ""){
				Ctrl.getFs();
			}else{
				Ctrl.VariablesFS = Rs.FsGet($filter('filter')(Ctrl.VariablesCRUD.rows, Ctrl.filterVariables),'Ruta','Variable',true);
			};
		};

		Ctrl.addVariable = (route) => {
			if(route){
				proceso_id = Rs.def(Ctrl.Procesos.filter(e => e.Ruta == route).pop().id, null);
			}else{
				proceso_id = null;
			};
			
			Ctrl.getFs();
			Rs.BasicDialog({
				Title: 'Crear Variable', Flex: 50,
				Fields: [
					{ Nombre: 'Nombre',  Value: '', 		Required: true, flex: 60 },
					{ Nombre: 'Proceso', Value: proceso_id, Required: true, flex: 40, Type: 'list', List: Ctrl.Procesos, Item_Val: 'id', Item_Show: 'Proceso' },
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
			//Rs.viewVariableDiag(V.id);
			Rs.http('/api/Variables/get-variable', { id: V.id }, Ctrl, 'VarSel').then(() => {
				//Rs.getVariableData([Ctrl.VarSel.id]);
				Rs.Storage.VariableSel = Ctrl.VarSel.id;
			});
			//Ctrl.VarSel = V;
		};

		Ctrl.updateVariable = () => {
			Ctrl.VariablesCRUD.update(Ctrl.VarSel).then(() => {
				Rs.showToast('Variable Actualizada', 'Success');
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

		Ctrl.getVariables();
	}
]);