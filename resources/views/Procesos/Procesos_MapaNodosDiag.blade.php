<md-dialog class="vh95" flex=40 aria-label=d layout=column>
	
	<div layout layout-align="center center" class="padding-left">
		<div class="text-bold text-clear">Seleccionar Nodo</div>
		<span flex></span>
		<md-button class="md-icon-button no-margin s40 no-padding" aria-label="Button" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close" class=""></md-icon>
		</md-button>
	</div>

	<div ng-repeat="F in ProcesosFS" class="mh25 padding-0-10 relative text-13px FsItem"
		md-ink-ripple layout ng-show="F.show">
		<div ng-style="{ width: (F.depth * 12) }"></div>
		<div ng-show="F.type == 'folder'" flex layout layout-align="center center" class="Pointer h30">
			<md-icon md-font-icon="fa-chevron-right fa-lg fa-fw transition Pointer" 
				ng-class="{'fa-rotate-90':F.open }" ng-click="FsOpenFolder(ProcesosFS, F)"></md-icon>
			<div flex class="Pointer text-16px" ng-click="openProceso(F.file)"
				ng-class="{ 'text-bold' : F.file.id == ProcesoSelId }">{{ F.name }}</div>
			<div class="text-clear">{{ F.file.Tipo }}</div>
		</div>
		<div ng-show="F.type == 'file'" flex layout class="Pointer" ng-click="openProceso(F.file)" 
			ng-class="{ 'text-bold' : F.file.id == ProcesoSelId }">
			<div flex style="padding: 5px 0 5px 24px" layout>
				<div flex class="text-16px">{{ F.file.Proceso }}</div>
				<div class="text-clear">{{ F.file.Tipo }}</div>
			</div>
		</div>
	</div>

	<div class="h40"></div>


	<style type="text/css">
		.FsItem:hover{
			transition: all 0.3s;
			background-color: #f1f1f1;
		}
	</style>

</md-dialog>