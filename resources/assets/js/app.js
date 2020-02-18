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

	'MainCtrl',
	'LoginCtrl',

	'InicioCtrl',

	'BDDCtrl',

	'EntidadesCtrl',
		'Entidades_Campos_ListaConfigCtrl',
		'Entidades_AddColumnsCtrl',
		'Entidades_VerCamposCtrl',
		'Entidades_GridsCtrl',
		'Entidades_GridDiagCtrl',
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
		'Indicadores_IndicadorDiagCtrl',

	'ScorecardsCtrl',
		'Scorecards_ScorecardDiagCtrl',

	'AppsCtrl',
		'App_ViewCtrl',

	'FuncionesCtrl',
	'ProcesosCtrl',
]);