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
			placeholder="Buscar elemento" class="h30">
			<md-item-template>
				<span md-highlight-text="B.accion_element" md-highlight-flags="^i">{{item.display}}</span>
			</md-item-template>
			<md-not-found>No encontrado</md-not-found>
		</md-autocomplete>

		<div class="bg-white border border-radius margin-top">
			<md-table-container class="">
				<table md-table class="md-table-short table-col-compress">
					<thead md-head>
						<tr md-row>
							<th md-column>Campo</th>
							<th md-column>Valor</th>
							<th md-column></th>
						</tr>
					</thead>
					<tbody md-body>
						<tr md-row class="" ng-repeat="C in Editor.campos">
							<td md-cell class="md-cell-compress">
								<md-icon md-svg-icon="{{ TiposCampo[C.campo.Tipo].Icon }}" class="s15"></md-icon>
								<div style="display:inline">{{ C.campo_title }}</div>
							</td>
							<td md-cell class="md-cell-compress">
								<md-select ng-model="C.tipo_valor" class="w100p">
									<md-option ng-repeat="Op in TiposValor" ng-value="Op">{{ Op }}</md-option>
								</md-select>
							</td>
							<td md-cell></td>
						</tr>
					</tbody>
				</table>
			</md-table-container>
		</div>





		<pre hide>{{ Editor | json }}</pre>

	</div>

</md-dialog>