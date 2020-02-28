angular.module('IndicadoresCtrl', [])
.controller('IndicadoresCtrl', ['$scope', '$rootScope', '$injector', '$filter',
	function($scope, $rootScope, $injector, $filter) {

		console.info('IndicadoresCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.IndSel = null;
		Ctrl.IndicadoresNav = true;

		Ctrl.tiposDatoInd = ['Numero','Porcentaje','Moneda'];
		Ctrl.OpsUsar = [
			{id: 'Cump', desc: 'Cumplimiento (1/0)'},
			{id: 'PorcCump', desc: '% de Cumplimiento'},
			{id: 'Valor', desc: 'Valor del Indicador'},
		];

		Ctrl.VariablesCRUD = $injector.get('CRUD').config({ base_url: '/api/Variables' });
		Ctrl.IndicadoresCRUD = $injector.get('CRUD').config({ base_url: '/api/Indicadores' });
		Ctrl.IndicadoresVarsCRUD = $injector.get('CRUD').config({ base_url: '/api/Indicadores/variables' });
		Ctrl.MetasCRUD = $injector.get('CRUD').config({ base_url: '/api/Indicadores/metas' });


		Ctrl.getIndicadores = () => {
			Ctrl.IndicadoresCRUD.get().then(() => {
				Ctrl.openIndicador(Ctrl.IndicadoresCRUD.rows[0]);
				Ctrl.getFs();
			});
		};

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
				proceso_id = Rs.def(Ctrl.Procesos.filter(e => e.Ruta == route).pop().id, null);
			}else{
				proceso_id = null;
			};
			Ctrl.getFs();
			Rs.BasicDialog({
				Title: 'Crear Indicador', Flex: 50,
				Fields: [
					{ Nombre: 'Nombre',  Value: '', Required: true, flex: 60 },				
					{ Nombre: 'Proceso', Value: proceso_id, Required: true, flex: 40, Type: 'list', List: Ctrl.Procesos, Item_Val: 'id', Item_Show: 'Proceso' },
					//{ Nombre: 'Ruta',    Value: '', flex: 70, Type: 'fsroute', List: Ctrl.IndicadoresFS },
					//{ Nombre: 'Crear Carpeta', Value: '', flex: 30, Type: 'string' },
				],
			}).then((r) => {
				if(!r) return;
				var f = Rs.prepFields(r.Fields);
				Ctrl.IndicadoresCRUD.add({
					//Ruta: Rs.FsCalcRoute(f.Ruta, f['Crear Carpeta']),
					Indicador: f.Nombre, proceso_id: f.Proceso,
					Filtros: []
				}).then(() => {
					Ctrl.getFs();
				});
			});
		};

		Ctrl.openIndicador = (V) => {
			Ctrl.IndSel = V;
			Ctrl.IndicadoresVarsCRUD.setScope('indicador', Ctrl.IndSel.id).get();
			Ctrl.MetasCRUD.setScope('indicador', Ctrl.IndSel.id).get();

			Rs.viewIndicadorDiag(Ctrl.IndSel.id); //FIX
		};

		Ctrl.updateIndicador = () => {
			Ctrl.IndicadoresCRUD.update(Ctrl.IndSel).then(() => {
				Rs.showToast('Indicador Actualizada', 'Success');
				Ctrl.saveVariables();
				//Ctrl.openIndicador(Ctrl.IndSel);
			});
		};

		Ctrl.VariablesCRUD.get().then(() => {
			Rs.http('api/Procesos', {}, Ctrl, 'Procesos');
			Ctrl.getIndicadores();
		});


		Ctrl.addVariable = () => {
			Rs.BasicDialog({
				Title: 'Agregar Componente', Flex: 50,
				Fields: [
					{ Nombre: 'Variable',    Value: '', Type: 'list', List: Ctrl.VariablesCRUD.rows, Item_Val: 'id', Item_Show: 'Variable' },
				],
			}).then((r) => {
				if(!r) return;
				var f = Rs.prepFields(r.Fields);
				Ctrl.IndicadoresVarsCRUD.add({
					indicador_id: Ctrl.IndSel.id,
					Letra: String.fromCharCode(97 + Ctrl.IndicadoresVarsCRUD.rows.length),
					Tipo: 'Variable', variable_id: f.Variable
				});
			});
		};

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

		Ctrl.delMeta = (Meta) => {
			Ctrl.MetasCRUD.delete(Meta);
		};

		
	}
]);