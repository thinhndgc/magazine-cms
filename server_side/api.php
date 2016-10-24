<?php
include "backend.php";
if($_SERVER['REQUEST_METHOD'] == "POST"){
  $callType=$_POST['callType'];
  switch ($callType) {
    case 'createAccount':
    $full_name = isset($_POST['fullName']) ? $_POST['fullName'] : "";
    $dob = isset($_POST['dob']) ? $_POST['dob'] : "";
    $gender = isset($_POST['gender']) ? $_POST['gender'] : "";
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";
    $phone = isset($_POST['phone']) ? $_POST['phone'] : "";
    $roleID = isset($_POST['roleID']) ? $_POST['roleID'] : "";
    $data = createAccount($full_name,$dob,$gender,$email,$password,$phone,$roleID);
    break;
    case 'createStudentAccount':
    $full_name = isset($_POST['fullName']) ? $_POST['fullName'] : "";
    $dob = isset($_POST['dob']) ? $_POST['dob'] : "";
    $gender = isset($_POST['gender']) ? $_POST['gender'] : "";
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";
    $phone = isset($_POST['phone']) ? $_POST['phone'] : "";
    $falcutiesName = isset($_POST['falcutiesName']) ? $_POST['falcutiesName'] : "";
    $data = createStudentAccount($full_name,$dob,$gender,$email,$password,$phone,$falcutiesName);
    break;
    case 'getAllStudentAccount':
    $data = getAllStudent();
    break;
    case 'editStudentAccount':
    $uid = isset($_POST['uid']) ? $_POST['uid'] : "";
    $full_name = isset($_POST['fullName']) ? $_POST['fullName'] : "";
    $dob = isset($_POST['dob']) ? $_POST['dob'] : "";
    $gender = isset($_POST['gender']) ? $_POST['gender'] : "";
    $currentEmail = isset($_POST['currentEmail']) ? $_POST['currentEmail'] : "";
    $newEmail = isset($_POST['newEmail']) ? $_POST['newEmail'] : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";
    $phone = isset($_POST['phone']) ? $_POST['phone'] : "";
    $falcutiesName = isset($_POST['falcutiesName']) ? $_POST['falcutiesName'] : "";
    $data = editStudentAccount($uid,$full_name,$dob,$gender,$currentEmail,$newEmail,$password,$phone,$falcutiesName);
    break;
    case 'deleteStudent':

    break;
    case 'checkLogin':
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";
    $data = checkLogin($email,$password);
    break;
    case 'createRole':
    $roleName = isset($_POST['roleName']) ? $_POST['roleName'] : "";
    $data = createRole($roleName);
    break;
    case 'getAllRole':
    $data = getAllRole();
    break;
    case 'editRole':
    $roleName = isset($_POST['roleName']) ? $_POST['roleName'] : "";
    $newRoleName = isset($_POST['newRoleName']) ? $_POST['newRoleName'] : "";
    $data = editRole($roleName,$newRoleName);
    break;
    case 'deleteRole':
    $roleName = isset($_POST['roleName']) ? $_POST['roleName'] : "";
    $data = deleteRole($roleName);
    break;
    case 'createAcademyYear':
    $year = isset($_POST['year']) ? $_POST['year'] : "";
    $data = createAcademyYear($year);
    break;
    case 'getAllAcademyYear':
    $data = getAllAcademyYear();
    break;
    case 'editAcademyYear':
    $year = isset($_POST['year']) ? $_POST['year'] : "";
    $newYear = isset($_POST['newYear']) ? $_POST['newYear'] : "";
    $data = editAcademyYear($year,$newYear);
    break;
    case 'deleteAcademyYear':
    $year = isset($_POST['year']) ? $_POST['year'] : "";
    $data = deleteAcademyYear($year);
    break;
    case 'createFalcuties':
    $falcutiesName = isset($_POST['falcutiesName']) ? $_POST['falcutiesName'] : "";
    $data = createFalcuties($falcutiesName);
    break;
    case 'getAllFalcuties':
    $data = getAllFalcuties();
    break;
    case 'editFalcuties':
    $falcutiesName = isset($_POST['falcutiesName']) ? $_POST['falcutiesName'] : "";
    $newFalcutiesName = isset($_POST['newFalcutiesName']) ? $_POST['newFalcutiesName'] : "";
    $data = editFalcuties($falcutiesName,$newFalcutiesName);
    break;
    case 'deleteFalcuties':
    $falcutiesName = isset($_POST['falcutiesName']) ? $_POST['falcutiesName'] : "";
    $data = deleteFalcuties($falcutiesName);
    break;
    default:
    $data = array("status" => 0, "msg" => "No match function for API call");
    break;
  }
}else {
  $data = array("status" => 0, "msg" => "Request method not accepted");
}
// Output header
header('Content-type: application/json');
echo json_encode($data);
?>
