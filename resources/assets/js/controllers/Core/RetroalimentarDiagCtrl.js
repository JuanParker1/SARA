angular.module('RetroalimentarDiagCtrl', [])
.controller(   'RetroalimentarDiagCtrl', ['$scope', '$rootScope', '$mdDialog', 'Subject',
	function ($scope, $rootScope, $mdDialog, Subject) {

		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.Stage = 'Writting';
		

		Ctrl.Cancel = function(){
			$mdDialog.cancel();
		}

		Ctrl.feedbackComment = '';
		Ctrl.Subject = Subject;

		Ctrl.enviarFeedback = () => {

			if(Ctrl.feedbackComment.trim() == '') return Rs.showToast('Por favor incluya un comentario', 'Error');

			Ctrl.Stage = 'Sending';

			Rs.http('api/Main/feedback', { Subject: Ctrl.Subject, feedbackComment: Ctrl.feedbackComment, usuario_id: Rs.Usuario.id }).then(() => {
				Ctrl.Stage = 'Sent';
			});

		}
	}

]);