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
				<input type="text" ng-model="newInd.Indicador" placeholder="Indicador" class="md-title" md-autofocus required autocomplete="off">
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

	</form>

	<div layout>
		<span flex></span>
		<md-button type="submit" form="newIndForm" class="md-raised bg-ocean" aria-label="b">Crear</md-button>
	</div>

</md-dialog>