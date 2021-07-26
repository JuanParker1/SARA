<div flex ng-if="inArray(R.campo.Tipo, ['Texto','TextoLargo','Booleano','Entidad','Lista'])" layout layout-align="start center">

	<md-input-container class="no-padding no-margin">
		<md-select ng-model="R.Comparador" class="mw120 md-no-underline" aria-label="s" ng-change="prepComparador(R)">
			<md-option value="=">Es</md-option>
			<md-option value="!=">No Es</md-option>
			<md-option value="like">Contiene</md-option>
			<md-option value="like_">Empieza con</md-option>
			<md-option value="_like">Termina con</md-option>
			<md-option value="notlike">No Contiene</md-option>
			<md-option value="notlike_">No Empieza con</md-option>
			<md-option value="_notlike">No Termina con</md-option>
			<md-option value="in">Incluye</md-option>
			<md-option value="not_in">No Incluye</md-option>
			<md-option value="nulo">Es nulo</md-option>
			<md-option value="no_nulo">No es nulo</md-option>
		</md-select>
	</md-input-container>

	<md-input-container class="no-padding no-margin" md-no-float ng-if="!inArray(R.Comparador, ['in','not_in','nulo','no_nulo'])">
		<input type="text" ng-model="R.Valor" aria-label="v" placeholder="Valor" ng-change="markChanged(R)">
	</md-input-container>

	<div ng-if="inArray(R.Comparador, ['in','not_in'])" flex layout layout-wrap style="padding-bottom: 0px;">
		
		<div ng-repeat="(kV, V) in R.Valor" class="bg-lightgrey-5 text-12px border border-rounded padding-left" 
			layout style="min-height: 24px; line-height: 23px; margin: 3px 3px 0 0; max-width: 100%;">
			{{ V }}
			<md-icon md-svg-icon="md-close" class="s20 focus-on-hover Pointer" style="margin: 1px 3px 0 2px;" 
				ng-click="removeFiltroOption(R, kV)"></md-icon>
		</div>

		<md-input-container class="h25 lh25 w100 no-margin no-padding" md-no-float>
		 	<input type="text" ng-model="R.newValor" placeholder="Agregar" class="no-padding" enter-stroke="pushFiltroOption(R)">
		</md-input-container>

		<md-button class="md-icon-button bg-lightgrey-5 border s25 no-padding no-margin-left" 
			style="margin-top: 2px; max-width: 100%;"
			ng-click="addFiltroOption(R)">
			<md-icon md-svg-icon="md-search" class="s15"></md-icon>
			<md-tooltip md-direction=right>Buscar Valores</md-tooltip>
		</md-button>
	</div>


</div>

<div flex ng-if="inArray(R.campo.Tipo, ['Entero','Decimal'])">

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
