<md-dialog layout=column class="mw400">
	
	<div layout class="h30 lh30 padding-left">
		<div flex class="">Opciones para: <b>{{ C.Alias || C.Columna }}</b></div>
		<md-button class="md-icon-button s30 no-padding only-dialog no-margin" aria-label="Button" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close" class=""></md-icon>
		</md-button>
	</div>

	<div layout=column flex class="overflow-y darkScroll">
		
		<md-table-container class="">
			<table md-table class="md-table-short table-col-compress">
				<tbody md-body as-sortable="dragListener" ng-model="C.Config.opciones">
					<tr md-row ng-repeat="(kOp,Op) in C.Config.opciones" as-sortable-item>
						<td md-cell class="md-cell-compress">
							<md-button class="md-icon-button s30 no-margin no-padding drag-handle" aria-label="b" as-sortable-item-handle>
								<md-icon md-svg-icon="md-drag-handle"></md-icon>
							</md-button>
						</td>
						<td md-cell class="">
							<md-input-container md-no-float class="no-margin w100p no-padding md-no-underline">
								<input type="text" ng-model="Op.value" placeholder="Opción" class="no-margin no-padding">
							</md-input-container>
						</td>
						<td md-cell class="">
							<md-input-container md-no-float class="no-margin w100p no-padding md-no-underline">
								<input type="text" ng-model="Op.desc" placeholder="Descripción (Opcional)" class="no-margin no-padding">
							</md-input-container>
						</td>
						<td md-cell class="md-cell-compress">
							<input type="color" ng-model="Op.color">
							<md-button class="md-icon-button no-padding s30 border md-whiteframe-2dp" ng-click="changeIcon(Op)">
								<md-icon md-font-icon="fa-lg {{ Op.icono }}"></md-icon>
								<md-tooltip md-direction="left">Icono</md-tooltip>
							</md-button>
							<md-button class="md-icon-button no-padding s30">
								<md-icon md-font-icon="fa-times"></md-icon>
							</md-button>
						</td>
					</tr>
				</tbody>
				<tbody md-body>
					<tr md-row>
						<td md-cell class="md-cell-compress"></td>
						<td md-cell class="" colspan="2">
							<md-input-container md-no-float class="no-margin w100p no-padding">
								<input type="text" ng-model="newOpt" placeholder="Nueva Opción" class="no-margin" enter-stroke="addElemento(newOpt)">
							</md-input-container>
						</td>
						<td md-cell class="md-cell-compress"></td>
					</tr>
				</tbody>
			</table>
		</md-table-container>

		<div class="h20"></div>
	</div>

	<div layout class="">
		<span flex></span>
		<md-button class="md-raised md-primary margin-5" ng-click="guardarConfig()">Guardar</md-button>
	</div>

</md-dialog>