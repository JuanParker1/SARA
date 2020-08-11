<md-dialog class="wu600" aria-label=m>

	<div class="h30 padding-0-5" layout>
		<div flex class="lh30 md-subhead">Configurar Editor</div>
		<md-button class="md-icon-button s30 no-padding only-dialog no-margin" aria-label="Button" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close" class=""></md-icon>
		</md-button>
	</div>

	<div layout=column flex class="padding well overflow-y darkScroll">
		
		<div class="md-caption margin-bottom-5">Editor</div>
		<md-autocomplete 
			md-selected-item="selectedItem"
			md-search-text="B.accion_element"
			md-selected-item-change="selectElm(item, B)"
			md-items="item in queryElm(B.accion_element, B.accion)"
			md-item-text="item.display"
			md-min-length="0" 
			placeholder="Buscar elemento" class="h30 margin-bottom">
			<md-item-template>
				<span md-highlight-text="B.accion_element" md-highlight-flags="^i">{{item.display}}</span>
			</md-item-template>
			<md-not-found>No encontrado</md-not-found>
		</md-autocomplete>

		<md-input-container class="no-margin-bottom">
			<label>Modo</label>
			<md-select ng-model="B.modo" aria-label=s class="no-margin">
				<md-option value="Crear">Crear</md-option>
				<md-option value="Editar">Editar</md-option>
			</md-select>
		</md-input-container>
		

		<div class="bg-white border border-radius margin-top">
			<md-table-container class="">
				<table md-table class="md-table-short table-col-compress">
					<thead md-head>
						<tr md-row>
							<th md-column></th>
							<th md-column>Campo</th>
							<th md-column><md-icon md-font-icon="fa-asterisk">	 <md-tooltip md-direction=up>Requerido</md-tooltip></md-icon></th>
							<th md-column><md-icon md-font-icon="fa-fingerprint"><md-tooltip md-direction=up>Unico</md-tooltip></md-icon></th>
							<th md-column>Valor</th>
							<th md-column></th>
						</tr>
					</thead>
					<tbody md-body>
						<tr md-row class="" ng-repeat="R in Editor.campos">
							<td md-cell class="md-cell-compress"><md-icon md-font-icon="fa-key fa-fw" ng-if="R.campo.id == Editor.entidad.campo_llaveprim"></md-icon></td>
							<td md-cell class="md-cell-compress">
								<md-icon md-svg-icon="{{ TiposCampo[R.campo.Tipo].Icon }}" class="s15"></md-icon>
								<div style="display:inline">{{ R.campo_title }}</div>
							</td>
							<td md-cell class="md-cell-compress"><md-icon md-font-icon="fa-check fa-fw" ng-if="R.campo.Requerido"></md-icon></td>
							<td md-cell class="md-cell-compress"><md-icon md-font-icon="fa-check fa-fw" ng-if="R.campo.Unico"></md-icon></td>
							<td md-cell class="md-cell-compress">

								<md-select ng-model="B.campos[R.id]['tipo_valor']" class="w100p" aria-label="s">
									<md-option value="Por Defecto">Por Defecto</md-option>
									<md-option value="Columna" ng-if="B.modo == 'Editar'">Columna</md-option>
									<md-option value="Fijo">Fijo</md-option>
									<md-option value="Variable">Variable</md-option>
									<md-option value="Sin Valor">Sin Valor</md-option>
								</md-select>

							</td>
							<td md-cell>

								<md-select ng-model="B.campos[R.id]['columna_id']" class="mw50 margin-left" aria-label="s" ng-if="B.campos[R.id]['tipo_valor'] == 'Columna'">
									<md-option ng-repeat="Op in GridColumnas" ng-value="Op.id">
										<md-icon md-svg-icon="{{ TiposCampo[Op.campo.Tipo].Icon }}" class="s15" style="transform: translateY(-5px);"></md-icon>
										{{ Op.column_title }}
									</md-option>
								</md-select>

								<md-select ng-model="B.campos[R.id]['valor']" class="mw50 margin-left" aria-label="s" ng-if="B.campos[R.id]['tipo_valor'] == 'Variable'">
									<md-option value="User">Usuario Logeado</md-option>
									<md-option value="Date">Fecha Actual</md-option>
									<md-option value="DateTime">FechaHora Actual</md-option>
									<md-option value="Time">Hora Actual</md-option>
								</md-select>

							</td>
						</tr>
					</tbody>
				</table>
			</md-table-container>
		</div>

		<pre>{{ B | json }}</pre>

	</div>

	<div layout class="bg-lightgrey-5">
		<span flex></span>
		<md-button class="md-raised md-primary" ng-click="guardarConfig()">Guardar</md-button>
	</div>

</md-dialog>