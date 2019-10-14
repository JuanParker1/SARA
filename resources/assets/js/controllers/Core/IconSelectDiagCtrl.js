angular.module('IconSelectDiagCtrl', [])
.controller(   'IconSelectDiagCtrl', ['$scope',  '$mdDialog', '$http', '$filter',
	function ($scope, $mdDialog, $http, $filter) {

		var Ctrl = $scope;
		Ctrl.Cancel = function(){ $mdDialog.cancel(); }
		Ctrl.filter = ''; Ctrl.CatSel = null;

		$http.get('/api/Main/iconos').then((r) => {
			Ctrl.Categorias = r.data.Categorias;
			Ctrl.IconosRaw	= r.data.Iconos;
		});

		Ctrl.Iconos = [];

		Ctrl.filterCat = (C) => { Ctrl.CatSel = C; Ctrl.filterIconos(); }

		Ctrl.filterIconos = () => {
			console.log(Ctrl.CatSel, Ctrl.filter);
			if(Ctrl.CatSel == null && Ctrl.filter == ''){ Ctrl.Iconos = []; }
			else if(Ctrl.filter !== ''){   Ctrl.Iconos = $filter('filter')(Ctrl.IconosRaw, Ctrl.filter) }
			else if(Ctrl.CatSel !== null){ Ctrl.Iconos = $filter('filter')(Ctrl.IconosRaw, { Categoria: Ctrl.CatSel }) };
		};

		Ctrl.selectIcon = (I) => {
			$mdDialog.hide(I.IconoFull);
		};
		
	}

]);