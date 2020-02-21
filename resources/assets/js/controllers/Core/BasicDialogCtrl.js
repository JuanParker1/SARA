angular.module('BasicDialogCtrl', [])
.controller(   'BasicDialogCtrl', ['$scope', 'Config', '$mdDialog', 
	function ($scope, Config, $mdDialog) {

		var Ctrl = $scope;

		Ctrl.Config = Config;
		Ctrl.periodDateLocale = {
			formatDate: (date) => {
				if(typeof date == 'undefined' || date === null || isNaN(date.getTime()) ){ return null; }else{
					return moment(date).format('YMM');
				}
			}
		};

		Ctrl.Cancel = function(){
			$mdDialog.hide();
		}

		Ctrl.SendData = function(){
			$mdDialog.hide(Ctrl.Config);
		}

		Ctrl.selectItem = (Field, item) => {
			if(!Field.opts.itemVal){
				Field.Value = item;
			}else{
				Field.Value = item[Field.opts.itemVal];
			}
			
		};

		Ctrl.Delete = function(ev) {
			if(Config.HasDelete){
				Config.HasDeleteConf = true;

				Ctrl.SendData();
			}
		}
	}

]);