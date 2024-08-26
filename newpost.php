<?php
session_start();
include('./db.php');

if(!isset($_SESSION['loggato'])){
    header("location: login.php");
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $descrizione = $_POST['descrizione'];

    $sql = "INSERT INTO `posts`(`id_utente`, `descrizione`, `created_at`) VALUES ('".$_SESSION['iduser']."', '$descrizione', '".date('Y-m-d h:i:s')."')";
    $ris = $conn->query($sql);

    if($ris){
        header('location: profile.php');
    }else{
        echo "problema con il database riprova piu tardi";
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crea post</title>
</head>
<body>
    <h1>CREA POST</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
        <label>Descrzione:</label><br><br>
        <textarea name="descrizione"  cols="30" rows="10"></textarea><br><br>

        <input type="submit" value="CREA">
    </form>
</body>
</html>

<?php $conn->close() ?>