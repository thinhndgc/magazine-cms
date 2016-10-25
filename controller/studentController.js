module.controller('studentController', function($scope, $rootScope, $timeout, $http, service) {
    $scope.$on('$viewContentLoaded', function() {
        console.log('here');
        $scope.authen();
    });
    $scope.authen = function() {
        console.log('authen');
        service.authen('Student');
        $scope.setUserInfor();
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
        var file_data = $('#pictureFile').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        alert(form_data);

    };
    $scope.uploadDoc = function() {

    };
});
