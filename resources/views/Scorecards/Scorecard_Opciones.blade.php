<div class="md-subhead text-clear margin-bottom-5">Opciones</div>

<md-input-container class="margin-bottom-5">
	<label>Abrir al Nivel</label>
	<input type="number" ng-model="ScoSel.config.open_to_level" aria-label=s ng-change="ScoSel.changed = true">
</md-input-container>

<md-input-container class="margin-bottom-5">
	<label>Método Calculo</label>
	<md-select ng-model="ScoSel.config.calc_method" ng-change="ScoSel.changed = true">
		<md-option ng-value="'peso'">Por Peso</md-option>
		<md-option ng-value="'indicadores'">Promedio de Indicadores</md-option>
	</md-select>
</md-input-container>


<md-input-container class="margin-bottom-5">
	<label class="mxw300 w300">Frecuencia de Análisis (Predeterminada)</label>
	<md-select ng-model="ScoSel.config.default_frecuencia_analisis" class="block md-select-nowrap" multiple 
		ng-change="checkFrecuenciaAnalisis()" aria-label=s>
		<md-option value="-1">Todas</md-option>
		<md-option ng-value="k" ng-repeat="(k, Op) in Frecuencias" ng-if="!inArray('-1', ScoSel.config.default_frecuencia_analisis)">{{ Op }}</md-option>
	</md-select>
</md-input-container>

<md-input-container class="margin-bottom-5">
	<label class="mxw300 w300">Ver (Predeterminada)</label>
	<md-select ng-model="ScoSel.config.default_see" class="" ng-change="ScoSel.changed = true" aria-label=s>
		<md-option ng-value="'Res'">Resultado</md-option>
		<md-option ng-value="'Cump'">Cumplimiento</md-option>
	</md-select>
</md-input-container>