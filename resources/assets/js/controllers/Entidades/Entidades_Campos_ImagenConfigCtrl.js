angular.module('Entidades_Campos_ImagenConfigCtrl', [])
.controller('Entidades_Campos_ImagenConfigCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', 'C',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, C) {

		console.info('Entidades_Campos_ImagenConfigCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.Cancel = () => { $mdDialog.cancel(); };
		Ctrl.inArray  = Rs.inArray;
		Ctrl.C = angular.copy(C);

		Ctrl.ImageModes = [ 'Recortar', 'Ajustar Ancho', 'Ajustar Alto', 'Contener' ];

		var ConfigDefault = {
			img_ruta: '/img/photos/$id.jpg',
			img_width: 450, img_height: 350,
			img_uploader: Rs.Usuario.url + 'api/Main/upload-image',
			img_imagemode: 'Recortar',
			img_quickpreview: true,
		};

		//console.log(Rs.Usuario);

		Ctrl.C.Config = angular.extend({}, ConfigDefault, C.Config);

		Ctrl.guardarConfig = () => {
			$mdDialog.hide(Ctrl.C);
		};

	}
]);