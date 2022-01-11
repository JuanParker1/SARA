angular.module('SARA', [
	'ui.router',

	'ngStorage',
	'ngMaterial',
	'ngSanitize',

	'md.data.table',
	'fixed.table.header',
	'ngFileUpload',
	'angular-loading-bar',
	'angularResizable',
	'nvd3',
	'ui.utils.masks',
	'as.sortable',
	'ngCsv',
	'angular-img-cropper',
	'ui.ace',

	'scorecardNodo',

	'appRoutes',
	'appConfig',
	'appFunctions',
	'CRUD',
	'CRUDDialogCtrl',

	
	'Filters',
	'enterStroke',
	'printThis',
	'ngRightClick',
	'fileread',
	'hoverClass',
	'horizontalScroll',
	'focusOn',
	'extSubmit',

	'BasicDialogCtrl',
	'ConfirmCtrl',
	'ConfirmDeleteCtrl',
	'ListSelectorCtrl',
	'FileDialogCtrl',
	'ImageEditor_DialogCtrl',
	'IconSelectDiagCtrl',
	'ExternalLinkCtrl',
	'TableDialogCtrl',
	'RetroalimentarDiagCtrl',

	'MainCtrl',
	'LoginCtrl',

	'InicioCtrl',

	'BDDCtrl',
		'BDD_ListasDiagCtrl',

	'EntidadesCtrl',
		'Entidades_Campos_ListaConfigCtrl',
		'Entidades_Campos_ImagenConfigCtrl',
		'Entidades_AddColumnsCtrl',
		'Entidades_VerCamposCtrl',
		'Entidades_GridsCtrl',
		'Entidades_GridDiagCtrl',
			'Entidades_GridDiag_PreviewDiagCtrl',
		'Entidades_Grids_TestCtrl',

		'Entidades_EditoresCtrl',
		'Entidades_EditorDiagCtrl',
		'Entidades_EditorConfigDiagCtrl',

		'Entidades_CargadoresCtrl',
		'Entidades_CargadorDiagCtrl',
		
	'VariablesCtrl',
		'VariablesGetDataDiagCtrl',
		'Variables_VariableDiagCtrl',

	'IndicadoresCtrl',
		'Indicadores_AddDiagCtrl',
		'Indicadores_IndicadorDiagCtrl',
		'Indicadores_CommentsManagerCtrl',
		//'Indicadores_IndicadorDiag_ValorMenuCtrl',

	'ScorecardsCtrl',
		'Scorecards_NodoSelectorCtrl',
		'Scorecards_ScorecardDiagCtrl',

	'AppsCtrl',
		'App_ViewCtrl',

	'FuncionesCtrl',

	'UsuariosCtrl',

	'ProcesosCtrl',
		'Procesos_MapaNodosDiagCtrl',

	'IngresarDatosCtrl',
	'MisIndicadoresCtrl',
	'MiProcesoCtrl',

	'ConsultasSQLCtrl',

	'IntegracionesCtrl',
		'Integraciones_SOMACtrl',
		'Integraciones_SolgeinCtrl',
		'Integraciones_RUAFCtrl',
		'Integraciones_EnterpriseCtrl',
		'Integraciones_IkonoCtrl',

	'BotsCtrl',
		'Bot_LogsCtrl',

	'ConfiguracionCtrl'
]);