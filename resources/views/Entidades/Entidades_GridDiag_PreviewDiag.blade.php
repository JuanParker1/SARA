<md-dialog class="" style="max-height: 100%; max-width: 100%;">
	
	<img ng-if="C.campo.Tipo == 'Imagen'" ng-src="{{ val.url }}">

	<div ng-if="C.campo.Tipo == 'TextoLargo'" class="mxw500 padding-20 text-justify" style="white-space: break-spaces;">{{ val }}</div>

	<md-button ng-click="Cancel()" class="md-icon-button no-margin no-padding s30 focus-on-hover" 
		style="position: absolute;right: 0;top: 0;">
		<md-icon md-svg-icon="md-close"></md-icon>
	</md-button>

</md-dialog>