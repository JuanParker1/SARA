<div flex id="Usuarios" layout=column ng-controller="UsuariosCtrl">

	<div layout class="bg-white md-short border-bottom">
		<md-tabs flex class="tabs-capit">
			<md-tab ng-repeat="(kS,S) in Sections" 
				md-active="State.route[3] == kS"
				ng-click="navTo('Home.Section.Subsection', { subsection: kS })">{{ S[0] }}</md-tab>
		</md-tabs>
	</div>
	
	<div flex layout=column ng-include="'Frag/Usuarios.Usuarios_' + State.route[3] "></div>

</div>