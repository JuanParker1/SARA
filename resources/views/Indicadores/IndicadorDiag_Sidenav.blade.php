<md-sidenav md-is-locked-open="$mdMedia('gt-md') && showSidenav" md-is-open="showSidenav"  
	class="md-sidenav-right w350 text-white" layout=column>

	<md-tabs flex class="md-tabs-fullheight md-tabs-icons">
		<md-tab>
			<md-tab-label>
				<md-icon md-svg-icon="md-trending-up"></md-icon>
			</md-tab-label>

			<md-tab-body>
				<div layout=column>
					<div class="padding-5-10" layout>
						<h3 class="no-margin md-subhead" flex>Mejoramiento</h3>
						<md-menu md-position-mode="target-right target">
							<md-button ng-click="$mdMenu.open($event)" class="md-icon-button s25 no-margin no-padding" aria-label="s">
								<md-icon md-svg-icon="md-plus"></md-icon>
							</md-button>
						 <md-menu-content>
						   <md-menu-item><md-button ng-click="addComment()">
						   		<md-icon md-font-icon="fa-comment fa-lg fa-fw margin-right"></md-icon>Agregar Comentario
						   </md-button></md-menu-item>
						   <md-menu-item><md-button ng-click="addAccion()">
						   		<md-icon md-font-icon="fa-clipboard-list fa-lg fa-fw margin-right"></md-icon>Agregar Acción
						   </md-button></md-menu-item>
						 </md-menu-content>
						</md-menu>
					</div>
						
					<div class="padding-but-top overflow-y hasScroll" flex>
						<div ng-repeat="C in ComentariosCRUD.rows" md-whiteframe=2 class="comment" layout=column>
							<div layout class="margin-bottom-5">
								<b flex>{{ C.autor.Nombres }}</b>
								<div class="comment_pill">{{ C.Op1 }}</div>
							</div>
							<p class="no-margin">
								{{ C.Comentario }}
							</p>
							<md-button class="md-raised margin-5-0 md-warn bg-warmblue" ng-if="C.Op4 !== null" ng-click="seeExternal(C.Op4)">
								<md-icon md-font-icon="fa-external-link-alt fa-fw margin-right"></md-icon>Ver Acción
							</md-button>
							<div layout class="margin-top-5 comment-details">
								<span flex></span>
								<span class="text-clear text-12px">{{ C.created_at }}</span>
							</div>
						</div>
						<div class="h20"></div>
					</div>
				</div>
			</md-tab-body>

		</md-tab>
		
		<!--
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

		</md-tab>-->

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