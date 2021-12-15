<md-progress-linear ng-show="FeedbackCRUD.ops.loading"></md-progress-linear>

<div layout layout-align="center center" class="h40 padding-0-10" ng-show="!FeedbackCRUD.ops.loading">
	<div class="text-bold text-clear margin-0-10" hide-xs>Retroalimentacion ({{ FeedbackCRUD.rows.length | number }})</div>
	<md-select ng-model="feedbackFilters.estado" class="no-margin md-no-underline" ng-change="getFeedback()">
	  <md-option ng-value="Op" ng-repeat="Op in feedbackEstados">{{ Op }}</md-option>
	</md-select>
	<md-input-container class="md-no-underline md-icon-float no-margin" md-no-float flex style="padding-left: 25px">
		<md-icon md-svg-icon="md-search" class="s20"></md-icon>
		<input type="search" placeholder="Buscar..." ng-model="filterRowsFeedback" ng-model-options="{ debounce: 300 }">
	</md-input-container>
</div>

<div flex layout>

	<md-card flex class="border-radius margin-but-top overflow-y hasScroll" 
		ng-show="!FeedbackCRUD.ops.loading">
		<md-table-container class="border-bottom" >
			<table md-table class="table-col-compress">
				<thead md-head>
					<tr md-row>
						<th md-column>Fecha</th>
						<th md-column>Usuario</th>
						<th md-column>Tema</th>
						<th md-column>Comentario</th>
						<th md-column>Estado</th>
					</tr>
				</thead>
				<tbody md-body>
					<tr md-row class="" ng-repeat="R in FeedbackCRUD.rows | filter:filterRowsFeedback">
						<td md-cell class="md-cell-compress">{{ R.created_at }}</td>
						<td md-cell class="md-cell-compress">
							<div layout=column class="w100p">
								<div>{{ R.usuario.Nombres }}</div>
								<div class="text-clear">{{ R.usuario.Email }}</div>
							</div>
						</td>
						<td md-cell class="md-cell-compress">{{ R.Tema }}</td>
						<td md-cell class="">{{ R.Comentario }}</td>
						<td md-cell class="md-cell-compress">
							<md-select ng-model="R.Estado" class="no-margin md-no-underline" ng-change="FeedbackCRUD.update(R)">
								<md-option ng-value="Op" ng-repeat="Op in feedbackEstados">{{ Op }}</md-option>
							</md-select>
						</td>
					</tr>
				</tbody>
			</table>
		</md-table-container>
	</md-card>

</div>

