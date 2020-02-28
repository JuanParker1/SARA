<md-sidenav md-is-locked-open="$mdMedia('gt-md') && showSidenav" md-is-open="showSidenav"  
	class="md-sidenav-right w350 text-white" layout=column>

	<md-tabs flex class="md-tabs-fullheight md-short">
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
						<div ng-repeat="C in Comentarios" class="comment">{{ C }}</div>
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
	</md-tabs>


</md-sidenav>