angular.module('focusOn', [])
.directive('focusOn', function() {
   return function(scope, elem, attr) {
      scope.$on(attr.focusOn, function(e) {
      		setTimeout(function(){ 
      			elem[0].focus();
          		console.log('Focused', elem);
      		}, 3000);
      });
   };
});