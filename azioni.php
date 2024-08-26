<?php
session_start();
include('./db.php');


if (isset($_GET['like'])) {
    $idpost = substr($_GET['like'], 1);
    //echo $idpost;

    //prima bisogna fare una select se c'è bisogno di togliere o aggiungere like

    $sql = "SELECT * FROM `likes` where id_utente=" . $_SESSION['iduser'] . " and id_post=" . $idpost;
    $control = $conn->query($sql);


    if ($control->num_rows == 0) { //il like non ce quindi bisogna fare un insert
        $sql = "INSERT INTO `likes`(`id_utente`, `id_post`) VALUES ('" . $_SESSION['iduser'] . "', '$idpost')";
        $ris = $conn->query($sql);

        getFormaAzioni($idpost);
    } else {
        $sql = "delete from likes  where id_utente=" . $_SESSION['iduser'] . " and id_post=" . $idpost;
        $ris = $conn->query($sql);

        getFormaAzioni($idpost);
    }
}

if (isset($_GET['save'])) {
    $idpost = substr($_GET['save'], 1);

    $sql = "SELECT * FROM `saved` where id_utente=" . $_SESSION['iduser'] . " and id_post=" . $idpost;
    $control = $conn->query($sql);


    if ($control->num_rows == 0) { //il like non ce quindi bisogna fare un insert
        $sql = "INSERT INTO `saved`(`id_utente`, `id_post`) VALUES ('" . $_SESSION['iduser'] . "', '$idpost')";
        $ris = $conn->query($sql);

        $sql = "select * from saved";
        $saved = $conn->query($sql);

        $rissaved = false;

        $button = "";


        if ($saved->num_rows > 0) {
            while ($rowsaved = $saved->fetch_assoc()) {
                if ($rowsaved['id_utente'] == $_SESSION['iduser'] && $rowsaved['id_post'] == $idpost) {
                    $rissaved = true;
                }
            }
            if ($rissaved) {
                $button = "<button class=\"btnSave\" onclick=\"azioni('save', 'p" . $idpost . "')\">";
                $button .= '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="blue" class="bi bi-bookmark-fill" viewBox="0 0 16 16">';
                $button .= '<path d="M2 2v13.5a.5.5 0 0 0 .74.439L8 13.069l5.26 2.87A.5.5 0 0 0 14 15.5V2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2"/></svg>';
                $button .= '</button>';
                echo $button;
                //break;
            } else {
                $button = "<button class=\"btnSave\" onclick=\"azioni('save', 'p" . $idpost . "')\">";
                $button .= '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmark" viewBox="0 0 16 16">';
                $button .= '  <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z"/></svg>';
                $button .= '</button>';
                echo $button;
                //break;
            }

            $rissaved = false;
        }
    } else {
        $sql = "delete from saved where id_utente=" . $_SESSION['iduser'] . " and id_post=" . $idpost;
        $ris = $conn->query($sql);

        $button = "<button class=\"btnSave\" onclick=\"azioni('save', 'p" . $idpost . "')\">";
        $button .= '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmark" viewBox="0 0 16 16">';
        $button .= '  <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z"/></svg>';
        $button .= '</button>';
        echo $button;
    }
}

if (isset($_GET['delete'])) {
    $idpost = substr($_GET['delete'], 1);
    echo $idpost;

    $sql = "DELETE FROM posts WHERE id_post=" . $idpost;

    $ris = $conn->query($sql);


    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    //header("Location: profile.php");
}


if (isset($_GET['view-post'])) {
    $post = $_GET['view-post'];

    switch ($post) {
        case "post-pubb":
            getPostPubb();
            break;
        case "post-liked":
            getPostLikedSaved("liked");
            break;
        case "post-saved":
            getPostLikedSaved("saved");
            break;
    }
}


