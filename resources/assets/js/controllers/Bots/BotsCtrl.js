angular.module('BotsCtrl', [])
.controller('BotsCtrl', ['$scope', '$rootScope', '$injector', '$filter', '$mdDialog',
	function($scope, $rootScope, $injector, $filter, $mdDialog) {

		console.info('BotsCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Rs.mainTheme = 'Black';
		Ctrl.BotsNav = true;
		Ctrl.BotRunning = false;

		Ctrl.EstadosBots = ['Espera', 'Corriendo', 'Inactivo', 'Error'];
		Ctrl.EstadosBotsDet = {
			'Espera':    { Color: '#03ab3b' },
			'Corriendo': { Color: '#d3ff76' },
			'Inactivo':  { Color: '#545454' },
			'Error':     { Color: '#ff0000' },
		};

		Ctrl.BotsCRUD  = $injector.get('CRUD').config({ base_url: '/api/Bots' });
		Ctrl.PasosCRUD = $injector.get('CRUD').config({ base_url: '/api/Bots/pasos' });
		Ctrl.VariablesCRUD = $injector.get('CRUD').config({ base_url: '/api/Bots/variables' });

		Ctrl.getBots = () => {
			Ctrl.BotsCRUD.get().then(() => {

				var bot_sel_id = Rs.Storage.BotSelId ? Rs.getIndex(Ctrl.BotsCRUD.rows, Rs.Storage.BotSelId) : 0;
				Ctrl.openBot(Ctrl.BotsCRUD.rows[bot_sel_id]);
			});
		}
		
		Ctrl.addBot = () => {
			Ctrl.BotsCRUD.dialog({ Estado: 'Inactivo' }, {
				title: 'Crear Nuevo Bot',
				only: ['Nombre']
			}).then(nB => {
				Ctrl.BotsCRUD.add(nB);
			});
		};

		Ctrl.openBot = (B) => { 
			Ctrl.BotSel = B;
			Rs.Storage.BotSelId = Ctrl.BotSel.id;
			Ctrl.BotSel.config.Horas = Ctrl.BotSel.config.Horas.map(H => {
				var D = moment(H, ['HH:mm']).toDate();
				return D;
			});
			//
			Ctrl.PasosCRUD.setScope('bot', Ctrl.BotSel.id).get();
			Ctrl.VariablesCRUD.setScope('bot', Ctrl.BotSel.id).get();

			//Ctrl.seeLogs();
		}

		Ctrl.saveBot = async () => {
			var Bot = angular.copy(Ctrl.BotSel);
			Bot.config.Horas = Bot.config.Horas.map(H => {
				var D = moment(H).format('HH:mm');
				console.log(H, D);
				return D;
			});
			await Ctrl.BotsCRUD.update(Bot);

			var Variables = Ctrl.VariablesCRUD.rows.filter(V => V.changed);
			if(Variables.length > 0) await Ctrl.VariablesCRUD.updateMultiple(Variables);

			await Ctrl.PasosCRUD.updateMultiple(Ctrl.PasosCRUD.rows);

			Rs.showToast('Bot Actualizado', 'Success');
		}
 	
		Ctrl.DiasSemana = [
			[ 'Lun', 'Lun'],
			[ 'Mar', 'Mar'],
			[ 'Mie', 'Mié'],
			[ 'Jue', 'Jue'],
			[ 'Vie', 'Vie'],
			[ 'Sab', 'Sáb'],
			[ 'Dom', 'Dom'],
		];

		Ctrl.addHour = () => {
			var Horas = Ctrl.BotSel.config.Horas;
			var Time = (Horas.length > 0) ? moment(Horas[(Horas.length - 1)]) : moment('05:00', ['H:m']);
			Time.add(1, 'hours');
			Horas.push(Time.toDate());
		}

		Ctrl.setHour = (H, kH) => {
			Ctrl.BotSel.config.Horas[kH] = H;
		}

		Ctrl.removeHour = (kH) => {
			Ctrl.BotSel.config.Horas.splice(kH, 1);
		}

		//Pasos
		Ctrl.addPaso = () => {
			var Indice = Ctrl.PasosCRUD.rows.length;
			Ctrl.PasosCRUD.dialog({
				Tipo: 'Url', Indice: Indice, bot_id: Ctrl.BotSel.id, config: '[]'
			}, {
				title: 'Agregar Paso', class: 'wu600',
				only: ['Tipo', 'Nombre'],
				confirmText: 'Agregar'
			}).then(nP => {
				if(!nP) return;
				Ctrl.PasosCRUD.add(nP);
			});
		}

		Ctrl.delPaso = (P) => {
			Rs.confirmDelete({
				Title: '¿Eliminar el paso: "'+P.Nombre+'"?',
			}).then((d) => {
				if(!d) return;
				Ctrl.PasosCRUD.delete(P);
			});
		}

		//Variables
		Ctrl.addVariable = () => {
			Ctrl.VariablesCRUD.add({
				bot_id: Ctrl.BotSel.id
			});
		}

		Ctrl.delVariable = (V) => {
			Ctrl.VariablesCRUD.delete(V);
		}


		//Run
		Ctrl.runBot = () => {
			Ctrl.BotRunning = true;
			Rs.http('/api/Bots/run/' + Ctrl.BotSel.id, {}, false, false, 'GET').finally(() => {
				Ctrl.BotRunning = false;
				Ctrl.getBots();
			});
		}


		Ctrl.seeLogs = () => {
			$mdDialog.show({
				controller: 'Bot_LogsCtrl',
				templateUrl: '/Frag/Bots.Bot_Logs',
				locals: { Bot : Ctrl.BotSel },
				clickOutsideToClose: false, fullscreen: true, multiple: true,
			});
		}


		//ACE
		Ctrl.aceOptionsJs = {
			theme:'twilight',
			mode: 'json',
			onLoad: (_editor) => {
				var _session = _editor.getSession();
				_editor.setFontSize(15);
				_session.setTabSize(3);
				_editor.setOptions({
				    minLines: 1,
				    maxLines: 5000
				});
				//_editor.setUseSoftTabs(true);
			},
		};

		Ctrl.aceOptionsSql = {
			theme:'twilight',
			mode: 'sql',
			onLoad: (_editor) => {
				var _session = _editor.getSession();
				_editor.setFontSize(13);
				_session.setTabSize(3);
				_editor.setOptions({
				    minLines: 2,
				    maxLines: 5000
				});
				//_editor.setUseSoftTabs(true);
			},
		};




		Promise.all([
			Rs.http('api/Bdds/all', {}, Ctrl, 'Bdds')
		]).then(() => {
			Ctrl.getBots();
		});
		




	}
]);