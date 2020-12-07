<div class="bg-white border border-radius">
	<div class="h30" layout layout-align="center center">
		<div class="md-subheader margin-left margin-right">Metas</div>
		<span flex></span>
		<md-button class="md-icon-button no-margin no-padding s30 margin-right focus-on-hover" aria-label="b" ng-click="addMeta()">
			<md-icon md-svg-icon="md-plus"></md-icon>
			<md-tooltip md-direction="left">Agregar Meta</md-tooltip>
		</md-button>
	</div>
	<md-table-container class="" ng-show="MetasCRUD.rows.length > 0">
		<table md-table class="md-table-short table-col-compress">
			<thead md-head>
				<tr md-row>
					<th md-column>Desde</th>
					<th md-column md-numeric>{{ IndSel.Sentido == 'RAN' ? 'Límite Inferior' : 'Meta' }}</th>
					<th md-column md-numeric ng-show="IndSel.Sentido == 'RAN'">{{ IndSel.Sentido == 'RAN' ? 'Límite Superior' : null }}</th>
					<th md-column></th>
					<th md-column></th>
				</tr>
			</thead>
			<tbody md-body>
				<tr md-row class="" ng-repeat="M in MetasCRUD.rows | orderBy:'PeriodoDesde'" ng-class="{'bg-yellow': M.changed}">
					<td md-cell class="md-cell-compress">{{ M.PeriodoDesde }}</td>
					<td md-cell class="md-cell-compress text-16px text-bold mw80">
						{{ M.Meta  | numberformat:IndSel.TipoDato:IndSel.Decimales  }}
					</td>
					<td md-cell class="md-cell-compress text-16px text-bold mw80" ng-show="IndSel.Sentido == 'RAN'">
						{{ M.Meta2 | numberformat:IndSel.TipoDato:IndSel.Decimales }}
					</td>
					<td md-cell class=""></td>
					<td md-cell class="md-cell-compress">
						<md-button class="md-icon-button no-margin no-padding s30 focus-on-hover" aria-label="b" ng-click="editMeta(M)">
							<md-icon md-font-icon="fa-pencil-alt fa-lg"></md-icon>
						</md-button>
					</td>
				</tr>
			</tbody>
		</table>
	</md-table-container>
</div>