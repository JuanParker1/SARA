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