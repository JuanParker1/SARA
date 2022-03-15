angular.module('MiProcesoCtrl', [])
.controller('MiProcesoCtrl', ['$scope', '$rootScope', '$injector', '$filter', '$mdDialog',
	function($scope, $rootScope, $injector, $filter, $mdDialog) {

		console.info('MiProcesoCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Rs.mainTheme = 'Snow_White';


		Ctrl.ProcesoSel = false;
		Ctrl.Anio  = angular.copy(Rs.AnioActual);
		Ctrl.Mes   = angular.copy(Rs.MesActual);
		Ctrl.filterIndicadoresText = '';
		Ctrl.Loading = true;
		Ctrl.SelectedTab = 0;
		Ctrl.Sentidos = Rs.Sentidos;
		

		var Indicadores = [];
		Ctrl.anioAdd = (num) => {Ctrl.Anio = Ctrl.Anio + num; Ctrl.getIndicadores(); };

		Ctrl.cambiarFondo = () => {
			var Config = {
				CanvasWidth:  600,
				CanvasHeight: 400,
				CropWidth:    600,
				CropHeight:   160,
				Class: 'mw600',
				UploadPath: 'img/procesos_bgs/'+Ctrl.ProcesoSel.id+'.jpg',
			};

			$mdDialog.show({
				templateUrl: 'templates/dialogs/image-editor.html',
				controller: 'ImageEditor_DialogCtrl',
				locals: { Config: Config }
			}).then((r) => {
				Ctrl.getProceso(Ctrl.ProcesoSel.id);
			});
		}

		Ctrl.SelectedTab = 0;
		Ctrl.SubSecciones = [
			['General'		,'General' ],
			['Equipo'		,'Equipo' ],
			['Indicadores'	,'Indicadores y Tableros' ],
			//Logros:  ['Logros'],
		];

		Ctrl.goToTab = (id) => {
			var tab_index = Rs.getIndex(Ctrl.SubSecciones, id, 0);
			//Object.keys(Ctrl.SubSecciones).indexOf(id);
			Ctrl.SelectedTab = tab_index;
		}

		Ctrl.getProceso = (proceso_id) => {
			Rs.http('api/Procesos/get-proceso', { proceso_id: proceso_id, Anio: Ctrl.Anio }, Ctrl, 'ProcesoSel').then(r => {

			});
		}


		//Introduccion
		Ctrl.addedIntro = false;
		Ctrl.markIntro = () => { Ctrl.addedIntro = true; }
		Ctrl.saveIntro = () => {
			Rs.http('api/Procesos/update', { Proceso: Ctrl.ProcesoSel  }).then(r => {
				Ctrl.addedIntro = false;
			});
			
		}

		Ctrl.viewTableroDiag = (T) => {
			//Rs.viewScorecardDiag(T.id);
			$mdDialog.show({
				controller: 'Scorecards_ScorecardDiagCtrl',
				templateUrl: '/Frag/Scorecards.ScorecardDiag',
				clickOutsideToClose: false, fullscreen: true, multiple: true,
				onComplete: (scope, element) => {
					scope.getScorecard(T.id, { proceso_id: Ctrl.ProcesoSel.id });
				}
			});
		}


		Ctrl.verMapaNodos = () => {
			$mdDialog.show({
				controller: 'Procesos_MapaNodosDiagCtrl',
				templateUrl: '/Frag/Procesos.Procesos_MapaNodosDiag',
				clickOutsideToClose: false, fullscreen: true, multiple: true,
				locals: { ProcesosFS: Ctrl.ProcesosFS }
			}).then(P => {
				if(!P) return;
				Ctrl.getProceso(P.id)
			});
		}



		Promise.all([
			Rs.getProcesos(Ctrl)
		]).then(() => {

			Ctrl.ProcesosFS = Rs.FsGet(Ctrl.Procesos,'Ruta','Proceso',false,true);

			//console.table(Ctrl.ProcesosFS);

			angular.forEach(Ctrl.ProcesosFS, (P) => {
				if(P.type == 'folder'){
					P.file = Ctrl.Procesos.find(p => (p.Ruta == P.route && p.Proceso == P.name) );
				}
			});

			var first_proceso = Rs.Usuario.Procesos.find((P) => {
				return (P.Tipo !== 'Utilitario');
			});
			//var first_proceso = {id:50};
			if(first_proceso) Ctrl.getProceso(first_proceso.id);
			//Ctrl.verMapaNodos();
		});

		
		
		


	}
]);