<?php
session_start();
include('./db.php');
include('./functions.php');

if (!isset($_SESSION['loggato'])) {
    header("location: login.php");
}

if (!isset($_GET['page'])) {
    header("location: profile.php?page=user_data");
}

$data = request_data_profile($_SESSION['iduser']);

$extimg = array("png", "jpg", "jpeg");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //echo $data["username"];
    //echo $_POST["username"];
    if (isset($_POST['modifica-profilo'])) {
        //controllo se i dati sono uguali, se sono diversi quindi cambiati li modifica nel db
        if ($_POST['username'] !== $data['username']) {
            $sql = "UPDATE `utenti` SET `username`='" . $_POST['username'] . "' WHERE id_user=" . $_SESSION['iduser'] . "";
            $ris = $conn->query($sql);

            header("location: login.php");
        }

        if ($_POST['email'] !== $data['email']) {
            $sql = "UPDATE `utenti` SET `email`='" . $_POST['email'] . "' WHERE id_user=" . $_SESSION['iduser'] . "";
            $ris = $conn->query($sql);

            //header("location: login.php");
        }

        //images
        $target_dir = "/uploads/picture-profile/";
        $target_file = $target_dir . basename($_FILES["new-img"]["name"]);
        $uploadOk = true;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $imgname = $_SESSION['iduser'] . "." . $imageFileType;

        $check = getimagesize($_FILES["new-img"]["tmp_name"]);

        if ($check == false) {
            echo "File is not an image.";
            $uploadOk = false;
        }

        if (!in_array($imageFileType, $extimg)) {
            $uploadOk = false;
        }

        if (!$uploadOk) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["new-img"]["tmp_name"], "." . $target_dir . $imgname)) {
                $sql = "UPDATE `utenti` SET `image_path`='" . $target_dir . $imgname . "' WHERE id_user=" . $_SESSION['iduser'] . "";
                $ris = $conn->query($sql);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
}

$data = request_data_profile($_SESSION['iduser']);

$pagesget = [
    "user_data" => [
        "label" => "Dati personali",
        "icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" /></svg>'
    ],
    "post_pubb" => [
        "label" => "Post pubblicati",
        "icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-grid-3x3" viewBox="0 0 16 16"><path d="M0 1.5A1.5 1.5 0 0 1 1.5 0h13A1.5 1.5 0 0 1 16 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5zM1.5 1a.5.5 0 0 0-.5.5V5h4V1zM5 6H1v4h4zm1 4h4V6H6zm-1 1H1v3.5a.5.5 0 0 0 .5.5H5zm1 0v4h4v-4zm5 0v4h3.5a.5.5 0 0 0 .5-.5V11zm0-1h4V6h-4zm0-5h4V1.5a.5.5 0 0 0-.5-.5H11zm-1 0V1H6v4z" /></svg>'
    ],
    "post_liked" => [
        "label" => "Post piaciuti",
        "icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16"><path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15" /></svg>'
    ],
    "post_saved" => [
        "label" => "Post salvati",
        "icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bookmark" viewBox="0 0 16 16"><path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z" /></svg>',
    ],
];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./style/profile.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js">
    </script>
</head>

<body>

    <div class="sec-profile-view-settings">
        <div class="nav-right">
            <!--<b><a href="/">Social Yassine</a></b>-->
            <b>
                <h1>Profilo</h1>
            </b>

            <div class="links-profile">
                <?php
                foreach ($pagesget as $key => $value) {
                    $link = "<div>";
                    $link .= $value['icon'];
                    if ($_GET['page'] == $key) {
                        $link .= '<a class="active" href="profile.php?page=' . $key . '">' . $value['label'] . '</a>';
                    } else {
                        $link .= '<a href="profile.php?page=' . $key . '">' . $value['label'] . '</a>';
                    }
                    $link .= "</div>";
                    echo $link;
                }
                ?>
            </div>

            <button class="btn-loggout">
                <a href="./loggout.php">Esci</a>
            </button>
        </div>

        <div class="view-settings">
            <?php
            switch ($_GET['page']) {
                case "user_data":
                    ?>
                    <span class="title">Account</span><br><br>
                    <span>Qua trovi tutti i tuoi dati presonali.</span>

                    <hr class="line">

                    <div class="container pic-fullname">

                        <div class="row">

                            <div class="col-8 img-txt-prof">
                                <img src='<?= "." . $data["image_path"] ?>' class="img-profile">


                                <div class="col">
                                    <span>Immagine profilo</span><br>
                                    <span
                                        style="margin-top: 60px; font-size: medium; color: grey"><?= strtoupper(implode(', ', $extimg)) . " sotto 15MB" ?></span>
                                </div>
                            </div>

                            <div class="col-4 btn-mod-pic">
                                <form name="frm" action="a3 - Copy.php" method="post" enctype="multipart/form-data">
                                    <input type="submit" name="submitFile" style="display:none" id="submit">
                                    <input type="file" id="upfile" name="uploadFile" onchange="document.getElementById('submit').click()">
                                </form>

                                <button class="button-2" role="button">Elimina</button>
                            </div>

                        </div>

                        <div class="row fullname">
                            <div class="col">
                                <label>Nome</label>
                                <input type="text">
                            </div>
                            <div class="col">
                                <label>Cognome</label>
                                <input type="text">
                            </div>
                        </div>

                        <hr class="line">

                        <?php ?>
                    </div>
                    <?php
                    break;
                case "post_pubb":
                    ?>
                    <span>post pubblicati</span>
                    <?php
                    break;
                case "post_liked":
                    ?>
                    <span>post piaciuti</span>
                    <?php
                    break;
                case "post_saved":
                    ?>
                    <span>post salvati</span>
                    <?php
                    break;
                default:
                    $html = "ciao";
            }
            ?>
        </div>

        <!--
        <section class="banner-right">
            <span class="title-name">SOCIAL YASSINE</span>
    
            <?php
            foreach ($pages as $nome => $listato) {
                $link = "<a class=\"link\" href=\"" . $listato . "\">";
                $link .= $iconslinks[$nome];
                if (explode("/", $_SERVER['PHP_SELF'])[2] == $listato) {
                    $link .= "<b><span>" . $nome . "</span></b>";
                } else {

                    $link .= "<span>" . $nome . "</span>";
                }

                $link .= "</a>";

                echo $link;
            }

            ?>
        </section>-->
        <!--
        <section>
            <h1>PROFILO</h1>
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
                <label>Username:</label>
                <input type="text" name="username" value="<?= $data['username'] ?>"><br><br>
                <label>Email:</label>
                <input type="text" name="email" value="<?= $data['email'] ?>"><br><br>
    
                <label>Modifica immagine di profilo:</label> <br>
                <img class="img-profile" src="<?= "." . $data["image_path"] ?>" alt="immagine profilo">
                <input type="file" name="new-img" value="<?= "." . $data["image_path"] ?>">
    
                <br><br>
    
                <input type="submit" name="modifica-profilo" value="MODIFICA PROFILO">
            </form>
    
            <br><br>
            <a href="loggout.php">
                <button>ESCI</button>
            </a>
            <button onclick="changePass()">Cambia password</button>
        </section>
        <br>
    
        <section class="sec-view">
            <div class="sec-labels">
                <button id="btn-post-pubb" onclick="view('post-pubb')">POST PUBBLICATI</button>
                <button id="btn-post-liked" onclick="view('post-liked')">POST PIACIUTI</button>
                <button id="btn-post-saved" onclick="view('post-saved')">POST SALVATI</button>
            </div>
    
            <div class="view-body" onload="view('post-pubb')"></div>
        </section>-->
    </div>


    <script src="./script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>