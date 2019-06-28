<div ng-if="inArray(R.campo.Tipo, ['Texto','TextoLargo','Entidad'])">

	<md-input-container class="no-padding no-margin">
		<md-select ng-model="R.Comparador" class="" aria-label="s" ng-change="markChanged(R)">
			<md-option value="lista">Lista</md-option>
			<md-option value="query">Busqueda</md-option>
			<md-option value="radios">Radios (Selección única)</md-option>
		</md-select>
	</md-input-container>

</div>

<div ng-if="inArray(R.campo.Tipo, ['Entero','Decimal'])">



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