<div ng-if="inArray(R.campo.Tipo, ['Texto','TextoLargo'])" layout>

	<md-input-container class="no-padding no-margin">
		<md-select ng-model="R.Comparador" aria-label="s" ng-change="markChanged(R)" class="h30">
			<md-option value="=">Es</md-option>
			<md-option value="lista">Lista</md-option>
			<md-option value="query">Busqueda</md-option>
		</md-select>
	</md-input-container>

	<div ng-if="inArray(R.Comparador, ['lista'])">
		<md-chips ng-model="R.Valor" class="h30" placeholder="Valores por defecto" ng-change="markChanged(R)"></md-chips>
	</div>

	<md-input-container ng-if="inArray(R.Comparador, ['=', 'query'])" class="no-margin" md-no-float>
		<input type="text" ng-model="R.Valor" placeholder="Valor por Defecto">
	</md-input-container>

</div>

<div ng-if="inArray(R.campo.Tipo, ['Entero','Decimal'])">
	<md-input-container class="no-padding no-margin">
		<md-select ng-model="R.Comparador" aria-label="s" ng-change="markChanged(R)" class="h30">
			<md-option value="=">Es</md-option>
			<md-option value=">">Mayor a</md-option>
			<md-option value=">=">Mayor o Igual a</md-option>
			<md-option value="<">Menor a</md-option>
			<md-option value="<=">Menor o Igual a</md-option>
		</md-select>
	</md-input-container>
	<md-input-container class="no-margin" md-no-float>
		<input type="number" ng-model="R.Valor" placeholder="Valor" class="text-right w105">
	</md-input-container>
</div>

<div ng-if="inArray(R.campo.Tipo, ['Dinero'])" layout>
	<md-input-container class="no-padding no-margin">
		<md-select ng-model="R.Comparador" aria-label="s" ng-change="markChanged(R)" class="h30">
			<md-option value="=">Es</md-option>
			<md-option value=">">Mayor a</md-option>
			<md-option value=">=">Mayor o Igual a</md-option>
			<md-option value="<">Menor a</md-option>
			<md-option value="<=">Menor o Igual a</md-option>
		</md-select>
	</md-input-container>
	<md-input-container class="no-margin" md-no-float>
		<input type="text" ng-model="R.Valor" placeholder="Valor" ui-money-mask="0" class="text-right w105">
	</md-input-container>
</div>

<div ng-if="inArray(R.campo.Tipo, ['Fecha'])">

	<md-input-container class="no-padding no-margin">
		<md-select ng-model="R.Comparador" class="" aria-label="s" ng-change="markChanged(R)">
			<md-option ng-repeat="(k,Op) in TiposCampo.Fecha.Comparators" value="{{ ::k }}">{{ ::Op }}</md-option>
		</md-select>
	</md-input-container>

	<md-input-container class="no-padding no-margin">
		<md-select ng-model="R.Op1" class="" aria-label="s" ng-change="markChanged(R); R.Valor = null">
			<md-option value="rel">Fecha relativa</md-option>
			<md-option value="fij">Fecha fija</md-option>
		</md-select>
	</md-input-container>

	<md-input-container class="no-padding no-margin" ng-if="!inArray(R.Comparador, ['nulo','no_nulo']) && R.Op1 == 'rel'">
		<md-select ng-model="R.Valor" class="" aria-label="s" ng-change="markChanged(R)">
			<md-option ng-repeat="FRel in TiposCampo.Fecha.Relatives" value="{{ ::FRel[0] }}">{{ ::FRel[1] }}</md-option>
		</md-select>
	</md-input-container>

	<md-input-container class="no-padding no-margin" md-no-float ng-if="!inArray(R.Comparador, ['nulo','no_nulo']) && R.Op1 == 'fij'">
		<md-datepicker ng-model="R.Valor" ng-change="markChanged(R)" placeholder="Valor" aria-label=f></md-datepicker>
	</md-input-container>

</div>

<div ng-if="inArray(R.campo.Tipo, ['Entidad'])">
	<md-input-container>
		<input type="text" ng-model="R.Valor">
	</md-input-container>
</div>


<div ng-if="inArray(R.campo.Tipo, ['Lista'])" layout>
	<md-select placeholder="Valor por defecto" ng-model="R.Valor" ng-change="markChanged(R)" multiple 
		class="block md-select-nowrap" style="max-width: 100%;"
		md-selected-text="getSelectedText(R.Valor, 'Valor por defecto...')">
		<md-option ng-repeat="Op in R.campo.Config.opciones" ng-value="Op.value">{{ Op.desc || Op.value }}</md-option>
	</md-select>
	<md-button class="md-icon-button" ng-show="R.Valor !== ''" ng-click="R.Valor = ''; markChanged(R)">
		<md-icon md-font-icon="fa-eraser"></md-icon>
	</md-button>
</div>