angular.module('Entidades_EditorDiagCtrl', [])
.controller('Entidades_EditorDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', 'Upload',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, Upload) {

		console.info('Entidades_EditorDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.Cancel = () => { $mdDialog.cancel(); };
		Ctrl.inArray = Rs.inArray;
		Ctrl.calcAlertColor = Rs.calcAlertColor;
		Ctrl.viewNumericGauge = Rs.viewNumericGauge;
		Ctrl.submitForm = Rs.submitForm;
        Ctrl.formatPeriodo = (C) => {
        	return (dateVal) => {
        		let formatEquiv = { 'Ym': 'YYYYMM', 'Y-m': 'YYYY-MM' };
        		return Rs.formatPeriodo(dateVal, formatEquiv[C.campo.Op4]);
        	}
        }
        Ctrl.periodoFilter = (C) => { return true; }
        Ctrl.fixPeriodoValue = Rs.fixPeriodoValue;
		Ctrl.loading = true;
		Ctrl.errorMsg = '';

		var DefConfig = {
			color: '#e2e2e2', textcolor: 'black'
		};

		Ctrl.getEditor = (editor_id, Obj, Config) => {
			Ctrl.Config = angular.extend(DefConfig, Config);
			Ctrl.Config.llaveprim_val = (Ctrl.Config.modo == 'Crear') ? null : Obj.id;
			Rs.http('api/Entidades/editor-get', { editor_id: editor_id, Obj: Obj, Config: Config }).then((Editor) => {
				Ctrl.prepEditor(Editor);
				Ctrl.loading = false;
			});
		};

		Ctrl.prepEditor = (Editor) => {
			angular.forEach(Editor.campos, (C) => {
				if(Rs.inArray(C.campo.Tipo, ['Periodo','Fecha','Hora','FechaHora'])){
					C.val = Rs.parseDate(C.val);
				};

				if(C.campo.Tipo == 'ListaAvanzada'){
					if(C.campo.Op4 == 'AddDate' && C.val == '_SELECT_DATE_'){
						C.val_aux = Rs.parseDate(C.val_aux);
					}
				}

				if(C.campo.Tipo == 'Dinero'){
					if(C.val) C.val = Number(C.val.replace('$', '').replaceAll('.', '').trim());
				}

				if(C.campo.Tipo == 'Porcentaje'){
					if(C.val) C.val = C.val.replace('%', '').replaceAll('.', '').replaceAll(',', '.').trim() / 100;
					//C.val = '190.0%';
					console.log(C.val);
				}

			});

			Ctrl.Editor = Editor;
		};

		Ctrl.searchEntidad = (C) => {
			if(C.val !== null) return false;
			var search_elms = C.campo.entidadext.config.search_elms;
			return Rs.http('api/Entidades/search', { entidad_id: C.campo.Op1, searchText: C.searchText, search_elms: search_elms });
		};

		Ctrl.searchEntidadDiag = (C) => {
			$mdDialog.show({
				templateUrl: 'Frag/Entidades.Entidades_EntidadSearchDiag',
				controller: 'Entidades_EntidadSearchDiagCtrl',
				locals: { C },
				multiple: true,
				escToClose: false
			}).then(item => {
				C.selectedItem = item;
				C.val = item.C0;
			});
		};

		Ctrl.selectedItem = (item, C) => {
			if(!item) return;
			C.val = item.C0;
		};

		Ctrl.clearCampo = (C) => {
			C.val = null; C.searchText = null; C.selectedItem = null;
		};

		Ctrl.enviarDatos = (ev) => {
			angular.forEach(Ctrl.Editor.Secciones, S => {
				S.open = true;
			});

			Ctrl.errorMsg = '';

			//Validar Cambios
			console.log(Ctrl.EditorForm);
			if(Ctrl.EditorForm.$invalid) return Rs.showToast('Falta informaci??n, por favor verifique y reintente.', 'Error');

			Ctrl.loading = true;
			Rs.http('api/Entidades/editor-save', { Editor: Ctrl.Editor, Config: Ctrl.Config }).then(() => {
				Ctrl.loading = false;
				$mdDialog.hide(true);
			}, (d) => {
				Ctrl.loading = false;
				Ctrl.errorMsg = d.Msg;
				console.log(d);
				Rs.showToast('Ha ocurrido un error, por favor guarde la informaci??n e intente nuevamente.', 'Error');
			});
		};


		//Fields Changed
		Ctrl.changedField = (C) => {
			/*if(C.campo.Tipo == 'FechaHora'){
				C.val = moment(C.dateval).format('YYYY-MM-DD HH:mm');
			};

			console.log(C.val);*/
		};

		//Subir im??gen
		Ctrl.uploadImage = (C, file) => {
			if(!file) return;
			let data = {
				width: C.campo.Config.img_width,
				height: C.campo.Config.img_height,
				imagemode: C.campo.Config.img_imagemode
			};

			Upload.upload({
            	url: C.campo.Config.img_uploader, method: 'POST', 
            	file: file,
            	data: data
	        }).then(function(r) {
	        	C.val.changed = true;
	            C.val.url = r.data;
	        });
		};


	}
]);