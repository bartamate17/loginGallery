<?php
require_once "./dbconfig.php";
session_start();

$errorCheck = -1;

function pageRedirect($page)
{
    if (!@header("Location: " . $page))
        echo "\n<script type=\"text/javascript\">window.location.replace('$page');</script>\n";
    exit;
}

if (isset($_SESSION["success"]) && $_SESSION["success"] == 1) {
?>
    <div class="modal show" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal="true" role="dialog" style="display: block;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="exampleModalLabel">Helyzet jelentés</h5>
                    <button type="button" id="closeError" class="close" aria-label="Close" onclick="closeModal()">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Sikeres regisztráció!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Bezár</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show" id="backdrop" style="display: block;"></div>

<?php
    unset($_SESSION["success"]);
}

$error = 0;
$queryNumber = 0;

//QUERY NUMBER - DATABASE
$con = mysqli_connect($host, $username, $password, $dbname);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

$sql = "SELECT COUNT(*)as QueryUser FROM userslogin";
$result = mysqli_query($con, $sql);

$row = mysqli_fetch_assoc($result);
$QueryUser = $row["QueryUser"];

mysqli_free_result($result);

mysqli_close($con);

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
    <title>Bejelentkezés fül</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="formDiv">
        <div id="titleHeader">
            <h1>Bejelenetkezés:</h1>
        </div>
        <hr>
        <form method="POST">
            <label for="email">Felhasználónév:</label>
            <input type="email" name="email" id="email" placeholder="valami@gmail.com" required>
            <label for="password">Jelszó:</label>
            <input type="password" name="password" id="password" placeholder="Példajelszavam" required>
            <a href="register.php" class="registration_link">Regisztráció</a>
            <!-- <a href="" class="forgot_link">Elfelejtettem a jelszavam</a> -->
            <?php

            function findUserByEmail($usersResult, $emailClient, $passwordClient)
            {
                while ($row = mysqli_fetch_assoc($usersResult)) {
                    if ($row["email"] == $emailClient) {
                        if ($row["password"] == $passwordClient) {
                            return "";
                        } else {
                            return "Hibás jelszó!";
                        }
                    }
                }
                return "Hibás felhasználónév!";
            };

            if (isset($_POST['password'])) {
                $error = 0;

                $filteredEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $hashPass = hash("sha256", $_POST['password']);

                $con = mysqli_connect($host, $username, $password, $dbname);
                if (mysqli_connect_errno()) {
                    echo "Failed to connect to MySQL: " . mysqli_connect_error();
                    exit();
                }

                $sql = "SELECT email,password,id FROM userslogin ORDER BY email";
                $result = mysqli_query($con, $sql);

                // Associative array

                $error = findUserByEmail($result, $filteredEmail, $hashPass);

                if (strlen($error) == 0) {
                    $_SESSION["userId"] = uniqid();
                    pageRedirect("index.php");
                } else {
            ?>
                    <div class="modal show" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal="true" role="dialog" style="display: block;">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-danger">
                                    <h5 class="modal-title" id="exampleModalLabel">Hiba!</h5>
                                    <button type="button" id="closeError" class="close" aria-label="Close" onclick="closeModal()">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <?php print($error); ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Bezár</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-backdrop fade show" id="backdrop" style="display: block;"></div>
            <?php
                }
                // Free result set
                mysqli_free_result($result);

                mysqli_close($con);
            }
            ?>
            <button id="submit" type="submit">Küldés</button>
        </form>
    </div>

    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="script.js"></script>
</body>

</html>