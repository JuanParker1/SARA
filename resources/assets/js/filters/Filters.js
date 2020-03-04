angular.module('Filters', [])
	.filter('to_trusted', ['$sce', function($sce){
		return function(text) {
			return $sce.trustAsHtml(text);
		};
	}])
	.filter('findId', function() {
		return function(input, id) {
			var i=0, len=input.length;
			for (; i<len; i++) {
			  if (+input[i].id == +id) {
				return input[i];
			  }
			}
			return null;
		 };
	}).filter('getIndex', function() {
		return function(input, id, attr) {
			var len=input.length;
			attr = (typeof attr !== 'undefined') ? attr : 'id';
			for (i=0; i<len; i++) {
			  if(input[i][attr] === id) {
				return i;
			  }
			}
			return null;
		 };
	}).filter('include', function() {
		return function(input, include, prop) {
			if (!angular.isArray(input)) return input;
			if (!angular.isArray(include)) include = [];
			return input.filter(function byInclude(item) {
				return include.indexOf(prop ? item[prop] : item) != -1;
			});
		};
	}).filter('exclude', function() {
		return function(input, exclude, prop) {
			if (!angular.isArray(input)) return input;
			if (!angular.isArray(exclude)) exclude = [];
			/*if (prop) {
				exclude = exclude.map(function byProp(item) {
					return item[prop];
				});
			};*/

			return input.filter(function byExclude(item) {
				return exclude.indexOf(prop ? item[prop] : item) === -1;
			});
		};
	}).filter('category', function() {
		return function(input, category, prop) {
			//console.log(input, category, prop);
			if (!angular.isArray(input)) return input;
			if(!category) return input;
			return input.filter(function(item){
				return item[prop] == category;
			});
			//return input[prop] == category;
		};
	}).filter('toArray', function () {
		return function (obj, addKey) {
			if (!angular.isObject(obj)) return obj;
			if ( addKey === false ) {
			return Object.keys(obj).map(function(key) {
				return obj[key];
			});
			} else {
			return Object.keys(obj).map(function (key) {
				var value = obj[key];
				return angular.isObject(value) ?
				Object.defineProperty(value, '$key', { enumerable: false, value: key}) :
				{ $key: key, $value: value };
			});
			}
		};
	}).filter('pluck', function() {
		return function(array, key, unique) {
			var res = new Array();
			angular.forEach(array, function(v) {
				if(unique && res.indexOf(v[key]) !== -1) return false;
				res.push(v[key]);
			});
			return res;
		};
	}).filter('switch', function() {
	    return function(input, boolean) {
	    	return (boolean) ? input : [];
	    }
	}).filter('search', function() {
		return function(input, search) {
			if (!input) return input;
			if (!search) return input;
			var expected = ('' + search).toLowerCase();
			var result = {};
			angular.forEach(input, function(value, key) {
				var actual = ('' + value).toLowerCase();
				if (actual.indexOf(expected) !== -1) {
					result[key] = value;
				}
			});
			return result;
		}
	}).filter('capitalize', function() {
	    return function(input) {
	      return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
	    }
	}).filter('capitwords', function() {
	    return function(input,limit) {
	    	if (!input) return '';
	    	input = input.split('_').join(' ');
	    	limit = (!!limit) ? limit : 2;
	    	return input.split(' ').map(function(wrd){
	    		return (wrd.length) > limit ? wrd.charAt(0).toUpperCase() + wrd.substr(1).toLowerCase() : wrd.toLowerCase();
	    	}).join(' ');
	    }
	}).filter('traducirum', function() {
	    return function(input,um) {
	    	if(input <= 0){
	    		return 'No incluido';
	    	}else if(um == 'KG'){
	    			 if(input < 1){ input = input*1000; um = 'Gramos'  }
	    		else if(input == 1){ um = 'Kilo'  }
	    		else if(input > 1 && input < 1000 ){ um = 'Kilos'  }
	    		else if(input >= 1000){ input = input/1000; um = 'Toneladas'  }
	    	}else if(um == 'LT'){
	    			 if(input < 1){ input = input*1000; um = 'Mililitros'  }
	    		else if(input == 1){ um = 'Litro'  }
	    		else if(input > 1 ){ um = 'Litros'  }
	    	}else if(um == 'UN'){
	    			 if(input <= 1){ um = 'Unidad'  }
	    		else if(input > 1 ){ um = 'Unidades'  }
	    	};
	    	return input + ' ' + um;
	    }
	}).filter('percentage', ['$filter', function ($filter) {
		return function (input, decimals) {
		return $filter('number')(input * 100, decimals) + '%';
		};
	}]).filter('numberformat', ['$filter', function ($filter) {
		return function (input, tipodato, decimales) {
			if(!input) return input;
			if(tipodato == 'Porcentaje') input = input * 100;
			var number = $filter('number')(input, decimales);
			if(tipodato == 'Porcentaje') return number + "%";
			if(tipodato == 'Moneda') return "$ " + number;
			return number;
		};
	}]).filter('splice', function() {
		return function(input, index, len) {
			if(!input) return input;
			//if(!index || !len) return input;
			return input.splice(index, len);
		};
	}).filter('getword', function() {
		return function(input, index) {
			if(!input) return input;
			var arr = input.split(' ');
			return arr[index-1];
		};
	});