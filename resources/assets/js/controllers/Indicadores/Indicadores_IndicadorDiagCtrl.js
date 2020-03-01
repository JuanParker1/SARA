angular.module('Indicadores_IndicadorDiagCtrl', [])
.controller('Indicadores_IndicadorDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', 'indicador_id', '$timeout',
	function($scope, $rootScope, $mdDialog, $filter, indicador_id, $timeout) {

		console.info('Indicadores_IndicadorDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = () => { $mdDialog.cancel(); }

		Ctrl.Meses = Rs.Meses;
		Ctrl.inArray = Rs.inArray;
		Ctrl.Anio  = angular.copy(Rs.AnioActual);
		Ctrl.anioAdd = (num) => { Ctrl.Anio += num; Ctrl.getIndicadores(); };
		Ctrl.Sentidos = Rs.Sentidos;
        Ctrl.Usuario = Rs.Usuario;

        Ctrl.modoComparativo = false;

		Ctrl.getIndicadores = () => {

			Rs.http('api/Indicadores/get', { id: indicador_id, Anio: Ctrl.Anio }, Ctrl, 'Ind').then(() => {

				angular.forEach(Ctrl.Ind.valores, (m,k) => {
					var i = parseInt(m.mes);
					Ctrl.graphData[0].values[i-1] = { x: i, y: m.Valor, 	  val: m.val,         series: 0, key: 'Valor', color: m.color };
                    Ctrl.graphData[1].values[i-1] = { x: i, y: m.meta_Valor,  val: m.meta_val,    series: 1, key: 'Meta'     };
                    Ctrl.graphData[2].values[i-1] = { x: i, y: m.meta2_Valor, val: m.meta_val,    series: 2, key: 'Meta2'    };
					Ctrl.graphData[3].values[i-1] = { x: i, y: m.anioAnt,     val: m.anioAnt_val, series: 3, key: 'AnioAnt', color: m.anioAnt_color  };
				});

                Ctrl.updateChart();

			});

		};

        Ctrl.updateChart = () => {
            Ctrl.graphData[2].disabled = !(Ctrl.Ind.Sentido == 'RAN');
            Ctrl.graphData[3].disabled = !Ctrl.modoComparativo;
            d3.selectAll('.nvtooltip').style('opacity', 0);
            Ctrl.graphApi.update();
        }
		







 		Ctrl.grapOptions = {
            chart: {
                type: 'multiChart',
                margin: {
                	top:10, right:20, bottom:0, left:100
                },
                height: 150,
                y: function(d,i) { return d.y; },
                x: function(d,i) { return d.x; },
                showLegend: false,
                xAxis: {
                	showMaxMin: false,
                    ticks: 0,
                    tickFormat: function(d){
                        return Rs.Meses[d-1][1];
                    },
                },
                yAxis1: {
                    tickFormat: function(d){
                        return Rs.formatVal(d,Ctrl.Ind.TipoDato,Ctrl.Ind.Decimales);
                    },
                },
                bars1: {
                },
                lines1: {
                	padData: true,
                },
                padData: true,
                //yDomain1: [0,0.1],
                useInteractiveGuideline: true,
                interactiveLayer:{
                	showGuideLine: false,
                    tooltip: {
                        contentGenerator: (obj) => {
                            var Periodo = `${Rs.Meses[obj.index][1]} ${Ctrl.Anio}`;
                            var Resultado = obj.series[0].data.val;
                            var Meta      = obj.series[1].data.val;
                            var Color     = obj.series[0].data.color;
                            return `<table><thead><tr><td class=x-value colspan=3><div class='md-title'>${Periodo}</div></td></tr></thead><tbody>
                            <tr style='color:${Color}'><td class=key>Resultado</td><td class='value'>${Resultado}</td></tr>
                            <tr><td class=key>Meta:</td><td class=value>${Meta}</td></tr>
                            </tbody></table>`;
                        }
                    }
                    
                }
            }
        };

        Ctrl.graphData = [
        	{ key: 'Valor',    yAxis: 1, type: 'bar',  values: [] },
            { key: 'Meta',     yAxis: 1, type: 'line', values: [], classed: 'dashed', color: 'white' },
            { key: 'Meta2',    yAxis: 1, type: 'line', values: [], classed: 'dashed', color: 'white' },
        	{ key: 'AnioAnt',  yAxis: 1, type: 'bar',  values: [] },
        ];

        Ctrl.getIndicadores();

        Ctrl.viewCompDiag = (comp) => {
            if(comp.Tipo == 'Variable')  return Rs.viewVariableDiag(comp.variable_id);
            if(comp.Tipo == 'Indicador') return Rs.viewIndicadorDiag(comp.variable_id);
        };

        //Sidenav
        Ctrl.showSidenav = true;

        Ctrl.toogleSidenav = () => {
            Ctrl.showSidenav = !Ctrl.showSidenav;
            $timeout(() => {
                Ctrl.updateChart();
            }, 300);        
        }

        Ctrl.Comentarios = [
            { 'Comentario': "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sed urna nulla. Sed sem arcu", 'Autor': 'Christian Orrego', 'Periodo': 202001 },
            { 'Comentario': "Curabitur posuere auctor dolor non maximus. Ut volutpat tortor a varius eleifend.", 'Autor': 'Christian Orrego', 'Periodo': 202001 },
            { 'Comentario': "Fusce fringilla facilisis nibh nec porta. Proin molestie", 'Autor': 'Christian Orrego', 'Periodo': 202001 },
            { 'Comentario': "Fusce fringilla facilisis nibh nec porta. Ut volutpat tortor a varius eleifend.", 'Autor': 'Christian Orrego', 'Periodo': 202001 },
            { 'Comentario': "Fusce fringilla facilisis nibh nec porta. consectetur adipiscing elit.", 'Autor': 'Christian Orrego', 'Periodo': 202001 },
            { 'Comentario': "Fusce fringilla facilisis nibh nec porta. Proin molestie 2", 'Autor': 'Christian Orrego', 'Periodo': 202001 }
        ];


	}
]);
