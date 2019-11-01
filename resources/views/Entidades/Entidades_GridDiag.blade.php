<md-dialog class="no-overflow h100p" flex=100 aria-label=m>

	<div class="h40 border-bottom padding-0-5 inherit-color" layout layout-align="center center">

		<md-input-container class="md-no-underline md-icon-float no-margin" md-no-float flex style="padding-left: 25px">
			<md-icon md-svg-icon="md-search" class="s20"></md-icon>
			<input type="search" placeholder="Buscar..." ng-model="filterRows" autocomplete="false" name="a" ng-model-options="{ debounce: 450 }">
		</md-input-container>

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
			

			<md-table-container flex md-virtual-repeat-container ng-show="!loadingGrid">
				<table md-table class="md-table-short border-bottom table-nowrap table-col-compress">
					
					<thead md-head md-order="Grid.order">
						<tr md-row>
							<th md-column class="" ng-if="Grid.Config.row_buttons.length > 0"></th>
							<th md-column ng-repeat="C in Grid.columnas | filter:{Visible:true}" md-numeric="C.header_numeric" 
								md-order-by="{{C.header_index}}">{{ C.column_title }}</th>
						</tr>
					</thead>

					<tbody md-body> <!-- md-virtual-repeat -->
						<tr md-row class="md-row-hover" ng-repeat="R in Grid.data | filter:filterRows | orderBy:Grid.order">
							<td md-cell class="md-cell-compress " ng-if="Grid.Config.row_buttons.length > 0" style="padding: 0 5px !important;">
								<md-button class="md-icon-button s30 no-padding" aria-label="b" ng-repeat="B in Grid.Config.row_buttons">
									<md-icon md-font-icon="fa-fw {{ B.icono }} text-16px focus-on-hover"></md-icon>
									<md-tooltip md-direction="right" md-delay=500>{{ B.texto }}</md-tooltip>
								</md-button>
							</td>
							<td md-cell ng-repeat="C in Grid.columnas | filter:{Visible:true}" ng-class="{ 'md-cell-compress': $first }">
								{{ ::R[C.header_index] }}
							</td>
						</tr>
					</tbody>

				</table>
				<div class="h50"></div>
			</md-table-container>

		</div>
	</div>

</md-dialog>