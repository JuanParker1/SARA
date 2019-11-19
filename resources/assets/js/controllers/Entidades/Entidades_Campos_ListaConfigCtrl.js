angular.module('Entidades_Campos_ListaConfigCtrl', [])
.controller('Entidades_Campos_ListaConfigCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', 'C',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, C) {

		console.info('Entidades_Campos_ListaConfigCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.Cancel = () => { $mdDialog.cancel(); };
		Ctrl.inArray  = Rs.inArray;
		Ctrl.C = C;

		var ConfigDefault = {
			opciones: [],
		};

		Ctrl.C.Config = angular.extend({}, ConfigDefault, C.Config);

		Ctrl.addElemento = (newOpt) => {
			if(!newOpt) return;
			newOpt = newOpt.trim();
			if(newOpt !== ''){
			
				Ctrl.C.Config.opciones.push({
					value: newOpt,
					desc:  '',
					color: '#ffffff', icono: null
				});

			};


			Ctrl.newOpt = '';
		};

		Ctrl.dragListener = {
			accept: function (sourceItemHandleScope, destSortableScope) { return true; }
		};

		Ctrl.changeIcon = (Op) => {
			Rs.selectIconDiag().then(r => {
				if(!r) return;
				Op.icono = r;
			});
		};

		Ctrl.guardarConfig = () => {
			$mdDialog.hide(Ctrl.C);
		};

	}
]);