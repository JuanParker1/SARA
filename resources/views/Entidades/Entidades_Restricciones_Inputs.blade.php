<div ng-if="inArray(R.campo.Tipo, ['Texto','TextoLargo','Booleano','Entidad'])">

	<md-input-container class="no-padding no-margin">
		<md-select ng-model="R.Comparador" class="mw120" aria-label="s" ng-change="markChanged(R)">
			<md-option value="=">Es</md-option>
			<md-option value="like">Contiene</md-option>
			<md-option value="like_">Empieza con</md-option>
			<md-option value="_like">Termina con</md-option>
			<md-option value="nulo">Es nulo</md-option>
			<md-option value="no_nulo">No es nulo</md-option>
		</md-select>
	</md-input-container>

	<md-input-container class="no-padding no-margin" md-no-float ng-hide="inArray(R.Comparador, ['nulo','no_nulo'])">
		<input type="text" ng-model="R.Valor" aria-label="v" placeholder="Valor" ng-change="markChanged(R)">
	</md-input-container>

</div>

<div ng-if="inArray(R.campo.Tipo, ['Entero','Decimal'])">

	<md-input-container class="no-padding no-margin">
		<md-select ng-model="R.Comparador" class="" aria-label="s" ng-change="markChanged(R)">
			<md-option value="=">Es</md-option>
			<md-option value="<">Menor que</md-option>
			<md-option value="<=">Menor o igual que</md-option>
			<md-option value=">">Mayor que</md-option>
			<md-option value=">=">Mayor o igual que</md-option>
			<md-option value="nulo">Es nulo</md-option>
			<md-option value="no_nulo">No es nulo</md-option>
		</md-select>
	</md-input-container>

	<md-input-container class="no-padding no-margin" md-no-float ng-hide="inArray(R.Comparador, ['nulo','no_nulo'])">
		<input type="text" ng-model="R.Valor" aria-label="v" placeholder="Valor" ng-change="markChanged(R)">
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

<div ng-if="inArray(R.campo.Tipo, ['Lista'])" layout>
	<md-input-container class="no-padding no-margin">
		<md-select ng-model="R.Comparador" class="mw120 margin-right" aria-label="s" ng-change="markChanged(R)">
			<md-option value="in">Es</md-option>
			<md-option value="not_in">No Es</md-option>
		</md-select>
	</md-input-container>
	<md-select ng-model="R.Valor" class="md-select-inline no-margin" flex aria-label="s" ng-change="markChanged(R)" multiple="true">
		<md-option ng-repeat="Op in R.campo.Config.opciones" ng-value="Op.value">{{ Op.desc || Op.value }}</md-option>
	</md-select>
</div>