function getFormaAzioni($idpost)
{
    include('./db.php');

    $sql = "SELECT COUNT(likes.id_post) as likes, posts.id_post as idpost FROM posts left join likes on posts.id_post=likes.id_post group by posts.id_post having idpost=" . $idpost;
    $countlikes = $conn->query($sql);

    $nlikes = 0;
    //$idpost = 0;

    if ($countlikes->num_rows > 0) {
        while ($row = $countlikes->fetch_assoc()) {
            $nlikes = $row['likes'];

            $sql = "select * from likes";
            $likes = $conn->query($sql);

            $rislikes = false;

            $button = "";


            if ($likes->num_rows > 0) {
                while ($rowlikes = $likes->fetch_assoc()) {
                    if ($rowlikes['id_utente'] == $_SESSION['iduser'] && $rowlikes['id_post'] == $idpost) {
                        $rislikes = true;
                    }
                }
                if ($rislikes) {
                    $button = "<button class=\"btnlike\" onclick=\"azioni('like', 'p" . $idpost . "')\">";
                    $button .= '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" class="bi bi-heart-fill" viewBox="0 0 16 16">';
                    $button .= '    <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/></svg>';
                    $button .= '</button>';
                    echo $button;
                    break;
                } else {
                    $button = "<button class=\"btnlike\" onclick=\"azioni('like', 'p" . $idpost . "')\">";
                    $button .= '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">';
                    $button .= '<path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/></svg>';
                    $button .= '</button>';
                    echo $button;
                    break;
                }

                $rislikes = false;
            }
        }
    }

    //echo $idpostt;

    ?>

    <span class="nlikes">
        <?= $nlikes ?>
    </span>
    <?php
}

function getPostPubb()
{
    include('./db.php');
    
    $sql = "SELECT * FROM posts join utenti on posts.id_utente=utenti.id_user where id_utente=" . $_SESSION['iduser'] . "";
    $ris = $conn->query($sql);
    
    if ($ris->num_rows > 0) {
        while ($row = $ris->fetch_assoc()) {
            ?>
            <div style="width:50%; padding: 10px; border: 1px solid black" class="post">
                <b><span>Descrizione:</span></b>
                <p>
                    <?= $row['descrizione'] ?>
                </p><br>
                
                <b><span>Creato il:</span></b>
                <p>
                    <?= $row['created_at'] ?>
                </p>
                <?php
                echo '<a href="azioni.php?delete=' . $row["id_post"] . '">delete</a>';
                ?>
                
            </div>
            <?php
        }
    } else {
        echo '<span>Nessun post pubblicato</span>';
    }
}

function getPostLikedSaved($type)
{
    include('./db.php');
    if($type=="liked"){
        $sql = "SELECT posts.created_at, posts.descrizione as descrizione, posts.id_post as idpost, utenti.username as username, utenti.id_user as iduser FROM likes join posts on likes.id_post=posts.id_post join utenti on posts.id_utente=utenti.id_user where likes.id_utente=".$_SESSION['iduser'];
    }else{
        $sql = "SELECT posts.created_at, posts.descrizione as descrizione, posts.id_post as idpost, utenti.username as username, utenti.id_user as iduser FROM saved join posts on saved.id_post=posts.id_post join utenti on posts.id_utente=utenti.id_user where saved.id_utente=".$_SESSION['iduser'];
    }

    $ris = $conn->query($sql);


    if ($ris->num_rows > 0) {
        while ($row = $ris->fetch_assoc()) {
        ?>
            <div style="width:50%; padding: 10px; border: 1px solid black" class="post">
                <span><?= $row['username'] ?></span><br><br>
                <b><span>Descrizione:</span></b>
                <p>
                    <?= $row['descrizione'] ?>
                </p><br>

                <b><span>Creato il:</span></b>
                <p>
                    <?= $row['created_at'] ?>
                </p>
            </div>
<?php
        }
    } else {
        if($type=="liked"){
            echo '<span>Nessun post che ti piace</span>';
        }else{
            echo '<span>Nessun post salvato</span>';
        }
    }
}


$conn->close();
?>