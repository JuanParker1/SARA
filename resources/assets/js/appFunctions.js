angular.module('appFunctions', [])
.factory('appFunctions', [ '$rootScope', '$http', '$mdDialog', '$mdSidenav', '$mdToast', '$q', '$state', '$location', '$filter', '$window',
	function($rootScope, $http, $mdDialog, $mdSidenav, $mdToast, $q, $state, $location, $filter, $window){

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
			if(!array) return false;
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

		Rs.removeArrayElm = (array, index) => {
			array.splice(index,1);
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

		Rs.submitForm = (name) => {
			Rs.$broadcast('makeSubmit', {formName: name});
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

		Rs.parseDate = (string) => {
			if(!string) return null;
			var date = moment(string); if(!date.isValid()) return null;
			return date.toDate();
		}


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
				remoteListLogo: false,
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

		Rs.TableDialog = (Elements, Config) => {
			var DefConfig = {
				controller: 'TableDialogCtrl',
				templateUrl: '/templates/dialogs/TableSelector.html',
				clickOutsideToClose: true,
				fullscreen: true,
				Theme: 'default', Flex: 95,
				Title: 'Seleccionar',
				primaryId: 'id', pluck: true,
				Columns: [
					{ Nombre: 'id', Desc: 'Id.', numeric: true }
				],
				selected: [], multiple: true,
			};
			var Config = angular.extend(DefConfig, Config);

			return $mdDialog.show({
				controller: Config.controller,
				templateUrl: Config.templateUrl,
				locals: { Config : Config, Elements: Elements },
				clickOutsideToClose: Config.clickOutsideToClose,
				fullscreen: Config.fullscreen,
				multiple: true,
			});
		}
		
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

		Rs.selectIconDiag = () => {
			return $mdDialog.show({
				controller: 'IconSelectDiagCtrl',
				templateUrl: '/templates/dialogs/icon-selector.html',
				clickOutsideToClose: true,
				multiple: true,
			});
		};

		Rs.getItemsVal = (Items, Comparator, Prop) => {
			var Elm = $filter('filter')(Rs[Items],Comparator)[0];
			//console.log(Items,Comparator,Elm);
			return Elm[Prop];
		};



		Rs.FsGet = (arr, ruta, filename, defaultOpen,modeB,skipOrder) => {

			if(!skipOrder){
				var arr = arr.sort((a, b) => {
					var ar = (a[ruta]+'\\'+a[filename]).toLowerCase();
					var br = (b[ruta]+'\\'+b[filename]).toLowerCase();
					return ar > br ? 1 : -1;
				});
			}
			
			var fs = [];
	    	var routes = {};
	    	var defaultOpen = Rs.def(defaultOpen, false);
	    	var modeB       = Rs.def(modeB, false);

	    	angular.forEach(arr, (e) => {
	    		var r = e[ruta];
    			rex = r.split('\\');
    			
    			for (var i = 0; i < rex.length; i++) {
    				for (var n = 0; n <= i; n++) {
    					
    					var subroute = rex.slice(0,n+1).join('\\');
    					if(subroute != "" && !Object.keys(routes).includes(subroute)){
    						routes[subroute] = 0;

    						var parent_route = subroute.split('\\').slice(0, -1).join('\\');
    						
    						var show = defaultOpen || (n <= 1);
    						var open = defaultOpen || (n == 0);
    						
    						if(n > 0) routes[parent_route]++;

    						//if( !modeB || ( modeB && e.children > 0 ) ){
    							fs.push({ i: fs.length, type: 'folder', name: rex[n], depth: n, open: open, show: show, route: subroute });
    						//};

    					};
	    				
    				};
    			};

    			var depth = (r == "") ? 0 : (rex.length);
    			var show = defaultOpen || (depth == 0);

    			if( !modeB || (modeB && e.children == 0) ){
    				fs.push({ i: fs.length, type: 'file', depth: depth, show: show, route: subroute, file: e });
    			};
    			
	    	});

	    	angular.forEach(fs, f => {
	    		f.children = routes[f.route];
	    	});
	    	
	    	return fs;
		};

		Rs.FsOpenFolder = (arr,folder) => {
			folder.open = !folder.open;
			var cont = true;
			angular.forEach(arr, e => {
				if(cont){
					if(e.i > folder.i){
						if(e.depth == folder.depth + 1) e.show = folder.open;
						if(e.depth >  folder.depth + 1) e.show = false;
						if(e.type == 'folder' && e.depth >= folder.depth + 1) e.open = false;
						if(e.type == 'folder' && e.depth == folder.depth) cont = false;
					};
				};
			});
		};

		Rs.FsCalcRoute = (route, newfolder) => {
			//newfolder = newfolder.trim().split('\\').join('');
			if(newfolder == "" || (newfolder.toLowerCase() == route.toLowerCase()) ) return route;
			if(route == "") return newfolder;

			return route + "\\" + newfolder;
		};

		Rs.calcTextColor = (base_color) => {
			if(!base_color) return 'black';
		    var r, g, b, hsp;
		    if(base_color.match(/^rgb/)) {
		        color = base_color.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/);
		        r = color[1]; g = color[2]; b = color[3];
		    }else{
		        color = +("0x" + base_color.slice(1).replace(base_color.length < 5 && /./g, '$&$&'));
		        r = color >> 16;
		        g = color >> 8 & 255;
		        b = color & 255;
		    };
		    
		    // HSP (Highly Sensitive Poo) equation from http://alienryderflex.com/hsp.html
		    hsp = Math.sqrt( 0.299 * (r * r) + 0.587 * (g * g) + 0.114 * (b * b) );
		    var textColor = (hsp>127.5) ? 'black' : 'white';
		    //console.log(base_color, hsp, textColor);
		    return textColor;
		};



		Rs.AnioActual = new Date().getFullYear();
		Rs.MesActual  = parseInt(moment().subtract(40,'d').format('MM'));
		Rs.Meses = [
			['01','Ene','Enero'],
			['02','Feb','Febrero'],
			['03','Mar','Marzo'],
			['04','Abr','Abril'],
			['05','May','Mayo'],
			['06','Jun','Junio'],
			['07','Jul','Julio'],
			['08','Ago','Agosto'],
			['09','Sep','Septiembre'],
			['10','Oct','Octubre'],
			['11','Nov','Noviembre'],
			['12','Dic','Diciembre'],
		];

		Rs.periodDateLocale = {
			formatDate: (date) => {
				if(typeof date == 'undefined' || date === null || isNaN(date.getTime()) ){ return null; }else{
					return moment(date).format('YMM');
				}
			}
		};

		Rs.VariablesSistema = [ 'Fecha Actual', 'Hora Actual', 'FechaHora Actual', 'Usuario Logueado' ];

		Rs.formatVal = (d, TipoDato, Decimales) => {
			if(TipoDato == 'Porcentaje') return d3.format('.'+Decimales+'%')(d);
            if(TipoDato == 'Moneda')     return d3.format('$,.'+Decimales)(d);
            return d3.format(',.'+Decimales)(d);
		};

		Rs.getVariableData = (Variables, Tipo) => {
			$mdDialog.show({
				controller: 'VariablesGetDataDiagCtrl',
				templateUrl: '/Frag/Variables.VariablesGetDataDiag',
				locals: { Variables : Variables, Tipo: Tipo },
				clickOutsideToClose: false, fullscreen: true, multiple: true,
			});
		};

		Rs.viewVariableDiag = (variable_id) => {
			$mdDialog.show({
				controller: 'Variables_VariableDiagCtrl',
				templateUrl: '/Frag/Variables.VariableDiag',
				locals: { variable_id : variable_id },
				clickOutsideToClose: false, fullscreen: true, multiple: true,
			});
		};

		Rs.viewIndicadorDiag = (indicador_id) => {
			$mdDialog.show({
				controller: 'Indicadores_IndicadorDiagCtrl',
				templateUrl: '/Frag/Indicadores.IndicadorDiag',
				locals: { indicador_id : indicador_id },
				clickOutsideToClose: false, fullscreen: true, multiple: true,
			});
		};

		Rs.Sentidos = {
			ASC: { desc: 'Mayor Mejor', icon: 'fa-arrow-circle-up' },
			RAN: { desc: 'Mantener en Rango', icon: 'fa-arrow-circle-right' },
			DES: { desc: 'Menor Mejor', icon: 'fa-arrow-circle-down' },
		};

		Rs.viewScorecardDiag = (scorecard_id) => {
			$mdDialog.show({
				controller: 'Scorecards_ScorecardDiagCtrl',
				templateUrl: '/Frag/Scorecards.ScorecardDiag',
				clickOutsideToClose: false, fullscreen: true, multiple: true,
				onComplete: (scope, element) => {
					scope.getScorecard(scorecard_id, {});
				}
			});
		};

		Rs.changeIcon = (elm, prop) => {
			Rs.selectIconDiag().then(I => {
				if(!I) return;
				elm[prop] = I;
			});
		};


		Rs.queryElm = (searchText, accion) => {
			if(accion == 'Editor') 		var url = '/api/Entidades/editores-search/';
			return Rs.http(url, { searchText: searchText });
		};


		Rs.viewEditorDiag = (editor_id, Obj, Config) => {
			return $mdDialog.show({
				controller: 'Entidades_EditorDiagCtrl',
				templateUrl: '/Frag/Entidades.Entidades_EditorDiag',
				clickOutsideToClose: false, fullscreen: false, multiple: true,
				onComplete: (scope, element) => {
					scope.getEditor(editor_id, Obj, Config);
				}
			});
		};

		Rs.viewCargadorDiag = (cargador_id) => {
			return $mdDialog.show({
				controller: 'Entidades_CargadorDiagCtrl',
				templateUrl: '/Frag/Entidades.Entidades_CargadorDiag',
				clickOutsideToClose: false, fullscreen: false, multiple: true,
				onComplete: (scope, element) => {
					scope.getCargador(cargador_id);
				}
			});
		};

		Rs.viewVariableEditorDiag = (Ctrl) => {
			$mdDialog.show({
				controller: 'VariablesCtrl',
				templateUrl: '/Frag/Variables.VariableEditorDiag',
				clickOutsideToClose: false, fullscreen: true, multiple: true,
				scope: Ctrl, preserveScope: false,
			});
		}

		Rs.openApp = (A) => {
			//console.log('opening...', A);
			var url = Rs.Usuario.Url+"#/a/"+A.Slug;
			var w = screen.availWidth - 5; var h = screen.availHeight;
			//$window.open(url, 'popup', `width=${w},height=${h}`);
			
			if(!angular.isDefined(A.w) || A.w.closed) {
				A.w = window.open(url,"_blank",`menubar=0, scrollbars=0,width=${w},height=${h}`);
			};
			A.w.focus();
			
		};

		Rs.getProcesos = (Ctrl) => {
			if(!Rs.Storage.procesos_updated_at || !Rs.Storage.Procesos){
				return Rs.http('api/Procesos', {}).then(P => {
					Ctrl.Procesos = P;
					Rs.Storage.Procesos = P;
					Rs.Storage.procesos_updated_at = Rs.Usuario.procesos_updated_at;
				});
			}

			Ctrl.Procesos = Rs.Storage.Procesos;
			return Promise.resolve();
			
		}

		return {};
  }
]);