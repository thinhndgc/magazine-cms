<?php
include 'DBconnection.php';
// Check API start
////////////////////////////////////////////////////////
function isExist($value,$col,$table)
{
  global $conn;
  $query = "SELECT * FROM `$table` WHERE $col = '$value'";
  $check= mysqli_num_rows(mysqli_query($conn,$query));
  if ($check!=0) {
    return true;
    @mysqli_close($conn);
  }else {
    return false;
    @mysqli_close($conn);
  }
}

function isExistEmail($currentMail,$newMail)
{
  global $conn;
  $query = "SELECT * FROM `user` WHERE email = '$newMail' AND email NOT IN('$currentMail')";
  $check= mysqli_num_rows(mysqli_query($conn,$query));
  if ($check!=0) {
    return true;
    @mysqli_close($conn);
  }else {
    return false;
    @mysqli_close($conn);
  }
}

function isNotExist($value,$col,$table)
{
  global $conn;
  $query = "SELECT * FROM `$table` WHERE $col = '$value'";
  $check= mysqli_num_rows(mysqli_query($conn,$query));
  if ($check==0) {
    return true;
    @mysqli_close($conn);
  }else {
    return false;
    @mysqli_close($conn);
  }
}
// Check API end
////////////////////////////////////////////////////////

// Account API start
////////////////////////////////////////////////////////
function checkLogin($email,$password)
{
  global $conn;
  $md5password = md5($password);
  $query = "SELECT user.uid, user.full_name, role.role_name FROM `user` INNER JOIN user_role ON user.uid = user_role.uid INNER JOIN role ON user_role.rid = role.rid WHERE user.email = '$email' AND password = '$md5password'";
  $result = mysqli_query($conn,$query);
  $check= mysqli_num_rows($result);
  if ($check!=0) {
    $row=mysqli_fetch_assoc($result);
    $role = $row['role_name'];
    $userName = $row['full_name'];
    if ($role == 'MC') {
      // $falculties = getFalcutiesByEmail($email);
      $returnData = array("status" => 1, "msg" => "Login success!", "role" => $role, "fullName" => $userName);
    }else {
      $returnData = array("status" => 1, "msg" => "Login success!", "role" => $role, "fullName" => $userName);
    }
  }else {
    $returnData = array("status" => 0, "msg" => "Login false, check your email or password!");
  }
  mysqli_free_result($result);
  @mysqli_close($conn);
  return $returnData;
}

