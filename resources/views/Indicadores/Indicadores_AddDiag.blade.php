<md-dialog class="w100p mxw600 well" layout=column>
	
	<div layout layout-align="center center" class="padding-left-5">
		<div class="text-bold text-clear">Agregar Indicador</div>
		<span flex></span>
		<md-button class="md-icon-button no-margin s30 no-padding only-dialog" aria-label="Button" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close" class=""></md-icon>
		</md-button>
	</div>

	<form id="newIndForm" ng-submit="submitInd()" layout=column class="padding">
		
		<div layout layout layout-wrap>
			<md-input-container class="margin-bottom" flex=100>
				<input type="text" ng-model="newInd.Indicador" placeholder="Indicador" class="md-title" md-autofocus required>
			</md-input-container>

			<md-autocomplete flex=100 md-selected-item="newInd.proceso" md-search-text="searchText"  
				placeholder="Proceso" 
				md-items="item in searchProceso(searchText)" 
				md-min-length=0
				md-item-text="item.Proceso"
				class="margin-bottom">
				<span>{{ item.Proceso }}</span>
			</md-autocomplete>

		</div>

		<div layout layout-wrap>
			
			<md-input-container flex class="">
				<label>Formula</label>
				<input type="text" ng-model="newInd.Formula" aria-label=s>
			</md-input-container>

			<md-input-container class="">
				<label>Tipo de Dato</label>
				<md-select ng-model="newInd.TipoDato" aria-label=s>
					<md-option ng-repeat="Op in tiposDatoInd" ng-value="Op">{{ Op }}</md-option>
				</md-select>
			</md-input-container>
			<md-input-container class=" w40">
				<label>Dec.</label>
				<input type="number" ng-model="newInd.Decimales" aria-label=s min=0>
			</md-input-container>
			<md-input-container class="">
				<label>Sentido</label>
				<md-select ng-model="newInd.Sentido" aria-label=s>
					<md-option ng-value="'ASC'"><md-icon class="s20" md-font-icon="fa-fw fa-arrow-up"></md-icon></md-option>
					<md-option ng-value="'RAN'"><md-icon class="s20" md-font-icon="fa-fw fa-arrow-right"></md-icon></md-option>
					<md-option ng-value="'DES'"><md-icon class="s20" md-font-icon="fa-fw fa-arrow-down"></md-icon></md-option>
				</md-select>
			</md-input-container>
			<md-input-container class=" w40">
				<label>Meta</label>
				<input type="text" ng-model="newInd.Meta" aria-label=s>
			</md-input-container>
		</div>

	    <md-table-container flex>
			<table md-table class="md-table-short table-col-compress">
				<thead md-head>
					<tr md-row>
						<th md-column></th>
						<th md-column>Variables</th>
						<th md-column>Tipo</th>
						<th md-column>Dec.</th>
						<th md-column></th>
					</tr>
				</thead>
				<tbody md-body>
					<tr md-row ng-repeat="(k,V) in newInd.variables" class="md-row-hover">
						<td md-cell class="md-cell-compress">{{ getLetra(k) }}</td>
						<td md-cell class="">
							<md-input-container class="no-margin w100p md-no-underline" md-no-float>
								<input type="text" ng-model="V.Variable" placeholder="Variable" required>
							</md-input-container>
						</td>
						<td md-cell class="md-cell-compress">
							<md-select ng-model="V.TipoDato" aria-label=s>
								<md-option ng-repeat="Op in tiposDatoVar" ng-value="Op">{{ Op }}</md-option>
							</md-select>
						</td>
						<td md-cell class="md-cell-compress">
							<md-input-container class=" w40">
								<input type="number" ng-model="V.Decimales" aria-label=s min=0>
							</md-input-container>
						</td>
						<td md-cell class="md-cell-compress">
							<md-button class="md-icon-button no-margin no-padding s30 focus-on-hover" ng-click="removeVar(k)">
								<md-icon md-svg-icon="md-close"></md-icon>
							</md-button>
						</td>
					</tr>
					<tr md-row>
						<td md-cell class="md-cell-compress"></td>
						<td md-cell class="">
							<md-input-container class="no-margin w100p" md-no-float>
								<input type="text" ng-model="newVariable" placeholder="Agregar Variable" enter-stroke="addVariable(newVariable)">
							</md-input-container>
						</td>
						<td md-cell class="md-cell-compress"></td>
						<td md-cell class="md-cell-compress"></td>
						<td md-cell class="md-cell-compress"></td>
					</tr>
				</tbody>
			</table>
		</md-table-container>

		<pre>{{ newInd | json }}</pre>

	</form>

	<div layout>
		<span flex></span>
		<md-button type="submit" form="newIndForm" class="md-raised bg-ocean" aria-label="b">Crear</md-button>
	</div>

</md-dialog>