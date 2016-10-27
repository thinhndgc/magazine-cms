module.controller('MMController', function($scope, $rootScope, $timeout, $http, service) {
  $scope.$on('$viewContentLoaded', function() {
    $scope.authen();
  });
  $scope.authen = function() {
    console.log('authen');
    service.authen('MM');
    $scope.setUserInfor();
    $scope.getAllMagazine();
    $scope.getAllAcademyYear();
    $scope.getAllFalcuties();
  };
  $scope.logout = function() {
    service.clearLoginData();
    window.location.replace('login.html');
  };
  $scope.setUserInfor = function() {
    $scope.fullName = localStorage.getItem('fullName');
    $scope.fullNameWithRole = localStorage.getItem('fullName') + ' - ' + localStorage.getItem('role');
    $scope.role = localStorage.getItem('role');
    $('#fullName').text($scope.fullName);
    $('#fullNameWithRole').text($scope.fullNameWithRole);
    $('#role').text($scope.role);
  };
  $scope.getAllFalcuties = function() {
    var data = 'callType=getAllFalcuties&';
    service.makeRequest(data).then(function(response) {
      $scope.returnData = response.data;
      if ($scope.returnData.status == 1) {
        $scope.aFalcuties = $scope.returnData.data;
      } else {
        alert($scope.returnData.msg);
      }
    });
  };
  $scope.getAllAcademyYear = function() {
    var data = 'callType=getAllAcademyYear&';
    $scope.sAY = null;
    service.makeRequest(data).then(function(response) {
      $scope.returnData = response.data;
      if ($scope.returnData.status == 1) {
        $scope.sAY = $scope.returnData.data;
        var index = $scope.sAY.indexOf($scope.currentAY);
        $scope.sAY.splice(index,1);
      } else {
        alert($scope.returnData.msg);
      }

    });
  };
  $scope.getAllMagazine = function() {
    var data = 'callType=getAllMagazine&';
    $scope.sMA = null;
    service.makeRequest(data).then(function(response) {
      $scope.returnData = response.data;
      console.log($scope.returnData);
      if ($scope.returnData.status == 1) {
        $scope.sMA = $scope.returnData.data;
      } else {
        alert($scope.returnData.msg);
      }
    });
    var d = new Date();
    var year = d.getFullYear();
    $scope.currentAY = year;
  };
  $scope.createMagazine = function(){
    var data = 'callType=createMagazine&magazine_name='+$scope.magazineName+'&start_date='+$scope.startDate+'&end_date='+$scope.endDate;
    service.makeRequest(data).then(function(response) {
      $scope.returnData = response.data;
      if ($scope.returnData.status == 1) {
        $scope.getAllMagazine();
        alert($scope.returnData.msg);
      } else {
        alert($scope.returnData.msg);
      }
    });
  };
  $scope.editMagazine = function() {
    var data = 'callType=editMagazine&mid='+$scope.selectedMid+'&new_magazine_name='+$scope.magazineNameEdit+'&start_date='+$scope.startDateEdit+'&end_date='+$scope.endDateEdit;
    console.log(data);
    service.makeRequest(data).then(function(response) {
      $scope.returnData = response.data;
      if ($scope.returnData.status == 1) {
        $scope.getAllMagazine();
        alert($scope.returnData.msg);
      } else {
        alert($scope.returnData.msg);
      }
    });
  };
  $scope.fillterYear = function(){
    if ($scope.currentAY == 'Current') {
      var d = new Date();
      var year = d.getFullYear();
      $scope.currentAY = year;
      $scope.currentFal = "";
    }
  };
  $scope.fillterFauclties = function(){
    if ($scope.currentFal == 'All') {
      $scope.currentFal = "";
    }
  };
  $scope.fillterStatus = function() {
    if ($scope.currentStatus == 'All') {
      $scope.currentStatus = "";
    }
  };
  $scope.reset = function() {
    $scope.magazineName = "";
    $scope.startDate = "";
    $scope.endDate = "";
  };
  $scope.showEdit = function(index) {
    console.log('edit shoe');
    $('.abc1').hide();
    $('.abc2').show();
    $scope.magazineNameEdit = $scope.sMA[index].magazine_name;
    $scope.startDateEdit = $scope.sMA[index].start_date;
    $scope.endDateEdit = $scope.sMA[index].end_date;
    $scope.selectedMid = $scope.sMA[index].mid;
  };
  $scope.showCreate = function() {
    $('.abc2').hide();
    $('.abc1').show();
    $scope.reset();
  };
});
