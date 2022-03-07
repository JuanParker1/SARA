angular.module('Entidades_GridDiag_GaugeDiagCtrl', [])
.controller('Entidades_GridDiag_GaugeDiagCtrl', ['$scope', '$rootScope', '$http', '$injector', '$mdDialog', 'C', 'val',
	function($scope, $rootScope, $http, $injector, $mdDialog, C, val) {

		console.info('Entidades_GridDiag_GaugeDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = $mdDialog.cancel;

		Ctrl.val = val;
		Ctrl.C   = C;
	
		var opts = {
			angle: 0,
			lineWidth: 0.40,
			pointer: {
				length: 0.5,
				strokeWidth: 0.07,
				color: '#000000'
			},
			limitMin: false,
			limitMax: false,
			highDpiSupport: true,
			staticZones: [],
			staticLabels: {
				font: "11px sans-serif",  // Specifies font
				color: "#000000",  // Optional: Label text color
				labels: []
			},
			colorStart: '#6FADCF',   // Colors
			colorStop: '#8FC0DA',    // just experiment with them
			strokeColor: '#E00000',  // to see which ones work best for you
			renderTicks: {
				divisions: 0,
				divWidth: 0.3,
				divLength: 0.27,
				divColor: '#333333',
			}
		};

		Ctrl.start = () => {

			let min = C.Op1;
			let max = C.Op2;

			if(C.Config.alerts.length == 0){
				min = 0;
			};


			
			if(min == null || max == null){
				//Determine avg if necesary
				let sum = 0;
				let min_val = 0;
				let max_val = 0;
				C.Config.alerts.forEach((a,i) => {
					let first = (i == 0);
					let last  = (i == (C.Config.alerts.length - 1));
					if(!first){
						let prev_val = first ? min : C.Config.alerts[(i - 1)].upto;
						sum += (a.upto - prev_val);
					}else{
						min_val = a.upto;
					}

					if(last){
						max_val = a.upto;
					}
				});
				let avg = Math.round(sum / (C.Config.alerts.length - 1));

				if(min == null) min = min_val - avg;
				if(max == null) max = max_val;
			};

			if(val < min) min = val;
			if(val > max) max = val;

			//Generate Zones
			C.Config.alerts.forEach((a,i) => {
				let first = (i == 0);
				let last  = (i == (C.Config.alerts.length - 1));
				let prev_val = first ? min : C.Config.alerts[(i - 1)].upto;
				opts.staticZones.push({ strokeStyle: a.color, min: prev_val, max: a.upto, first: first, last: last });
				opts.staticLabels.labels.push(prev_val);
			});

			let last_zone = opts.staticZones.find(z => z.last);
			if(last_zone && last_zone.max < max){
				opts.staticZones.push({ strokeStyle: '#eee', min: last_zone.max, max: max });
				opts.staticLabels.labels.push(last_zone.max);
			};

			opts.staticLabels.labels.push(max);

			var target = document.getElementById('GaugeCanvas');
			var gauge = new Gauge(target).setOptions(opts);
			gauge.minValue = min;
			gauge.maxValue = max;
			gauge.animationSpeed = 4;
			gauge.set(val);
		};
		

	}
]);