<div id="Configuracion" flex layout ng-controller="ConfiguracionCtrl" md-theme="Black">

	<md-card class="w100p mxw450 padding bg-black-2" style="margin: 10px auto 0;" layout=column>
		<div class="md-title md-thin">Configuración</div>

		<div flex layout=column class="overflow-y">
			<div class="md-subheader margin-top">Variables</div>

			<p class="text-15px text-justify">Ventana en que los usuarios finales pueden ingresar valores, contados en dias a partir del cierre (el cierre se realiza el ultimo dia del periodo en cuestión), este valor puede ser ajustado al nivel individual.</p>

			<div layout>
				<md-input-container flex>
					<label>Días Desde</label>
					<input type="number" ng-model="Configuracion.VARIABLES_DIAS_DESDE.Valor"
						ng-change="markChanged('VARIABLES_DIAS_DESDE')">
				</md-input-container>

				<md-input-container flex>
					<label>Días Hasta</label>
					<input type="number" ng-model="Configuracion.VARIABLES_DIAS_HASTA.Valor"
						ng-change="markChanged('VARIABLES_DIAS_HASTA')">
				</md-input-container>
			</div>

			<div layout>
				<md-input-container flex>
					<label>Frecuencias Habilitadas&nbsp;<md-icon md-font-icon="fa-info-circle"></md-icon>
						<md-tooltip>Solo las variables con las siguientes frecuencias pueden ser ingresadas al sistema.</md-tooltip>
					</label>

					<md-select ng-model="Configuracion.VARIABLES_FRECUENCIAS_HAB.Valor" 
						class="" multiple aria-label=s ng-change="markChanged('VARIABLES_FRECUENCIAS_HAB')">
						<md-option ng-value="k" ng-repeat="(k, Op) in Frecuencias">{{ Op }}</md-option>
					</md-select>
				</md-input-container>
			</div>
			
		</div>

		<div layout>
			<div flex></div>
			<md-button class="md-raised bg-ocean no-margin" ng-click="saveConf()">Guardar</md-button>
		</div>


	</md-card>

</div>