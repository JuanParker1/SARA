<div flex id="MiProceso" layout=column layout-align="center center" ng-controller="MiProcesoCtrl" class="">
		
		<div class="w100p mxw600 margin-top bg-white border-radius-top" flex layout=column md-whiteframe=2>
			
			<div class="bg-cover bg-center-center mh160 border-radius-top relative show-child-on-hover" 
				style="box-shadow: inset 0 -85px 30px -40px #FFFFFF;" 
				ng-style="{ 'background-image': 'url('+ ProcesoSel.Bg +')' }"
					   layout=column>

				<div class="h105"></div>

				<div class="padding-left " layout=column>
					<div class="lh30 text-30px" style="text-shadow: 0 0 30px white;" layout>
						<div class="">{{ ProcesoSel.Proceso }}</div>
						<md-menu ng-show="ProcesoSel.subprocesos.length > 0">
							<md-button class="md-icon-button no-margin no-padding s30" ng-click="$mdMenu.open($event)"><md-icon md-svg-icon="md-chevron-down"></md-icon></md-button>
							<md-menu-content class="no-padding">
								<md-menu-item ng-repeat="P in ProcesoSel.subprocesos" ng-show="P.Tipo !== 'Utilitario'">
									<md-button ng-click="getProceso(P.id)">{{ P.Proceso }}</md-button>
								</md-menu-item>
							</md-menu-content>
						</md-menu>
					</div>
					<div layout class="margin-top-5">
						<div class="text-clear margin-right-5">{{ ProcesoSel.Tipo }}</div>
						<div class="Pointer" layout ng-click="getProceso(ProcesoSel.padre_id)" ng-show="ProcesoSel.padre_id">
							de<div class="text-clear margin-left-5">{{ ProcesoSel.padre.Proceso  }}</div>
						</div>
					</div>
				</div>
				
				<div class="abs child" style="top: 105px; right: 5px">
					<md-button class="md-icon-button focus-on-hover no-margin" ng-click="cambiarFondo()">
						<md-icon md-svg-icon="md-image" class=""></md-icon>
					</md-button>
				</div>
				
			</div>

			<div flex layout=column class="md-short">
				
				<md-tabs flex class="md-tabs-fullheight" md-selected="SelectedTab">
					<md-tab ng-repeat="(kSub,Sub) in SubSecciones" >
						<md-tab-label>{{ Sub[0] }}</md-tab-label>
						<md-tab-body>
							<div ng-include="'Frag/MiProceso.MiProceso_' + kSub" class="hasScroll"></div>
						</md-tab-body>
					</md-tab>
				</md-tabs>

			</div>

		</div>


		

		

</div>

<style type="text/css">
	
	.teammember .teammember_image{
		width: 70px;
		height: 70px;
		border-radius: 50%;
		border: 1px solid #cecece;
		box-shadow: 0 5px 10px 1px #00000029;
	}

	.teammember_name {
		width: 80px;
		font-size: 13px;
		font-weight: 400;
		text-align: center;
		height: 45px;
		overflow: hidden;
		margin-top: 3px;
		color: grey;
	}

</style> 