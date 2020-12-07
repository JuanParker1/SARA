<div layout=column ng-show="sidenavSel == 'Mejoramiento'">
	<div class="padding-5-10" layout>
		<h3 class="no-margin md-subhead" flex>Mejoramiento</h3>
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
			<div layout class="margin-bottom-5">
				<b flex>{{ C.autor.Nombres }}</b>
				<div class="comment_pill" style="height: 18px">{{ C.Op1 }}</div>
			</div>
			<p class="no-margin">

				{{ C.Comentario }}
			</p>
			<md-button class="md-raised margin-5-0 md-warn bg-warmblue" ng-if="C.Grupo == 'Accion'" ng-click="seeExternal(C.Op4)">
				<md-icon md-font-icon="fa-external-link-alt fa-fw margin-right"></md-icon>Ver Acción
			</md-button>

			<div layout class="margin-top-5 comment-details">
				<span flex></span>
				<span class="text-clear text-12px Pointer"><md-tooltip md-direction=left>{{ C.created_at }}</md-tooltip>{{ C.hace }}</span>
			</div>
		</div>
		<div class="h20"></div>
	</div>
</div>