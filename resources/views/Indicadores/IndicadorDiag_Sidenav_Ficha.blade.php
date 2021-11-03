<div layout=column ng-show="sidenavSel == 'Ficha Técnica'" class="">
	<div class="padding-5-10" layout>
		<h3 class="no-margin md-subhead" flex>Ficha Técnica</h3>
	</div>

	<div layout=column class="padding-0-10">

		<md-input-container class="md-no-underline">
			<label>Nombre</label>
			<input ng-model="Ind.Indicador" readonly>
		</md-input-container>

		<md-input-container class="md-no-underline" ng-show="Ind.Definicion">
			<label>Definición</label>
			<textarea ng-model="Ind.Definicion" readonly placeholder=""></textarea>
		</md-input-container>

		<div layout>
			<md-input-container class="md-no-underline" flex>
				<label>{{ Ind.proceso.Tipo }}</label>
				<input ng-model="Ind.proceso.Proceso" readonly>
			</md-input-container>

			<md-input-container class="md-no-underline w120">
				<label style="white-space: initial;">Frecuencia de Análisis</label>
				<input readonly value="{{ Frecuencias[Ind.FrecuenciaAnalisis] }}">
			</md-input-container>
		</div>

		

		<div layout>
			<md-input-container class="md-no-underline" flex ng-show="Ind.config.meta_tipo == 'fija'">
				<label>Meta</label>
				<input ng-model="Ind.valores[(Anio*100) + 12].meta_val" readonly>
			</md-input-container>


			<div flex layout=column ng-show="Ind.config.meta_tipo == 'variable'">
				<div class="text-clear md-caption">Meta Variable</div>
				<div layout=column class="bg-black-5 padding-5 border-radius">
					<div>{{ Ind.meta_variable.Variable }}</div>
					<div class="text-clear text-14px">{{ Ind.meta_variable.proceso.Proceso }}</div>
				</div>
			</div>

			<md-input-container class="md-no-underline md-float-icon w100">
				<md-icon class="margin-0-10 Pointer" md-font-icon="{{Sentidos[Ind.Sentido].icon}} fa-fw" style="transform: translateY(5px);"></md-icon>
				<input ng-model="Sentidos[Ind.Sentido].desc" readonly aria-label=a>
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
							<div class="text-bold margin-right" ng-repeat="A in agregators | filter:{ id:Comp.variable.Agrupador }:true">{{ A.Nombre }}:</div>
							<div>{{ Comp.variable.column.campo_title }}</div>
						</div>

						<div class="text-bold" ng-show="Comp.variable.Filtros.length > 0">Condiciones:</div>

						<md-table-container class="hasScroll">
							<table md-table class="md-table-short table-col-compress">
								<tbody md-body>
									<tr md-row ng-repeat="F in Comp.variable.Filtros">
										<td md-cell class="md-cell-compress">{{ F.column_title }}</td>
										<td md-cell class="md-cell-compress">{{ comparators[F.Comparador] }}</td>
										<td md-cell class="md-cell-compress">{{ F.Valor }}</td>
									</tr>
								</tbody>
							</table>
						</md-table-container>

					</div>

				</div>
			</div>


		</div>

		<div class="h50"></div>

	</div>

</div>