<md-dialog flex=100 class="vh100 mw820" layout=column>

	<div layout layout-align="center center" class="">
		<div class="md-title text-14px padding-left" flex>Gestión de Comentarios</div>
		<md-button class="md-icon-button no-margin no-padding focus-on-hover s30" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close"></md-icon>
		</md-button>
	</div>

	<div layout  class="padding-right" layout-align="center center">
		<md-input-container class="margin-left no-margin-bottom">
			<label>Desde</label>
			<md-datepicker  ng-model="filters.desde" md-mode="month" ng-change="getComments()" md-hide-icons="calendar"></md-datepicker>
		</md-input-container>
		<md-input-container class="margin-left no-margin-bottom">
			<label>Hasta</label>
			<md-datepicker  ng-model="filters.hasta" md-mode="month" ng-change="getComments()" md-hide-icons="calendar"></md-datepicker>
		</md-input-container>
		
		<div layout=column class="margin-left">
			<div class="text-clear text-12px margin-top-5">Indicador</div>
			<div class="bg-lightgrey-2 padding-5 border-radius border Pointer">{{ filters.Indicador.proceso.Proceso }} - {{ filters.Indicador.Indicador }}</div>
		</div>

		<span flex></span>

		<md-button class="md-icon-button focus-on-hover" aria-label="b" style="margin: 10px 5px 0 0;"
			ng-click="downloadLogs()" hide>
			<md-icon md-font-icon="fa-download"></md-icon>
			<md-tooltip>Descargar</md-tooltip>
		</md-button>

		<md-button class="md-raised md-primary no-margin" aria-label="b"
			ng-click="addComment()">
			<md-icon md-font-icon="fa-plus"></md-icon>
			Agregar
		</md-button>

	</div>
	
	<md-table-container flex class="darkScroll" md-virtual-repeat-container>
		<table md-table class="md-table-short border-bottom">
			<thead md-head>
				<tr md-row>
					<th md-column></th>
					<th md-column>Periodo</th>
					<th md-column></th>
					<th md-column>Usuario</th>
					<th md-column>Comentario</th>
					<th md-column>Creado</th>
				</tr>
			</thead>
			<tbody md-body>
				<tr md-row md-virtual-repeat="Row in CommentsCRUD.rows" class="md-row-hover">
					<td md-cell class="md-cell-compress" style="padding: 0 !important;">
						<md-button class="md-icon-button focus-on-hover" ng-click="editComment(Row)">
							<md-icon md-svg-icon="md-edit"></md-icon>
						</md-button>
					</td>
					<td md-cell class="md-cell-compress">{{ Row.Op1 }}</td>
					<td md-cell class="md-cell-compress w30" style="padding: 0 !important;">
						<div class="s30 bg-lightgrey border-rounded margin-right-5 border" 
							style="background-image: url({{ Row.autor.avatar }}); background-size: cover; background-position: top center;"></div>
					</td>
					<td md-cell class="md-cell-compress">{{ Row.autor.Nombres }}</td>
					<td md-cell class="">
						<div class="w100p margin-5-0" layout=column>
							<div>{{ Row.Comentario }}</div>
						</div>
					</td>
					<td md-cell class="md-cell-compress">{{ Row.created_at }}</td>
				</tr>
			</tbody>
		</table>
		<div class="h50"></div>
	</md-table-container>

</md-dialog>