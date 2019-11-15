<md-dialog id="EditorDiag" class="w100p" aria-label=m ng-style="{ 'max-width': Editor.Ancho }" style="max-height: 95%" layout=column>
	
	<div ng-show="!Editor" class="padding-20" layout layout-align="center center">
		<md-progress-circular md-diameter="48"></md-progress-circular>
	</div>

	<div flex layout=column ng-show="Editor">
		<div layout class="">
			<div layout flex class="padding-left lh30" md-truncate>{{ Editor.Titulo }}</div>
			
			<md-button class="md-icon-button s30 no-padding only-dialog" aria-label="Button" ng-click="Cancel()">
				<md-icon md-svg-icon="md-close" class=""></md-icon>
				<md-tooltip md-delay="500" md-direction="left">Cancelar</md-tooltip>
			</md-button>
		</div>


		<form id="EditorForm" flex layout=column layout-gt-xs=row layout-wrap class="overflow-y" ng-submit="enviarDatos()">
			
			<div ng-repeat="C in Editor.campos" layout=column flex-gt-xs={{C.Ancho}} class="EditorCampo" ng-show="C.Visible">
				@include('Entidades.Entidades_EditorCampo')
			</div>

		</form>

		<div layout>
			<span flex></span>
			<md-button class="md-raised margin-5 text-16px" ng-style="{ backgroundColor: Config.color, color: Config.textcolor }" 
				type="button" form="EditorForm" >{{ Config.modo }}</md-button>
		</div>
	</div>

	<pre hide>{{ Config | json }}</pre>

	<style type="text/css">
		
		#EditorDiag{
			max-width: 0px;
		}

		#EditorForm md-input-container {
    		margin: 0;
    		padding: 0;
		}

		#EditorForm .md-datepicker-input-container{
			width: 100%;
		}

		#EditorForm{
			padding: 0 5px 50px;
		}

		#EditorForm .EditorCampo{
			padding: 25px 5px 0;
		}

		#EditorForm md-input-container.md-input-focused label:not(.md-no-float), #EditorForm md-input-container.md-input-has-value label:not(.md-no-float){
			width: 130%; min-width: 130%;
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