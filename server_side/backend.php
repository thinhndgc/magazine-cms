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
  $query = "SELECT user.uid, role.role_name FROM `user` INNER JOIN user_role ON user.uid = user_role.uid INNER JOIN role ON user_role.rid = role.rid WHERE user.email = '$email' AND password = '$md5password'";
  $result = mysqli_query($conn,$query);
  $check= mysqli_num_rows($result);
  if ($check!=0) {
    $row=mysqli_fetch_assoc($result);
    $role = $row['role_name'];
    if ($role == 'MC') {
      // $falculties = getFalcutiesByEmail($email);
      $returnData = array("status" => 1, "msg" => "Login success!", "role" => $role, );
    }else {
      $returnData = array("status" => 1, "msg" => "Login success!", "role" => $role);
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

function revertAccount($email)
{
  global $conn;
  $query = "DELETE FROM `user` WHERE email = '$email'";
  $result=mysqli_query($conn,$query);
  @mysqli_close($conn);
}

function getAllAccount($value='')
{
  # code...
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
  $query = "SELECT `year` FROM `academyyear`";
  $q=mysqli_query($conn,$query);
  $lenght = mysqli_num_rows($q);
  $returnData=array();
  while ($row=mysqli_fetch_object($q)){
    $returnData[]=$row;
  }
  @mysqli_close($conn);
  return $returnData;
}

function editAcademyYear($year,$newYear)
{
  global $conn;
  if (isExist($year,'year','academyyear')) {
    $query = "UPDATE `academyyear` SET `year`='$newYear' WHERE `year` = '$year'";
    $qur = mysqli_query($conn,$query);
    if($qur){
      $returnData = array("status" => 1, "msg" => "Academy year edited!");
    }else{
      $returnData = array("status" => 0, "msg" => "Error edit academy year!");
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
?>
