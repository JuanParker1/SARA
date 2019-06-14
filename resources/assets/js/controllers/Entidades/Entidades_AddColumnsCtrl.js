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