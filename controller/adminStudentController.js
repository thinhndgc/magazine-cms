module.controller('adminStudentController', function($scope, $rootScope, $timeout, $http, service) {
    angular.element(document).ready(function() {
        $scope.getAllStudent();
        $scope.getAllFalcuties();
    });
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
    $scope.createStudent = function() {
        var data = 'callType=createStudentAccount&fullName='+$scope.fullNameCreate+'&dob='+$scope.dob+'&gender='+$scope.gender+'&password='+$scope.password+'&phone='+$scope.phone+'&email='+$scope.email+'m&falcutiesName='+$scope.selectedFalcities;
        console.log(data);
        service.makeRequest(data).then(function(response) {
            $scope.returnData = response.data;
            if ($scope.returnData.status == 1) {
                alert($scope.returnData.msg);
                $scope.getAllStudent();
            } else {
                alert($scope.returnData.msg);
            }
        });
    };
    $scope.getAllStudent = function() {
        var data = 'callType=getAllStudentAccount&';
        service.makeRequest(data).then(function(response) {
            $scope.returnData = response.data;
            if ($scope.returnData.status == 1) {
                $scope.sStudent = $scope.returnData.data;
            } else {
                alert($scope.returnData.msg);
            }
        });
    };
    $scope.editStudent = function() {
      var data = 'callType=editStudentAccount&uid='+$scope.uid+'&fullName='+$scope.fullNameEdit+'&dob='+$scope.dobEdit+'&gender='+$scope.genderEdit+'&password='+$scope.passwordEdit+'&phone='+$scope.phoneEdit+'&currentEmail='+$scope.currentEmail+'&newEmail='+$scope.emailEdit+'&falcutiesName='+$scope.selectedFalcitiesEdit;
      console.log(data);
      service.makeRequest(data).then(function(response) {
          $scope.returnData = response.data;
          if ($scope.returnData.status == 1) {
              alert($scope.returnData.msg);
              $scope.getAllStudent();
          } else {
              alert($scope.returnData.msg);
          }
      });
    };
    $scope.reset = function() {
        $scope.fullNameCreate = "";
        $scope.dob = "";
        $scope.gender = "";
        $scope.email = "";
        $scope.password = "";
        $scope.phone = "";
        $scope.selectedFalcities = "";
    };
    $scope.showEdit = function(index) {
        $('.abc1').hide();
        $('.abc2').show();
        $scope.uid = $scope.sStudent[index].uid;
        $scope.fullNameEdit = $scope.sStudent[index].full_name;
        $scope.dobEdit = $scope.sStudent[index].dob;
        $scope.genderEdit = $scope.sStudent[index].gender;
        $scope.emailEdit = $scope.sStudent[index].email;
        $scope.passwordEdit = "";
        $scope.phoneEdit = $scope.sStudent[index].phone;
        $scope.selectedFalcitiesEdit = $scope.sStudent[index].falcuties_name;
        $scope.currentEmail = $scope.sStudent[index].email;
    };
    $scope.showCreate = function() {
      $('.abc2').hide();
      $('.abc1').show();
      $scope.reset();
    };
    $scope.edit = function() {
      if ($scope.selectedYear!=$scope.editYear) {
        var data = 'callType=editStudent&year='+$scope.selectedYear+'&newYear='+$scope.editYear;
        service.makeRequest(data).then(function(response) {
            $scope.returnData = response.data;
            if ($scope.returnData.status == 1) {
                alert($scope.returnData.msg);
                $scope.getAllStudent();
            } else {
                alert($scope.returnData.msg);
            }
        });
      }else {
        alert('no change!');
      }
    };
});
