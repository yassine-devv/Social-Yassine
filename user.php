<?php 
session_start();
include("./db.php");

if(isset($_GET['id'])){
    $iduser = $_GET['id'];
}else{
    header("location: index.php");
}


/*
$sql = "SELECT * from utenti where id_user=".$iduser;

$ris = $conn->query($sql);

if($ris->num_rows !== 0){
    while($row = $ris->fetch_assoc()){
        echo $row["id_user"]."<br>";
        echo $row["username"]."<br>";
        echo $row["email"];
    }
}else{
    header("location: index.php");
}*/


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>