function createAccount($full_name,$dob,$gender,$email,$password,$phone,$roleID)
{
  global $conn;
  $md5password = md5($password);
  if (isNotExist($email,"email","user")) {
    $md5password = md5($password);
    $query = "INSERT INTO `user`(`full_name`, `dob`, `gender`, `email`, `password`, `phone`) VALUES ('$full_name',STR_TO_DATE('$dob', '%d/%m/%Y'),'$gender','$email','$md5password',$phone)";
    $qur = mysqli_query($conn,$query);
    if($qur){
      $returnData = addRoleForAccount($email,$roleID);
    }else{
      $returnData = array("status" => 0, "msg" => "Error create new account!");
    }
  }else {
    $returnData = array("status" => 0, "msg" => "This account existed!");
  }
  @mysqli_close($conn);
  return $returnData;
}
// Student API start
////////////////////////////////////////////////////////
function createStudentAccount($full_name,$dob,$gender,$email,$password,$phone,$falcutiesName)
{
  global $conn;
  $md5password = md5($password);
  $roleID = 4;
  if (isNotExist($email,"email","user")) {
    $md5password = md5($password);
    $query = "INSERT INTO `user`(`full_name`, `dob`, `gender`, `email`, `password`, `phone`) VALUES ('$full_name',STR_TO_DATE('$dob', '%d/%m/%Y'),'$gender','$email','$md5password',$phone)";
    $qur = mysqli_query($conn,$query);
    if($qur){
      $returnData = addRoleForAccount($email,$roleID);
      if ($returnData['status']==1) {
        $returnData = addFalcutiesForStudent($email,$falcutiesName);
      }else {
        $returnData = array("status" => 0, "msg" => "Error when add role for account!");
      }
    }else{
      $returnData = array("status" => 0, "msg" => "Error create new account!");
    }
  }else {
    $returnData = array("status" => 0, "msg" => "This account existed!");
  }
  @mysqli_close($conn);
  return $returnData;
}
function addFalcutiesForStudent($email,$falcutiesName){
  $falcutiesID = getFid($falcutiesName);
  $uid = getUid($email);
  global $conn;
  $query = "INSERT INTO `students_faculties`(`uid`, `fid`) VALUES ($uid,$falcutiesID)";
  $qur = mysqli_query($conn,$query);
  if($qur){
    $returnData = array("status" => 1, "msg" => "New account created!");
  }else{
    revertRole($uid);
    revertAccount($email);
    $returnData = array("status" => 0, "msg" => "Error when add falcuties for student!");
  }
  return $returnData;
};
function getAllStudent()
{
  global $conn;
  $query = "SELECT user.uid, user.full_name, user.dob, user.gender, user.email, user.password, user.phone, faculties.falcuties_name FROM user INNER JOIN students_faculties on user.uid = students_faculties.uid INNER JOIN faculties ON students_faculties.fid = faculties.fid ORDER BY faculties.falcuties_name";
  $q=mysqli_query($conn,$query);
  $lenght = mysqli_num_rows($q);
  if ($lenght!=0) {
    $data=array();
    while ($row=mysqli_fetch_object($q)){
      $data[]=$row;
    }
    $returnData = array("status" => 1, "data" => $data);
  }else {
    $returnData = array("status" => 0, "msg" => "No students data!");
  }
  @mysqli_close($conn);
  return $returnData;
}
function editStudentAccount($uid,$full_name,$dob,$gender,$currentEmail,$newEmail,$password,$phone,$falcutiesName)
{
  global $conn;
  if (isExist($uid,'uid','user')) {
    if (isExistEmail($currentEmail,$newEmail)) {
      $returnData = array("status" => 0, "msg" => "Account with this email is existed!");
    }else {
      $returnData = array("status" => 1, "msg" => "Student edited!");
      // $query = "UPDATE `faculties` SET `falcuties_name`='$newFalcutiesName' WHERE `falcuties_name` = '$falcutiesName'";
      // $qur = mysqli_query($conn,$query);
      // if($qur){
      //   $returnData = array("status" => 1, "msg" => "Faculties edited!");
      // }else{
      //   $returnData = array("status" => 0, "msg" => "Error edit faculties!");
      // }
    }
  }else {
    $returnData = array("status" => 0, "msg" => "This account not exist!");
  }
  @mysqli_close($conn);
  return $returnData;
}
// Student API end
////////////////////////////////////////////////////////
function addRoleForAccount($email,$roleID)
{
  $uid = getUid($email);
  global $conn;
  $query = "INSERT INTO `user_role`(`uid`, `rid`) VALUES ('$uid','$roleID')";
  $qur = mysqli_query($conn,$query);
  if($qur){
    $returnData = array("status" => 1, "msg" => "New account created!");
  }else{
    revertAccount($email);
    $returnData = array("status" => 0, "msg" => "Error when add role for account!");
  }
  return $returnData;
}

function getUid($email)
{
  global $conn;
  $query = "SELECT `uid` FROM `user` WHERE email = '$email'";
  $result=mysqli_query($conn,$query);
  $row=mysqli_fetch_assoc($result);
  $returnData = $row['uid'];
  mysqli_free_result($result);
  return $returnData;
}

function getFid($fName)
{
  global $conn;
  $query = "SELECT `fid` FROM `faculties` WHERE falcuties_name = '$fName'";
  $result=mysqli_query($conn,$query);
  $row=mysqli_fetch_assoc($result);
  $returnData = $row['fid'];
  mysqli_free_result($result);
  return $returnData;
}

