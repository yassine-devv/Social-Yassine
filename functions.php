<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();

include('./db.php');

$pages = [
        "Home" => "index.php",
        "Profile" => "profile.php",
        "Cerca" => "cerca.php",
        "Posta" => "newpost.php",
        "Direct" => "direct.php",
    ];

$iconslinks = [
        "Home" => '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16"><path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5" /></svg>',
        "Profile" => '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16"><path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" /><path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" /></svg>',
        "Cerca" => '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" /></svg>',
        "Posta" => '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" /><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" /></svg>',
        "Direct" => '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16"><path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z" /></svg>'
    ];

function getFormaAzioni(){
    include('./db.php');
    $sql = "SELECT COUNT(likes.id_post) as likes, posts.id_post as idpost FROM posts left join likes on posts.id_post=likes.id_post group by posts.id_post";
        $countlikes = $conn->query($sql);

        $nlikes = 0;

        if ($countlikes->num_rows > 0) {
            while ($row = $countlikes->fetch_assoc()) {
                $nlikes = $row['likes'];

                $sql = "select * from likes";
                $likes = $conn->query($sql);

                $rislikes = false;

                $button="";


                if ($likes->num_rows > 0) {
                    while ($rowlikes = $likes->fetch_assoc()) {
                        if ($rowlikes['id_utente'] == $_SESSION['iduser'] && $rowlikes['id_post'] == $idpost) {
                            $rislikes = true;
                        }
                    }
                    if ($rislikes) {
                        $button = "<button class=\"btnlike\" onclick=\"azioni('like', " . $row['idpost'] . ")\">";
                        $button .= '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">';
                        $button .= '    <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/></svg>';
                        $button .= '</button>';
                        echo $button;
                        break;
                    }else{
                        $button = "<button class=\"btnlike\" onclick=\"azioni('like', " . $idpost . ")\">";
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
}

function request_data_profile($id){
    include('./db.php');
    $sql = "SELECT * FROM utenti where id_user=" . $id . "";
    $ris = $conn->query($sql);

    $data = array();

    if ($ris->num_rows > 0) {
        while ($row = $ris->fetch_assoc()) {
            $data['username'] = $row['username'];
            $data['email'] = $row['email'];
            $data['password'] = $row['password'];
            $data['image_path'] = $row['image_path'];
        }
    } else {
        header("location: login.php");
    }

    return $data;
}

if(isset($_POST['mod-file'])){
    var_dump($_POST['mod-file']);
}

?>