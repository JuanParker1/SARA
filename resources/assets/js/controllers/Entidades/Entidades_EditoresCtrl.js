angular.module('Entidades_EditoresCtrl', [])
.controller('Entidades_EditoresCtrl', ['$scope', '$rootScope', '$timeout', '$filter',
	function($scope, $rootScope, $timeout, $filter) {

		var Ctrl = $scope.$parent;
		var Rs = $rootScope;
		console.log('Entidades_EditoresCtrl');

		$scope.EditoresSidenav = false;
		$scope.showEditorCampos = true;
		$scope.anchosCampo = [10,15,20,25,30,33,35,40,45,50,55,60,65,66,70,75,80,85,90,95,100];
		$scope.EditoresCamposSel = [];
		$scope.editoresSubnav = false;
		$scope.editoresSubnavs = [
			['Previsualizacion', 'fa-eye',    			'Previsualización'],
			['Validaciones',     'fa-tasks',  			'Validaciones']
		];

		Ctrl.setEditoresSubnav = (subnav) => { 
			if(subnav === $scope.editoresSubnav) subnav = false;
			$scope.editoresSubnav = subnav;
		}

		//Editores
		Ctrl.getEditores = () => {

			if(!Ctrl.EntidadSel) return;

			Ctrl.EditoresCRUD.setScope('entidad', Ctrl.EntidadSel.id);
			Ctrl.EditoresCRUD.get().then(() => {
				if(Ctrl.EditoresCRUD.rows.length > 0){
					Ctrl.openEditor(Ctrl.EditoresCRUD.rows[0]);
				}else{
					$scope.EditoresSidenav = true;
				};
			});
		};

		Ctrl.addEditor = () => {

			let Titulo = angular.copy(Ctrl.EntidadSel.Nombre);
			if(Ctrl.EditoresCRUD.rows.length > 0){
				Titulo += ` (${(Ctrl.EditoresCRUD.rows.length+1)})`;
			};

			Ctrl.EditoresCRUD.dialog({
				entidad_id: Ctrl.EntidadSel.id,
				Titulo: Titulo, Secciones: []
			}, {
				title: 'Crear Editor',
				only: ['Titulo']
			}).then((R) => {
				if(!R) return; Ctrl.EditoresCRUD.add(R);
			});
		};

		Ctrl.openEditor = (G) => {
			Ctrl.EditorSel = G;
			Ctrl.getEditorCampos().then(() => {  });
		};

		Ctrl.updateEditor = () => {
			Ctrl.EditoresCRUD.update(Ctrl.EditorSel).then(() => {
				Rs.showToast('Editor Actualizado', 'Success');
				Ctrl.saveEditorCampos();
			});
		};

		//Campos
		Ctrl.getEditorCampos = () => {
			if(!Ctrl.EditorSel) return;
			Ctrl.EditoresCamposCRUD.setScope('editor', Ctrl.EditorSel.id);
			return Ctrl.EditoresCamposCRUD.get();
		};

		Ctrl.autogetEditorCampos = () => {
			var Inseerts = [];
			var Indice = Ctrl.EditoresCamposCRUD.rows.length;
			var ids = Ctrl.EditoresCamposCRUD.rows.map(c => c.campo_id);

			angular.forEach(Ctrl.CamposCRUD.rows, C => {
				if(!ids.includes(C.id)){
					Indice++;
					Inseerts.push({ editor_id: Ctrl.EditorSel.id, Indice: Indice, campo_id: C.id, Ancho: 100, campo: C });
				};
			});

			if(Inseerts.length == 0) return Rs.showToast('No se encontraron nuevos campos para agregar');

			Rs.TableDialog(Inseerts, {
				Title: 'Seleccionar Campos',
				Columns: [
					{ Nombre: 'campo.campo_title', Desc: 'Campo', numeric: false },
					{ Nombre: 'campo.Tipo', 	   Desc: 'Tipo',  numeric: false },
				],
				Flex: 40,
				primaryId: 'campo_id', pluck: false,
				selected: Inseerts.filter(i => { return (i.campo_id !== Ctrl.EntidadSel.campo_llaveprim && i.campo.Visible)})
			}).then(r => {
				if(!r) return;
				Ctrl.EditoresCamposCRUD.addMultiple(r);
			});
		};

		Ctrl.saveEditorCampos = () => {
			var Updatees = $filter('filter')(Ctrl.EditoresCamposCRUD.rows, { changed: true });
			if(Updatees.length == 0) return;
			Ctrl.EditoresCamposCRUD.updateMultiple(Updatees);
			angular.forEach(Ctrl.EditoresCamposCRUD.rows, C => {C.changed = false;});
		};

		Ctrl.removeEditorCampos = () => {
			if($scope.EditoresCamposSel.length == 0) return;
			Ctrl.EditoresCamposCRUD.ops.selected = $scope.EditoresCamposSel;
			Ctrl.EditoresCamposCRUD.deleteMultiple().then(() => {
				 $scope.EditoresCamposSel = [];
			});
		};


		Ctrl.dragEditorListener = {
			accept: function (sourceItemHandleScope, destSortableScope) { return true; },
			orderChanged: () => {
				angular.forEach(Ctrl.EditoresCamposCRUD.rows, (C,index) => {
					if(C.Indice !== index){
						C.Indice = index;
						C.changed = true;
					};
				});
			}
		};

		Ctrl.addSeccion = ($chip) => {
			return { nombre: $chip, open: true };
		};

		Ctrl.markSeccionOpen = ($chip, ev) => {
			ev.stopPropagation();
			$chip.open = !$chip.open;
		};

		Ctrl.editSeccion = ($chip, ev) => {
			ev.stopPropagation();
			Rs.BasicDialog({
				Title: 'Editar Sección',
				Fields: [
					{ Nombre: 'Nombre',  Value: $chip.nombre, Required: true }
				],
				Confirm: { Text: 'Guardar' }
			}).then(r => {
				if(!r) return;
				$chip.nombre = r.Fields[0].Value.trim();
			});
		}

		Ctrl.setSeccion = () => {
			let Secciones = [{ id: null, Nombre: 'Ninguna' }];
			angular.forEach(Ctrl.EditorSel.Secciones, (s,k) => {
				Secciones.push({ id: k, Nombre: s.nombre });
			});

			Rs.ListSelector(Secciones).then(s => {
				if(!s) return;

				console.log(s);
				
				angular.forEach($scope.EditoresCamposSel, C => {
					if(s.id !== null){
						s.id = Rs.getIndex(Ctrl.EditorSel.Secciones, s.Nombre, 'nombre');
					};

					C.seccion_id = s.id;
					C.changed = true;
				});

				$scope.EditoresCamposSel = [];
			});
		};

		Ctrl.alinearCampos = () => {
			let len = $scope.EditoresCamposSel.length;

			let divisions = {
				2: 50,
				3: 33,
				4: 25,
				5: 20,
				6: 15,
				7: 10
			};

			let width = (len in divisions) ? divisions[len] : 10;

			angular.forEach($scope.EditoresCamposSel, C => {
				C.Ancho   = width;
				C.changed = true;
			});

			$scope.EditoresCamposSel = [];

		};

		Ctrl.getEditores();

		$scope.$on("Entidad_Loaded", (evt,data) => {
			Ctrl.getEditores();
		});

	}
]);