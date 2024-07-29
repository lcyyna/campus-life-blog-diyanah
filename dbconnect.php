<?php
$hostname = "localhost";
$user = "root";
$password = "";
$database = "dd";

$conn = mysqli_connect($hostname, $user, $password, $database);
if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}
echo "Connected Succesfully";
?>