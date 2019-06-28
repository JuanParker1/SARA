<md-dialog flex=95 class="vh90" layout=column aria-label="d">
	
	<div layout class="h30 padding-left bg-white border-bottom" layout-align="center center">
		<div class="md-subhead lh30 margin-right-20 text-bold">{{ Grid.Titulo }} </div>
		<span flex></span>
		<md-button class="md-icon-button no-margin no-padding s30 focus-on-hover" aria-label="b" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close" class="s20"></md-icon>
		</md-button>
	</div>

	<div flex layout>
		
		<md-sidenav class="w220 bg-lightgrey-5 border-right" layout=column md-is-locked-open="!hideFilters && Grid.filtros.length > 0">
			
			<div flex layout=column class="overflow-y darkScroll padding-0-10">

				<div ng-repeat="F in Grid.filtros" ng-class="{'margin-top':!F.filter_cont}">
					<div class="text-bold text-clear text-13px" ng-show="!F.filter_cont">{{ ::F.filter_header }}</div>
					
					<div ng-if="F.campo.Tipo == 'Fecha'" layout>
						<div class="lh30 text-clear text-12px margin-right-20">{{ ::F.filter_comparator }}</div>
						<md-input-container class="no-margin no-padding">
							<md-datepicker ng-model="F.val" md-hide-icons="calendar" aria-label="f" class="compact"></md-datepicker>
						</md-input-container>
					</div>

					<div ng-if="F.Comparador == 'lista'" layout>
						<md-input-container flex class="no-margin no-padding" md-no-float>
							<md-select ng-model="F.val" class="text-12px w100p block" multiple placeholder="Seleccionar" md-selected-text="getSelectedText(F.val)">
								<md-select-header class="demo-select-header" hide>
									<input ng-model="F.searchTerm" type="search" placeholder="Buscar..." class="md-text">
								</md-select-header>
								<md-option ng-value="Op" ng-repeat="Op in F.options | filter:F.searchTerm " class="h30">{{ ::Op }}</md-option>
							</md-select>
						</md-input-container>
						<md-button class="md-icon-button no-margin no-padding s20 focus-on-hover margin-top-5" aria-label="b" ng-show="F.val != null" ng-click="F.val = null">
							<md-icon md-svg-icon="md-close" class="s20 "></md-icon>
						</md-button>
					</div>

					<div ng-if="F.Comparador == 'query'" layout>
						<md-input-container flex class="no-margin no-padding text-12px" md-no-float>
							<input ng-model="F.val" placeholder="Buscar" autocomplete="false" name="a"></input>
						</md-input-container>
						<md-button class="md-icon-button no-margin no-padding s20 focus-on-hover margin-top-5" aria-label="b" ng-show="F.val != null" ng-click="F.val = null">
							<md-icon md-svg-icon="md-close" class="s20 "></md-icon>
						</md-button>
					</div>

					<div ng-if="F.Comparador == 'radios'" layout>
						<md-radio-group flex ng-model="F.val" class="block margin-top" layout=column>
					      <md-radio-button class="md-primary margin-bottom text-12px" ng-value="Op" ng-repeat="Op in F.options" aria-label="s">
					      	{{ ::Op }}
					      </md-radio-button>
					    </md-radio-group>
					    <md-button class="md-icon-button no-margin no-padding s20 focus-on-hover margin-top-5" aria-label="b" ng-show="F.val != null" ng-click="F.val = null">
							<md-icon md-svg-icon="md-close" class="s20 "></md-icon>
						</md-button>
					</div>
					
				</div>
				

				<div class="h30"></div>

			</div>
			<md-button class="margin-5" aria-label="a" ng-click="filterData()">
				<md-icon md-font-icon="fa-redo fa-fw"></md-icon>
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
				<div class="text-clear margin-left margin-right text-13px">{{ Data.length | number }} Registros</div>
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
						<tr md-row class="md-row-hover" ng-repeat="R in Data | filter:filterRows | orderBy:Grid.order">
							<td md-cell ng-repeat="C in Grid.columnas | filter:{Visible:true}">{{ ::R[C.header_index] }}</td>
						</tr>
					</tbody>

				</table>
				<div class="h50"></div>
			</md-table-container>
		</div>
	</div>



</md-dialog>