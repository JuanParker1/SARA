angular.module('Entidades_GridDiagCtrl', [])
.controller('Entidades_GridDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', 
	function($scope, $rootScope, $mdDialog, $filter) {

		console.info('Entidades_GridDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.inArray = Rs.inArray;
		Ctrl.loadingGrid = false;
		Ctrl.sidenavSel = null;
		Ctrl.SidenavIcons = [
			['fa-filter', 						'Filtros'		,false],
			['fa-sign-in-alt fa-rotate-90', 	'Descargar'		,false],
			['fa-info-circle', 					'Información'	,false],
		];
		Ctrl.openSidenavElm = (S) => {
			Ctrl.sidenavSel = (S[1] == Ctrl.sidenavSel) ? null : S[1];
		};


		Ctrl.Cancel = () => { $mdDialog.cancel(); };

		Ctrl.Data = [];

		Ctrl.filterData = () => {
			Rs.http('api/Entidades/grids-reload-data', { Grid: Ctrl.Grid }).then((r) => {
				Ctrl.Grid.sql  = r.sql;
				Ctrl.Grid.data = r.data;
			});
		};

		Ctrl.getSelectedText = (Text) => {
			if(Text === null) return 'Seleccionar...';
			if(angular.isArray(Text)){
				return JoinedText = Text.join(', ');
			};
			return Text;
		};
		
		Ctrl.getGrid = (grid_id) => {
			
			if(!grid_id) return;
			Ctrl.loadingGrid = true;
			Rs.http('api/Entidades/grids-get-data', { grid_id: grid_id }).then((r) => {
				Ctrl.Grid = r.Grid;
				Ctrl.loadingGrid = false;
				if(Ctrl.Grid.filtros.length > 0) Ctrl.SidenavIcons[0][2] = true;

				//return Ctrl.triggerButton(Ctrl.Grid.Config.row_buttons[0], Ctrl.Grid.data[0]); //TEST
				//return Ctrl.triggerButton(Ctrl.Grid.Config.main_buttons[0]); //TEST
			});
		};

		var prepRow = (R) => {
			if(!R) return null;
			var Obj = { id: R[0] };
			angular.forEach(Ctrl.Grid.columnas, (C, kC) => {
				if(C.id){ Obj[C.id] = { val: R[kC] }; };
			});
			return Obj;
		};

		Ctrl.triggerButton = (B,R) => {

			var Obj = prepRow(R);

			var DefConfig = {};
			if(Ctrl.AppSel){
				DefConfig = angular.extend(DefConfig, {
					color: Ctrl.AppSel.Color, textcolor: Ctrl.AppSel.textcolor
				});
			};

			if(B.accion == 'Editor'){
				Config = angular.extend(DefConfig, B);
				Rs.viewEditorDiag(B.accion_element_id, Obj, Config);
			};
		};

		
		
		
	}
]);