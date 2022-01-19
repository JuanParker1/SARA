<div layout=column ng-show="sidenavSel == 'Análisis y Mejoramiento'">
	<div class="padding-5-10" layout>
		<h3 class="no-margin md-subhead" flex>Análisis y Mejoramiento</h3>
		<md-menu md-position-mode="target-right target">
			<md-button ng-click="$mdMenu.open($event)" class="md-icon-button s25 no-margin no-padding" aria-label="s">
				<md-icon md-svg-icon="md-plus"></md-icon>
			</md-button>
		 <md-menu-content>
		   <md-menu-item><md-button ng-click="addComment()">
		   		<md-icon md-font-icon="fa-comment fa-lg fa-fw margin-right"></md-icon>Agregar Comentario
		   </md-button></md-menu-item>
		   <md-menu-item><md-button ng-click="addAccion()">
		   		<md-icon md-font-icon="fa-clipboard-list fa-lg fa-fw margin-right"></md-icon>Agregar Acción
		   </md-button></md-menu-item>
		 </md-menu-content>
		</md-menu>
	</div>
		
	<div class="padding-5 overflow-y hasScroll" flex>
		<div ng-repeat="C in ComentariosCRUD.rows" md-whiteframe=2 class="comment" layout=column>
			@include('Indicadores.IndicadorDiag_Comment')
		</div>
		<div class="h20"></div>
	</div>
</div>