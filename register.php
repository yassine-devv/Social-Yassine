<?php
$hostname = "localhost";
$username = "root";
$password = "";
$db = "social_yassine";

$conn = mysqli_connect($hostname, $username, $password, $db);

if(mysqli_error($conn)){
    die("Problema con il database");
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $passhashed = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO `utenti`(`username`, `password`, `email`) VALUES ('$username', '$passhashed', '$email')";
    $ris = $conn->query($sql);

    if($ris){
        header('location: login.php');
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
    <title>Registrati</title>
</head>
<body>
<h1>REGISTRATI</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>email:</label>
        <input type="email" name="email"><br><br>
        <label>Passoword:</label>
        <input type="password" name="password"><br><br>

        <input type="submit" value="REGISTRATI">
    </form>
</body>
</html>

<?php $conn->close() ?>