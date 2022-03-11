<md-dialog flex=100 class="vh100 no-overflow" layout=column>

	<div class="h40 padding-left border-bottom" layout layout-align="center center">
		<div class="text-bold mw200">Actualizar Variables ({{ Variables.length }})</div>
		<div class="w10"></div>
		
		<div class="md-toolbar-searchbar" layout>
			<md-icon md-font-icon="fa-search" class="fa-fw" style="margin: 7px 5px 0 6px;"></md-icon>
			<input flex type="search" placeholder="Buscar Variable..." ng-model="filterVariables" class="no-padding" ng-change="searchVariable()" ng-model-options="{ debounce : 500 }">
		</div>

		<span flex></span>

		<div>
			<md-datepicker class="compact lh30 h30 md-no-underline" ng-model="PeriodoIni" md-max-date="PeriodoFin" md-mode="month" md-date-locale="periodDateLocale" aria-label="f" ng-change="calcPeriodos()"></md-datepicker>
			<md-tooltip>Periodo Inicio</md-tooltip>
		</div>
		<md-icon md-font-icon="fa-arrow-right fa-fw margin-left-5" aria-label="f"></md-icon>
		<div>
			<md-datepicker class="compact lh30 h30 md-no-underline" ng-model="PeriodoFin" md-min-date="PeriodoIni" md-mode="month" md-date-locale="periodDateLocale" aria-label="f" ng-change="calcPeriodos()"></md-datepicker>
			<md-tooltip>Periodo Finalización</md-tooltip>
		</div>
		<div class="w10"></div>
		<div layout>
			<md-button class="md-icon-button no-margin focus-on-hover" ng-click="changeAnio(-1)">
				<md-icon md-font-icon="fa-chevron-left"></md-icon>
			</md-button>
			<div class="lh40">{{ Anio }}
				<md-tooltip>Ver Año</md-tooltip>
			</div>
			<md-button class="md-icon-button no-margin focus-on-hover" ng-click="changeAnio(1)">
				<md-icon md-font-icon="fa-chevron-right"></md-icon>
			</md-button>
		</div>

		<md-button class="md-icon-button no-padding s30" aria-label="Button" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close"></md-icon>
		</md-button>
	</div>

	<md-progress-linear ng-if="loading"></md-progress-linear>

	<div flex layout>
		
		<div class="w220 bg-lightgrey-5 border-right" layout=column>
			
			<div flex layout=column class="padding overflow-y darkScroll">
				
				<md-input-container class="margin-bottom-5">
					<label>Tipo</label>
					<md-select ng-model="TipoVar" aria-label=s class="md-no-underline" ng-change="getVariables()" ng-readonly="loading">
						<md-option ng-value="Op" ng-repeat="Op in ['Manual', 'Calculado de Entidad']">{{ Op }}</md-option>
					</md-select>
				</md-input-container>

				<md-input-container class="margin-bottom-5">
					<label>Frecuencia</label>
					<md-select ng-model="Frecuencia" aria-label=s class="md-no-underline" ng-change="getVariables()" ng-readonly="loading">
						<md-option ng-value="k" ng-repeat="(k, Op) in Frecuencias">{{ Op }}</md-option>
					</md-select>
				</md-input-container>


				<md-input-container class="margin-bottom-5">
					<label>Ordenar Por</label>
					<md-select ng-model="OrderBy" aria-label=s class="md-no-underline" ng-readonly="loading" ng-change="orderVars()">
						<md-option ng-value="Op[0]" ng-repeat="Op in OrderOps">{{ Op[1] }}</md-option>
					</md-select>
				</md-input-container>

			</div>

			<div layout=column ng-show="downloadStatus == 'iddle'" class="padding-but-top">

				<div layout class="margin-bottom">
					<md-button flex aria-label="b" class="no-margin border md-raised" ng-click="startDownload()">
						<md-icon md-font-icon="fa-cloud-download-alt" class="margin-right-5"></md-icon>Obtener Datos
					</md-button>
					<md-menu>
						<md-button aria-label="b" class="md-icon-button no-margin" style="margin-right: -10px !important;" ng-click="$mdMenu.open($event)">
							<md-icon md-svg-icon="md-more-v" ></md-icon>
						</md-button>
						<md-menu-content class="no-padding no-overflow">
							<md-menu-item>
								<md-checkbox ng-model="overwriteValues" aria-label="c" class="md-primary" style="padding-left: 2px;margin-left: 13px;">Sobreescribir Valores Existentes</md-checkbox>
							</md-menu-item>
							<md-menu-item>
								<md-button ng-click="eraseData()">
									<md-icon md-font-icon="fa-eraser" class="margin-right-5"></md-icon>Borrar Datos
								</md-button>
							</md-menu-item>
						</md-menu-content>
					</md-menu>
				</div>
				
				
				<md-button aria-label="b" class="md-primary md-raised no-margin" ng-click="storeVars()">
					<md-icon md-font-icon="fa-box-open" class="margin-right-5"></md-icon>Guardar Datos
				</md-button>
			</div>

			<div layout=column ng-show="downloadStatus !== 'iddle'" >
				

				<div class="md-display-1 w100p text-center">{{(VarIndex) / Variables.length | percentage:1}}</div> 

				<md-button flex aria-label="b" class="md-raised" ng-click="pauseDownload()" ng-show="downloadStatus == 'running'">
					<md-icon md-font-icon="fa-pause" class=""></md-icon> Pausar
				</md-button>

				<md-button flex aria-label="b" class="md-raised" ng-click="resumeDownload()" ng-show="downloadStatus != 'running'">
					<md-icon md-font-icon="fa-play" class=""></md-icon> Continuar
				</md-button>

				<md-button flex aria-label="b" class="border" ng-click="stopDownload()" ng-show="downloadStatus != 'running'">
					<md-icon md-font-icon="fa-stop" class=""></md-icon> Detener
				</md-button>

			</div>

		</div>

		<div flex layout=column class=" ">
			<md-table-container flex class="overflow darkScroll margin-bottom-5" ng-show="!loading">
				<table md-table class="md-table-short table-col-compress border-bottom border-right">
					<thead md-head class="">
						<th md-column>
							<md-checkbox ng-model="allSelected" aria-label="c" ng-change="markAll(allSelected)"></md-checkbox>
						</th>
						<th md-column class="text-left h30">Variable</th>
						<th md-column ng-repeat="(kM,M) in Meses" md-numeric class=" text-right padding-right Pointer" ng-class="{ 'bg-lightgrey' : M.selected }" ng-click="selectPeriodo(M)">{{ M.MesCorto }}</th>
					</thead>
					<tbody md-body>
						<tr md-row class="" ng-repeat="V in Variables | filter:filterVariables" ng-class="{ 'text-clear': !V.selected }">
							<td md-cell class="md-cell-compress">
								<md-checkbox ng-model="V.selected" aria-label="c"></md-checkbox>
								<md-icon md-font-icon="fa-fw" ng-class="{ 'far fa-circle': (V.status == 'iddle'), 'fa-spinner fa-spin': (V.status == 'downloading'), 'fa-circle': (V.status == 'done'), 'fa-exclamation-triangle text-red': (V.status == 'error') }"></md-icon>
							</td>
							<td md-cell class="">
								<div layout=column style="padding: 3px 0;" class="mw220">
									<div>{{ V.Variable }}</div>
									<div class="text-clear">{{ V.proceso.Proceso }}</div>
								</div>
							</td>
							<td md-cell class="text-right mw50 " ng-repeat="M in Meses" ng-class="{ 'bg-lightgrey' : (M.selected && V.selected) }">
								<div layout=column class="no-wrap">
									<div>{{ V.valores[M.Periodo].val }}</div>
									<div class="text-green text-bold">{{ V.newValores[M.Periodo].val }}</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="h20"></div>
			</md-table-container>
			
		</div>

	</div>

</md-dialog>