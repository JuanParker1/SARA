<div layout=column ng-show="sidenavSel == 'Ficha Técnica'">
	<div class="padding-5-10" layout>
		<h3 class="no-margin md-subhead" flex>Ficha Técnica</h3>
	</div>

	<div layout=column class="padding-0-10">

		<md-input-container class="md-no-underline">
			<label>Nombre</label>
			<input ng-model="Ind.Indicador" readonly>
		</md-input-container>

		<md-input-container class="md-no-underline">
			<label>Definición</label>
			<textarea ng-model="Ind.Definicion" readonly placeholder=""></textarea>
		</md-input-container>

		<md-input-container class="md-no-underline">
			<label>{{ Ind.proceso.Tipo }}</label>
			<input ng-model="Ind.proceso.Proceso" readonly>
		</md-input-container>

		<div layout>
			<md-input-container class="md-no-underline" flex>
				<label>Meta</label>
				<input ng-model="Ind.valores[(Anio*100) + 12].meta_val" readonly>
			</md-input-container>
			<md-input-container class="md-no-underline md-float-icon" flex>
				<md-icon class="margin-0-10 Pointer" md-font-icon="{{Sentidos[Ind.Sentido].icon}} fa-fw" style="transform: translateY(5px);"></md-icon>
				<input ng-model="Sentidos[Ind.Sentido].desc" readonly>
			</md-input-container>
		</div>


		<md-input-container class="md-no-underline">
			<label>Fórmula</label>
			<input ng-model="Ind.Formula" readonly>
		</md-input-container>

		<div class="md-subheader">Donde:</div>

		<div ng-repeat="Comp in Ind.variables" layout=column>

			<div layout class="padding-5">
				<div class="text-20px margin-right text-bold">{{ Comp.Letra }}:</div>
				<div flex layout=column >
					<div class=""> {{ Comp.variable_name }}</div>
					<div class="text-clear">{{ Comp.Tipo }}</div>


					<div ng-show="Comp.variable.Tipo == 'Calculado de Entidad'" layout=column class="margin-top">
						<div class="md-subheader" style="color: #61ffd3 !important">Calculada Automáticamente</div>

						<div layout>
							<div class="text-bold margin-right">Fuente:</div>
							<div>{{ Comp.variable.grid.Titulo }}</div>
						</div>

						<div layout>
							<div class="text-bold margin-right">{{ Comp.variable.Agrupador }}</div>
							<div></div>
						</div>

					</div>

				</div>
			</div>

			
			


		</div>
		

	</div>

</div>