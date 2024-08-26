<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            margin: 0;
        }

        #main-post {
            width: 40%;
            height: 100vh;
            border-left: 1px solid black;
            border-right: 1px solid black;
            padding: 10px;
            /*margin-left: auto;
            margin-right: auto;*/
        }

        .all-banner-posts {
            display: flex;
            justify-content: center;
        }

        .all-banner-posts .banner-right {
            padding: 10px;
            width: 20%;
            display: grid;
            margin-left: 10%;
            position: fixed; 
            top:0px; 
            left: 0%; 
            z-index:1;
        }
    </style>
</head>

<body>
    <div class="all-banner-posts">
        <section class="banner-right">
            <h2>SOCIAL YASSINE</h2>
            <a href="./index.php">HOME</a>
            <a href="./index.php">HOME</a>
            <a href="./index.php">HOME</a>
            <a href="./index.php">HOME</a>
            <a href="./index.php">HOME</a>
        </section>

        <section id="main-post">
            dsa
        </section>
    </div>

</body>

</html>