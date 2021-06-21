<?php
header('Access-Control-Allow-Origin: *');
$target_path = "usersimages/";
 
$target_path = $target_path . basename( $_FILES['file']['name']);
if(file_exists($target_path)) {
    chmod($target_path,0755); //Change the file permissions if allowed
    unlink($target_path); //remove the file
} 
if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
    echo "Upload and move success";
} else {
echo $target_path;
    echo "There was an error uploading the file, please try again!";
}
?>