<md-dialog class="mw300" aria-label=d layout=column style="max-height: 100%;">
	
	<div class="h30 padding-left " layout layout-align="center center">

		<div class="text-bold" flex>Buscar: {{ C.campo_title }}</div>

		<md-button class="md-icon-button s30 no-padding only-dialog no-margin" 
			aria-label="Button" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close" class=""></md-icon>
		</md-button>

	</div>
		
	<md-table-container class="border-bottom">
		<table md-table class="md-table-short">
			<thead md-head class="">
				<tr md-row>
					<th md-column ng-repeat="(kC,C) in SearchTable.campos" ng-if="kC > 0">
						{{ C.campo_title }}
					</th>
				</tr>
				<tr md-row>
					<th md-column ng-repeat="(kC,C) in SearchTable.campos" ng-if="kC > 0">
						<md-input-container md-no-float class="no-margin no-padding mw100">
							<input type="text" class="no-padding" ng-model="C.searchText" placeholder="Buscar..." enter-stroke="searchRows()" ng-blur="searchRows()" ng-readonly="searching">
						</md-input-container>
					</th>
				</tr>
			</thead>
			<tbody md-body ng-show="!searching">
				<tr md-row class="md-row-hover Pointer" ng-repeat="R in Rows" ng-click="selectItem(R)">
					<td md-cell class="" ng-repeat="(kC,C) in SearchTable.campos" ng-if="kC > 0">
						{{R['C'+kC]}}
					</td>
				</tr>
			</tbody>
		</table>
		<md-progress-linear ng-show="searching"></md-progress-linear>
	</md-table-container>

</md-dialog>