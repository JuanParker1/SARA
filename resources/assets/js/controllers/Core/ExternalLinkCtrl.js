angular.module('ExternalLinkCtrl', [])
.controller(   'ExternalLinkCtrl', ['$scope', 'Link', '$mdDialog', '$sce',  
	function ($scope, Link, $mdDialog, $sce) {

		var Ctrl = $scope;

		Ctrl.Link = $sce.trustAsResourceUrl(Link);

		Ctrl.Cancel = function(){
			$mdDialog.cancel();
		}
		
	}

]);