<div class="bg-white border margin-but-top border-radius" layout=column ng-show="CamposCRUD.rows.length > 0">
	<md-subheader class="no-padding margin md-no-sticky">Restricciones</md-subheader>

	<md-table-container>
		<table md-table class="md-table-short">
			<thead md-head ng-show="RestricCRUD.rows.length > 0">
				<tr md-row>
					<th md-column>Campo</th>
					<th md-column>Restricción</th>
					<th md-column></th>
				</tr>
			</thead>
			<tbody md-body>
				<tr md-row class="" ng-repeat="R in RestricCRUD.rows" ng-class="{ 'bg-yellow': R.changed }">
					<td md-cell class="md-cell-compress">
						<div class="w100p"><md-icon md-svg-icon="{{ TiposCampo[R.campo.Tipo].Icon }}" class="s20 margin-right-5"></md-icon>
						{{ R.campo.Alias !== null ? R.campo.Alias : R.campo.Columna }}</div>
					</td>
					<td md-cell>
						@include('Entidades.Entidades_Restricciones_Inputs')
					</td>
					<td md-cell class="md-cell-compress">
						<md-button class="md-icon-button s20 no-margin" aria-label="b" ng-click="removeRestriccion(R)">
							<md-icon md-svg-icon="md-close" class="s20"></md-icon>
						</md-button>
					</td>
				</tr>
				<tr md-row class="">
					<td md-cell class="md-cell-compress">
						<md-input-container md-no-float>
							<label>Agregar Restricción</label>
							<md-select ng-model="newRestriccion" ng-change="addRestriccion(newRestriccion); newRestriccion = null" aria-label=s >
								<md-option ng-value="C.id" ng-repeat="C in CamposCRUD.rows">
									<md-icon md-svg-icon="{{ TiposCampo[C.Tipo].Icon }}" class="s20 margin-right-5"></md-icon>
									{{ C.Alias !== null ? C.Alias : C.Columna }}
								</md-option>
							</md-select>
						</md-input-container>
					</td>
					<td md-cell></td>
					<td md-cell></td>
				</tr>
			</tbody>
		</table>
	</md-table-container>

</div>