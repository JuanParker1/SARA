angular.module('Entidades_GridDiagCtrl', [])
.controller('Entidades_GridDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', 
	function($scope, $rootScope, $mdDialog, $filter) {

		console.info('Entidades_GridDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.inArray = Rs.inArray;
		Ctrl.loadingGrid = false;
		Ctrl.sidenavSel = null;
		Ctrl.filterRows = '';
		Ctrl.orderRows = '';
		Ctrl.SidenavIcons = [
			['fa-filter', 						'Filtros'		,false],
			['fa-sign-in-alt fa-rotate-90', 	'Descargar'		,false],
			['fa-info-circle', 					'Información'	,false],
		];
		Ctrl.openSidenavElm = (S) => {
			Ctrl.sidenavSel = (S[1] == Ctrl.sidenavSel) ? null : S[1];
		};


		Ctrl.Cancel = () => { $mdDialog.cancel(); };

		var Data = null;
		var filteredData = null;
		Ctrl.Data = [];
		Ctrl.load_data_len = 0;
		Ctrl.pag_pages = 50;
		Ctrl.pag_from  = 0;
		Ctrl.pag_to    = null;

		Ctrl.pag_go = (i) => {
			var from = (Ctrl.pag_from + (Ctrl.pag_pages*i) );
			if(from < 0 || from >= Ctrl.load_data_len) return false;
			Ctrl.pag_from = from;
			Ctrl.pag_to = Math.min((Ctrl.pag_from + Ctrl.pag_pages), (Ctrl.load_data_len));
			Ctrl.Data = filteredData.slice(Ctrl.pag_from, Ctrl.pag_to);
		};

		Ctrl.filterData = () => {

			filteredData = Data.slice();
			if(Ctrl.filterRows.trim() !== '') filteredData = $filter('filter')(filteredData, Ctrl.filterRows);
			if(Ctrl.orderRows !== ''){
				var orderNum = parseInt(Ctrl.orderRows);
				filteredData = filteredData.sort((a,b) => {
					if(orderNum < 0){ //DESC
						return (a[(orderNum*-1)] < b[(orderNum*-1)]) ? 1 : -1;
					}else{
						return (a[orderNum]      > b[orderNum]     ) ? 1 : -1;
					};
				});
			};

			Ctrl.load_data_len = filteredData.length;
			Ctrl.pag_go(0);
		};

		Ctrl.reloadData = () => {
			Ctrl.loadingGrid = true;
			Rs.http('api/Entidades/grids-reload-data', { Grid: Ctrl.Grid }).then((r) => {
				Ctrl.Grid.sql  = r.sql;
				Data = r.Data;

				Ctrl.loadingGrid = false;
				Ctrl.filterRows = '';
				Ctrl.filterData();
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
				Data = r.Data;

				if(Ctrl.Grid.filtros.length > 0) Ctrl.SidenavIcons[0][2] = true;
				
				Ctrl.loadingGrid = false;
				Ctrl.filterRows = '';
				Ctrl.filterData();
				//return Ctrl.triggerButton(Ctrl.Grid.Config.row_buttons[0], Ctrl.Grid.data[0]); //TEST
				return Ctrl.triggerButton(Ctrl.Grid.Config.main_buttons[0]); //TEST
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
				Rs.viewEditorDiag(B.accion_element_id, Obj, Config).then((r) => {
					if(!r) return;
					Ctrl.reloadData();
				});
			};
		};

		
		
		
	}
]);