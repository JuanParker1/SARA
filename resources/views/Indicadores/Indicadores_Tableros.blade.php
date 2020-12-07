<div class="bg-white border border-radius margin-left">
	<div class="h30" layout layout-align="center center">
		<div class="md-subheader margin-left margin-right">Tableros</div>
		<span flex></span>
		<md-button class="md-icon-button no-margin no-padding s30 margin-right focus-on-hover" aria-label="b" ng-click="addToTablero()">
			<md-icon md-svg-icon="md-plus"></md-icon>
			<md-tooltip md-direction="left">Agregar a Tablero</md-tooltip>
		</md-button>
	</div>

	<div layout=column>
		 
		<div ng-repeat="N in NodosCRUD.rows | filter:{ tipo: 'Indicador', elemento_id: IndSel.id }:true" class="padding-5-10 border-bottom"
			ng-class="{ 'border-top': $first }" layout layout-align="center center">
			<div flex>{{ N.Ruta }}</div>
			<md-menu>
				<md-button ng-click="$mdMenu.open($event)" class="md-icon-button no-margin focus-on-hover s30 no-padding" aria-label="m">
					<md-icon md-svg-icon="md-more-v"></md-icon>
				</md-button>
				<md-menu-content class="no-padding">
					<md-menu-item><md-button ng-click="deleteToTablero(N)" class="md-warn"><md-icon md-font-icon="fa-trash margin-right fa-fw"></md-icon>Remover del Tablero</md-button></md-menu-item>
				</md-menu-content>
			</md-menu>
		</div>
		

	</div>
	
</div>