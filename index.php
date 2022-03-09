<?php
session_start();

if (isset($_SESSION["userId"])) {
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
        <title>Tárold el emlékeidet az utókornak! - KÉPGALÉRIA &#169</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <div class="formDiv">
            <div id="headerTitle">
                <h2>Töltse fel az Ön álltal kiválasztott file-ját!</h2>
            </div>
            <form method="POST" enctype="multipart/form-data" action="savedata.php">
                <hr>
                <label class="labelElement" for="pictureUpload">Kérem töltsön fel egy jpg file-t!<br><span style="margin:8px; display: block">(A file ne haladja meg az 500 kilobyte-ot!)</span></label>
                <hr>
                <input id="picUpload" type="file" name="pictureUpload">
                <hr>
                <label class="labelElement" for="nameUpload">Adjon kép aláírást!</label>
                <hr>
                <input class="formElement widthLong" type="text" name="nameUpload" id="nameUpload">
                <button id="submit" type="submit">Küldés</button>
                <a href="logout.php" class="registration_linkIndex">Kijelentkezés</a>
            </form>
        </div>
    </body>

<?php
} else {
    echo '<script>alert("Hiba a bejelentkezés során!")</script>';
    unset($_SESSION["userId"]);
    header("Location: login.php");
}
?>

    </html>