angular.module('extSubmit', []).directive("extSubmit", ['$timeout',function($timeout){
    return {
        link: function($scope, $el, $attr) {
            $scope.$on('makeSubmit', function(event, data){
              if(data.formName === $attr.name) {
                $timeout(function() {
                  $el.triggerHandler('submit'); //<<< This is Important
                  //$el[0].dispatchEvent(new Event('submit')) //equivalent with native event
                }, 0, false);   
              }
            })
        }
    };
}]);