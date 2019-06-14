<div id="Login" flex layout layout-align="center center" class="padding-20" >

	<md-card class="md-whiteframe-4dp margin margin-bottom-20 mw380" layout="column">
		<md-card-content class="no-padding" layout="column">
		
			<div layout="column" class="padding-20">
				<div>
					<span class="md-headline">Bienvenido</span>
				</div>
				<form id="Login_Form" name="Login_Form" ng-submit="Login()" layout="column">

					<md-input-container class="md-icon-float margin-top-20 margin-bottom-30" md-no-float>
						<md-icon md-font-icon="fa-user" class="fa-lg"></md-icon>
						<input placeholder="Correo" ng-model="User" required>
					</md-input-container>

					<span class="w30"></span>

					<md-input-container class="md-icon-float no-margin-top margin-bottom-30" md-no-float>
						<md-icon md-font-icon="fa-lock" class="fa-lg"></md-icon>
						<input placeholder="Contraseña" type="password" ng-model="Pass" required>
					</md-input-container>

					<div layout="column" class="">
						<md-button class="md-raised md-primary h40 no-margin" type='submit'>Ingresar</md-button>
					</div>

				</form>
			</div>
		</md-card-content>
	</md-card>

</div>