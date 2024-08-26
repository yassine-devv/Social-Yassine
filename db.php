<?php 
$hostname = "localhost";
$username = "root";
$password = "";
$db = "social_yassine";

$conn = mysqli_connect($hostname, $username, $password, $db);

if(mysqli_error($conn)){
    die("Problema con il database");
}

?>