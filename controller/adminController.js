module.controller('adminController', function($scope, $rootScope, $timeout, $http,service) {
  $scope.$on('$viewContentLoaded', function() {
    $scope.authen();
  });
  $scope.authen = function(){
    service.authen('admin');
  };
  $scope.logout = function(){
    service.clearLoginData();
    window.location.replace('login.html');
  };
});
