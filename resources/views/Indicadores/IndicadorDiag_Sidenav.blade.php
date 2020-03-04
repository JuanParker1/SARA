<md-sidenav md-is-locked-open="$mdMedia('gt-md') && showSidenav" md-is-open="showSidenav"  
	class="md-sidenav-right w350 text-white" layout=column>

	<md-tabs flex class="md-tabs-fullheight md-tabs-icons" md-center-tabs>
		
		@include('Indicadores.IndicadorDiag_Sidenav_Mejora')

		@include('Indicadores.IndicadorDiag_Sidenav_Ficha')
		
		

		<md-tab>
			<md-tab-label>
				<md-icon md-font-icon="fa-dice-d20 fa-lg"></md-icon>
			</md-tab-label>

			<md-tab-body>
				<div layout=column>
					<div class="padding-5-10" layout>
						<h3 class="no-margin md-subhead" flex>Análisis I.A.</h3>
					</div>
				</div>
			</md-tab-body>

		</md-tab>

	</md-tabs>


</md-sidenav>

<style type="text/css">
	.comment{
		background: #303030;
		font-size: 15px;
		margin: 5px 0 10px;
		padding: 9px 10px;
		border-radius: 4px;
	}

	.comment.mine{
	    background: #173d46;
	}

	.comment .comment_pill{
		background: #5d5d5d;
		border-radius: 25px;
		padding: 0px 10px;
	}
</style>