<div flex layout ng-controller="Entidades_EditoresCtrl">
	
	<div layout=column class="border-right w200 bg-white">

		<div layout class="border-bottom" layout-align="center center" style="height: 41px">
			<div class="md-toolbar-searchbar" flex layout>
				<md-icon md-font-icon="fa-search" class="fa-fw" style="margin: 8px 4px 0 8px;"></md-icon>
				<input flex type="search" placeholder="Editores" ng-model="filterEditores" class="no-padding">
			</div>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="addEditor()">
				<md-icon md-font-icon="fa-plus"></md-icon>
				<md-tooltip md-direction=right>Agregar Editor</md-tooltip>
			</md-button>
		</div>

		<div class="h30 lh30 padding-left border-bottom relative Pointer" md-ink-ripple layout
			ng-repeat="G in EditoresCRUD.rows | filter:filterEditores" ng-click="openEditor(G)"
			ng-class="{'bg-lightgrey-5': G.id == EditorSel.id}" md-truncate>
			<md-icon hide md-font-icon="fa-table"></md-icon>
			<div flex class="text-12px">{{ G.Titulo }}</div>
		</div>

	</div>


	<div layout=column class="border-right" flex ng-show="EditorSel">
		
		<div flex layout=column class="padding overflow-y darkScroll border-radius">

			<div layout class="margin-bottom margin-top">
				<md-input-container class="no-margin" flex >
					<input type="text" ng-model="EditorSel.Titulo" placeholder="Titulo">
				</md-input-container>
				<md-input-container class="no-margin w60" >
					<input type="number" ng-model="EditorSel.Ancho" placeholder="Ancho">
				</md-input-container>
			</div>
			
			<div class="h30"></div>
		</div>


		<div layout class="border-top seam-top">
			<span flex></span>
			<md-button class="md-primary md-raised" ng-click="updateEditor()">
				<md-icon md-svg-icon="md-save" class="margin-right"></md-icon>Guardar
			</md-button>
		</div>


	</div>

	<div layout=column class="padding-5" ng-show="EditorSel">
		
		<md-card class="no-margin mh300" ng-style="{ width: EditorSel.Ancho }">
			<div class="md-subhead padding-5 text-bold text-clear" layout>Prueba</div>
		</md-card>


	</div>

</div>