function revertAccount($email)
{
  global $conn;
  $query = "DELETE FROM `user` WHERE email = '$email'";
  $result=mysqli_query($conn,$query);
  @mysqli_close($conn);
}

function revertRole($uid)
{
  global $conn;
  $query = "DELETE FROM `user_role` WHERE uid = $uid";
  $result=mysqli_query($conn,$query);
  @mysqli_close($conn);
}

// Role API start
////////////////////////////////////////////////////////
function createRole($roleName)
{
  global $conn;
  if (isNotExist($roleName,'role_name','role')) {
    $query = "INSERT INTO `role`(`role_name`) VALUES ('$roleName')";
    $qur = mysqli_query($conn,$query);
    if($qur){
      $returnData = array("status" => 1, "msg" => "New role created!");
    }else{
      $returnData = array("status" => 0, "msg" => "Error create role!");
    }
  }else {
    $returnData = array("status" => 0, "msg" => "This role existed!");
  }
  @mysqli_close($conn);
  return $returnData;
}

function getAllRole()
{
  global $conn;
  $query = "SELECT `role_name` FROM `role`";
  $q=mysqli_query($conn,$query);
  $lenght = mysqli_num_rows($q);
  $returnData=array();
  while ($row=mysqli_fetch_object($q)){
    $returnData[]=$row;
  }
  @mysqli_close($conn);
  return $returnData;
}

function editRole($roleName,$newRoleName)
{
  global $conn;
  if (isExist($roleName,'role_name','role')) {
    $query = "UPDATE `role` SET `role_name`='$newRoleName' WHERE `role_name` = '$roleName'";
    $qur = mysqli_query($conn,$query);
    if($qur){
      $returnData = array("status" => 1, "msg" => "Role edited!");
    }else{
      $returnData = array("status" => 0, "msg" => "Error edit role!");
    }
  }else {
    $returnData = array("status" => 0, "msg" => "This role not exist!");
  }
  @mysqli_close($conn);
  return $returnData;
}

function deleteRole($roleName)
{
  global $conn;
  if (isExist($roleName,'role_name','role')) {
    $query = "DELETE FROM `role` WHERE role_name = '$roleName'";
    $qur = mysqli_query($conn,$query);
    if($qur){
      $returnData = array("status" => 1, "msg" => "Role deleted!");
    }else{
      $returnData = array("status" => 0, "msg" => "Error delete role!");
    }
  }else {
    $returnData = array("status" => 0, "msg" => "This role not exist!");
  }
  @mysqli_close($conn);
  return $returnData;
}
// Role API end
////////////////////////////////////////////////////////

// AcademyYear API start
////////////////////////////////////////////////////////
function createAcademyYear($year)
{
  global $conn;
  if (isNotExist($year,'year','academyyear')) {
    $query = "INSERT INTO `academyyear`(`year`) VALUES ('$year')";
    $qur = mysqli_query($conn,$query);
    if($qur){
      $returnData = array("status" => 1, "msg" => "New academy year created!");
    }else{
      $returnData = array("status" => 0, "msg" => "Error create academy year!");
    }
  }else {
    $returnData = array("status" => 0, "msg" => "This academy year existed!");
  }
  @mysqli_close($conn);
  return $returnData;
}

function getAllAcademyYear()
{
  global $conn;
  $query = "SELECT `year` FROM `academyyear` ORDER BY year";
  $q=mysqli_query($conn,$query);
  $lenght = mysqli_num_rows($q);
  if ($lenght!=0) {
    $data=array();
    while ($row=mysqli_fetch_object($q)){
      $data[]=$row;
    }
    $returnData = array("status" => 1, "data" => $data);
  }else {
    $returnData = array("status" => 0, "msg" => "Can not get academy year!");
  }

  @mysqli_close($conn);
  return $returnData;
}

