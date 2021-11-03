<div class="bg-white border border-radius">
	<div class="h30" layout layout-align="center center">
		<div class="md-subheader margin-left margin-right">Metas</div>
		<span flex></span>
	</div>
	<div layout=column class="padding-0-10 margin-bottom-5">
		<div layout>
			<md-select flex class="no-margin" ng-model="IndSel.config.meta_tipo" aria-label=s>
				<md-option ng-value="'fija'">Meta Fija</md-option>
				<md-option ng-value="'variable'">Basada en Variable</md-option>
			</md-select>
			<md-button class="md-icon-button no-margin no-padding s30 focus-on-hover" aria-label="b" ng-click="addMeta()"
				ng-show="IndSel.config.meta_tipo == 'fija'">
				<md-icon md-svg-icon="md-plus"></md-icon>
				<md-tooltip md-direction="left">Agregar Meta</md-tooltip>
			</md-button>
		</div>
	</div>
	<md-table-container class="" ng-show="IndSel.config.meta_tipo == 'fija' && MetasCRUD.rows.length > 0">
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
	
	<div layout=column ng-show="IndSel.config.meta_tipo == 'variable'" class="padding-0-10">
		<div class="h5"></div>

		<div ng-repeat="Var in VariablesCRUD.rows | filter:{ id: IndSel.config.meta_elemento_id }:true" ng-click="selectMetaVariable()"
			class="well border border-radius padding-5-10 Pointer"
			layout=column>
			<div>{{ Var.Variable }}</div>
			<div class="text-clear text-14px">{{ Var.proceso.Proceso }}</div>
		</div>

		<md-button class="md-raised no-margin bg-lightgrey-2" 
			ng-show="!IndSel.config.meta_elemento_id"
			ng-click="selectMetaVariable()">
			Seleccionar Variable
		</md-button>

		<div class="h5"></div>

		<md-input-container class="margin-bottom">
			<label>Desfase (Meses)</label>
			<input type="number" ng-model="IndSel.config.meta_desfase">
		</md-input-container>

		
	</div>
</div>