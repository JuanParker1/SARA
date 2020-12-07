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

		Ctrl.getOpcionLista = (Val, Config) => {
			return Config.opciones.find(el => el.value === Val);
		};

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

		Ctrl.reloadData = (emptyFilter = true) => {
			Ctrl.loadingGrid = true;
			Rs.http('api/Entidades/grids-reload-data', { Grid: Ctrl.Grid }).then((r) => {
				Ctrl.Grid.sql  = r.sql;
				Data = r.Data;

				Ctrl.loadingGrid = false;
				if(emptyFilter) Ctrl.filterRows = '';
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
				//return Ctrl.triggerButton(Ctrl.Grid.Config.row_buttons[0], Data[0]); //TEST
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
				Rs.viewEditorDiag(B.accion_element_id, Obj, Config).then((r) => {
					if(!r) return;
					Ctrl.reloadData(false);
				});
			};
		};

		function s2ab(s) {
            var buf = new ArrayBuffer(s.length);
            var view = new Uint8Array(buf);
            for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
            return buf;        
        }

        function excelColName(n) {
			var ordA = 'a'.charCodeAt(0);
			var ordZ = 'z'.charCodeAt(0);
			var len = ordZ - ordA + 1;

			var s = "";
			while(n >= 0) {
				s = String.fromCharCode(n % len + ordA).toUpperCase() + s;
				n = Math.floor(n / len) - 1;
			}
			return s;
		}

		Ctrl.downloadData = () => {
			var wb = XLSX.utils.book_new();
	        wb.Props = {
	                Title: "SheetJS Tutorial",
	                CreatedDate: new Date(2017,12,19)
	        };

	        var SheetData = [ [] ];
	        var ColumnsNo = 0;
	        Ctrl.Grid.columnas.forEach((C) => {
	        	if(C.Visible){
	        		SheetData[0].push(C.column_title);
	        		ColumnsNo++;
	        	}
	        });

	        filteredData.forEach((Row) => {
	        	var RowData = [];
	        	Ctrl.Grid.columnas.forEach((C,kC) => {
	        		if(C.Visible){
		        		RowData.push(Row[kC]);
		        	}
		        });
		        SheetData.push(RowData);
	        });

			var ws = XLSX.utils.aoa_to_sheet(SheetData);
			var last_cell = excelColName(ColumnsNo - 1) + (Data.length + 1);
			ws['!autofilter'] = { ref: ('A1:'+last_cell) };
	        
	        XLSX.utils.book_append_sheet(wb, ws, "Datos");
	        var wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});
	     
	        saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), Ctrl.Grid.Titulo + '.xlsx');
		};

		//Previsualizar un Campo
		Ctrl.previewCampo = (C, val) => {
			if(!val || val == '') return;
			$mdDialog.show({
				templateUrl: 'Frag/Entidades.Entidades_GridDiag_PreviewDiag',
				controller: 'Entidades_GridDiag_PreviewDiagCtrl',
				locals: { C: C, val: val },
				clickOutsideToClose: true, fullscreen: false, multiple: true,
			});
		};
		
		//Ctrl.openSidenavElm(['fa-sign-in-alt fa-rotate-90', 'Descargar',false]) //FIX
		
	}
]);