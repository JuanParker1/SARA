angular.module('Bot_LogsCtrl', [])
.controller(   'Bot_LogsCtrl', ['$scope', '$rootScope', '$mdDialog', 'Bot',
	function ($scope, $rootScope, $mdDialog, Bot) {

		console.info('Bot_LogsCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Bot = Bot;

		Ctrl.Cancel = function(){ $mdDialog.cancel(); }

		Ctrl.filters = {
			bot_id: Bot.id,
			Inicio: moment().add(-2, 'days').toDate(),
			Fin:    moment().toDate()
		};

		Ctrl.getLogs = () => {
			var filters = angular.copy(Ctrl.filters);
			filters.Inicio = moment(filters.Inicio).format('YYYY-MM-DD');
			filters.Fin    = moment(filters.Fin).format('YYYY-MM-DD');

			Rs.http('/api/Bots/logs', filters, Ctrl, 'BotLogs');

		}	

		Ctrl.getLogs();
	}

]);