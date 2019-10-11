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



		Rs.FsGet = (arr, ruta, filename, defaultOpen) => {
			var arr = arr.sort((a, b) => {
				var ar = (a[ruta]+'\\'+a[filename]).toLowerCase();
				var br = (b[ruta]+'\\'+b[filename]).toLowerCase();
				return ar > br ? 1 : -1;
			});
			var fs = [];
	    	var routes = [];
	    	var defaultOpen = defaultOpen || false;

	    	angular.forEach(arr, (e) => {
	    		var r = e[ruta];

    			rex = r.split('\\');
    			for (var i = 0; i < rex.length; i++) {
    				for (var n = 0; n <= i; n++) {
    					
    					var subroute = rex.slice(0,n+1).join('\\');
    					if(subroute != "" && !routes.includes(subroute)){
    						routes.push(subroute);
    						var show = defaultOpen || (n == 0);
    						fs.push({ i: fs.length, type: 'folder', name: rex[n], depth: n, open: defaultOpen, show: show, route: subroute });
    					};
	    				
    				};
    			};
    			var depth = (r == "") ? 0 : (rex.length);
    			var show = defaultOpen || (depth == 0);
    			fs.push({ i: fs.length, type: 'file', depth: depth, show: show, route: subroute, file: e });
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
		    console.log(base_color, hsp, textColor);
		    return textColor;
		};

		return {};
  }
]);