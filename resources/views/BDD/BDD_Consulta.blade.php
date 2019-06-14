<div flex layout=column class="padding-but-top">
		
	<div layout class="margin-top-5 margin-bottom-5" layout-align="center center">
		<md-input-container flex class="no-margin" md-no-float>
          <textarea ng-model="SQLQuery" rows="2" placeholder="Consulta SQL"></textarea>
        </md-input-container>
        <md-button class="md-icon-button md-raised" aria-label="b" ng-click="executeQuery()" ng-disabled="executingQuery">
        	<md-icon md-font-icon="fa-bolt"></md-icon>
        	<md-tooltip>Ejecutar Consulta</md-tooltip>
        </md-button>
         <md-button class="md-icon-button no-margin-right" aria-label="b" ng-click="BDDFavSidenav = !BDDFavSidenav">
        	<md-icon md-font-icon="fa-star" ng-class="{ far: !BDDFavSidenav }"></md-icon>
        	<md-tooltip>Favoritos</md-tooltip>
        </md-button>
    </div>

    <div flex layout layout-align="center center" ng-show="executingQuery">
    	<md-progress-circular md-mode="indeterminate" md-diameter="30"></md-progress-circular>
    	<h3 class="md-title margin-left-20">Cargando...</h3>
    </div>

    <md-card flex layout=column class="no-margin" ng-if="!executingQuery && QueryRows !== null">
    <md-table-container flex md-virtual-repeat-container>
		<table md-table class="md-table-short">
			<thead md-head>
				<tr md-row>
					<th md-column md-numeric class="text-clear">#</th>
					<th md-column ng-repeat="(C,V) in QueryRows[0]">{{ C }}</th>
				</tr>
			</thead>
			<tbody md-body>
				<tr md-row md-virtual-repeat="R in QueryRows" class="md-row-hover repeated-item">
					<td md-cell class="md-cell-compress text-clear padding-left padding-right">{{ $index + 1 }}</td>
					<td md-cell class="md-cell-compress" ng-repeat="V in R">{{ V }}</td>
				</tr>
			</tbody>
		</table>
	</md-table-container>
	</md-card>

	<div layout class="margin-top text-clear">
		<span flex></span>
		<span class="text-12px" ng-show="QueryRows !== null">{{ QueryRows.length | number }} Filas</span>
	</div>

</div>