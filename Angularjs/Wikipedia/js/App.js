/**
 * Wiki Search module.
 *
 */
 // create module for popup and assign with bootstrap and autocomplate module.
angular.module("PopupDemo", ['ui.bootstrap', 'autocomplete'])
    .controller("wikiController", ["$scope", "searchResults", function($scope, searchResults) {   
       // create wikicontroller for displying search result and assign it into popup module
        $scope.reset = function() {
            if($scope.content) $scope.content = '';
            if($scope.results) $scope.results = '';
        };

        $scope.check = function() {
            if ($scope.content === "" || !$scope.content) return false;
            return true;
        }
        

        // get the results on autocomplate search.        
        $scope.getResults = function(){
            if($scope.check()) {
                searchResults.get($scope.content).then(function(data){
                    $scope.results = data.data.query.pages;
                    for(var page in $scope.results){
                        $scope.results[page].link = 'https://en.wikipedia.org/wiki/' + $scope.results[page].title; 
                    }
                });
            }
        };
    }])
    .factory("searchResults", function($http) {  
        // create service for searchresult using factory method.
        var config = {
            params: {
                format: "json",
                action: "query",
                gsrsearch: 'intitle:'+$http,
                prop: "extracts|pageimages|categories|templates",
                exintro: "",
                explaintext: "",
                rawcontinue: "",
                generator: "search",
                gsrlimit: "10",
                pilimit: 'max',
                exlimit: 'max',
                cilimit:'max',
                callback: "JSON_CALLBACK",
            }
        };
        var url = "https://en.wikipedia.org/w/api.php?";
        
        var results = {
            get: function(data) {
                config.params.gsrsearch = data;
                return $http.jsonp(url,config).then(function(rq){
                    return rq;
                });
            }
        };

        return results;
    })
    .filter('orderObjectBy', function() {
      return function(items, field, reverse) {
        var filtered = [];
        angular.forEach(items, function(item) {
          filtered.push(item);
        });
        filtered.sort(function (a, b) {
          return (a[field] > b[field] ? 1 : -1);
        });
        if(reverse) filtered.reverse();
        return filtered;
      };
    })
    .controller("PopupDemoCont", ["$scope", "$uibModal", function($scope, $uibModal) {  
        // controller for calling wikisearch inside the module.
        $scope.defaultLogo = 'images/no-image.png';
        $scope.open = function () {
            var modalInstance = $uibModal.open({
                controller: 'wikiController',
                templateUrl: 'Popup.html',
                scope: $scope
            });
            $scope.close = function () {
                modalInstance.dismiss('cancel');
            };
        }
    }]);