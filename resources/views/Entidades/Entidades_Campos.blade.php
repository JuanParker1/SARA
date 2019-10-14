<div class="bg-white border margin-but-top border-radius" layout=column >
	<md-subheader class="no-padding margin md-no-sticky Pointer" ng-click="showCampos = !showCampos">
		<md-icon md-font-icon="fa-chevron-right fa-fw s20" ng-class="{'fa-rotate-90': showCampos}"></md-icon>
		Campos ({{ CamposCRUD.rows.length }})
	</md-subheader>
	<md-progress-linear md-mode="indeterminate" ng-show="CamposCRUD.ops.loading"></md-progress-linear>
	<md-table-container flex ng-show="!CamposCRUD.ops.loading && showCampos">
	<table md-table class="md-table-short table-col-compress" md-row-select multiple ng-model="camposSel">
		<thead md-head>
			<tr md-row>
				<th md-column></th>
				<th md-column>Columna</th>
				<th md-column>Alias</th>
				<th md-column><md-icon md-font-icon="fa-asterisk">	 <md-tooltip md-direction=up>Requerido</md-tooltip></md-icon></th>
				<th md-column><md-icon md-font-icon="fa-eye">		 <md-tooltip md-direction=up>Visible</md-tooltip></md-icon></th>
				<th md-column><md-icon md-font-icon="fa-fingerprint"><md-tooltip md-direction=up>Unico</md-tooltip></md-icon></th>
				<th md-column>Tipo</th>
				<th md-column>Opciones</th>
				<th md-column>Valor por Defecto</th>
			</tr>
		</thead>
		<tbody md-body as-sortable="dragListener" ng-model="CamposCRUD.rows">
			<tr md-row class="" ng-repeat="C in CamposCRUD.rows" as-sortable-item 
				md-select="C.id" md-select-id="id" ng-class="{ 'bg-yellow': C.changed }">
				<td md-cell class="md-cell-compress padding-0-5">
					<md-button class="md-icon-button w30 mw30 h30 mh30 no-margin no-padding drag-handle" aria-label="b" as-sortable-item-handle>
						<md-icon md-svg-icon="md-drag-handle"></md-icon>
					</md-button>
				</td>
				@include('Entidades.Entidades_Campos_Inputs', ['withSave' => false])
			</tr>
			<tr ng-repeat="C in [newCampo]">
				<td md-cell></td>
				<td md-cell>
					<md-button class="md-icon-button w30 mw30 h30 mh30 no-margin no-padding" aria-label="b" ng-click="addCampo()">
						<md-icon md-svg-icon="md-plus"></md-icon>
						<md-tooltip md-direction="left">Agregar</md-tooltip>
					</md-button>
				</td>
				@include('Entidades.Entidades_Campos_Inputs', ['withSave' => true])
			</tr>
		</tbody>
	</table>
</md-table-container>
</div>