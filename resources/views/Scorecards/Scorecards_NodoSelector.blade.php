<md-dialog class="mh100p h100p" flex=50 layout=column>
	
	<div layout>
		<div class="md-title padding-5" flex>Seleccione Nodo</div>
		<md-button class="md-icon-button s35 no-padding" aria-label="Button" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close" class=""></md-icon>
		</md-button>
	</div>

	<div flex layout=column class="overflow-y">
		
		<div ng-repeat="F in NodosFS" class="mh25 borders-bottom padding-0-5 relative text-13px"
			md-ink-ripple layout ng-show="F.show && F.type == 'folder'">
			<div ng-style="{ width: (F.depth * 12) }"></div>
			<div flex layout class="" ng-class="{ 'text-bold': ( F.file.Ruta == NodoSel.Ruta ) }">
				<md-icon md-font-icon="fa-chevron-right  fa-fw transition Pointer" ng-class="{'fa-rotate-90':F.open}" ng-click="FsOpenFolder(NodosFS, F)"></md-icon>
				<div flex style="padding: 5px 0" class="Pointer" ng-click="selectNodo(F.file)">{{ F.name }}</div>
			</div>
			<!--<div ng-show="F.type == 'file'" flex layout>
				<div flex style="padding: 5px 0 5px 12px">{{ F.file.Nodo }}</div>
				<div style="padding: 5px" class="text-clear text-right">{{ F.file.peso }}</div>
			</div>-->
		</div>

		<div class="h50">&nbsp;</div>

	</div>

	<div layout ng-show="NodoSel" class="border-top padding-left" layout-align="center center">
		<div flex>{{ NodoSel.Ruta }}</div>
		<md-button ng-click="submitNodo()" class="md-raised md-primary">Seleccionar</md-button>
	</div>

</md-dialog>