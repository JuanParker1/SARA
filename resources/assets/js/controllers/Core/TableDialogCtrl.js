angular.module('TableDialogCtrl', [])
.controller('TableDialogCtrl', ['$scope', '$rootScope', '$mdDialog', 'Elements', 'Config',
	function($scope, $rootScope, $mdDialog, Elements, Config) {

		//console.info('TableDialogCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.Config = Config;
		Ctrl.Searching = false;
		Ctrl.Elements = Elements;

		Ctrl.Cancel = function(){ $mdDialog.cancel(); }

		Ctrl.getProp = (Obj, Prop) => {
			return Prop.split('.').reduce(function(a, b) {
				return a[b];
			}, Obj);
		}

		Ctrl.Resp = function(){

			if(Config.pluck){
				var Sel = Ctrl.Config.selected.map( e => e[Config.primaryId] );
			}else{
				var Sel = Ctrl.Config.selected;
			}

			$mdDialog.hide(Sel);
		}


	}
]);