function editAcademyYear($year,$newYear)
{
  global $conn;
  if (isExist($year,'year','academyyear')) {
    if (isExist($newYear,'year','academyyear')) {
      $returnData = array("status" => 0, "msg" => "This academy year is existed!");
    }else {
      $query = "UPDATE `academyyear` SET `year`='$newYear' WHERE `year` = '$year'";
      $qur = mysqli_query($conn,$query);
      if($qur){
        $returnData = array("status" => 1, "msg" => "Academy year edited!");
      }else{
        $returnData = array("status" => 0, "msg" => "Error edit academy year!");
      }
    }
  }else {
    $returnData = array("status" => 0, "msg" => "This academy year not exist!");
  }
  @mysqli_close($conn);
  return $returnData;
}

function deleteAcademyYear($year)
{
  global $conn;
  if (isExist($year,'year','academyyear')) {
    $query = "DELETE FROM `academyyear` WHERE year = '$year'";
    $qur = mysqli_query($conn,$query);
    if($qur){
      $returnData = array("status" => 1, "msg" => "Academy Year deleted!");
    }else{
      $returnData = array("status" => 0, "msg" => "Error delete academy year!");
    }
  }else {
    $returnData = array("status" => 0, "msg" => "This academy year not exist!");
  }
  @mysqli_close($conn);
  return $returnData;
}
// AcademyYear API end
////////////////////////////////////////////////////////

// Falculties API end
////////////////////////////////////////////////////////
function createFalcuties($falcutiesName)
{
  global $conn;
  if (isNotExist($falcutiesName,'falcuties_name','faculties')) {
    $query = "INSERT INTO `faculties`(`falcuties_name`) VALUES ('$falcutiesName')";
    $qur = mysqli_query($conn,$query);
    if($qur){
      $returnData = array("status" => 1, "msg" => "New faculties created!");
    }else{
      $returnData = array("status" => 0, "msg" => "Error create faculties!");
    }
  }else {
    $returnData = array("status" => 0, "msg" => "This faculties existed!");
  }
  @mysqli_close($conn);
  return $returnData;
}

function getAllFalcuties()
{
  global $conn;
  $query = "SELECT `falcuties_name` FROM `faculties` ORDER BY falcuties_name";
  $q=mysqli_query($conn,$query);
  $lenght = mysqli_num_rows($q);
  if ($lenght!=0) {
    $data=array();
    while ($row=mysqli_fetch_object($q)){
      $data[]=$row;
    }
    $returnData = array("status" => 1, "data" => $data);
  }else {
    $returnData = array("status" => 0, "msg" => "Can not get falcuties data!");
  }

  @mysqli_close($conn);
  return $returnData;
}

function editFalcuties($falcutiesName,$newFalcutiesName)
{
  global $conn;
  if (isExist($falcutiesName,'falcuties_name','faculties')) {
    if (isExist($newFalcutiesName,'falcuties_name','faculties')) {
      $returnData = array("status" => 0, "msg" => "This faculties is existed!");
    }else {
      $query = "UPDATE `faculties` SET `falcuties_name`='$newFalcutiesName' WHERE `falcuties_name` = '$falcutiesName'";
      $qur = mysqli_query($conn,$query);
      if($qur){
        $returnData = array("status" => 1, "msg" => "Faculties edited!");
      }else{
        $returnData = array("status" => 0, "msg" => "Error edit faculties!");
      }
    }
  }else {
    $returnData = array("status" => 0, "msg" => "This faculties not exist!");
  }
  @mysqli_close($conn);
  return $returnData;
}

function deleteFalcuties($falcutiesName)
{
  global $conn;
  if (isExist($falcutiesName,'falcuties_name','faculties')) {
    $query = "DELETE FROM `faculties` WHERE falcuties_name = '$falcutiesName'";
    $qur = mysqli_query($conn,$query);
    if($qur){
      $returnData = array("status" => 1, "msg" => "Faculties deleted!");
    }else{
      $returnData = array("status" => 0, "msg" => "Error delete faculties!");
    }
  }else {
    $returnData = array("status" => 0, "msg" => "This faculties not exist!");
  }
  @mysqli_close($conn);
  return $returnData;
}
// Falculties API end
////////////////////////////////////////////////////////
?>
