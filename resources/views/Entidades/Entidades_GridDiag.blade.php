<md-dialog class="no-overflow h100p" flex=100 aria-label=m layout=column>

	<div class="h40 border-bottom padding-0-5 inherit-color" layout layout-align="center center">

		<md-input-container class="md-no-underline md-icon-float no-margin" md-no-float flex style="padding-left: 25px">
			<md-icon md-svg-icon="md-search" class="s20"></md-icon>
			<input type="search" placeholder="Buscar..." ng-model="filterRows" 
				autocomplete="false" name="a" ng-model-options="{ debounce: 300 }" ng-change="filterData()">
		</md-input-container>

		<div layout ng-show="!loadingGrid && load_data_len > 0" layout-align="center center" class="padding-0-5">
			<md-button ng-click="pag_go(-1)" class="md-icon-button focus-on-hover no-padding no-margin s30" aria-label="b"><md-icon md-font-icon="fa-chevron-left"></md-icon></md-button>
			<div class="text-clear text-14px" style="transform: translateY(1px);">{{ pag_from + 1 | number }} a {{ pag_to | number }} de {{ load_data_len | number }}</div>
			<md-button ng-click="pag_go(1)" class="md-icon-button focus-on-hover no-padding no-margin s30" aria-label="b"><md-icon md-font-icon="fa-chevron-right"></md-icon></md-button>
		</div>

		<md-button class="md-icon-button no-margin" aria-label="b" ng-click="reloadData()">
			<md-icon md-svg-icon="md-refresh"></md-icon>
			<md-tooltip>Recargar Datos</md-tooltip>
		</md-button>

		<div class="w5"></div>

		<md-button class="mh30 h30 lh30 no-margin margin-left-5 button_main" ng-repeat="B in Grid.Config.main_buttons"
			ng-style="{ backgroundColor: AppSel.Color, color: AppSel.textcolor }"
			ng-click="triggerButton(B)">
			<md-icon md-font-icon="{{ B.icono }} margin-right-5 fa-fw"></md-icon>{{ B.texto }}
		</md-button> 

		<md-button class="md-icon-button s30 no-padding only-dialog no-margin" aria-label="Button" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close" class=""></md-icon>
		</md-button>

	</div>

	<div flex layout>
		
		@include('Entidades.Entidades_GridDiag_Sidenav')

		<div flex layout=column class="border-left">

			<div flex layout layout-align="center center" ng-show="loadingGrid">
				<md-progress-circular md-diameter="48"></md-progress-circular>
			</div>
			
			<md-table-container flex ng-show="!loadingGrid" class="overflow">
				<table md-table class="md-table-short border-bottom table-nowrap table-col-compress">
					
					<thead md-head md-order="orderRows" md-on-reorder="filterData">
						<tr md-row>
							<th md-column class="" ng-if="Grid.Config.row_buttons.length > 0"></th>
							<th md-column ng-repeat="C in Grid.columnas | filter:{Visible:true}" md-numeric="C.header_numeric" 
								md-order-by="{{C.header_index}}">{{ C.column_title }}</th>
						</tr>
					</thead>

					<tbody md-body> <!-- md-virtual-repeat -->
						<tr md-row class="md-row-hover" ng-repeat="R in Data"> <!-- | filter:filterRows | orderBy:orderRows -->
							<td md-cell class="md-cell-compress " ng-if="Grid.Config.row_buttons.length > 0" style="padding: 0 5px !important;">
								<md-button class="md-icon-button s30 no-padding" aria-label="b" 
									ng-repeat="B in Grid.Config.row_buttons" 
									ng-click="triggerButton(B,R)">
									<md-icon md-font-icon="fa-fw {{ ::B.icono }} text-16px focus-on-hover"></md-icon>
									<md-tooltip md-direction="right" md-delay=500>{{ ::B.texto }}</md-tooltip>
								</md-button>
							</td>
							<td md-cell ng-repeat="C in Grid.columnas | filter:{Visible:true}"
								ng-class="{ 'md-cell-compress': ($first || inArray(C.campo.Tipo, ['Lista', 'Imagen'])) }" >

								<div ng-if="C.campo.Tipo == 'Lista'" class="w100p">
									<div ng-repeat="Op in [getOpcionLista(R[C.header_index], C.campo.Config)]">
										<div class="lista-pill" ng-style="{ backgroundColor: Op.color, color: calcTextColor(Op.color) }">
											<md-icon ng-if="Op.icono" md-font-icon="{{ Op.icono }}" class="text-inherit fa-fw"></md-icon>
											{{ Op.desc || Op.value }}
										</div>
									</div>
								</div>

								<div ng-if="C.campo.Tipo == 'Imagen'" ng-click="previewCampo(C, R[C.header_index])">
									<img ng-src="{{ ::R[C.header_index].url }}" height="30" style="margin-bottom: -3px;">
								</div>
								<div ng-if="C.campo.Tipo == 'TextoLargo'" class="w100p">{{ ::R[C.header_index] | limitTo:50 }}
									<md-icon md-svg-icon="md-more-h" ng-show="R[C.header_index].length > 50" class="s20 Pointer" ng-click="previewCampo(C, R[C.header_index])"></md-icon>
								</div>
								<div ng-if="inArray(C.campo.Tipo, ['Entero', 'Decimal', 'Dinero'])" class="w100p">
									<div class="s20 border-rounded border v-inline-middle Pointer" 
										ng-style="{ backgroundColor: calcAlertColor(C.campo.Config.alerts, R[C.header_index]) }" style="margin-right: 3px;"
										ng-if="C.campo.Config.use_alerts && R[C.header_index] !== null"
										ng-click="viewNumericGauge(C.campo, R[C.header_index])"></div>
									<div class="v-inline-middle mw25">{{ ::R[C.header_index] }}</div>
								</div>
								<div ng-if="C.campo.Tipo == 'Link'" class="w100p">
									<a ng-href="{{ ::R[C.header_index] }}" target="_blank">{{ ::R[C.header_index] | limitTo:50 }}</a>
								</div>
								<div ng-if="!inArray(C.campo.Tipo, ['Lista', 'Imagen','TextoLargo', 'Entero', 'Decimal', 'Dinero', 'Link'])" class="w100p">{{ ::R[C.header_index] }}</div>
							</td>
						</tr>
					</tbody>

				</table>
				<div class="h50"></div>
			</md-table-container>

		</div>
	</div>

	<style type="text/css">
		.v-inline-middle{
			display: inline-block;
			vertical-align: middle;
		}
	</style>

</md-dialog>