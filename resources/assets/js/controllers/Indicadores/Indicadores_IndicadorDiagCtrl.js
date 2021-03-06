angular.module('Indicadores_IndicadorDiagCtrl', [])
.controller('Indicadores_IndicadorDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', '$injector', '$mdPanel', 'indicador_id', 'config', 
	function($scope, $rootScope, $mdDialog, $filter, $timeout, $injector, $mdPanel, indicador_id, config) {

		console.info('Indicadores_IndicadorDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = () => { 
            d3.selectAll('.nvtooltip').style('opacity', 0); $mdDialog.cancel();
        }

        Ctrl.SidenavIcons = [
            ['fa-comment',      'Análisis y Mejoramiento',     false, 'w380' ],
            ['fa-list',         'Desagregar Datos', false, 'w260' ],
            ['fa-info-circle',  'Ficha Técnica',    false, 'w420' ],
        ];
        Ctrl.openSidenavElm = (S) => {
            Ctrl.sidenavSel = (S[1] == Ctrl.sidenavSel) ? null : S[1];
            $timeout(() => {
                Ctrl.updateChart();
            }, 300);
        };
        Ctrl.activeSidenav = () => {
            return Ctrl.SidenavIcons.find(I => I[1] == Ctrl.sidenavSel);
        }

		Ctrl.Meses = Rs.Meses;
		Ctrl.inArray = Rs.inArray;
		Ctrl.Anio  = ('Anio' in config) ? angular.copy(config.Anio) : angular.copy(Rs.AnioActual);
        Ctrl.Mes   = angular.copy(Rs.MesActual);
		Ctrl.anioAdd = (num) => { Ctrl.Anio += num; Ctrl.getIndicadores(); };
		Ctrl.Sentidos = Rs.Sentidos;
        Ctrl.Usuario = Rs.Usuario;
        Ctrl.viewVariableDiag = Rs.viewVariableDiag;
        Ctrl.Frecuencias = Rs.Frecuencias;
        Ctrl.agregators = Rs.agregators;
        Ctrl.comparators = Rs.comparators;

        Ctrl.modoComparativo = false;

		Ctrl.getIndicadores = () => {

			Rs.http('api/Indicadores/get', { id: indicador_id, Anio: Ctrl.Anio, modoComparativo: Ctrl.modoComparativo, obtenerScorecards: true }, Ctrl, 'Ind').then(() => {

				angular.forEach(Ctrl.Ind.valores, (m,k) => {
					var i = parseInt(m.mes);
					Ctrl.graphData[0].values[i-1] = { x: i, y: m.Valor, 	  val: m.val,         series: 0, key: 'Valor', color: m.color };
                    Ctrl.graphData[1].values[i-1] = { x: i, y: m.meta_Valor,  val: m.meta_val,    series: 1, key: 'Meta'     };
                    Ctrl.graphData[2].values[i-1] = { x: i, y: m.meta2_Valor, val: m.meta_val,    series: 2, key: 'Meta2'    };
					Ctrl.graphData[3].values[i-1] = { x: i, y: m.anioAnt,     val: m.anioAnt_val, series: 3, key: 'AnioAnt', color: m.anioAnt_color  };
				});

                $timeout(() => {
                    Ctrl.updateChart();
                }, 500);

                Ctrl.Desagregacion = null;
                Ctrl.getComentarios();
                //Ctrl.getDesagregatedData();

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
            if(comp.Tipo == 'Variable')  return Rs.viewVariableDiag(comp.variable_id,  { Anio: Ctrl.Anio });
            if(comp.Tipo == 'Indicador') return Rs.viewIndicadorDiag(comp.variable_id, { Anio: Ctrl.Anio });
        };

        //Comments
        Ctrl.ComentariosCRUD = $injector.get('CRUD').config({ 
            base_url: '/api/Main/comentarios', 
            query_with: [ 'autor' ], add_append: 'refresh', 
            order_by: ['-Op1','created_at']
        });
        var ComentariosLoaded = false;
        Ctrl.getComentarios = () => {
            Ctrl.ComentariosCRUD.setScope('Entidad', ['Indicador', indicador_id]).get().then(() => {
                ComentariosLoaded = true;
            });
        };

        Ctrl.addComment = () => {

            var Periodos = [
                moment().add(-1, 'month').format('YYYYMM'),
                moment().format('YYYYMM')
            ];

            Rs.BasicDialog({
                Theme: 'Black', Title: 'Agregar Comentario',
                Fields: [
                    { Nombre: 'Periodo',     Value: Periodos[0], Required: true, Type: 'simplelist',  List: Periodos },
                    { Nombre: 'Comentario',  Value: '',          Required: true, Type: 'textarea',    opts: { rows: 4 } }
                ],
                Confirm: { Text: 'Comentar' },
            }).then(r => {
                if(!r) return;
                var f = Rs.prepFields(r.Fields);

                Ctrl.ComentariosCRUD.add({
                    Entidad: 'Indicador', Entidad_id: indicador_id, Grupo: 'Comentario',
                    usuario_id: Rs.Usuario.id, Comentario: f.Comentario, Op1: f.Periodo
                });
            });
        };

        Ctrl.addAccion = () => {
            var Periodos = [
                moment().add(-1, 'month').format('YYYYMM')
            ];

            var Tipos = ['Preventiva', 'Correctiva', 'De Mejora'];

            Rs.BasicDialog({
                Theme: 'Black', Title: 'Agregar Acción',
                Fields: [
                    { Nombre: 'Periodo',     flex: 50, Value: Periodos[0],  Required: true, Type: 'simplelist',  List: Periodos },
                    { Nombre: 'Tipo',        flex: 50, Value: 'Correctiva', Required: true, Type: 'simplelist',  List: Tipos },
                    { Nombre: 'Link',        Value: '',           Required: true }
                ],
                Confirm: { Text: 'Agregar' },
            }).then(r => {
                if(!r) return;
                var f = Rs.prepFields(r.Fields);

                Ctrl.ComentariosCRUD.add({
                    Entidad: 'Indicador', Entidad_id: indicador_id, Grupo: 'Accion',
                    usuario_id: Rs.Usuario.id, Comentario: 'Se registró una: Acción '+f.Tipo, Op1: f.Periodo, Op2: f.Tipo, Op4: f['Link']
                });
            });
        };

        Ctrl.editComment = (C) => {
            let Tipos = ['Preventiva', 'Correctiva', 'De Mejora'];

            if(C.Grupo == 'Comentario') return Rs.BasicDialog({
                Theme: 'Black', Title: 'Editar Comentario',
                Fields: [
                    { Nombre: 'Comentario',  Value: C.Comentario, Required: true, Type: 'textarea',    opts: { rows: 4 } }
                ],
                Confirm: { Text: 'Cambiar' },
                HasDelete: true
            }).then(r => {
                if(!r) return;
                if(r.HasDeleteConf) return Ctrl.deleteComment(C);
                let NewComentario = r.Fields[0].Value;
                C.Comentario = NewComentario;
                Ctrl.ComentariosCRUD.update(C).then(() => {
                    Ctrl.getComentarios();
                });
            });

            if(C.Grupo == 'Accion') return Rs.BasicDialog({
                Theme: 'Black', Title: 'Editar Comentario',
                Fields: [
                    { Nombre: 'Tipo',        Value: C.Op2,   Required: true, Type: 'simplelist',  List: Tipos },
                    { Nombre: 'Link',        Value: C.Op4,           Required: true }
                ],
                Confirm: { Text: 'Cambiar' },
                HasDelete: true
            }).then(r => {
                if(!r) return;
                if(r.HasDeleteConf) return Ctrl.deleteComment(C);
                var f = Rs.prepFields(r.Fields);
                C = angular.extend(C, {
                    Comentario: 'Se registró una: Acción '+f.Tipo,
                    Op2: f['Tipo'],
                    Op4: f['Link']
                });
                Ctrl.ComentariosCRUD.update(C).then(() => {
                    Ctrl.getComentarios();
                });
            });
        };

        Ctrl.deleteComment = (C) => {
            Rs.confirmDelete({
                Title: '¿Eliminar el comentario?'
            }).then(r => {
                if(!r) return;
                Ctrl.ComentariosCRUD.delete(C);
            });
        };

        Ctrl.seeExternal = (Link) => {
            window.open(Link,'popup','width=1220,height=700');
        }

        Ctrl.verMejoramientoDiag = (Periodo, ev) => {

            let Comentarios = Ctrl.ComentariosCRUD.rows.filter(c => c.Op1 == Periodo);
            if(Comentarios.length == 0) return;

            $mdDialog.show({
                controller: 'Indicadores_MejoramientoDiagCtrl',
                templateUrl: 'Frag/Indicadores.IndicadorDiag_MejoramientoDiag',
                locals: { Periodo, Comentarios, seeExternal: Ctrl.seeExternal },
                clickOutsideToClose: true, fullscreen: false, multiple: true,
                targetEvent: ev
            });
        }

        //Ctrl.toogleSidenav(); //FIX

        //Desagregacion
        Ctrl.viewDesagregacionVal = 'IndVal';
        
        Ctrl.addDesagregado = () => {
            Ctrl.Ind.desagregados.push(angular.copy(Ctrl.newChip));
            var index = Rs.getIndex(Ctrl.Ind.desagregables, Ctrl.newChip.id);
            Ctrl.Ind.desagregables.splice(index,1);
            Ctrl.newChip = null;
        };

        Ctrl.removedDesagregado = ($chip) => {
            Ctrl.Ind.desagregables.push($chip);
        };

        Ctrl.getDesagregatedData = (ev) => {
            if(ev) ev.stopPropagation();
           
            Rs.http('api/Indicadores/get-desagregacion', { Indicador: Ctrl.Ind, Anio: Ctrl.Anio, desag_campos: Ctrl.Ind.desagregados }, Ctrl, 'Desagregacion');
        };



        //Menu Valores
        Ctrl.openMenuValores = (ev, Comp, M) => {
            if(Comp.Tipo == 'Indicador') return Rs.viewIndicadorDiag(Comp.variable_id);
            var Val = Comp.valores[Ctrl.Anio+M[0]];
            Rs.viewVariableMenu(ev, Comp.variable, Ctrl.Anio+M[0], Val, Ctrl.getIndicadores);
        }

        


	}
]);
