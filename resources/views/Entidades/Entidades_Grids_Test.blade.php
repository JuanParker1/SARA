<md-dialog flex=95 class="vh90" layout=column aria-label="d">
	
	<div layout class="h30 padding-left bg-white border-bottom" layout-align="center center">
		<div class="md-subhead lh30 margin-right-20 text-bold">{{ Grid.Titulo }} </div>
		<span flex></span>
		<md-button class="md-icon-button no-margin no-padding s30 focus-on-hover" aria-label="b" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close" class="s20"></md-icon>
		</md-button>
	</div>

	<div flex layout>
		
		<md-sidenav class="w230 bg-lightgrey-5 border-right" layout=column md-is-locked-open="!hideFilters && Grid.filtros.length > 0">

			<div flex layout=column class="overflow-y darkScroll padding-0-10">

				<div ng-repeat="F in Grid.filtros">
					@include('Core.Filtros')
				</div>
				<div class="h30"></div>
			</div>
			<md-button class="margin md-raised" aria-label="a" ng-click="filterData()">
				<md-icon md-font-icon="fa-bolt fa-fw"></md-icon>
			</md-button>

		</md-sidenav>
		<div flex layout=column>
			<div class="h30 border-bottom" layout layout-align="center center">
				<div class="w5"></div>
				<md-button class="md-icon-button no-margin no-padding s30 focus-on-hover" aria-label="b" ng-click="hideFilters = !hideFilters"
					ng-show="Grid.filtros.length > 0">
					<md-tooltip>Ver/Ocultar Filtros</md-tooltip>
					<md-icon md-font-icon="fa-filter fa-fw" class="s20"></md-icon>
				</md-button>

				<md-input-container class="md-no-underline md-icon-float no-margin" md-no-float flex style="padding-left: 25px">
					<md-icon md-svg-icon="md-search" class="s20"></md-icon>
					<input type="search" placeholder="Buscar..." ng-model="filterRows">
				</md-input-container>
				<div class="text-clear margin-left margin-right text-13px">{{ Grid.data.length | number }} Registros</div>
				<md-button class="md-icon-button no-margin no-padding s30 focus-on-hover" aria-label="b" ng-click="downloadData()">
					<md-tooltip>Descargar</md-tooltip>
					<md-icon md-font-icon="far fa-arrow-alt-circle-down fa-lg fa-fw" class="s20"></md-icon>
				</md-button>
			</div>
			<md-table-container flex md-virtual-repeat-container>
				<table md-table class="md-table-short border-bottom table-nowrap table-col-compress">
					
					<thead md-head md-order="Grid.order">
						<tr md-row>
							<th md-column ng-repeat="C in Grid.columnas | filter:{Visible:true}" md-numeric="C.header_numeric" 
								md-order-by="{{C.header_index}}">{{ C.header }}</th>
						</tr>
					</thead>

					<tbody md-body> <!-- md-virtual-repeat -->
						<tr md-row class="md-row-hover" ng-repeat="R in Grid.data | filter:filterRows | orderBy:Grid.order">
							<td md-cell ng-repeat="C in Grid.columnas | filter:{Visible:true}">{{ ::R[C.header_index] }}</td>
						</tr>
					</tbody>

				</table>
				<div class="h50"></div>
			</md-table-container>
		</div>
	</div>



</md-dialog>