<div layout class="margin-top">
	<div flex class="text-bold text-clear text-13px lh20 h20">{{ ::F.filter_header }}</div>
	<md-button class="md-icon-button no-margin no-padding s20 focus-on-hover" aria-label="b" 
		ng-show="F.val !== F.default" ng-click="F.val = F.default">
		<md-icon md-font-icon="fa-undo s20"></md-icon>
		<md-tooltip md-delay="400" md-direction="right">Resetear filtro</md-tooltip>
	</md-button>
</div>
			

<div ng-if="F.campo.Tipo == 'Fecha'" layout>
	<div class="lh30 text-clear text-12px margin-right-20">{{ ::F.filter_comparator }}</div>
	<md-input-container class="no-margin no-padding">
		<md-datepicker ng-model="F.val" md-hide-icons="calendar" aria-label="f" class="compact"></md-datepicker>
	</md-input-container>
</div>

<div ng-if="F.Comparador == 'lista'" layout>
	<md-input-container flex class="no-margin no-padding" md-no-float>
		<md-select ng-model="F.val" class="text-12px w100p block md-select-nowrap" multiple placeholder="Seleccionar" md-selected-text="getSelectedText(F.val)">
			<md-select-header>
				<input ng-model="F.searchTerm" type="search" placeholder="Buscar..." class="md-text" ng-keydown="$event.stopPropagation()">
			</md-select-header>
			<md-option ng-value="Op" ng-repeat="Op in F.options | filter:F.searchTerm " class="h30">{{ ::Op }}</md-option>
		</md-select>
	</md-input-container>
</div>

<div ng-if="F.Comparador == 'query'" layout>
	<md-input-container flex class="no-margin no-padding text-12px" md-no-float>
		<input ng-model="F.val" placeholder="Buscar" autocomplete="false" name="a"></input>
	</md-input-container>
</div>

<div ng-if="F.Comparador == 'radios'" layout>
	<md-radio-group flex ng-model="F.val" class="block margin-top" layout=column>
      <md-radio-button class="md-primary margin-bottom text-12px" ng-value="Op" ng-repeat="Op in F.options" aria-label="s">
      	{{ ::Op }}
      </md-radio-button>
    </md-radio-group>
</div>