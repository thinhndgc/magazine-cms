<?php

// Directory where uploaded images are saved
$dirname = "upload/";


// if ($_POST["uploadImage"]) {
//     $label = $_POST["uploadImage"];
// }
$label = "123";
$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png"))
&& ($_FILES["file"]["size"] < 200000)
&& in_array($extension, $allowedExts)) {
    if ($_FILES["file"]["error"] > 0) {
        $returnData = array("status" => 0, "msg" => "File error!");
    } else {
        $filename = $label.$_FILES["file"]["name"];
        // echo "Upload: " . $_FILES["file"]["name"] . "<br>";
        // echo "Type: " . $_FILES["file"]["type"] . "<br>";
        // echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
        // echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
        if (file_exists("file/image/".$filename)) {
            $returnData = array("status" => 0, "msg" => $filename."already exists!");
        } else {
            move_uploaded_file($_FILES["file"]["tmp_name"],
            "file/image" . $filename);
            $returnData = array("status" => 1, "msg" => "File uploaded!", "dir" => "file/image".$filename);
        }
    }
} else {
    $returnData = array("status" => 0, "msg" => "File type not allowed!");
}
header('Content-type: application/json');
echo json_encode($returnData);
?>
