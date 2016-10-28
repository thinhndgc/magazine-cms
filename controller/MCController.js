module.controller('MCController', function($scope, $rootScope, $timeout, $http, service) {
  $scope.$on('$viewContentLoaded', function() {
    $scope.authen();
  });
  $scope.authen = function() {
    console.log('authen');
    service.authen('MC');
    $scope.setUserInfor();
    $scope.getArticleData();
    $scope.getAllAcademyYear();
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
  $scope.getArticleData = function() {
    var uid = localStorage.getItem('uid');
    var data = 'callType=getAllArticleByMcId&uid='+uid;
    console.log(data);
    service.makeRequest(data).then(function(response) {
      $scope.returnData = response.data;
      if ($scope.returnData.status == 1) {
        console.log($scope.returnData);
        $scope.sArticle = $scope.returnData.data;
      } else {
        alert($scope.returnData.msg);
      }
    });
    $scope.statusFilter = 'uploaded';
  };
  $scope.getAllAcademyYear = function() {
    var data = 'callType=getAllAcademyYear&';
    var d = new Date();
    var year = d.getFullYear();
    $scope.currentAY = year;
    service.makeRequest(data).then(function(response) {
      $scope.returnData = response.data;
      if ($scope.returnData.status == 1) {
        $scope.sAY = $scope.returnData.data;
      } else {
        alert($scope.returnData.msg);
      }
    });
  };
  $scope.getAllCommentByArticleId = function() {
    var data = 'callType=getAllCommentByArticleId&atid='+$scope.seletedArticle.atid;
    service.makeRequest(data).then(function(response) {
      $scope.returnData = response.data;
      if ($scope.returnData.status == 1) {
        $scope.comment = $scope.returnData.data;
        $scope.hasComment = true;
      } else {
        $scope.hasComment = false;
        console.log($scope.returnData.msg);
      }
    });
  };
  $scope.placeComment = function() {
    if ($scope.txtComment==="" || $scope.txtComment===undefined || $scope.txtComment===null) {
      return false;
    }else {
      var uid = localStorage.getItem('uid');
      var data = 'callType=comment&uid='+uid+'&atid='+$scope.seletedArticle.atid+'&comment='+$scope.txtComment;
      service.makeRequest(data).then(function(response) {
        $scope.returnData = response.data;
        if ($scope.returnData.status == 1) {
          $scope.getAllCommentByArticleId();
          $scope.txtComment = "";
          alert($scope.returnData.msg);
        } else {
          alert($scope.returnData.msg);
        }
      });
      return true;
    }
  };
  $scope.view = function(index) {
    $scope.seletedArticle = $scope.sArticle[index];
    $scope.getAllCommentByArticleId();
    $('#photo').attr('src','file/image/'+$scope.seletedArticle.img_source);
    $('#detailModal').modal({backdrop: 'static', keyboard: false});
  };
  $scope.closeView = function() {
    $scope.getArticleData();
    $scope.comment = null;
    $('#detailModal').modal('hide');
  };
  $scope.fillterYear = function(index) {
    $scope.currentAY = $scope.sAY[index].year;
  };
  $scope.fillterStatus = function(value) {
    if (value===0) {
      $scope.statusFilter = 'uploaded';
    }else {
      $scope.statusFilter = 'submited';
    }
  };
});
