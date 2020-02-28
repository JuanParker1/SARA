angular.module('scorecardNodo', []).component('scorecardNodo', {
  templateUrl: 'templates/scorecard/nodo.html',
  bindings: {
    nodo: '=',
    periodo: '<'
  }
});