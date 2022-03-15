angular.module('CodeDialogCtrl', [])
.controller('CodeDialogCtrl', ['$scope', '$rootScope', '$http', '$injector', '$mdDialog', 'Code', 'Config',
	function($scope, $rootScope, $http, $injector, $mdDialog, Code, Config) {

		console.info('CodeDialogCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Config = Config;
		Ctrl.Code = Code;
	
		Ctrl.Cancel = function(){ $mdDialog.cancel(); }

		Ctrl.aceOptions = {
			theme:'twilight',
			mode: Config.Language,
			onLoad: (_editor) => {
				var _session = _editor.getSession();
				_editor.setFontSize(15);
				_session.setTabSize(3);
				_editor.setOptions({
				    minLines: 1,
				    maxLines: 5000
				});
				//_editor.setUseSoftTabs(true);
			},
		};

		Ctrl.setCode = (dacode) => {
			Ctrl.Code = dacode;
		};

		Ctrl.updateConfig = (daconfig) => {
			Ctrl.Config = angular.extend(Ctrl.Config, daconfig);
		};

	}
]);