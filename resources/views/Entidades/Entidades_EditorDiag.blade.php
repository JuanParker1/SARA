<md-dialog id="EditorDiag" class="w100p" aria-label=m ng-style="{ 'max-width': Editor.Ancho }" style="max-height: 95%">
	
	<div ng-show="!Editor" class="padding-20" layout layout-align="center center">
		<md-progress-circular md-diameter="48"></md-progress-circular>
	</div>

	<div layout=column ng-show="Editor">
		<div layout class="margin-bottom-5">
			<div layout flex class="padding-left lh30" md-truncate>{{ Editor.Titulo }}</div>
			
			<md-button class="md-icon-button s30 no-padding only-dialog" aria-label="Button" ng-click="Cancel()">
				<md-icon md-svg-icon="md-close" class=""></md-icon>
				<md-tooltip md-delay="500" md-direction="left">Cancelar</md-tooltip>
			</md-button>
		</div>


		<form id="EditorForm" flex layout=column layout-gt-xs=row layout-wrap class="padding-0-10 overflow-y darkScroll" ng-submit="enviarDatos()">
			
			<div ng-repeat="C in Editor.campos" layout=column flex-gt-xs={{C.Ancho}} class="">@include('Entidades.Entidades_EditorCampo')</div>
			<div class="h20"></div>

		</form>

		<div layout>
			<span flex></span>
			<md-button class="md-raised" ng-style="{ backgroundColor: Config.color, color: Config.textcolor }" 
				type="submit" form="EditorForm">{{ Config.modo }}</md-button>
		</div>
	</div>

	<style type="text/css">
		
		#EditorDiag{
			max-width: 0px
		}

		.autocomplete-custom li {
		  height: auto;
		  padding: 8px 8px 5px !important;
		  white-space: normal;
		}

		.entidad_chip > *{
			line-height: 1.3;
		}

		.entidad_chip .entidad_title{
			font-size: 0.85em;
		}

		.entidad_chip .entidad_id {
			opacity: 0.6;
		}

		.entidad_chip .entidad_metadata{
			font-size: 0.7em;
			opacity: 0.6;
		}


	</style>

</md-dialog>