<?php
session_start();
include('./db.php');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM utenti where username='$username'";
    $ris = $conn->query($sql);

    if ($ris->num_rows > 0) {
        while($row = $ris->fetch_assoc()){
            if(password_verify($password, $row['password'])){
                $_SESSION['loggato'] = 'ciao';
                $_SESSION['username'] = $row['username'];
                $_SESSION['iduser'] = $row['id_user'];
                header('location: profile.php');
            }else{
                $err = "Username o password non corretti";
            }
        }
    } else {
        $err = "Username o password non validi";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
<h1>LOGIN</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        <label>Passoword:</label>
        <input type="password" name="password"><br><br>

        <span style="color: red">
            <?php 
                if(isset($err)){
                    echo $err."<br><br>";
                }
            ?>
        </span>

        <input type="submit" value="ACCEDI ">
    </form>
</body>

</html>

<?php $conn->close() ?>