<md-dialog layout=column class="w100p mxw500" style="max-height: 100%">
	
	<div layout=column ng-show="Stage == 'Writting'">
		
		<div id="feedback_bg" style="background-image: url('img/feedback_bg.png');" class="h200" layout=column>
			<div layout layout-align="center center" class="padding-left">
				<div class="md-title"><span>Retroalimentar</span></div>
				<span flex></span>
				<md-button class="md-icon-button no-margin focus-on-hover" aria-label="Button" ng-click="Cancel()" >
					<md-tooltip md-direction=left>Salir</md-tooltip>
					<md-icon md-svg-icon="md-close"></md-icon>
				</md-button>
			</div>
		</div>

		<div class="padding-5" layout=column>
			
			<p class="no-margin text-center text-18px">Muchas gracias por enviarnos sus comentarios,<br> estos nos ayudarán a mejorar nuestro sistema.</p>

			<div class=" margin-top padding-0-10" layout>
				<md-input-container class="" flex> 
					<label>Mi Comentario</label>
					<textarea ng-model="feedbackComment" rows="3" md-autofocus=""></textarea>
				</md-input-container>
			</div>

			<div layout>
				<span flex></span>
				<md-button class="md-raised md-primary border-rounded" style="padding-right: 25px" ng-click="enviarFeedback()">
					<md-icon md-font-icon="fa-paper-plane margin-right"></md-icon>Enviar
				</md-button>
			</div>

		</div>

	</div>

	<div layout=column ng-show="Stage == 'Sending'" layout-align="center center" class="h430">
		<md-progress-circular md-diameter="48"></md-progress-circular>
	</div>

	<div layout=column ng-show="Stage == 'Sent'" layout-align="center center" class="h430 relative">
		<md-button class="md-icon-button no-margin focus-on-hover abs" style="top: 0; right: 0;" aria-label="Button" ng-click="Cancel()" >
			<md-tooltip md-direction=left>Salir</md-tooltip> 
			<md-icon md-svg-icon="md-close"></md-icon>
		</md-button>
		<div layout=column>
			<md-icon id="sent_icon" md-svg-icon="md-check" aria-label="check"></md-icon>
			<div class="text-center text-18px"><b>¡Recibido!</b>, Muchas gracias.</div>
		</div>
		
	</div>


	<style type="text/css">
		#feedback_bg{
			background-size: cover; 
			background-position: center center;
			box-shadow: inset 0 -35px 40px 0px white;
		}

		#sent_icon{
			color: #fff;
			background: #19a001;
			border-radius: 50%;
			width: 80px;
			height: 80px;
			padding: 20px;
			box-shadow: 0 14px 25px -5px #1aab00;
			margin-bottom: 30px;
		}
	</style>

</md-dialog>