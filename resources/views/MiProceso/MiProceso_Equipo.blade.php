<div flex layout=column class="padding">
	
	<div ng-repeat="Perfil in ProcesoSel.equipo" layout>

		<div class="text-clear w100 text-14px text-bold" style="padding-top: 10px;">{{ Perfil[0].perfil.Perfil_Show }}</div>

		<div flex layout=column>
			
			<div layout ng-repeat="TM in Perfil">
				<div class="s50 border-rounded border" style="background-color: #eaeaea; background-image: url({{ 'https://sec.comfamiliar.com/images/fotosEmpleados/' + TM.usuario.Cedula + '.jpg' }}); background-size: cover; background-position: top center;"></div>
				<div layout=column class="padding-left" layout-align="center start">
					<div class="text-18px text-bold">{{ TM.usuario.Nombres }}</div>
					<div class="text-16px text-clear">{{ TM.usuario.Email }}</div>
				</div>
				
			</div>


		</div>

	</div>

	<div class="h50"></div>

</div>

