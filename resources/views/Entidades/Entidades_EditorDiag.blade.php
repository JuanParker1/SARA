<md-dialog id="EditorDiag" class="w100p" aria-label=m ng-style="{ 'max-width': Editor.Ancho }" style="max-height: 100%" layout=column >
	
	<div ng-show="loading" class="padding-20" layout layout-align="center center">
		<md-progress-circular md-diameter="48"></md-progress-circular>
	</div>

	<div flex layout=column ng-show="!loading">
		<div layout class="">
			<div layout flex class="padding-left lh30" md-truncate>{{ Editor.Titulo }}</div>
			<div>{{ Obj.id }}</div>
			<md-button class="md-icon-button s30 no-padding only-dialog" aria-label="Button" ng-click="Cancel()">
				<md-icon md-svg-icon="md-close" class=""></md-icon>
				<md-tooltip md-delay="500" md-direction="left">Cancelar</md-tooltip>
			</md-button>
		</div>


		<form ext-submit id="EditorForm" name="EditorForm" flex layout=row layout-wrap class="overflow-y darkScroll" ng-submit="enviarDatos($event)">
			
			<div ng-repeat="C in Editor.campos | filter:{seccion_id:null, Visible:true}" 	 layout=column flex=100 
				flex-gt-xs="{{C.Ancho}}" class="EditorCampo" ng-if="C.Visible">
				@include('Entidades.Entidades_EditorCampo')
			</div>

			<div ng-repeat="(kS,S) in Editor.Secciones" flex=100 layout=column>
				
				<div layout layout-align="center center" class="Pointer focus-on-hover" 
					ng-click="S.open = !S.open" ng-class="{ 'opacity-90': S.open }">
					<md-button class="md-icon-button no-margin no-padding s30">
						<md-icon md-font-icon="fa-fw fa-chevron-right transition" ng-class="{ 'fa-rotate-90': S.open }"></md-icon>
					</md-button>
					<div class="md-subheader lh30" flex>{{ S.nombre }}</div>
				</div>
				
				<div layout layout-wrap style="margin-bottom: 30px;" ng-show="S.open">
					<div ng-repeat="C in Editor.campos | filter:{seccion_id:kS, Visible:true}" 	 layout=column flex=100 
						flex-gt-xs="{{C.Ancho}}" class="EditorCampo" ng-if="C.Visible">
						@include('Entidades.Entidades_EditorCampo')
					</div>
				</div>

			</div>

			<datalist id="listaHoras">
				<option ng-repeat="H in ['07','08','09','10','11','12','13','14','15','16','17','18']" value="{{ H }}:00">
			</datalist>

			<div flex=100 class="editor_error_msg" ng-show="errorMsg !== ''">{{ errorMsg }}</div>

		</form>

		<div layout>

			<div flex>
				
			</div>
			<md-button class="md-raised margin-5 text-16px" 
				ng-style="{ backgroundColor: Config.color, color: Config.textcolor }" 
				type=submit form=EditorForm >{{ (Config.modo == 'Crear') ? 'Crear' : 'Guardar' }}</md-button>
		</div>
	</div>

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

		#EditorForm .md-datepicker-input{
			min-width: 0;
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

		#EditorForm .md-select-value{ 
			min-width: 0;
			width: 100%;
		}

		#EditorForm .md-select-icon{
			width: 12px;
			margin: 0;
			transform: none;
		}

		.custom-label{
			padding-left: 3px;
			transform-origin: left top;
			color: rgba(0,0,0,0.54);
			position: absolute;
			bottom: 100%;
			left: 0;
			right: auto;
			transform: translate3d(0, 6px, 0) scale(0.75);
		}

		.autocomplete-custom li {
		  height: auto;
		  padding: 8px 8px 5px !important;
		  white-space: normal;
		}

		.entidad_pill{
			background-color: #eaeaea;
			border-radius: 5px;
			padding: 8px;
			border: 1px solid #e1e1e1;
			margin-top: 1px;
		}

		.entidad_chip > *{
			line-height: 1.3;
		}

		.entidad_chip .entidad_title{
			font-size: 0.9em;
		}

		.entidad_chip .entidad_id {
			opacity: 0.6;
		}

		.entidad_chip .entidad_metadata{
			font-size: 0.7em;
			opacity: 0.6;
		}

		.editor_error_msg{
			padding: 10px;
			background: #fddfc3;
			margin: 5px;
			border-radius: 5px;
			border: 1px solid #fdd1a8;
		}
	</style>

</md-dialog>