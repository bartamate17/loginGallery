<?php

function pageRedirect($page)
{
    if (!@header("Location: " . $page))
        echo "\n<script type=\"text/javascript\">window.location.replace('$page');</script>\n";
    exit;
}
?>
<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Felhasználói fiókba bejelentkezés, adatbázis alapján.">
    <meta name="keywords" content="Login, SQL, PHP">
    <meta name="author" content="Barta Máté György">
    <title>Tárold el emlékeidet az utókornak! - KÉPGALÉRIA &#169 - Böngésző</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    body {
        width: 90%;
        margin: 0 auto;
        background-color: grey;
    }

    div.container {
        margin: 5px;
        border: 1px solid #ccc;
        float: left;
        width: 180px;
    }

    div.container:hover {
        border: 1px solid #777;
    }

    div.container img {
        width: 100%;
        height: auto;
    }

    div.desc {
        padding: 15px;
        text-align: center;
        background-color: #FFA059;
    }

    #mainTitle {
        text-align: center;
        text-transform: uppercase;
        color: chocolate;
        text-shadow: 1px 1px 1px rgba(0, 0, 0, .6);
    }

    .container {
        position: relative;
        width: 50%;
    }

    .image {
        opacity: 1;
        display: block;
        width: 100%;
        height: auto;
        transition: .5s ease;
        backface-visibility: hidden;
    }

    div.container:hover {
        opacity: 1;
    }

    div.container {
        opacity: 0.6;
    }

    .text {
        color: white;
        font-size: 16px;
        padding: 16px 32px;
    }

    #backToStartButton {
        text-decoration: none;
        font-size: 20px;
        text-transform: uppercase;
        color: white;
        display: block;
    }

    #footerDiv {
        display: block;
        margin-top: 500px;
        text-align: center;
    }
</style>

<body>
    <h2 id="mainTitle">GALÉRIA!</h2>
    <?php
    if (isset($_POST["nameUpload"])) {
        if (isset($_FILES["pictureUpload"])) {
            if ($_FILES["pictureUpload"]["error"] === 0 && $_FILES["pictureUpload"]["type"] == "image/jpeg") {

                $pictureFrom = $_FILES["pictureUpload"]["tmp_name"];
                $pictureName = $_FILES["pictureUpload"]["name"];
                $pictureDescription = $_POST["nameUpload"];
                $pictureTo = "./img/" . $_FILES["pictureUpload"]["name"];
                copy($pictureFrom, $pictureTo);

                if ($_FILES["pictureUpload"]["size"] <= 500000) {

                    $fileOpen = fopen($pictureFrom, "r");
                    $fileRead = fread($fileOpen, 250);
                    fclose($fileOpen);
                    $validatePicture = strpos($fileRead, "JFIF");
                    if ($validatePicture !== false) {

                        $writeOpen = fopen("imgSource.txt", "a");
                        fwrite($writeOpen, $pictureTo . ';' . $pictureName . ";" . $pictureDescription . "\n");
                        fclose($writeOpen);

                        if (file_exists("imgSource.txt")) {

                            $fileOpen = fopen("imgSource.txt", "r");
                            $imgSourceArray = [];
                            if ($fileOpen) {
                                while (($buffer = fgets($fileOpen)) !== false) {
                                    //többdimenziós tömbb, beletesszük a 3 kritériumot
                                    $imgSourceArray[] = explode(";", $buffer);
                                }
                            }
                        }
                        fclose($fileOpen);

                        foreach ($imgSourceArray as $imageData) {
    ?>
                            <div class="container">
                                <a target="_blank" href="<?php print($imageData[0]) ?>">
                                    <img src="<?php print($imageData[0]) ?>" alt="<?php print($imageData[1]) ?>" width="600" height="400">
                                </a>
                                <div class="text"><?php print($imageData[2]) ?></div>
                                <div class="desc"><?php print($imageData[1]) ?></div>
                            </div>
    <?php
                        }
                    }
                } else {
                    echo '<script>alert("A file mérete meghaladja a 300 kilobyte-ot!")</script>';
                }
            } else {
                echo '<script>alert("A kép formátuma nem jpg!")</script>';
            }
        }
    }
    ?>
    <footer>
        <div id="footerDiv">
            <a id="backToStartButton" href="index.php">Vissza</a>
        </div>
    </footer>

</body>

</html>