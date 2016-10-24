module.controller('adminController', function($scope, $rootScope, $timeout, $http,service) {
  $scope.$on('$viewContentLoaded', function() {
    $scope.authen();
  });
  $scope.authen = function(){
    service.authen('admin');
    $scope.setUserInfor();
  };
  $scope.logout = function(){
    service.clearLoginData();
    window.location.replace('login.html');
  };
  $scope.setUserInfor = function(){
    $scope.fullName = localStorage.getItem('fullName');
    $scope.fullNameWithRole = localStorage.getItem('fullName') + ' - '+ localStorage.getItem('role');
    $scope.role = localStorage.getItem('role');
    $('#fullName').text($scope.fullName);
    $('#fullNameWithRole').text($scope.fullNameWithRole);
    $('#role').text($scope.role);
  };
});
