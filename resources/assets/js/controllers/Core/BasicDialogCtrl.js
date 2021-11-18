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
			},
			isDateComplete: (date) => {
				return true;
			}
		};

		Ctrl.fixPeriodoValue = (Field) => {
        	if(!Field.Value) return;
        	let BaseYear = Field.Value.getFullYear().toString();
        	if(BaseYear.length == 6){
        		Field.Value.setFullYear(BaseYear.substr(0,4));
        		let Month = parseInt(BaseYear.substr(4,2)) - 1;
        		Field.Value.setMonth(Month);
        	};
        }

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