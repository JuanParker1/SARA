<md-sidenav md-is-locked-open="$mdMedia('gt-md') && showSidenav" md-is-open="showSidenav"  
	class="md-sidenav-right w350 text-white" layout=column>

	<md-tabs flex class="md-tabs-fullheight md-tabs-icons">
		<md-tab>
			<md-tab-label>
				<md-icon md-font-icon="fa-comment fa-lg"></md-icon>
			</md-tab-label>

			<md-tab-body>
				<div layout=column>
					<div class="padding-5-10" layout>
						<h3 class="no-margin md-subhead" flex>Comentarios</h3>
						<md-button class="md-icon-button no-margin no-padding s25">
							<md-icon md-svg-icon="md-plus"></md-icon>
						</md-button>
					</div>
						
					<div class="padding-but-top overflow-y hasScroll" flex>
						<div ng-repeat="C in Comentarios" md-whiteframe=2 class="comment" layout=column>
							<div layout class="margin-bottom-5">
								<b flex>{{ C.Autor }}</b>
								<div class="comment_pill">{{ C.Periodo }}</div>
							</div>
							<p class="no-margin">
								{{ C.Comentario }}
							</p>
						</div>
						<div class="h20"></div>
					</div>
				</div>
			</md-tab-body>

		</md-tab>
		<md-tab>
			<md-tab-label>
				<md-icon md-font-icon="fa-flag fa-lg"></md-icon>
			</md-tab-label>

			<md-tab-body>
				<div layout=column>
					<div class="padding-5-10" layout>
						<h3 class="no-margin md-subhead" flex>Mejora</h3>
						<md-button class="md-icon-button no-margin no-padding s25">
							<md-icon md-svg-icon="md-plus"></md-icon>
						</md-button>
					</div>
				</div>
			</md-tab-body>

		</md-tab>


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