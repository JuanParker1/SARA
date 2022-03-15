<div layout=column class="bg-white overflow-y darkScroll w270"  style="padding: 5px 5px 5px 0;" 
	ng-show="editoresSubnav == 'Previsualizacion'">
	<div layout layout-align="center center">
		<div class="md-subheader" flex>Previsualización</div>
		<md-button class="md-icon-button no-margin focus-on-hover" aria-label="b" style="transform: translateY(-3px);"
			ng-click="editoresSubnav = false">
			<md-icon md-svg-icon="md-close"></md-icon>
			<md-tooltip md-direction=left>Cerrar</md-tooltip>
		</md-button>
	</div>
	<md-card class="no-margin border border-radius">
		<div class="padding text-bold text-clear" layout md-truncate>{{ EditorSel.Titulo }}</div>
		<div layout=column class="">
			
			<div layout class="padding-0-10" layout-wrap>
				<div ng-repeat="C in EditoresCamposCRUD.rows | filter:{seccion_id:null, Visible:true}" flex=100 flex-gt-xs="{{C.Ancho}}" layout class=""
					ng-class="{'opacity-70': !C.Editable}">

					<md-input-container class="margin-bottom" flex>
						<label>{{ C.Etiqueta || CamposCRUD.one(C.campo_id).campo_title }}</label><input type="text" value="&nbsp;" class="bg-lightgrey-5 border">
					</md-input-container>

				</div>
			</div>

			<div ng-repeat="(kS,S) in EditorSel.Secciones" layout=column>
				
				<div layout class="md-subheader padding-5 Pointer" ng-click="S.open = !S.open" >
					<md-button 
						class="md-icon-button no-margin focus-on-hover s20 transition" 
						style="transform: translateY(-4.5px);">
						<md-icon md-font-icon="fa-chevron-right" ng-class="{ 'fa-rotate-90': S.open }"></md-icon>
					</md-button>
					{{ S.nombre }}
				</div>
				<div layout class="padding-0-10" layout-wrap ng-show="S.open">
					<div ng-repeat="C in EditoresCamposCRUD.rows | filter:{seccion_id:kS, Visible:true}" ng-style="{ width: C.Ancho + '%' }" layout class=""
						ng-class="{'opacity-70': !C.Editable}">
						<md-input-container class="margin-bottom" flex>
							<label>{{ C.Etiqueta || CamposCRUD.one(C.campo_id).campo_title }}</label><input type="text" value="&nbsp;" class="bg-lightgrey-5 border">
						</md-input-container>

					</div>
				</div>
			</div>

		</div>
		<div class="padding" layout>
			<span flex></span>
			<md-button class="no-margin md-button md-raised md-primary" aria-label="b" disabled=true>Guardar</md-button>
		</div>
	</md-card>


</div>