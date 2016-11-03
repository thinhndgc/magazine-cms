module.controller('studentController', function($scope, $rootScope, $timeout, $http, service, fileUpload) {
  $scope.$on('$viewContentLoaded', function() {
    $scope.authen();
  });
  $scope.authen = function() {
    console.log('authen');
    service.authen('Student');
    $scope.setUserInfor();
    $scope.getCurrentMagazine();
    $scope.isUploadDoc = false;
    $scope.btnUpload = ' Upload';
    $('#img_document').removeClass('text-green');
    $('#img_document').addClass('text-red');
    $scope.doc_msg = 'Document are require';
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
  $scope.showChangPass = function() {
    $scope.validConfirmPassword = false;
    $scope.confirmPasswordMsg = "Confirm new password required!";
    $('#changePassModal').modal({backdrop: 'static', keyboard: false});
  };
  $scope.checkRequirePass = function() {
    if ($scope.newPass !== null && $scope.confirmNewPass !== null && $scope.newPass !== $scope.confirmNewPass) {
      $scope.confirmPasswordMsg = "Confirm password not match!";
      $scope.validConfirmPassword = false;
    }else if ($scope.newPass !== null && $scope.confirmNewPass !== null && $scope.newPass === $scope.confirmNewPass){
      $scope.validConfirmPassword = true;
    }else {
      $scope.validConfirmPassword = false;
    }
  };
  $scope.changePass = function() {
    var uid = localStorage.getItem('uid');
    var data = 'callType=changePassword&uid='+uid+'&currentPass='+$scope.currentPass+'&newPass='+$scope.newPass;
    service.makeRequest(data).then(function(response) {
      $scope.returnData = response.data;
      if ($scope.returnData.status == 1) {
        alert($scope.returnData.msg);
      } else {
        alert($scope.returnData.msg);
      }
    });
  };
  $scope.closeChangePassView = function() {
    $scope.currentPass = '';
    $scope.newPass = '';
    $scope.confirmNewPass = '';
    $('#changePassModal').modal('hide');
  };
  $scope.getCurrentMagazine = function() {
    var data = 'callType=getCurrentMagazine&';
    service.makeRequest(data).then(function(response) {
      $scope.returnData = response.data;
      if ($scope.returnData.status == 1) {
        // console.log($scope.returnData.data);
        if ($scope.validMagazineDay($scope.returnData.data[0].start_date,$scope.returnData.data[0].end_date)) {
          $scope.currentMagazine = $scope.returnData.data[0].magazine_name;
          $scope.isExistMagazine = true;
        }else {
          $scope.currentMagazine = $scope.returnData.data[0].magazine_name+' (submit expired)';
          $scope.isExistMagazine = false;
        }
      } else {
        $scope.currentMagazine = 'No active Magazine';
        $scope.isExistMagazine = false;
        alert($scope.returnData.msg);
      }
    });
  };
  $scope.validMagazineDay = function(start_date, end_date) {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth();
    var yyyy = today.getFullYear();
    if(dd<10){
      dd='0'+dd;
    }
    var currentDate = new Date(yyyy, mm, dd);
    var startDateArr =  start_date.split('-');
    var m = startDateArr[1]-1;
    var startDate = new Date(startDateArr[0],m,startDateArr[2]);
    var enddateArr = end_date.split('-');
    var m2 = enddateArr[1]-1;
    var endDate = new Date(enddateArr[0],m2,enddateArr[2]);
    if (startDate > currentDate) {
      return false;
    }else if (currentDate > endDate) {
      return false;
    }else {
      return true;
    }
  };
  $scope.uploadImage = function() {
    var file_data = $('#pictureFile')[0].files[0];
    var form_data = new FormData();
    form_data.append('file', file_data);
    $.ajax({
      url: "server_side/upload-image.php",
      type: "POST",
      data: form_data,
      contentType: false,
      cache: false,
      processData:false,
      transformRequest:angular.identity,
      headers:{'Content-Type':undefined},
      success: function(data)
      {
        console.log(data);
        $scope.uploadImageReturnData = data;
        if ($scope.uploadImageReturnData.status == 1) {
          $('#img_mess').removeClass('text-red');
          $('#img_mess').addClass('text-green');
          $scope.img_msg = 'Upload file '+file_data.name+' success!';
          $scope.imgDir = $scope.uploadImageReturnData.file_name;
        }else {
          $('#img_mess').removeClass('text-green');
          $('#img_mess').addClass('text-red');
          $scope.img_msg = $scope.uploadImageReturnData.msg;
        }
        $scope.$apply();
      }
    });
  };
  $scope.uploadDoc = function() {
    var file_data = $('#docFile')[0].files[0];
    var form_data = new FormData();
    form_data.append('file', file_data);
    console.log('start');
    $.ajax({
      url: "server_side/upload-doc.php",
      type: "POST",
      data: form_data,
      contentType: false,
      cache: false,
      processData:false,
      transformRequest:angular.identity,
      headers:{'Content-Type':undefined},
      success: function(data)
      {
        console.log(data);
        $scope.uploadDocReturnData = data;
        if ($scope.uploadDocReturnData.status == 1) {
          $('#img_document').removeClass('text-red');
          $('#img_document').addClass('text-green');
          $scope.doc_msg = 'Upload file '+file_data.name+' success!';
          $scope.isUploadDoc = true;
          $scope.docDir = $scope.uploadDocReturnData.file_name;
        }else {
          $('#img_document').removeClass('text-green');
          $('#img_document').addClass('text-red');
          $scope.doc_msg = $scope.uploadDocReturnData.msg;
          $scope.isUploadDoc = false;
        }
        $scope.$apply();
      }
    });
  };
  $scope.uploadArticle = function(){
    var uid = localStorage.getItem('uid');
    var data = 'callType=createArticle&uid='+uid+'&title='+$scope.title+'&description='+$scope.description+'&imgDir='+$scope.imgDir+'&docDir='+$scope.docDir;
    $scope.btnUpload = 'Uploading....';
    $scope.isExistMagazine = false;
    service.makeRequest(data).then(function(response) {
      $scope.returnData = response.data;
      if ($scope.returnData.status == 1) {
        $scope.btnUpload = 'Upload';
        $scope.isExistMagazine = true;
        alert($scope.returnData.msg);
      } else {
        $scope.btnUpload = 'Upload';
        $scope.isExistMagazine = true;
        alert($scope.returnData.msg);
      }
    });
  };
});
