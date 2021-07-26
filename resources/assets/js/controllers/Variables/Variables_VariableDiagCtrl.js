angular.module('Variables_VariableDiagCtrl', [])
.controller('Variables_VariableDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', 'variable_id', '$timeout',
	function($scope, $rootScope, $mdDialog, $filter, variable_id, $timeout) {

		console.info('Variables_VariableDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = () => { $mdDialog.cancel(); }

		Ctrl.Meses = Rs.Meses;
		Ctrl.inArray = Rs.inArray;
        Ctrl.viewVariableDiag = Rs.viewVariableDiag;
		Ctrl.Anio  = angular.copy(Rs.AnioActual);
		Ctrl.anioAdd = (num) => { Ctrl.Anio += num; Ctrl.getVariables(); };
        
        Ctrl.viewRelatedVariables = false;

		Ctrl.getVariables = () => {

            Rs.http('api/Variables/get', { id: variable_id, Anio: Ctrl.Anio }, Ctrl, 'Var').then(() => {
                Ctrl.Anios = [(Ctrl.Anio - 1), Ctrl.Anio];
                angular.forEach(Ctrl.Anios, (Anio, kA) => {
                    angular.forEach(Rs.Meses, (Mes, kM) => {
                        
                        var i = parseInt(kM);
                        var VarVal = Ctrl.Var.valores[(Anio*100)+(i+1)];
                        var Valor = (VarVal == null) ? null : VarVal.Valor;
                        Ctrl.graphData[kA].values[i] = { x: i, y: Valor };
                    });
                });

                Ctrl.updateChart();

            });
		};

        Ctrl.updateChart = () => {
            d3.selectAll('.nvtooltip').style('opacity', 0);
            Ctrl.graphApi.update();
        }

 		Ctrl.grapOptions = {
            chart: {
                type: 'multiChart',
                margin: {
                	top:5, right:0, bottom:5, left:80
                },
                height: 150,
                y: function(d,i) { return d.y; },
                x: function(d,i) { return d.x; },
                showLegend: false,
                xAxis: {
                	showMaxMin: false,
                    ticks: 0,
                    tickFormat: function(d){
                        //return d;
                        return Rs.Meses[d][1];
                    },
                },
                yAxis1: {
                    tickFormat: function(d){
                        return Rs.formatVal(d,Ctrl.Var.TipoDato,Ctrl.Var.Decimales);
                    },
                },
                bars1: {
                },
                lines1: {
                	padData: true,
                },
                padData: true,
                //forceY:[0],
                //yDomain1: [0,0.1],
                useInteractiveGuideline: true,
                interactiveLayer:{
                	showGuideLine: false,
                },
                legend: {
                    //margin: { right: 10 }
                },

            }
        };

        Ctrl.graphData = [
            { key: (Ctrl.Anio-1), yAxis: 1, type: 'line', values: [], color: '#ababab',  },
            { key: Ctrl.Anio,     yAxis: 1, type: 'line', values: [], color: '#6ab8ff', strokeWidth: 4 },
        ];

        Ctrl.getVariables();

        //Menu
        Ctrl.openMenuValores = (ev, Periodo) => {
            var Val = Ctrl.Var.valores[Periodo] || {};
            Rs.viewVariableMenu(ev, Ctrl.Var, Periodo, Val, Ctrl.getVariables);
        };

        //Desagregacion
        Ctrl.addedDesagregado = ($chip) => {
            var index = Rs.getIndex(Ctrl.Var.desagregables, $chip.id);
            Ctrl.Var.desagregables.splice(index,1);
        };

        Ctrl.removedDesagregado = ($chip) => {
            Ctrl.Var.desagregables.push($chip);
        };

        Ctrl.getDesagregatedData = () => {
             Rs.http('api/Variables/get-desagregacion', { variable_id: variable_id, Anio: Ctrl.Anio, desag_campos: Ctrl.Var.desagregados }, Ctrl, 'Desagregacion');
        };



	}
]);
