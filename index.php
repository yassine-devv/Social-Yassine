<?php
session_start();
include('./db.php');
include('./functions.php');

if (!isset($_SESSION['iduser'])) {
    header("location: login.php");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $textpost = "";
    if (isset($_POST['descrizione-post'])) {
        $textpost = $_POST['descrizione-post'];
    }

    $sql = "INSERT INTO `posts`(`id_utente`, `descrizione`, `created_at`) VALUES ('" . $_SESSION['iduser'] . "', '" . $textpost . "', '" . date('Y-m-d h:i:s') . "')";
    $ris = $conn->query($sql);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="./style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">


    <style>
        .btnlike {
            border: none;
            background: none;
        }

        .btnSave {
            border: none;
            background: none;
        }

        /*
        .img-profile {
            border-radius: 50%;
            width: 50px;
            height: 50px;
        }*/
    </style>
</head>

<body>

    <div class="all-banner-posts">
       
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
        </section>

        <section class="main-post">
            <div class="create-post-home">
                <b>
                    <a style="text-decoration: none;" href="profile.php">
                        <img class="img-profile" src="<?= "." . request_data_profile($_SESSION['iduser'])["image_path"] ?>" alt="immagine-prof">
                        <?php echo request_data_profile($_SESSION['iduser'])["username"] ?>
                    </a>
                </b>

                <form id="form-newpost-home" style="margin-top: 10px;" class="form-create-post-home" method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">@</span>
                        </div>
                        <input type="text" name="descrizione-post" class="form-control" placeholder="A cosa stai pensando?" aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <button type="submit" class="btn btn-primary">Posta</button>
                </form>
            </div>

            <?php
            $sql = "SELECT TIMEDIFF(CURTIME(), TIME(posts.created_at)) as difftime, DATEDIFF(CURRENT_DATE(), DATE(posts.created_at)) AS diffdate, posts.created_at, COUNT(likes.id_post) as likes, posts.id_post as idpost, posts.descrizione as descrizione, utenti.image_path, utenti.username as username, utenti.id_user as iduser FROM utenti join posts on utenti.id_user=posts.id_utente left join likes on posts.id_post=likes.id_post group by posts.id_post ORDER BY posts.created_at DESC;";
            $allposts = $conn->query($sql);


            if ($allposts->num_rows > 0) {
                while ($row = $allposts->fetch_assoc()) {
            ?>
                    <div class="post" id="p<?= $row['idpost'] ?>">
                        <b>
                            <a id="link-user-page" href=<?php
                                                        if ($row['iduser'] == $_SESSION['iduser'])
                                                            echo "profile.php";
                                                        else
                                                            echo "'user.php?id=" . $row['iduser'] . "'";
                                                        ?>>
                                <img class="img-profile" src="<?= "." . $row["image_path"] ?>" alt="immagine-prof">
                                <?= $row['username'] ?>
                            </a>
                        </b>
                        <?php
                        if ($row['diffdate'] == '0') {
                            $htimepost = explode(":", $row['difftime'])[0]; //ore
                            $minpost = explode(":", $row['difftime'])[1]; //minuti

                            //echo $row['difftime'];

                            if(str_split($htimepost)[0] == "-"){
                                $htimepost = substr($htimepost, 1);
                            }

                            if(str_split($minpost)[0] == "-"){
                                $minpost = substr($minpost, 2);
                            }

                            if ($htimepost == "00") {
                                if($minpost== "00"){
                                    ?>
                                        <b><span class="date-post">Adesso</span></b>
                                    <?php
                                }else{
                                    ?>
                                        <b><span class="date-post"><?= $minpost ?> minuti fa</span></b>
                                    <?php
                                }
                            } else {
                                ?>
                                <b><span class="date-post"><?= $htimepost ?> ore fa</span></b>
                            <?php
                            }
                        } else { // il post non è stato pubblicato oggi
                            $daypost = explode("-", explode(" ", $row['created_at'])[0])[2]; //prendo il giorno
                            $monthpost = explode("-", explode(" ", $row['created_at'])[0])[1]; //prendo il mese
                            $yearpost = explode("-", explode(" ", $row['created_at'])[0])[0]; //prendo il year
                            $txtmonth = "";


                            switch ($monthpost) {
                                case "01":
                                    $txtmonth = "Gennaio";
                                    break;
                                case "02":
                                    $txtmonth = "Febbraio";
                                    break;
                                case "03":
                                    $txtmonth = "Marzo";
                                    break;
                                case "04":
                                    $txtmonth = "Aprile";
                                    break;
                                case "05":
                                    $txtmonth = "Maggio";
                                    break;
                                case "06":
                                    $txtmonth = "Giugno";
                                    break;
                                case "07":
                                    $txtmonth = "Luglio";
                                    break;
                                case "08":
                                    $txtmonth = "Agosto";
                                    break;
                                case "09":
                                    $txtmonth = "Settembre";
                                    break;
                                case "10":
                                    $txtmonth = "Ottobre";
                                    break;
                                case "11":
                                    $txtmonth = "Novembre";
                                    break;
                                case "12":
                                    $txtmonth = "Dicembre";
                                    break;
                            }
                            if ($yearpost == date("Y")) {
                            ?>
                                <b><span class="date-post"><?= $daypost . " " . $txtmonth ?></span></b>
                            <?php
                            } else {
                            ?>
                                <b><span class="date-post"><?= $daypost . " " . $txtmonth  . " " . $yearpost ?></span></b>
                        <?php
                            }
                        }

                        ?>

                        <br>

                        <p style="margin-top: 10px;">
                            <?= $row['descrizione'] ?>
                        </p>
                        <div class="btns">

                            <?php
                            $sql = "select * from likes";
                            $likes = $conn->query($sql);

                            $rislikes = false;


                            //if ($likes->num_rows > 0) {
                            while ($rowlikes = $likes->fetch_assoc()) {
                                if ($rowlikes['id_utente'] == $_SESSION['iduser'] && $rowlikes['id_post'] == $row['idpost']) {
                                    $rislikes = true;
                                }
                            }
                            if ($rislikes) {
                                $button = "<button class=\"btnlike\" onclick=\"azioni('like', 'p" . $row['idpost'] . "')\">";
                                $button .= '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" class="bi bi-heart-fill" viewBox="0 0 16 16">';
                                $button .= '    <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/></svg>';
                                $button .= '</button>';
                                echo $button;
                            } else {
                                $button = "<button class=\"btnlike\" onclick=\"azioni('like', 'p" . $row['idpost'] . "')\">";
                                $button .= '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">';
                                $button .= '<path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/></svg>';
                                $button .= '</button>';
                                echo $button;
                            }

                            $button = "";

                            $rislikes = false;

                            ?>

                            <span class="nlikes">
                                <?= $row['likes'] ?>
                            </span>

                            <?php

                            $sql = "select * from saved";
                            $saved = $conn->query($sql);

                            $rissaved = false;

                            while ($rowsaved = $saved->fetch_assoc()) {
                                if ($rowsaved['id_utente'] == $_SESSION['iduser'] && $rowsaved['id_post'] == $row['idpost']) {
                                    $rissaved = true;
                                }
                            }
                            if ($rissaved) {
                                $buttonsave = "<button class=\"btnSave\" onclick=\"azioni('save', 'p" . $row['idpost'] . "')\">";
                                $buttonsave .= '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="blue" class="bi bi-bookmark-fill" viewBox="0 0 16 16">';
                                $buttonsave .= '<path d="M2 2v13.5a.5.5 0 0 0 .74.439L8 13.069l5.26 2.87A.5.5 0 0 0 14 15.5V2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2"/></svg>';
                                $buttonsave .= '</button>';
                                echo $buttonsave;
                            } else {
                                $buttonsave = "<button class=\"btnSave\" onclick=\"azioni('save', 'p" . $row['idpost'] . "')\">";
                                $buttonsave .= '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmark" viewBox="0 0 16 16">';
                                $buttonsave .= '  <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z"/></svg>';
                                $buttonsave .= '</button>';
                                echo $buttonsave;
                            }

                            $buttonsave = "";

                            $rissaved = false;
                            ?>

                        </div>

                    </div>
            <?php
                }
            } else {
                echo '<span>Nessun post pubblicato</span>';
            }

            ?>
        </section>

        <div class="banner-bottom-mobile">
            <!--<span class="title-name">SOCIAL YASSINE</span>-->

            <?php
            foreach ($pages as $nome => $listato) {
                $link = "<a class=\"link\" href=\"" . $listato . "\">";
                $link .= $iconslinks[$nome];

                $link .= "</a>";

                echo $link;
            }

            ?>
        </div>

    </div>

    <script src="./script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>


</body>

</html>


<?php $conn->close() ?>