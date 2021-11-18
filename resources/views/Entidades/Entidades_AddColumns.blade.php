<md-dialog class="vh90" flex="95">
<div layout class="padding-left">
	<div class="md-title lh40" flex>Agregar Campos a: {{ EntidadSel.Nombre }}</div>
	<md-button class="md-icon-button no-margin" aria-label="Button" ng-click="CancelDiag()">
		<md-icon md-svg-icon="md-close"></md-icon>
	</md-button>
</div>
<md-table-container flex>
	<table md-table class="md-table-short table-col-compress" md-row-select multiple ng-model="newCamposSel">
		<thead md-head>
			<tr md-row>
				<th md-column>Columna</th>
				<th md-column>Alias</th>
				<th md-column><md-icon md-font-icon="fa-asterisk"><md-tooltip>Requerido</md-tooltip></md-icon></th>
				<th md-column><md-icon md-font-icon="fa-eye"><md-tooltip>Visible</md-tooltip></md-icon></th>
				<th md-column><md-icon md-font-icon="fa-fingerprint"><md-tooltip>Único</md-tooltip></md-icon></th>
				<th md-column><md-icon md-font-icon="fa-list"><md-tooltip>Desagregable</md-tooltip></md-icon></th>
				<th md-column>Tipo</th>
				<th md-column>Opciones</th>
				<th md-column>Valor por Defecto</th>
			</tr>
		</thead>
		<tbody md-body>
			<tr md-row class="" ng-repeat="C in newCampos" md-select="C" md-select-id="id">
				@include('Entidades.Entidades_Campos_Inputs', ['withSave' => false])
			</tr>
		</tbody>
	</table>
	<div class="h40"></div>
</md-table-container>

<div layout ng-show="newCamposSel.length > 0" class="border-top">
	<span flex></span>
	<md-button class="md-primary md-raised" ng-click="addNewColumns()">
		<md-icon md-font-icon="fa-download" class="margin-right"></md-icon>Agregar {{ newCamposSel.length }} Campos
	</md-button>
</div>
</md-dialog>