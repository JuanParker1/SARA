<td md-cell class="md-cell-compress ">
	<md-input-container md-no-float class="no-padding w200">
		<input type="text" placeholder="Columna" ng-model="C.Columna" ng-required="C.id" class="h30" ng-change="markChanged(C)"
		@if($withSave) enter-stroke="addCampo()" id="newCampo" @endif >
	</md-input-container>
</td>
<td md-cell class="md-cell-compress ">
	<md-input-container md-no-float class="no-padding w150">
		<input type="text" placeholder="Alias" ng-model="C.Alias" class="h30" ng-change="markChanged(C)"
		@if($withSave) enter-stroke="addCampo()" @endif >
	</md-input-container>
</td>
<td md-cell class="md-cell-compress" ng-repeat="Op in ['Requerido','Visible','Editable','Buscable']">
	<md-checkbox ng-model="C[Op]" aria-label="{{ Op }}" style="transform: translateX(3px);" class="md-primary"
		ng-change="markChanged(C)"></md-checkbox>
</td>
<td md-cell class="md-cell-compress" style="">
	<md-input-container class="no-padding w100p">
		<md-select ng-model="C.Tipo" class="w100p" aria-label="T" ng-change="setTipoDefaults(C)">
			<md-option ng-repeat="(kT,Tipo) in TiposCampo" ng-value="kT" ng-class="{'border-bottom':Tipo.Divide}">
				<md-icon md-svg-icon="{{ Tipo.Icon }}" class="margin-right-5"></md-icon>
				{{ kT }}
			</md-option>
		</md-select>
	</md-input-container>
</td>
<td md-cell class="">

	<div ng-if="inArray(C.Tipo, ['Texto','TextoLargo'])">
		<md-input-container md-no-float class="no-padding no-margin w70">
			<input type="number" ng-model="C.Op2" class="h30" min="1" max="255" aria-label="L" placeholder="Long." ng-change="markChanged(C)">
			<md-tooltip md-direction=right>Longitud Máxima</md-tooltip>
		</md-input-container>
	</div>

	<div ng-if="inArray(C.Tipo, ['Entero','Decimal'])">
		<md-input-container md-no-float class="no-padding no-margin w70">
			<input type="number" ng-model="C.Op1" class="h30" aria-label="L" placeholder="Min" ng-change="markChanged(C)">
			<md-tooltip md-direction=right>Mínimo</md-tooltip>
		</md-input-container>
		<md-input-container md-no-float class="no-padding no-margin w70">
			<input type="number" ng-model="C.Op2" class="h30" aria-label="L" placeholder="Max" ng-change="markChanged(C)">
			<md-tooltip md-direction=right>Máximo</md-tooltip>
		</md-input-container>
		<md-input-container md-no-float class="no-padding no-margin w70" ng-if="C.Tipo == 'Decimal'">
			<input type="number" ng-model="C.Op3" class="h30" aria-label="L" placeholder="Dec" ng-change="markChanged(C)">
			<md-tooltip md-direction=right>Decimales</md-tooltip>
		</md-input-container>
	</div>

	<div ng-if="inArray(C.Tipo, ['Booleano'])">
		<md-input-container md-no-float class="no-padding no-margin w70">
			<input type="text" ng-model="C.Op4" class="h30" aria-label="L" placeholder="true" ng-change="markChanged(C)">
			<md-tooltip md-direction=right>Valor si Verdadero</md-tooltip>
		</md-input-container>
		<md-input-container md-no-float class="no-padding no-margin w70">
			<input type="text" ng-model="C.Op5" class="h30" aria-label="L" placeholder="false" ng-change="markChanged(C)">
			<md-tooltip md-direction=right>Valor si Falso</md-tooltip>
		</md-input-container>
	</div>

	<div ng-if="inArray(C.Tipo, ['Fecha','Hora','FechaHora'])">
		<md-input-container md-no-float class="no-padding no-margin w100">
			<md-select ng-model="C.Op4" aria-label="s" ng-change="markChanged(C)">
			  <md-option ng-value="Op[0]" ng-repeat="Op in TiposCampo[C.Tipo]['Formatos']">{{ Op[1] }}</md-option>
			</md-select>
		</md-input-container>
	</div>

	<div ng-if="inArray(C.Tipo, ['Entidad'])">
		<md-select ng-model="C.Op1" aria-label="s" ng-change="markChanged(C)" style="font-weight: 400;font-size: 16px;">
		  <md-option ng-value="Op.id" ng-repeat="Op in EntidadesCRUD.rows | filter:{ 'bdd_id':BddSel.id }:true"
		  	ng-if="Op.id !== EntidadSel.id">{{ Op.Nombre }}</md-option>
		</md-select>
	</div>

</td>
<td md-cell class="">

	<div ng-if="inArray(C.Tipo, ['Texto','TextoLargo'])">
		<md-input-container md-no-float class="no-padding no-margin">
			<input type="text" ng-model="C.Defecto" class="h30" aria-label="L" placeholder="Valor por Defecto" autocomplete="new-password" ng-change="markChanged(C)">
		</md-input-container>
	</div>

	
	<div ng-if="inArray(C.Tipo, ['Entero','Decimal'])">
		<md-input-container md-no-float class="no-padding no-margin">
			<input type="number" ng-model="C.Defecto" class="h30" aria-label="L" placeholder="Valor por Defecto" autocomplete="new-password"
				ng-min="C.Op1" ng-max="C.Op2" ng-change="markChanged(C)">
		</md-input-container>
	</div>

	<div ng-if="inArray(C.Tipo, ['Booleano'])">
		<md-select ng-model="C.Defecto" aria-label="s" class="w140" ng-change="markChanged(C)">
		  <md-option ng-value="Op.Valor" ng-repeat="Op in OpsBooleano">{{ Op.Mostrar }}</md-option>
		</md-select>
	</div>

</td>