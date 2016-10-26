module.controller('studentController', function($scope, $rootScope, $timeout, $http, service, fileUpload) {
  $scope.$on('$viewContentLoaded', function() {
    $scope.authen();
  });
  $scope.authen = function() {
    console.log('authen');
    service.authen('Student');
    $scope.setUserInfor();
    $scope.isUploadDoc = false;
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
    console.log('ok'+$scope.imgDir+'-'+$scope.docDir+'-'+$scope.title+'-'+$scope.description);
  };
  $scope.validate = function(){
    // if ($scope.title === null || $scope.description===null|| $scope.isUploadDoc === false) {
    //
    // }
  };
});
