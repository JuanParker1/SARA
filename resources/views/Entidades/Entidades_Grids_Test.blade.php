<md-dialog flex=95 class="vh90">
	
	<div layout class="h40 padding-left bg-white border-bottom" layout-align="center center">
		<div class="md-title lh40 no-margin">{{ Grid.Titulo }} </div>
		<div class="text-clear margin-left text-13px">{{ Grid.data.length | number }}</div>
		<span flex></span>
		<md-button class="md-icon-button no-margin no-padding s40" aria-label="b" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close" class="s20"></md-icon>
		</md-button>
	</div>

	<div flex layout>
		
		<div class="w220 border-right padding" layout=column>
			<md-subheader class="no-padding">Filtros</md-subheader>
			<div flex layout=column class="overflow-y">
				
				<md-input-container class="">
					<label>Fecha</label>
					<md-datepicker ng-model="a"></md-datepicker>
				</md-input-container>

			</div>
			<md-button class="no-margin" aria-label="a">
				<md-icon md-font-icon="fa-sync fa-fw fa-lg"></md-icon>
			</md-button>
		</div>
		<md-table-container flex md-virtual-repeat-container>
			<table md-table class="md-table-short border-bottom">
				
				<thead md-head>
					<tr md-row>
						<th md-column ng-repeat="C in Grid.columnas" md-numeric="C.header_numeric">{{ C.header }}</th>
					</tr>
				</thead>

				<tbody md-body>
					<tr md-row class="md-row-hover" md-virtual-repeat="R in Grid.data">
						<td md-cell ng-repeat="C in R track by $index" class="md-cell-compress">{{ C }}</td>
					</tr>
				</tbody>

			</table>
			<div class="h50"></div>
		</md-table-container>
	</div>



</md-dialog>