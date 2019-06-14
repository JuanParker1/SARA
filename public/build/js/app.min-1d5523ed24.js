angular.module('MainCtrl', [])
.controller('MainCtrl', ['$rootScope', 'appFunctions', '$http', '$mdDialog', '$mdSidenav', '$mdToast', '$q', '$state', '$location', '$localStorage', '$mdMedia', 
	function($rootScope, appFunctions, $http, $mdDialog, $mdSidenav, $mdToast, $q, $state, $location, $localStorage, $mdMedia) {

		console.info('MainCtrl');
		var Rs = $rootScope;

		Rs.ToogleSidebar = function(nav_id){ $mdSidenav(nav_id).toggle(); }
		Rs.CloseSidebar = function(nav_id){  $mdSidenav(nav_id).close();  }
		Rs.OpenSidebar = function(nav_id){ 	 $mdSidenav(nav_id).open();   }

		//Check state
		Rs.StateChanged = function(){
			Rs.State = $state.current;
			Rs.State.route = $location.path().split('/');
		};

		Rs.Refresh = function() {
			$state.go($state.current, $state.params, {reload: true});
		}
		
		Rs.$on("$stateChangeSuccess", Rs.StateChanged);
		Rs.navTo = function(Dir, params){ $state.go(Dir, params); }

		Rs.StateChanged();

		Rs.Logout = function(){
			//Rs.navTo('Login', {});
			location.replace("/#/Login");
		};

		Rs.$watch(function() { return $mdMedia('gt-sm'); }, function(gtsm) { Rs.gtsm = gtsm; });

		if(typeof Rs.Storage.mainSidenavLabels == 'undefined') Rs.Storage.mainSidenavLabels = false;
		Rs.mainSidenavLabels = Rs.Storage.mainSidenavLabels;
		Rs.mainSidenav = function() {
			Rs.mainSidenavLabels = !Rs.mainSidenavLabels;
			Rs.Storage.mainSidenavLabels = !Rs.Storage.mainSidenavLabels;
			Rs.OpenSidebar('SectionsNav');
		};
	}
]);
angular.module('InicioCtrl', [])
.controller('InicioCtrl', ['$scope', '$rootScope', 
	function($scope, $rootScope) {

		console.info('InicioCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.makeFavorite = (A,make) => {
			A.favorito = make;
			Rs.http('api/App/favorito', { usuario_id: Rs.Usuario.id, app_id: A.Id, favorito: make });
		};

		Ctrl.openApp = (A) => {
			console.log(A);
		};
	}
]);
angular.module('LoginCtrl', [])
.controller('LoginCtrl', ['$scope', '$rootScope', '$http', '$localStorage', '$mdToast', '$state', 
	function($scope, $rootScope, $http, $localStorage, $mdToast, $state) {

		console.info('LoginCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
	
		delete $localStorage.token;

		Ctrl.User = '';
		Ctrl.Pass = '';

		Ctrl.ShowErr = function(Msg, Delay){
			var errTxt = '<md-toast class="md-toast-error"><span flex>' + Msg + '<span></md-toast>';
			$mdToast.show({
				template: errTxt,
				hideDelay: Delay
			});
		}

		Ctrl.Login = function(){
			$http.post('api/Usuario/login', { Email: Ctrl.User, Pass: Ctrl.Pass }).then(function(r){
				var token = r.data;
				$localStorage.token = token;
				$state.go('Home');
			}, function(r){
				Ctrl.ShowErr(r.data.Msg); 
				Ctrl.Pass = '';
			});
		};
	}
]);
angular.module('BDDCtrl', [])
.controller('BDDCtrl', ['$scope', '$rootScope', '$injector',
	function($scope, $rootScope, $injector) {

		console.info('BDDCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.BDDSidenav = true;
		Ctrl.BDDFavSidenav = false;

		Ctrl.BDDsCRUD = $injector.get('CRUD').config({ base_url: '/api/Bdds' });

		Ctrl.BDDsCRUD.get().then(() => {
			if(Ctrl.BDDsCRUD.rows.length > 0){
				Ctrl.openBDD(Ctrl.BDDsCRUD.rows[0]);
			};
		});

		Ctrl.openBDD = (B) => {
			Ctrl.BDDSel = B;
			Ctrl.getFavoritos();
			//Ctrl.executeQuery(); //REmove
		};

		Ctrl.TiposBDD = {
			ODBC_DB2:     { Op1: 'DSN', Op2: 'Servidor', 		Op3: 'Base de Datos', Op4: false, Op5: false },
			ODBC_MySQL:   { Op1: 'DSN', Op2: 'Servidor', 		Op3: 'Base de Datos', Op4: false, Op5: false },
			MySQL:  	  { Op1: false, Op2: 'Servidor', 		Op3: 'Base de Datos', Op4: false, Op5: false },
			SQLite: 	  { Op1: false, Op2: 'Ruta al Archivo', Op3: 'Base de Datos', Op4: false, Op5: false },
		};

		Ctrl.addBDD = () => {
			Rs.BasicDialog({
				Title: 'Crear Conexión a Base de Datos'
			}).then((r) => {
				var Nombre = r.Fields[0].Value.trim();
				if(Rs.found(Nombre, Ctrl.BDDsCRUD.rows, 'Nombre')) return;

				Ctrl.BDDsCRUD.add({
					Nombre: Nombre,
					Tipo: 'ODBC'
				});
			});
		};

		Ctrl.updateBDD = () => {
			Ctrl.BDDsCRUD.update(Ctrl.BDDSel).then(() => {
				Rs.showToast('Actualizado', 'Success', 5000, 'bottom right');
			});
		};

		Ctrl.removeBDD = () => {
			Rs.confirmDelete({
				Title: '¿Borrar la Conexión a la Base de Datos "'+Ctrl.BDDSel.Nombre+'"?'
			}).then((del) => {
				if(!del) return;
				Ctrl.BDDsCRUD.delete(Ctrl.BDDSel).then(() => {
					Ctrl.BDDSel = null;
				});
			});
		};

		Ctrl.testBDD = () => {
			Rs.http('/api/Bdds/probar', { BDD: Ctrl.BDDSel }).then((r) => {
				Rs.showToast('Conexión Exitosa', 'Success', 5000, 'bottom right');
			});
		};

		//Panel de Consultas SQL
		Ctrl.SQLQuery = "";
		Ctrl.executingQuery = false;
		Ctrl.QueryRows = null;
		Ctrl.executeQuery = () => {
			if(Ctrl.SQLQuery == "" || Ctrl.executingQuery) return;
			Ctrl.executingQuery = true;

			Rs.http('/api/Bdds/query', { BDD: Ctrl.BDDSel, Query: Ctrl.SQLQuery }).then((r) => {
				Ctrl.QueryRows = r;
			}).finally(() => {
				Ctrl.executingQuery = false;
			});
		};



		//Panel de Favoritos
		Ctrl.FavsCRUD = $injector.get('CRUD').config({ 
			base_url: '/api/Bdds/favoritos',
			query_scopes: [
				[ 'mine', true ]
			]
		});

		Ctrl.getFavoritos = () => {
			Ctrl.FavsCRUD.setScope('bddid', Ctrl.BDDSel.id);
			Ctrl.FavsCRUD.get();
		};

		Ctrl.useFav = (F) => {
			if(Ctrl.executingQuery) return;

			Ctrl.SQLQuery = F.Consulta;

			if(F.EjecutarAutom == 'S'){
				Ctrl.executeQuery();
			};
		};

		Ctrl.addFav = () => {
			Ctrl.FavsCRUD.dialog({
				Consulta: angular.copy(Ctrl.SQLQuery),
				EjecutarAutom: 'N',
				bdd_id: Ctrl.BDDSel.id,
				usuario_id: Rs.Usuario.id
			}, {
				title: 'Crear Favorito',
				only: [ 'Nombre', 'Consulta', 'EjecutarAutom' ]
			}).then((R) => {
				if(!R) return;
				Ctrl.FavsCRUD.add(R);
			});
		};

		Ctrl.editFav = (F) => {
			Ctrl.FavsCRUD.dialog(angular.copy(F), {
				title: 'Favorito: ' + F.Nombre,
				only: [ 'Nombre', 'Consulta', 'EjecutarAutom' ]
			}).then((R) => {
				if(!R) return;
				if(R == 'DELETE') return Ctrl.FavsCRUD.delete(F);
				Ctrl.FavsCRUD.update(R);
			});
		};

	}
]);
angular.module('BasicDialogCtrl', [])
.controller(   'BasicDialogCtrl', ['$scope', 'Config', '$mdDialog', 
	function ($scope, Config, $mdDialog) {

		var Ctrl = $scope;

		Ctrl.Config = Config;

		Ctrl.Cancel = function(){
			$mdDialog.hide();
		}

		Ctrl.SendData = function(){
			$mdDialog.hide(Ctrl.Config);
		}

		Ctrl.Delete = function(ev) {
			if(Config.HasDelete){
				Config.HasDeleteConf = true;

				Ctrl.SendData();
			}
		}
	}

]);
angular.module('BottomSheetCtrl', [])
.controller('BottomSheetCtrl', ['$scope', '$rootScope', '$mdBottomSheet', 'Config', 
	function($scope, $rootScope, $mdBottomSheet, Config) {

		var Ctrl = $scope;
		var Rs = $rootScope;
	
		Ctrl.Cancel = function(){ $mdBottomSheet.cancel(); }

		Ctrl.Config = angular.copy(Config);

		Ctrl.Send = function(Item){
			$mdBottomSheet.hide(Item);
		}
	}
]);
angular.module('ConfirmCtrl', [])
.controller(   'ConfirmCtrl', ['$scope', 'Config', '$mdDialog', 
	function ($scope, Config, $mdDialog) {

		var Ctrl = $scope;

		Ctrl.Config = Config;

		Ctrl.Cancel = function(){
			$mdDialog.cancel();
		}

		Ctrl.Send = function(val){
			$mdDialog.hide(val);
		}
		
	}

]);
angular.module('ConfirmDeleteCtrl', [])
.controller(   'ConfirmDeleteCtrl', ['$scope', 'Config', '$mdDialog', 
	function ($scope, Config, $mdDialog) {

		var Ctrl = $scope;

		Ctrl.Config = Config;

		Ctrl.Cancel = function(){
			$mdDialog.hide(false);
		}

		Ctrl.Delete = function(){
			$mdDialog.hide(true);
		}
		
	}

]);
angular.module('CRUDDialogCtrl', [])
.controller('CRUDDialogCtrl', ['$rootScope', '$scope', '$mdDialog', 'ops', 'config', 'columns', 'Obj', 'rows', 
	function($rootScope, $scope, $mdDialog, ops, config, columns, Obj, rows) {

		console.info('CRUDDialogCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.config = {};
		Ctrl.columns = columns;
		Ctrl.Obj = {};
		//Ctrl.Obj = angular.copy(Obj);

		//Saber si es nuevo
		Ctrl.new = !(ops.primary_key in Obj);
		Ctrl.config.confirmText = Ctrl.new ? 'Crear' : 'Guardar';
		Ctrl.config.title = Ctrl.new ? ('Nuevo '+ops.name) : ('Editando '+ops.name);
		Ctrl.config.delete_title = '¿Borrar '+ops.name+'?';

		angular.forEach(columns, function(F){
			if(F.Default !== null){
				var DefValue = angular.copy(F.Default);
				Ctrl.Obj[F.Field] = DefValue;
			};

			F.show = true;
			if(config.only.length > 0){
				F.show = Rs.inArray(F.Field, config.only);
			};
		});

		angular.extend(Ctrl.Obj, Obj);
		angular.extend(Ctrl.config, config);

		Ctrl.cancel = function(){ $mdDialog.hide(false); };

		Ctrl.sendData = function(){
			//Verificar los Uniques
			var Errors = 0;
			angular.forEach(columns, function(C){
				if(C.Unique){
					//console.log(ops.primary_key, Ctrl.Obj[ops.primary_key]);
					var except = Ctrl.new ? false : [ ops.primary_key, Ctrl.Obj[ops.primary_key] ];
					var Found = Rs.found(Ctrl.Obj[C.Field], rows, C.Field, undefined, except );
					if(Found) Errors++;
				};
			});

			if(Errors > 0) return false;

			$mdDialog.hide(Ctrl.Obj);
		};


		Ctrl.delete = function(ev){
			var config = {
				Title: Ctrl.config.delete_title,
			};

			Rs.confirmDelete(config).then(function(del){
				if(del){
					$mdDialog.hide('DELETE');
				};
			});
		};


		
		//Campos
		//Ctrl.fields = angular.copy

	}
]);
angular.module('FileDialogCtrl', [])
.controller('FileDialogCtrl', ['$scope', '$rootScope', '$http', '$mdDialog', '$mdToast', 'FileSel', 
	function($scope, $rootScope, $http, $mdDialog, $mdToast, FileSel) {

		console.info('FileDialogCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.FileSel = FileSel;
		Ctrl.inArray = Rs.inArray;

		//Dialog
		Ctrl.Cancel = function(){
			$mdDialog.hide();
		};

	}
]);
angular.module('ImageEditor_DialogCtrl', [])
.controller(   'ImageEditor_DialogCtrl', ['$scope', '$rootScope', '$mdDialog', '$mdToast', '$timeout', '$http', 'Upload', 'Config', 
	function ($scope, $rootScope, $mdDialog, $mdToast, $timeout, $http, Upload, Config) {

		var Ctrl = $scope;
		var Rs = $rootScope;

		//console.info('-> Image Editor');

		Ctrl.Config = {
			Theme : 'Snow_White',		//El tema
			Title: 'Cambiar Imágen',	//El Titulo
			CanvasWidth:  350,			//Ancho del canvas
			CanvasHeight: 350,			//Alto del canvas
			CropWidth:  100,			//Ancho del recorte que se subirá
			CropHeight: 100,			//Alto del recorte que se subirá
			MinWidth:  50,				//Ancho mínimo del selector
			MinHeight: 50,				//Ancho mínimo del selector
			KeepAspect: true,			//Mantener aspecto
			Preview: false,				//Mostrar vista previa
			PreviewClass: '',			//md-img-round
			RemoveOpt: false,			//Si es texto muestra la opcion de borrar
			Daten: null					//La data a enviar al servidor
		};

		Ctrl.RotationCanvas = document.createElement("canvas");

		Ctrl.cropper = {};
		Ctrl.cropper.sourceImage = null;
		Ctrl.cropper.croppedImage = null;
		Ctrl.bounds = {};

		Ctrl.Progress = null;

		angular.extend(Ctrl.Config, Config);

		Ctrl.CancelText = Ctrl.Config.RemoveOpt ? Ctrl.Config.RemoveOpt : 'Cancelar';
		
		Ctrl.CancelBtn = function(){
			if(!Ctrl.Config.RemoveOpt){
				Ctrl.Cancel();
			}else{
				$http.post('/api/Upload/remove', { Path: Ctrl.Config.Daten.Path }).then(function(){
					$mdDialog.hide({Removed: true});
				});
			}
		}

		Ctrl.Cancel = function(){
			$mdDialog.hide();
		}

		Ctrl.Rotar = function(dir){
			var canvas = Ctrl.RotationCanvas;
			var ctx = canvas.getContext("2d");

			var image = new Image();
			image.src = Ctrl.cropper.sourceImage;
			image.onload = function() {
				canvas.width = image.height;
				canvas.height = image.width;
				ctx.rotate(dir * Math.PI / 180);
				ctx.translate(0, -canvas.width);
				ctx.drawImage(image, 0, 0); 
				Ctrl.cropper.sourceImage = canvas.toDataURL();
			};
		}

		Ctrl.$watch('Ctrl.cropper.sourceImage', function(nv, ov){
			if(nv){
				console.log('Imagen Cargada');
			}
		});

		Ctrl.SendImage = function(){

			var Daten = {
				file: Upload.dataUrltoBlob(Ctrl.cropper.croppedImage),
				Quality: 90
			};

			angular.extend(Daten, Config.Daten);

			Upload.upload({

				url: '/api/Archivos/upload-img',
				data: Daten,

			}).then(function (res) {
				
				$timeout(function () {
					$mdDialog.hide(res.data);
				});

			}, function (response) {
				if (response.status > 0){
					
					var Msg = response.status + ': ' + response.data;
					var errTxt = '<md-toast class="md-toast-error"><span flex>' + Msg + '<span></md-toast>';

					$mdToast.show({
						template: errTxt,
						hideDelay: 5000
					});

				}
			}, function (evt) {
				Ctrl.Progress = parseInt(100.0 * evt.loaded / evt.total);
			});

		}

		//console.log(angular.element(document.querySelector('#Canvas')));
	}

]);
angular.module('ImportCtrl', [])
.controller('ImportCtrl', ['$scope', '$rootScope', '$http', '$mdDialog', 'Upload', 'Config',
	function($scope, $rootScope, $http, $mdDialog, Upload, Config) {

		console.info('ImportCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		var DefConfig = {
			Paso: 1,
		};
		Ctrl.Config = Config;

		Ctrl.Cancel = function(){ $mdDialog.cancel(); }

		Ctrl.Pasos = [ '',
			'Paso 1: Diligenciar la plantilla',
			'Paso 2: Verificar datos a importar',
			'Paso 3: Importando',
			'Finalizado',
			'Errores encontrados',
			'Error al cargar el archivo'
		];

		Ctrl.Config.Paso = 1;

		Ctrl.DownloadPlantilla = function(){
			$http.get(Ctrl.Config.PlantillaUrl, { responseType: 'arraybuffer' }).then(function(r) {
        		var blob = new Blob([r.data], { type: "application/vnd.ms-excel; charset=UTF-8" });
		        var filename = Ctrl.Config.PlantillaUrl.split('/').pop();
		        saveAs(blob, filename);
        	});
		};


		Ctrl.UploadTemplate = function(file, invalidfile){
			if(file) {
	            Upload.upload({
					url: '/api/Upload/file',
					data: {
						file: file,
						Path: Ctrl.Config.Upload.Path,
						Name: Ctrl.Config.Upload.Name,
					}
				}).then(function(r){
					if(r.status == 200){
						Ctrl.VerifyData();
					}else{
						Ctrl.Config.Paso = 6;
					};
				});
			};
		};

		Ctrl.VerifyData = function(){
			Ctrl.Config.Paso = 2;
			$http.post(Ctrl.Config.VerifyUrl, { Config: Ctrl.Config }).then(function(r){
				var Msgs = r.data;
				console.log(Msgs);
				if(Msgs.length == 0){
					Ctrl.Config.Paso = 3;
				}else{ //Hubo errores en la verificacion
					Ctrl.Config.Paso = 5;
					Ctrl.Errores = Msgs;
				}
			});
		}

		//Ctrl.VerifyData();

		Ctrl.DownloadErrors = function(){
			var Headers = [ 'Fila', 'Error' ];
			var e = {
        		filename: 'Errores_Importacion',
        		ext: 'xls',
        		sheets: [
        			{
						name: 'Errores',
						headers: Headers,
						rows: Ctrl.Errores,
					}
        		]
			};
			Rs.DownloadExcel(e);
		};

		//console.log(Ctrl.Config.PlantillaUrl);

	}
]);
angular.module('ListSelectorCtrl', [])
.controller('ListSelectorCtrl', ['$scope', '$rootScope', '$http', '$mdDialog', 'List', 'Config',
	function($scope, $rootScope, $http, $mdDialog, List, Config) {

		//console.info('ListSelectorCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.Config = Config;
		Ctrl.Searching = false;

		Ctrl.Cancel = function(){ $mdDialog.cancel(); }

		Ctrl.getData = function(){
			Ctrl.Searching = true;
			//Traer los datos del servidor
			$http({
				method: Ctrl.Config.remoteMethod,
				url: Ctrl.Config.remoteUrl,
				data: Ctrl.Config.remoteData,
			}).then(function(r){
				Ctrl.Searching = false;
				Ctrl.List = r.data;
			}, function(){
				Ctrl.Searching = false;
			});
		};

		//Si pasan la lista usarla
		if(List !== null){
			Ctrl.List = List;
		}else if(Ctrl.Config.remoteUrl){
			Ctrl.getData();
		};

		Ctrl.changeSearch = function(){

			if(Ctrl.Config.remoteQuery){
				if(Ctrl.Searching) return false;
				Ctrl.Config.remoteData.filter = Ctrl.Search;
				Ctrl.getData();
			}else{
				Ctrl.SearchFilter = Ctrl.Search;
			}
		}

		Ctrl.Resp = function(Row){
			$mdDialog.hide(Row);
		}


	}
]);
angular.module('EntidadesCamposCtrl', [])
.controller('EntidadesCamposCtrl', ['$scope', '$rootScope', 
	function($scope, $rootScope) {

		console.info('EntidadesCamposCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		
	}
]);
angular.module('EntidadesCtrl', [])
.controller('EntidadesCtrl', ['$scope', '$rootScope', '$injector', '$mdDialog', '$filter',
	function($scope, $rootScope, $injector, $mdDialog, $filter) {

		console.info('EntidadesCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.EntidadSidenav = true;

		Ctrl.getBdds = () => {
			Rs.http('api/Bdds/all', {}, Ctrl, 'Bdds').then(() => {
				if(Ctrl.Bdds.length > 0){
					Ctrl.BddSel = Ctrl.Bdds[0];
					//return Ctrl.testGrid(1); //QUITAR
					Ctrl.getEntidades();
				}
			});
		};

		Ctrl.EntidadesCRUD = $injector.get('CRUD').config({ base_url: '/api/Entidades', order_by: ['Nombre'] });

		Ctrl.getEntidades = () => {
			Ctrl.EntidadesCRUD.get().then(() => {
				Ctrl.openEntidad(Ctrl.EntidadesCRUD.rows[1]); //QUITAR
			});
		};

		Ctrl.getEntidad = (id) => {
			return $filter('filter')(Ctrl.EntidadesCRUD.rows, { id: id }, true)[0];
		};

		Ctrl.openEntidad = (E) => {
			//return Ctrl.verCamposDiag(1,[5]); //QUITAR
			if(!E) return;
			if(Ctrl.EntidadSel){ if(Ctrl.EntidadSel.id == E.id) return; }
			Ctrl.EntidadSel = E;
			Ctrl.getCampos();
			Ctrl.getGrids();

			Ctrl.GridColumnasCRUD.rows = [];
			Ctrl.GridSel = null;
		}

		Ctrl.addEntidad = () => {
			Ctrl.EntidadesCRUD.dialog({
				bdd_id: Ctrl.BddSel.id,
				Tipo: 'Tabla',
			}, {
				title: 'Crear Entidad',
				only: ['Nombre']
			}).then((R) => {
				if(!R) return;
				Ctrl.EntidadesCRUD.add(R);
			});
		};

		Ctrl.updateEntidad = () => {
			Ctrl.EntidadesCRUD.update(Ctrl.EntidadSel).then(() => {

				//Actualizar los campos
				Rs.http('/api/Entidades/campos-update', { Campos: Ctrl.CamposCRUD.rows }).then(() => {
					angular.forEach(Ctrl.CamposCRUD.rows, (C,index) => {
						C.changed = false;
					});
					Rs.showToast('Entidad Actualizada', 'Success');

				});

				
			});
		};


		//Campos
		Ctrl.CamposCRUD = $injector.get('CRUD').config({ base_url: '/api/Entidades/campos', order_by: ['Indice'] });

		Ctrl.getCampos = () => {
	
			Ctrl.CamposCRUD.setScope('entidad', Ctrl.EntidadSel.id);
			Ctrl.CamposCRUD.get();

			Ctrl.newCampo = angular.copy(newCampoDef);
			Ctrl.setTipoDefaults(Ctrl.newCampo);
		};

		Ctrl.camposSel = [];
		Ctrl.setTipoDefaults = (C) => {
			C.Defecto = null;
			var Defaults = Ctrl.TiposCampo[C.Tipo]['Defaults'];
			C = angular.extend(C,Defaults);
			C.changed = true;
		};

		Ctrl.markChanged = (C) => {
			C.changed = true;
		};

		var newCampoDef = {
			Columna: '',
			Alias: null,
			Requerido: false,
			Visible: true,
			Editable: true,
			Buscable: false,
			Tipo: 'Texto'
		};
		

		Ctrl.addCampo = () => {
			Ctrl.newCampo.Columna = Ctrl.newCampo.Columna.trim();

			if(Ctrl.newCampo.Columna == '') return Rs.showToast('Falta Columna', 'Error');
			if(Rs.found(Ctrl.newCampo.Columna, Ctrl.CamposCRUD.rows, 'Columna')) return;

			Ctrl.newCampo.entidad_id = Ctrl.EntidadSel.id;
			Ctrl.newCampo.Indice = Ctrl.CamposCRUD.rows.length;

			Ctrl.CamposCRUD.add(Ctrl.newCampo).then(() => {
				Ctrl.newCampo = angular.copy(newCampoDef);
				Ctrl.setTipoDefaults(Ctrl.newCampo);

				setTimeout(function(){ $("#newCampo").focus(); }, 500);
			});
		};

		Ctrl.removeCampos = () => {
			Rs.confirmDelete({
				Title: '¿Borrar '+Ctrl.camposSel.length+' campos?',
			}).then((del) => {
				console.log(del);
				if(!del) return;
				Rs.http('/api/Entidades/campos-delete', { ids: Ctrl.camposSel }).then((msg) => {
					if(msg == 'OK'){
						Ctrl.getCampos();
						Rs.showToast(Ctrl.camposSel.length+' campos eliminados');
						Ctrl.camposSel = [];
					}else{
						Rs.showToast(msg, 'Error');
					};
				});
			});
		};

		Ctrl.OpsBooleano = [
			{ Mostrar: 'Ninguno', 	Valor: null  },
			{ Mostrar: 'Verdadero', Valor: true  },
			{ Mostrar: 'Falso',     Valor: false },
		];

		Ctrl.dragListener = {
			accept: function (sourceItemHandleScope, destSortableScope) { return true; },
			orderChanged: () => {
				angular.forEach(Ctrl.CamposCRUD.rows, (C,index) => {
					if(C.Indice !== index){
						C.Indice = index;
						C.changed = true;
					};
				});
			}
		};

		Ctrl.getCamposAuto = () => {
			Rs.http('api/Entidades/campos-autoget', { Bdd: Ctrl.BddSel, Entidad: Ctrl.EntidadSel, Campos: Ctrl.CamposCRUD.rows }).then((r) => {
				if(r.length == 0) return Rs.showToast('No se encontraron nuevos campos');

				$mdDialog.show({
					controller: 'Entidades_AddColumnsCtrl',
					templateUrl: 'Frag/Entidades.Entidades_AddColumns',
					clickOutsideToClose: false,
					fullscreen: false,
					multiple: true,
					locals: { ParentCtrl: Ctrl, newCampos: r }
				}).then((newCampos) => {
					if(newCampos.length == 0) return;
					
					var current_index = Ctrl.CamposCRUD.rows.length;
					angular.forEach(newCampos, (nc) => {
						nc.Indice = current_index;
						current_index++;
					});

					Rs.http('api/Entidades/campos-add', { newCampos: newCampos }).then(() => {
						Ctrl.getCampos();
						Rs.showToast(newCampos.length+' campos agregados');
					});
				});
			});
		};


		//Grids
		Ctrl.GridsCRUD = $injector.get('CRUD').config({ base_url: '/api/Entidades/grids', order_by: ['Titulo'] });

		Ctrl.getGrids = () => {
			Ctrl.GridsCRUD.setScope('entidad', Ctrl.EntidadSel.id);
			Ctrl.GridsCRUD.get().then(() => {
				if(Ctrl.GridsCRUD.rows.length == 0) return;
				Ctrl.openGrid(Ctrl.GridsCRUD.rows[0]);
			});
		};

		Ctrl.addGrid = () => {
			Ctrl.GridsCRUD.dialog({
				entidad_id: Ctrl.EntidadSel.id,
			}, {
				title: 'Crear Grid',
				only: ['Titulo']
			}).then((R) => {
				if(!R) return;
				Ctrl.GridsCRUD.add(R);
			});
		};

		Ctrl.openGrid = (G) => {
			Ctrl.GridSel = G;
			Ctrl.getColumnas();
		};

		//Columnas
		Ctrl.GridColumnasCRUD = $injector.get('CRUD').config({ base_url: '/api/Entidades/grids-columnas', query_with:['campo'], add_append:'refresh', order_by: ['Indice'] });

		Ctrl.getColumnas = () => {
			Ctrl.GridColumnasCRUD.setScope('grid', Ctrl.GridSel.id);
			Ctrl.GridColumnasCRUD.get().then(() => {
				
			});
		};

		Ctrl.addColumna = (C, Ruta, Llaves) => {
			var Indice = Ctrl.GridColumnasCRUD.rows.length;
			return Ctrl.GridColumnasCRUD.add({
				grid_id: Ctrl.GridSel.id,
				Tipo: 'Campo', Ruta: Ruta, Llaves: Llaves, campo_id: C.id,
				Indice: Indice,
			}).then(() => {
				Rs.showToast('Columna Añadida', 'Success');
			});
		};

		Ctrl.removeColumna = (C) => {
			Ctrl.GridColumnasCRUD.delete(C);
		};

		Ctrl.dragListener2 = {
			accept: function (sourceItemHandleScope, destSortableScope) { return true; },
			orderChanged: () => {
				angular.forEach(Ctrl.GridColumnasCRUD.rows, (C,index) => {
					if(C.Indice !== index){
						C.Indice = index;
						C.changed = true;
					};
				});
			}
		};

		Ctrl.verCamposDiag = (entidad_id, Ruta, Llaves) => {
			if(!entidad_id) return;

			var Entidad = Ctrl.getEntidad(entidad_id);

			$mdDialog.show({
				controller: 'Entidades_VerCamposCtrl',
				templateUrl: 'Frag/Entidades.Entidades_VerCampos',
				clickOutsideToClose: false,
				fullscreen: false,
				multiple: true,
				locals: { ParentCtrl: Ctrl, Entidad: Entidad, Ruta: Ruta, Llaves: Llaves }
			}).then((r) => {
				Ctrl.verCamposDiag(r[0],r[1],r[2]);
			});

		};

		Ctrl.updateGrid = () => {
			Ctrl.GridsCRUD.update(Ctrl.GridSel).then(() => {

				//Actualizar los campos
				Rs.http('/api/Entidades/grids-columnas-update', { Columnas: Ctrl.GridColumnasCRUD.rows }).then(() => {
					angular.forEach(Ctrl.GridColumnasCRUD.rows, (C,index) => {
						C.changed = false;
					});
					Rs.showToast('Grid Actualizada', 'Success');
				});
				
			});
		};

		Ctrl.testGrid = (grid_id) => {
			$mdDialog.show({
				controller: 'Entidades_Grids_TestCtrl',
				templateUrl: 'Frag/Entidades.Entidades_Grids_Test',
				clickOutsideToClose: true,
				fullscreen: true,
				multiple: true,
				locals: { grid_id: grid_id }
			})
		};


		//Start Up
		Rs.http('api/Entidades/tipos-campo', {}, Ctrl, 'TiposCampo').then(Ctrl.getBdds);
        
		
	}
]);
angular.module('Entidades_AddColumnsCtrl', [])
.controller('Entidades_AddColumnsCtrl', ['$scope', '$mdDialog', 'ParentCtrl', 'newCampos',
	function($scope, $mdDialog, ParentCtrl, newCampos) {

		console.info('Entidades_AddColumnsCtrl');
		var Ctrl = $scope;

		Ctrl.CancelDiag = () => {
			$mdDialog.cancel();
		};

		Ctrl.EntidadSel = ParentCtrl.EntidadSel;
		Ctrl.newCampos = newCampos;
		Ctrl.newCamposSel = [];
		Ctrl.TiposCampo 	 = ParentCtrl.TiposCampo;
		Ctrl.markChanged 	 = ParentCtrl.markChanged;
		Ctrl.setTipoDefaults = ParentCtrl.setTipoDefaults;
		Ctrl.inArray         = ParentCtrl.inArray;

		Ctrl.addNewColumns = () => {
			$mdDialog.hide(Ctrl.newCamposSel);
		};
	}
]);
angular.module('Entidades_Grids_TestCtrl', [])
.controller('Entidades_Grids_TestCtrl', ['$scope', '$rootScope', '$mdDialog', 'grid_id', 
	function($scope, $rootScope, $mdDialog, grid_id) {

		console.info('Entidades_Grids_TestCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = () => {
			$mdDialog.cancel();
		};

		Rs.http('api/Entidades/grids-get-data', { grid_id: grid_id }).then((r) => {
			Ctrl.Grid = r.Grid;
		});
		
	}
]);
angular.module('Entidades_VerCamposCtrl', [])
.controller('Entidades_VerCamposCtrl', ['$scope', '$rootScope', '$injector', 'Entidad', 'Ruta', 'Llaves', 'ParentCtrl', '$mdDialog',
	function($scope, $rootScope, $injector, Entidad, Ruta, Llaves, ParentCtrl, $mdDialog) {

		console.info('Entidades_VerCamposCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Entidad = Entidad;
		Ctrl.TiposCampo = ParentCtrl.TiposCampo;

		Ctrl.CamposCRUD = $injector.get('CRUD').config({ base_url: '/api/Entidades/campos', order_by: ['Indice'] });

		Ctrl.CamposCRUD.setScope('entidad', Entidad.id);
		Ctrl.CamposCRUD.get();

		var DaRuta = angular.copy(Ruta);
		DaRuta.push(Entidad.id);

		Ctrl.Cancel = () => {
			$mdDialog.cancel();
		};

		Ctrl.addColumna = (C) => {
			var DaLlaves = angular.copy(Llaves);
			DaLlaves.push(C.id);
			ParentCtrl.addColumna(C, DaRuta, DaLlaves).then(() => {

			});
		};

		Ctrl.verCamposDiag = (entidad_id, campo_id) => {
			var DaLlaves = angular.copy(Llaves);
			DaLlaves.push(campo_id);
			$mdDialog.hide([entidad_id, DaRuta, DaLlaves]);
		};
	}
]);
angular.module('CRUD', [])
.factory('CRUD', [ '$rootScope', '$q', '$mdDialog', 
	function($rootScope, $q, $mdDialog){

		var Rs = $rootScope;

		var CRUD = function(ops) {
			var t = this;

			t.ops = {
				base_url: '',
				name: '',
				primary_key: 'id',
				ready: false,
				where: {},
				limit: 1000,
				loading: false,
				obj: null,
				only_columns: [],
				add_append: 'end',
				add_research: false,
				add_with: false,
				query_scopes: [],
				query_with: [],
				query_call: [],
				order_by: [],
			};
			t.columns = [];
			t.rows = [];

			angular.extend(t.ops, ops);

			//console.info('Crud initiated', t.ops);

			t.get = function(columns){
				
				if(t.ops.loading) return false;
				t.ops.loading = true;

				t.ops.only_columns = Rs.def(columns, []);
				t.rows = [];

				return Rs.http(t.ops.base_url, { fn: 'get', ops: t.ops }).then(function(r) {
					if(r.ops){
						t.columns = r.ops.columns;
						delete r.ops.columns;
						angular.extend(t.ops, r.ops);
					};
					t.rows = r.rows;
					t.ops.loading = false;
				});
			};


			t.where = function(where){
				t.ops.where[where[0]] = where;
				return t;
			};

			t.find = function(id, main, prop){
				t.ops.find_id = id;
				return Rs.http(t.ops.base_url, { fn: 'find', ops: t.ops }, main, prop);
			};

			t.add = function(Obj){
				t.ops.obj = Obj;
				return Rs.http(t.ops.base_url, { fn: 'add', ops: t.ops }).then(function(r) {
					t.ops.obj = null;
					if(t.ops.add_append == 'end'){ t.rows.push(r); }
					else if(t.ops.add_append == 'start'){ t.rows.unshift(r); }
					else if(t.ops.add_append == 'refresh'){ t.get(); };
					return r;
				});
			};

			t.update = function(Obj){
				t.ops.obj = Obj;
				return Rs.http(t.ops.base_url, { fn: 'update', ops: t.ops }).then(function(r) {
					t.ops.obj = null;
					Rs.updateArray(t.rows, r, t.ops.primary_key);
					return r;
				});
			};

			t.delete = function(Obj){
				t.ops.obj = Obj;
				var Index = Rs.getIndex(t.rows, Obj[t.ops.primary_key], t.ops.primary_key);
				return Rs.http(t.ops.base_url, { fn: 'delete', ops: t.ops }).then(function(r) {
					t.ops.obj = null;
					t.rows.splice(Index, 1);
				});
			};

			t.dialog = function(Obj, diagConfig){
				var config = {
					theme: 'default',
					title: '',
					class: 'wu400',
					controller: 'CRUDDialogCtrl',
					templateUrl: '/templates/dialogs/crud-dialog.html',
					fullscreen: false,
					clickOutsideToClose: false,
					multiple: true,
					ev: null,
					confirmText: 'Guardar',
					with_delete: true,
					delete_title: '',
					only: [],
					buttons: [],
				};

				angular.extend(config, diagConfig);

				return $mdDialog.show({
					controller:  config.controller,
					templateUrl: config.templateUrl,
					locals: 	{ ops : t.ops, config: config, columns: t.columns, Obj: Obj, rows: t.rows },
					clickOutsideToClose: config.clickOutsideToClose,
					fullscreen:  config.fullscreen,
					multiple: 	 config.multiple,
					targetEvent: config.ev
				});
			};

			//Poner un scope
			t.setScope = (Scope, Params) => {
				var Index = -1;
				angular.forEach(t.ops.query_scopes, ($S, $k) => {
					if($S[0] == Scope){ Index = $k; return; }
				});
				if(Index == -1){
					t.ops.query_scopes.push([ Scope, Params ]);
				}else{
					t.ops.query_scopes[Index] = [ Scope, Params ];
				};
			};


		};

		return {
			config: function (ops) {
				//console.log('Creating', ops);
				var DaCRUD = new CRUD(ops);
				return DaCRUD;
			}
		};
	}
]);
angular.module('Filters', [])
	.filter('to_trusted', ['$sce', function($sce){
		return function(text) {
			return $sce.trustAsHtml(text);
		};
	}])
	.filter('findId', function() {
		return function(input, id) {
			var i=0, len=input.length;
			for (; i<len; i++) {
			  if (+input[i].id == +id) {
				return input[i];
			  }
			}
			return null;
		 };
	}).filter('getIndex', function() {
		return function(input, id, attr) {
			var len=input.length;
			attr = (typeof attr !== 'undefined') ? attr : 'id';
			for (i=0; i<len; i++) {
			  if(input[i][attr] === id) {
				return i;
			  }
			}
			return null;
		 };
	}).filter('exclude', function() {
		return function(input, exclude, prop) {
			if (!angular.isArray(input))
				return input;

			if (!angular.isArray(exclude))
				exclude = [];

			if (prop) {
				exclude = exclude.map(function byProp(item) {
					return item[prop];
				});
			}

			return input.filter(function byExclude(item) {
				return exclude.indexOf(prop ? item[prop] : item) === -1;
			});
		};
	}).filter('category', function() {
		return function(input, category, prop) {
			//console.log(input, category, prop);
			if (!angular.isArray(input)) return input;
			if(!category) return input;
			return input.filter(function(item){
				return item[prop] == category;
			});
			//return input[prop] == category;
		};
	}).filter('toArray', function () {
		return function (obj, addKey) {
			if (!angular.isObject(obj)) return obj;
			if ( addKey === false ) {
			return Object.keys(obj).map(function(key) {
				return obj[key];
			});
			} else {
			return Object.keys(obj).map(function (key) {
				var value = obj[key];
				return angular.isObject(value) ?
				Object.defineProperty(value, '$key', { enumerable: false, value: key}) :
				{ $key: key, $value: value };
			});
			}
		};
	}).filter('pluck', function() {
		return function(array, key, unique) {
			var res = new Array();
			angular.forEach(array, function(v) {
				if(unique && res.indexOf(v[key]) !== -1) return false;
				res.push(v[key]);
			});
			return res;
		};
	}).filter('search', function() {
		return function(input, search) {
			if (!input) return input;
			if (!search) return input;
			var expected = ('' + search).toLowerCase();
			var result = {};
			angular.forEach(input, function(value, key) {
				var actual = ('' + value).toLowerCase();
				if (actual.indexOf(expected) !== -1) {
					result[key] = value;
				}
			});
			return result;
		}
	}).filter('capitalize', function() {
	    return function(input) {
	      return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
	    }
	}).filter('capitwords', function() {
	    return function(input,limit) {
	    	if (!input) return '';
	    	input = input.split('_').join(' ');
	    	limit = (!!limit) ? limit : 2;
	    	return input.split(' ').map(function(wrd){
	    		return (wrd.length) > limit ? wrd.charAt(0).toUpperCase() + wrd.substr(1).toLowerCase() : wrd.toLowerCase();
	    	}).join(' ');
	    }
	}).filter('traducirum', function() {
	    return function(input,um) {
	    	if(input <= 0){
	    		return 'No incluido';
	    	}else if(um == 'KG'){
	    			 if(input < 1){ input = input*1000; um = 'Gramos'  }
	    		else if(input == 1){ um = 'Kilo'  }
	    		else if(input > 1 && input < 1000 ){ um = 'Kilos'  }
	    		else if(input >= 1000){ input = input/1000; um = 'Toneladas'  }
	    	}else if(um == 'LT'){
	    			 if(input < 1){ input = input*1000; um = 'Mililitros'  }
	    		else if(input == 1){ um = 'Litro'  }
	    		else if(input > 1 ){ um = 'Litros'  }
	    	}else if(um == 'UN'){
	    			 if(input <= 1){ um = 'Unidad'  }
	    		else if(input > 1 ){ um = 'Unidades'  }
	    	};
	    	return input + ' ' + um;
	    }
	}).filter('percentage', ['$filter', function ($filter) {
		return function (input, decimals) {
		return $filter('number')(input * 100, decimals) + '%';
		};
	}]);
// Reacts upon enter key press.
angular.module('enterStroke', []).directive('enterStroke',
  function () {
    return function (scope, element, attrs) {
      element.bind('keydown keypress', function (event) {
        if(event.which === 13) {
          scope.$apply(function () {
            scope.$eval(attrs.enterStroke);
          });
          event.preventDefault();
        }
      });
    };
  }
);
angular.module('fileread', [])
.directive("fileread", [function () {
    return {
        scope: {
            fileread: "="
        },
        link: function (scope, element, attributes) {
            element.bind("change", function (changeEvent) {
                var reader = new FileReader();
                reader.onload = function (loadEvent) {
                    scope.$apply(function () {
                        scope.fileread = JSON.parse(loadEvent.target.result);
                    });
                }
                reader.readAsDataURL(changeEvent.target.files[0]);
            });
        }
    }
}]);
angular.module('focusOn', [])
.directive('focusOn', function() {
   return function(scope, elem, attr) {
      scope.$on(attr.focusOn, function(e) {
      		setTimeout(function(){ 
      			elem[0].focus();
          		console.log('Focused', elem);
      		}, 3000);
      });
   };
});
angular.module('horizontalScroll', []).
directive('horizontalScroll', function () {

    return {
        link:function (scope, element, attrs) {
            var base = 0

            element.bind("DOMMouseScroll mousewheel onmousewheel", function(event) {

                // cross-browser wheel delta
                var event = window.event || event; // old IE support
                var delta = Math.max(-1, Math.min(1, (event.wheelDelta || -event.detail)));


                scope.$apply(function(){
                    base += (30*delta);
                    //console.log(element, base);
                    element.children().css({'transform':'translateX('+base+'px)'});
                    //element.scrollLeft(base);
                });

                // for IE
                event.returnValue = false;
                // for Chrome and Firefox
                if(event.preventDefault) { event.preventDefault(); }


            });
        }
    };
});
angular.module('hoverClass', [])
.directive('hoverClass', [function () {
    return {
        restrict: 'A',
        scope: {
            hoverClass: '@'
        },
        link: function (scope, element) {
            element.on('mouseenter', function() {
                element.addClass(scope.hoverClass);
            });
            element.on('mouseleave', function() {
                element.removeClass(scope.hoverClass);
            });
        }
    };
}]);
(function () {
    'use strict';

    angular.module('ngJsonExportExcel', [])
        .directive('ngJsonExportExcel', function () {
            return {
                restrict: 'AE',
                scope: {
                    data : '=',
                    filename: '=?',
                    reportFields: '=',
                    separator: '@'
                },
                link: function (scope, element) {
                    scope.filename = !!scope.filename ? scope.filename : 'export-excel';
                    scope.extension = !!scope.extension ? scope.extension : '.csv';

                    var fields = [];
                    var header = [];
                    var separator = scope.separator || ';';

                    angular.forEach(scope.reportFields, function(field, key) {
                        if(!field || !key) {
                            throw new Error('error json report fields');
                        }

                        fields.push(key);
                        header.push(field);
                    });

                    element.bind('click', function() {
                        var bodyData = _bodyData();
                        var strData = _convertToExcel(bodyData);

                        var blob = new Blob([strData], { 
                            type: "text/plain;charset=utf-8"
                            //type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                        });

                        return saveAs(blob, [scope.filename + scope.extension ]);
                    });

                    function _bodyData() {
                        var data = scope.data;
                        var body = "";
                        angular.forEach(data, function(dataItem) {
                            var rowItems = [];

                            angular.forEach(fields, function(field) {
                                if(field.indexOf('.')) {
                                    field = field.split(".");
                                    var curItem = dataItem;

                                    // deep access to obect property
                                    angular.forEach(field, function(prop){
                                        if (curItem !== null && curItem !== undefined) {
                                            curItem = curItem[prop];
                                        }
                                    });

                                    data = curItem;
                                }
                                else {
                                    data = dataItem[field];
                                }

                                var fieldValue = data !== null ? data : ' ';

                                if (fieldValue !== undefined && angular.isObject(fieldValue)) {
                                    fieldValue = _objectToString(fieldValue);
                                }

                                rowItems.push(fieldValue);
                            });

                            body += rowItems.join(separator) + '\n';
                        });

                        return body;
                    }

                    function _convertToExcel(body) {
                        return header.join(separator) + '\n' + body;
                    }

                    function _objectToString(object) {
                        var output = '';
                        angular.forEach(object, function(value, key) {
                            output += key + ':' + value + ' ';
                        });

                        return '"' + output + '"';
                    }
                }
            };
        });
})();
/*! ng-csv 10-10-2015 */
!function(a){angular.module("ngCsv.config",[]).value("ngCsv.config",{debug:!0}).config(["$compileProvider",function(a){angular.isDefined(a.urlSanitizationWhitelist)?a.urlSanitizationWhitelist(/^\s*(https?|ftp|mailto|file|data):/):a.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|file|data):/)}]),angular.module("ngCsv.directives",["ngCsv.services"]),angular.module("ngCsv.services",[]),angular.module("ngCsv",["ngCsv.config","ngCsv.services","ngCsv.directives","ngSanitize"]),"undefined"!=typeof module&&"undefined"!=typeof exports&&module.exports===exports&&(module.exports="ngCsv"),angular.module("ngCsv.services").service("CSV",["$q",function(a){var b="\r\n",c="﻿",d={"\\t":"	","\\b":"\b","\\v":"","\\f":"\f","\\r":"\r"};this.stringifyField=function(a,b){return"locale"===b.decimalSep&&this.isFloat(a)?a.toLocaleString():"."!==b.decimalSep&&this.isFloat(a)?a.toString().replace(".",b.decimalSep):"string"==typeof a?(a=a.replace(/"/g,'""'),(b.quoteStrings||a.indexOf(",")>-1||a.indexOf("\n")>-1||a.indexOf("\r")>-1)&&(a=b.txtDelim+a+b.txtDelim),a):"boolean"==typeof a?a?"TRUE":"FALSE":a},this.isFloat=function(a){return+a===a&&(!isFinite(a)||Boolean(a%1))},this.stringify=function(d,e){var f=a.defer(),g=this,h="",i="",j=a.when(d).then(function(a){if(angular.isDefined(e.header)&&e.header){var d,j;d=[],angular.forEach(e.header,function(a){this.push(g.stringifyField(a,e))},d),j=d.join(e.fieldSep?e.fieldSep:","),i+=j+b}var k=[];if(angular.isArray(a)?k=a:angular.isFunction(a)&&(k=a()),angular.isDefined(e.label)&&e.label&&"boolean"==typeof e.label){var l,m;l=[],angular.forEach(k[0],function(a,b){this.push(g.stringifyField(b,e))},l),m=l.join(e.fieldSep?e.fieldSep:","),i+=m+b}angular.forEach(k,function(a,c){var d,f,h=angular.copy(k[c]);f=[];var j=e.columnOrder?e.columnOrder:h;angular.forEach(j,function(a){var b=e.columnOrder?h[a]:a;this.push(g.stringifyField(b,e))},f),d=f.join(e.fieldSep?e.fieldSep:","),i+=c<k.length?d+b:d}),e.addByteOrderMarker&&(h+=c),h+=i,f.resolve(h)});return"function"==typeof j["catch"]&&j["catch"](function(a){f.reject(a)}),f.promise},this.isSpecialChar=function(a){return void 0!==d[a]},this.getSpecialChar=function(a){return d[a]}}]),angular.module("ngCsv.directives").directive("ngCsv",["$parse","$q","CSV","$document","$timeout",function(b,c,d,e,f){return{restrict:"AC",scope:{data:"&ngCsv",filename:"@filename",header:"&csvHeader",columnOrder:"&csvColumnOrder",txtDelim:"@textDelimiter",decimalSep:"@decimalSeparator",quoteStrings:"@quoteStrings",fieldSep:"@fieldSeparator",lazyLoad:"@lazyLoad",addByteOrderMarker:"@addBom",ngClick:"&",charset:"@charset",label:"&csvLabel"},controller:["$scope","$element","$attrs","$transclude",function(a,b,e){function f(){var b={txtDelim:a.txtDelim?a.txtDelim:'"',decimalSep:a.decimalSep?a.decimalSep:".",quoteStrings:a.quoteStrings,addByteOrderMarker:a.addByteOrderMarker};return angular.isDefined(e.csvHeader)&&(b.header=a.$eval(a.header)),angular.isDefined(e.csvColumnOrder)&&(b.columnOrder=a.$eval(a.columnOrder)),angular.isDefined(e.csvLabel)&&(b.label=a.$eval(a.label)),b.fieldSep=a.fieldSep?a.fieldSep:",",b.fieldSep=d.isSpecialChar(b.fieldSep)?d.getSpecialChar(b.fieldSep):b.fieldSep,b}a.csv="",angular.isDefined(a.lazyLoad)&&"true"==a.lazyLoad||angular.isArray(a.data)&&a.$watch("data",function(){a.buildCSV()},!0),a.getFilename=function(){return a.filename||"download.csv"},a.buildCSV=function(){var g=c.defer();return b.addClass(e.ngCsvLoadingClass||"ng-csv-loading"),d.stringify(a.data(),f()).then(function(c){a.csv=c,b.removeClass(e.ngCsvLoadingClass||"ng-csv-loading"),g.resolve(c)}),a.$apply(),g.promise}}],link:function(b,c){function d(){var c=b.charset||"utf-8",d=new Blob([b.csv],{type:"text/csv;charset="+c+";"});if(a.navigator.msSaveOrOpenBlob)navigator.msSaveBlob(d,b.getFilename());else{var g=angular.element('<div data-tap-disabled="true"><a></a></div>'),h=angular.element(g.children()[0]);h.attr("href",a.URL.createObjectURL(d)),h.attr("download",b.getFilename()),h.attr("target","_blank"),e.find("body").append(g),f(function(){h[0].click(),h.remove()},null)}}c.bind("click",function(){b.buildCSV().then(function(){d()}),b.$apply()})}}}])}(window,document);
angular.module('ngRightClick', [])
.directive('ngRightClick', ['$parse', function($parse){
	return function(scope, element, attrs) {
        var fn = $parse(attrs.ngRightClick);
        element.bind('contextmenu', function(event) {
            scope.$apply(function() {
                event.preventDefault();
                fn(scope, {$event:event});
            });
        });
    };
}]);
// Reacts upon enter key press.
angular.module('printThis', []).directive('printThis',
  function () {
    return function (scope, element, attrs) {
      element.bind('click', function (event) {
          event.preventDefault();
          //console.log(_config);

          //return false;

          $(attrs.printThis).printThis({
          		debug: false,
              importStyle: true,
          });
      });
    };
  }
);
angular.module('SARA', [
	'ui.router',

	'ngStorage',
	'ngMaterial',
	'ngSanitize',

	'md.data.table',
	'ngFileUpload',
	'angular-loading-bar',
	'angularResizable',
	'nvd3',
	'ui.utils.masks',
	'as.sortable',
	'ngCsv',
	'angular-img-cropper',

	'appRoutes',
	'appConfig',
	'appFunctions',
	'CRUD',
	'CRUDDialogCtrl',
	
	'Filters',
	'enterStroke',
	'printThis',
	'ngRightClick',
	'fileread',
	'hoverClass',
	'horizontalScroll',
	'focusOn',

	'BasicDialogCtrl',
	'ConfirmCtrl',
	'ConfirmDeleteCtrl',
	'ListSelectorCtrl',
	'FileDialogCtrl',
	'ImageEditor_DialogCtrl',

	'MainCtrl',
	'LoginCtrl',

	'InicioCtrl',

	'BDDCtrl',

	
	'EntidadesCtrl',
		'Entidades_AddColumnsCtrl',
		'Entidades_VerCamposCtrl',
		'Entidades_Grids_TestCtrl',
]);

angular.module('appConfig', [])
.config(['$mdThemingProvider', '$mdIconProvider', '$mdDateLocaleProvider', 'cfpLoadingBarProvider', '$compileProvider', 
	function($mdThemingProvider, $mdIconProvider, $mdDateLocaleProvider, cfpLoadingBarProvider, $compileProvider){

		//Definicion de paletas
		$mdThemingProvider.definePalette('Sea', {
			'50': '#cce0ef',
			'100': '#92bedc',
			'200': '#67a4ce',
			'300': '#3a83b4',
			'400': '#32729d',
			'500': '#2b6186',
			'600': '#24506f',
			'700': '#1c3f58',
			'800': '#152f40',
			'900': '#0d1e29',
			'A100': '#cce0ef',
			'A200': '#92bedc',
			'A400': '#32729d',
			'A700': '#1c3f58',
			'contrastDefaultColor': 'light',
			'contrastDarkColors': '50 100 200 A100 A200'
		});

		$compileProvider.aHrefSanitizationWhitelist(/^\s*(blob|http|https?):/);

		//$mdAriaProvider.disableWarnings();

		$mdThemingProvider.definePalette('Gold', {
			'50': '#ffffff',
			'100': '#fcf3df',
			'200': '#f6e0ad',
			'300': '#efc86c',
			'400': '#ecbe51',
			'500': '#e9b435',
			'600': '#e6aa19',
			'700': '#cb9616',
			'800': '#af8113',
			'900': '#936d10',
			'A100': '#ffffff',
			'A200': '#fcf3df',
			'A400': '#ecbe51',
			'A700': '#cb9616',
			'contrastDefaultColor': 'light',
			'contrastDarkColors': '50 100 200 300 400 500 600 700 800 A100 A200 A400 A700'
		});


		$mdThemingProvider.theme('default')
			.primaryPalette('blue', { 'default' : '900' })
			.accentPalette('yellow', { 'default' : '800' });

		$mdThemingProvider.theme('Money', 'default')
			.primaryPalette('teal', { 'default' : '800' })
			.accentPalette('orange');

		$mdThemingProvider.theme('Ocean', 'default')
			.primaryPalette('Sea')
			.accentPalette('Gold');

		$mdThemingProvider.theme('Danger', 'default')
			.primaryPalette('red')
			.accentPalette('yellow');

		$mdThemingProvider.theme('Snow_White', 'default')
			.primaryPalette('grey', { 'default' : '100' })
			.accentPalette('orange');

		$mdThemingProvider.theme('Greyshade', 'default')
			.primaryPalette('grey', { 'default' : '800' })
			.accentPalette('Gold').dark();

		$mdThemingProvider.theme('Black', 'default')
			.primaryPalette('grey', { 'default' : '900' })
			.accentPalette('Gold').dark();

		$mdThemingProvider.theme('Transparent', 'default')
			.primaryPalette('grey', { 'default' : '900' })
			.accentPalette('grey').dark();

		$mdThemingProvider.theme('Valak', 'default')
			.primaryPalette('blue-grey', { 'default' : '700' })
			.accentPalette('red');

		//Calendar
		$mdDateLocaleProvider.months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre' ];
		$mdDateLocaleProvider.shortMonths = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
		$mdDateLocaleProvider.days = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado' ];
		$mdDateLocaleProvider.shortDays = ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'];

		$mdDateLocaleProvider.parseDate = function(dateString) {
			var m = moment(dateString, 'L', true);
			return m.isValid() ? m.toDate() : new Date(NaN);
		};
		$mdDateLocaleProvider.formatDate = function(date) {
			if(typeof date == 'undefined' || date === null || isNaN(date.getTime()) ){
				return null;
			}else{
				return moment(date).format('L');
			}
		};

		$mdDateLocaleProvider.firstDayOfWeek = 1;

		// In addition to date display, date components also need localized messages
		// for aria-labels for screen-reader users.
		$mdDateLocaleProvider.weekNumberFormatter = function(weekNumber) {
			return 'Semana ' + weekNumber;
		};
		$mdDateLocaleProvider.msgCalendar = 'Calendario';
		$mdDateLocaleProvider.msgOpenCalendar = 'Abrir el Calendario';


		//Loading Bar
		cfpLoadingBarProvider.includeSpinner = false;
		cfpLoadingBarProvider.latencyThreshold = 300;

		var icons = {
			'md-plus' 			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>',
			'md-close' 			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>',
			'md-arrow-back' 	: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>',
			'md-apps' 			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><g transform="translate(3, 3)"><circle cx="2" cy="2" r="2"></circle><circle cx="2" cy="9" r="2"></circle><circle cx="2" cy="16" r="2"></circle><circle cx="9" cy="2" r="2"></circle><circle cx="9" cy="9" r="2"></circle><circle cx="16" cy="2" r="2"></circle><circle cx="16" cy="9" r="2"></circle><circle cx="16" cy="16" r="2"></circle><circle cx="9" cy="16" r="2"></circle></g></svg>',
			'md-enter' 			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M11 5v5.59H7.5l4.5 4.5 4.5-4.5H13V5h-2zm-5 9c0 3.31 2.69 6 6 6s6-2.69 6-6h-2c0 2.21-1.79 4-4 4s-4-1.79-4-4H6z"/></svg>',
			'md-save' 			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>',
			'md-delete' 		: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>',
			'md-bars' 			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>',
			'md-more-v' 		: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>',
			'md-more-h'			: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>',
			'md-search'			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/><path d="M0 0h24v24H0z" fill="none"/></svg>',
			'md-chevron-down' 	: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6z"/><path d="M0 0h24v24H0z" fill="none"/></svg>',
			'md-check'			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>',
			'md-edit'			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/><path d="M0 0h24v24H0z" fill="none"/></svg>',
			'md-settings'		: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M19.43 12.98c.04-.32.07-.64.07-.98s-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.23-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98s.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.23.09.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65zM12 15.5c-1.93 0-3.5-1.57-3.5-3.5s1.57-3.5 3.5-3.5 3.5 1.57 3.5 3.5-1.57 3.5-3.5 3.5z"/></svg>',
			'md-reorder'		: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M3 15h18v-2H3v2zm0 4h18v-2H3v2zm0-8h18V9H3v2zm0-6v2h18V5H3z"/></svg>',
			'md-drag-handle'	: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" ><defs><path id="a" d="M0 0h24v24H0V0z"/></defs><clipPath id="b"><use xlink:href="#a" overflow="visible"/></clipPath><path clip-path="url(#b)" d="M20 9H4v2h16V9zM4 15h16v-2H4v2z"/></svg>',
			'md-format-quote'   : '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M7.17 17c.51 0 .98-.29 1.2-.74l1.42-2.84c.14-.28.21-.58.21-.89V8c0-.55-.45-1-1-1H5c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1h2l-1.03 2.06c-.45.89.2 1.94 1.2 1.94zm10 0c.51 0 .98-.29 1.2-.74l1.42-2.84c.14-.28.21-.58.21-.89V8c0-.55-.45-1-1-1h-4c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1h2l-1.03 2.06c-.45.89.2 1.94 1.2 1.94z"/></svg>',
			'md-insert-comment' : '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4V4c0-1.1-.9-2-2-2zm-3 12H7c-.55 0-1-.45-1-1s.45-1 1-1h10c.55 0 1 .45 1 1s-.45 1-1 1zm0-3H7c-.55 0-1-.45-1-1s.45-1 1-1h10c.55 0 1 .45 1 1s-.45 1-1 1zm0-3H7c-.55 0-1-.45-1-1s.45-1 1-1h10c.55 0 1 .45 1 1s-.45 1-1 1z"/></svg>',
			'md-calendar' 		: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M20 3h-1V1h-2v2H7V1H5v2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 18H4V8h16v13z"/><path fill="none" d="M0 0h24v24H0z"/></svg>',
			'md-calendar-event' : '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/><path d="M0 0h24v24H0z" fill="none"/></svg>',
			'md-time'			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/><path d="M0 0h24v24H0z" fill="none"/><path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>',
			'md-timer'			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M15 1H9v2h6V1zm-4 13h2V8h-2v6zm8.03-6.61l1.42-1.42c-.43-.51-.9-.99-1.41-1.41l-1.42 1.42C16.07 4.74 14.12 4 12 4c-4.97 0-9 4.03-9 9s4.02 9 9 9 9-4.03 9-9c0-2.12-.74-4.07-1.97-5.61zM12 20c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7z"/></svg>',
			'md-pawn'			: '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg"><g><path stroke="null" id="svg_1" d="m9.651737,9.946874l-1.073613,0a0.684375,0.684375 0 0 0 -0.684375,0.684375l0,1.36875a0.684375,0.684375 0 0 0 0.684375,0.684375l0.684375,0l0,0.234826c0,1.882031 -0.177082,3.70418 -1.026563,5.240174l7.528126,0c-0.850764,-1.535994 -1.026563,-3.358143 -1.026563,-5.240174l0,-0.234826l0.684375,0a0.684375,0.684375 0 0 0 0.684375,-0.684375l0,-1.36875a0.684375,0.684375 0 0 0 -0.684375,-0.684375l-1.073613,0c1.257111,-0.786176 2.100176,-2.172035 2.100176,-3.764063a4.448438,4.448438 0 0 0 -8.896876,0c0,1.592027 0.843065,2.977887 2.100176,3.764063zm8.507637,9.581251l-12.318751,0a0.684375,0.684375 0 0 0 -0.684375,0.684375l0,1.36875a0.684375,0.684375 0 0 0 0.684375,0.684375l12.318751,0a0.684375,0.684375 0 0 0 0.684375,-0.684375l0,-1.36875a0.684375,0.684375 0 0 0 -0.684375,-0.684375z" fill="currentColor"/></g></svg>',
			'md-toggle-on'		: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M17 7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h10c2.76 0 5-2.24 5-5s-2.24-5-5-5zm0 8c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z"/><path fill="none" d="M0 0h24v24H0z"/></svg>',
			'md-money'			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>',
			'my-entero'			: '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg"><g><text style="cursor: move;" font-weight="bold" stroke="#000" transform="matrix(0.8789344025030082,0,0,0.8789344025030082,-0.006719467772585017,1.6922866269988484) " xml:space="preserve" text-anchor="start" font-family="Helvetica, Arial, sans-serif" font-size="24" id="svg_1" y="20.046582" x="6.90954" stroke-width="0" fill="#757575">#</text></g></svg>',
			'my-decimal'		: '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg"><g><text font-weight="bold" stroke="#000" transform="matrix(0.8789344025030082,0,0,0.8789344025030082,-0.006719467772585017,1.6922866269988484) " xml:space="preserve" text-anchor="start" font-family="Helvetica, Arial, sans-serif" font-size="19" id="svg_1" y="18.308238" x="0.457763" stroke-width="0" fill="#757575">.01</text></g></svg>',
			'md-color'			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M24 0H0v24h24z" fill="none"/><path d="M17.66 7.93L12 2.27 6.34 7.93c-3.12 3.12-3.12 8.19 0 11.31C7.9 20.8 9.95 21.58 12 21.58c2.05 0 4.1-.78 5.66-2.34 3.12-3.12 3.12-8.19 0-11.31zM12 19.59c-1.6 0-3.11-.62-4.24-1.76C6.62 16.69 6 15.19 6 13.59s.62-3.11 1.76-4.24L12 5.1v14.49z"/></svg>',
		};

		iconp = $mdIconProvider.defaultFontSet( 'fa' );

		angular.forEach(icons, function(icon, k) {
			iconp.icon(k, 'data:image/svg+xml, '+icon, 24);
		});
  }
]);


/**
 * Available palettes: red, pink, purple, deep-purple, indigo, blue, light-blue, cyan, teal, 
 * green, light-green, lime, yellow, amber, orange, deep-orange, brown, grey, blue-grey
 */
angular.module('appFunctions', [])
.factory('appFunctions', [ '$rootScope', '$http', '$mdDialog', '$mdSidenav', '$mdToast', '$q', '$state', '$location', '$filter', 
	function($rootScope, $http, $mdDialog, $mdSidenav, $mdToast, $q, $state, $location, $filter){

		var Rs = $rootScope;

		//State
		Rs.stateChanged = function(){
			Rs.State = $state.current;
			Rs.State.route = $location.path().split('/');

			/*if(Rs.State.route.length > 2){
				Rs.State.tabSelected = Rs.Sections[Rs.State.route[2]]['No'];
			};*/

		};
		Rs.navTo = function(Dir, params){ $state.go(Dir, params); };
		Rs.Refresh = function() { $state.go($state.current, $state.params, {reload: true}); };



		//Helpers
		Rs.def = function(arg, def) {
			return (typeof arg == 'undefined' ? def : arg);
		};

		Rs.getSize = function(obj) {
			if(typeof obj !== "undefined" && typeof obj !== "null"){
				return Object.keys(obj).length;
			}
		};

		Rs.inArray = function (item, array) {
			return (-1 !== array.indexOf(item));
		};

		Rs.getIndex = function(array, keyval, key){
			var key = Rs.def(key, 'id');
			return $filter('getIndex')(array, keyval, key);
		};

		Rs.updateArray = function(array, newelm, key){
			var key = Rs.def(key, 'id');
			var keyval = newelm[key];
			var I = Rs.getIndex(array, keyval, key);
			array[I] = newelm;
		};

		Rs.http = function(url, data, scp, prop, method){
			var method = Rs.def(method, 'POST');
			var data = Rs.def(data, {});
			var prop = Rs.def(prop, false);

			return $q(function(res, rej) {
				$http({
					method: method,
					url: url,
					data: data
				}).then(function(r){
					if(prop) scp[prop] = r.data;
					res(r.data);
				}, function(r){
					Rs.showToast(r.data.Msg, 'Error');
					rej(r.data);
				});
			});
		};

		Rs.found = function(needle, haysack, key, msg, except){
			var except = Rs.def(except, false);
			var Found = false;

			angular.forEach(haysack, function(elm){
				if(elm[key].toUpperCase().trim() == needle.toUpperCase().trim()){
					if(except){
						if(elm[except[0]] != except[1]) Found = true;
					}else{
						Found = true;
					}
				};
			});
			if(Found){
				var msg = Rs.def(msg, needle+' ya existe.');
				if(msg !== '') Rs.showToast(msg, 'Error');
			}
			return Found;
		};

		Rs.prepFields = function(Fields, Model){
			var Model = Rs.def(Model, {});
			angular.forEach(Fields, function(F, i){
				Model[F['Nombre']] = F['Value'];
			});
			return Model;
		};


		Rs.download = function(strData, strFileName, strMimeType) {
			var D = document,
			    a = D.createElement("a");
			    strMimeType= strMimeType || "application/octet-stream";

			if (navigator.msSaveBlob) { // IE10
			    return navigator.msSaveBlob(new Blob([strData], {type: strMimeType}), strFileName);
			};

			if ('download' in a) { //html5 A[download]
			    a.href = "data:" + strMimeType + "," + encodeURIComponent(strData);
			    a.setAttribute("download", strFileName);
			    a.innerHTML = "downloading...";
			    D.body.appendChild(a);
			    setTimeout(function() {
			        a.click();
			        D.body.removeChild(a);
			    }, 66);
			    return true;
			};

			//do iframe dataURL download (old ch+FF):
			var f = D.createElement("iframe");
			D.body.appendChild(f);
			f.src = "data:" +  strMimeType   + "," + encodeURIComponent(strData);

			setTimeout(function() {
			    D.body.removeChild(f);
			}, 333);

			return true;
		};



		//Sidenav
		Rs.toogleSidenav = function(navID){
			$mdSidenav(navID).toggle();
		};



		//Quick Lauch
		Rs.showToast = function(Msg, Type, Delay, Position){
			var Type = Rs.def(Type, 'Normal');
			var Delay = Rs.def(Delay, 5000);
			var Position = Rs.def(Position, 'bottom left')

			var Templates = {
				Normal: '<md-toast class="md-toast-normal"><span flex>' + Msg + '<span></md-toast>',
				Error:  '<md-toast class="md-toast-error"><span flex>' + Msg + '<span></md-toast>',
				Success:  '<md-toast class="md-toast-success"><span flex>' + Msg + '<span></md-toast>',
			};
			return $mdToast.show({
				template: Templates[Type],
				hideDelay: Delay,
				position: Position
			});
		};





		//Dialogs
		Rs.BasicDialog = function(params) {
			var DefConfig = {
				Theme: 'default',
				Flex: 30,
				Title: 'Crear',
				Fields: [
					{ Nombre: 'Nombre',  Value: '', Required: true }
				],
				Confirm: { Text: 'Crear' },
				HasDelete: false,
				controller: 'BasicDialogCtrl',
				templateUrl: '/templates/dialogs/basic-string.html',
				fullscreen: true,
				clickOutsideToClose: true,
				multiple: true,
			};

			var Config = angular.extend(DefConfig, params);

			return $mdDialog.show({
				controller: Config.controller,
				templateUrl: Config.templateUrl,
				locals: { Config : Config },
				clickOutsideToClose: Config.clickOutsideToClose,
				fullscreen: Config.fullscreen,
				multiple: Config.multiple,
			});
		};

		Rs.prepFields = (Fields) => {
			var F = {};
			angular.forEach(Fields, (i) => {
				F[i.Nombre] = i.Value;
			});

			return F;
		};

		Rs.ListSelector = function(List, Config, ev){
			var List = Rs.def(List, null);
			var DefConfig = {
				controller: 'ListSelectorCtrl',
				templateUrl: '/templates/dialogs/ListSelector.html',
				clickOutsideToClose: true,
				hasBackdrop: true,
				fullscreen: false,
				parent: null,
				remoteUrl: false,
				remoteMethod: 'POST',
				remoteData: {},
				remoteQuery: false,
				remoteListName: 'Nombre',
				remoteListLogo: 'Logo',
				searchPlaceholder: 'Buscar',
			};
			var Config = angular.extend(DefConfig, Config);

			return $mdDialog.show({
				controller: Config.controller,
				templateUrl: Config.templateUrl,
				locals: { Config : Config, List: List },
				clickOutsideToClose: Config.clickOutsideToClose,
				fullscreen: Config.fullscreen,
				multiple: Config.multiple,
				parent: Config.parent,
			});
		};
		
		Rs.Confirm = function(params){
			var DefConfig = {
				Theme: 'default',
				Titulo: '¿Seguro que desea realizar esta acción?',
				Detail: '',
				Buttons: [
					{ Text: 'Ok', Class: 'md-raised md-primary', Value: true }
				],
				Icon: false,
				hasCancel: true,
				CancelText: 'Cancelar',
				controller: 'ConfirmCtrl',
				templateUrl: '/templates/dialogs/confirm.html',
				fullscreen: false,
				clickOutsideToClose: true,
				multiple: true
			};

			var Config = angular.extend(DefConfig, params);

			return $mdDialog.show({
				controller: Config.controller,
				templateUrl: Config.templateUrl,
				locals: { Config : Config },
				clickOutsideToClose: Config.clickOutsideToClose,
				fullscreen: Config.fullscreen,
				multiple: Config.multiple,
			});
		};

		Rs.confirmDelete = function(params){
			var DefConfig = {
				Theme: 'Danger',
				Title: '¿Eliminar?',
				Detail: 'Esta acción no se puede deshacer',
				ConfirmText: 'Eliminar',
				controller: 'ConfirmDeleteCtrl',
				templateUrl: '/templates/dialogs/confirm-delete.html',
				fullscreen: false,
				clickOutsideToClose: true,
				multiple: true,
			};

			var Config = angular.extend(DefConfig, params);

			return $mdDialog.show({
				controller: Config.controller,
				templateUrl: Config.templateUrl,
				locals: { Config : Config },
				clickOutsideToClose: Config.clickOutsideToClose,
				fullscreen: Config.fullscreen,
				multiple: Config.multiple,
			});
		};

		Rs.getItemsVal = (Items, Comparator, Prop) => {
			var Elm = $filter('filter')(Rs[Items],Comparator)[0];
			//console.log(Items,Comparator,Elm);
			return Elm[Prop];
		};




		return {};
  }
]);
angular.module('appRoutes', [])
.config(['$stateProvider', '$urlRouterProvider', '$httpProvider', 
	function($stateProvider, $urlRouterProvider, $httpProvider){
	
			$stateProvider
					.state('Login', {
						url: '/Login',
						templateUrl: '/Login',
						controller: 'LoginCtrl',
					})
					.state('Home', {
						url: '/Home',
						templateUrl: '/Home',
						controller: 'MainCtrl',
						resolve: {
							promiseObj:  function($rootScope, $localStorage, $http, $location, $q){

								var Rs = $rootScope;
								Rs.Storage = $localStorage;

								return $http.post('api/Usuario/check-token', { token: Rs.Storage.token });
							},
							controller: function($rootScope, $localStorage, promiseObj){
								var Rs = $rootScope;
								Rs.Usuario = promiseObj.data;
								$localStorage.token = Rs.Usuario.token;
							}
						},
					})
					.state('Home.Section', {
						url: '/:section',
						templateUrl: function (params) { return '/Home/'+params.section; },
					})
					.state('Home.Section.Subsection', {
						url: '/:subsection',
						templateUrl: function (params) { return '/Home/'+params.section+'/'+params.subsection; },
					});

			$urlRouterProvider.otherwise('/Home');
			

			$httpProvider.interceptors.push(['$q', '$localStorage', 
				function ($q, $localStorage) {
					return {
						request: function (config) {
							config.headers = config.headers || {};
							if ($localStorage.token) {
								config.headers.token = $localStorage.token;
							}
							//console.log(config);
							//config.url = '/app/public/index.php' + config.url;
							return config;
						},
						response: function (res) {
							return res || $q.when(res);
						},
						responseError: function(rejection) {
						  // do something on error

						  if ([400, 401].indexOf(rejection.status) !== -1) {
							// Handle unauthenticated user.
							//location.reload();
							
							//var r = confirm("Su sesión expiró, por favor ingrese nuevamente");
							location.replace("/#/Login");
						  }

						  return $q.reject(rejection);
						}

					};
				}
			]);
	}
]);