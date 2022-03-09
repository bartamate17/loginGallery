<?php
require_once "./dbconfig.php";
session_start();

function pageRedirect($page)
{
    if (!@header("Location: " . $page))
        echo "\n<script type=\"text/javascript\">window.location.replace('$page');</script>\n";
    exit;
}
$optionArray = ["Vezérigazgató", "Alkalmazott", "Vezető", "Eseti megbízott"];
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Felhasználói fiókba regisztráció, adatbázis alapján.">
    <meta name="keywords" content="Register, SQL, PHP">
    <meta name="author" content="Barta Máté György">
    <title>Regisztrációs felület</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <section class="h-100 h-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-8 col-xl-6">
                    <div class="card rounded-3">
                        <img src="img/UI/pictureForm.jpg" class="w-100" style="border-top-left-radius: .3rem; border-top-right-radius: .3rem;" alt="Sample photo">
                        <div class="card-body p-4 p-md-5" style="background-color: #f7dd72;">
                            <div id="registerDescription">
                                <h3 class="mb-4 pb-2 pb-md-0 mb-md-5 px-md-2">Regisztrációs felület:</h3>
                                <p>Kérem adja meg a regisztrációhoz szükséges adatokat. Minden mezőt kötelező kitölteni. </p>
                                <p>A szerződési feltételek beleegyezésével pedig elfogadja adatai biztonságos tárolását nyilvántartásunkban.</p>
                            </div>
                            <form method="POST" class="px-md-2">
                                <div class="form-outline mb-4" style="margin: 0 auto;">
                                    <label class="form-label" for="name">Teljes név:</label>
                                    <input type="text" id="name" name="name" title="Jogn Doe" class="form3Example1q name form-control w-75" required>
                                    <label class="form-label" for="email">E-mail cím:</label>
                                    <input type="text" id="email" name="email" title="john.doe@yahoo.com" class="form3Example1q email form-control w-75" pattern=".+@globex\.com" required>
                                    <label class="form-label" for="passwordv1">Jelszó:</label>
                                    <input type="password" id="passwordv1" name="passwordv1" class="form3Example1q passwordv1 form-control w-75" required>
                                    <label class="form-label" for="passwordv2">Jelszó még egyszer:</label>
                                    <input type="password" id="passwordv2" name="passwordv2" class="form3Example1q passwordv2 form-control w-75" required>
                                    <label class="form-label" for="select">Válassza ki posztját:</label>
                                    <select id="selectPosition" name="selectPosition" class="select w-75" required>
                                        <option value="Vezérigazgató">Vezérigazgató</option>
                                        <option value="Alkalmazott">Alkalmazott</option>
                                        <option value="Vezető">Vezető</option>
                                        <option value="Eseti megbízott">Eseti megbízott</option>
                                    </select>
                                    <label for="checkbox">
                                        <input type="checkbox" id="checkbox" name="checkbox" value="understand" required>
                                        Az adatvédelmi szabályzatot <a href="https://www.ksh.hu/docs/szolgaltatasok/adatigenyles/ksh_adatvedelmi_szabalyzat_2020.pdf" class="secret_link">elfogadtam</a>.
                                    </label><br>
                                </div>
                                <div class="formDiv">
                                    <button id="submit" type="submit" style="margin-top: 0 !important;">Regisztrálok</button>
                                </div>
                            </form>
                        </div>
                        <a href="login.php" class="registration_link" style="font-size: 15px; text-align:center">Vissza</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    if (isset($_POST["name"]) && $_POST["name"] != "") {

        $_SESSION["success"] = 0;

        $name = $_POST["name"];
        $email = $_POST["email"];
        $passwordv1 = $_POST["passwordv1"];
        $passwordv2 = $_POST["passwordv2"];
        $postion = $_POST["selectPosition"];

        for ($i = 0; $i < count($optionArray); $i++) {
            if ($optionArray[$i] == $_POST["selectPosition"]) {

                if ($passwordv1 == $passwordv2 && $_SESSION["success"] != 1) {

                    $_SESSION["success"] = 1;

                    $passwordHashed = hash("sha256", $passwordv1);
                    $nameFiltered = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
                    $emailFiltered = filter_var($email, FILTER_SANITIZE_EMAIL);


                    if ($_SESSION["success"] == 1) {

                        $con = mysqli_connect($host, $username, $password, $dbname);

                        $sql = "INSERT INTO userslogin (name, email, password, position) VALUES (?,?,?,?)";
                        $stmt = $con->prepare($sql);
                        $stmt->bind_param("ssss", $nameFiltered, $emailFiltered, $passwordHashed, $optionArray[$i]);

                        if ($stmt->execute()) {
                            pageRedirect("login.php");
                            die();
                        }
                    }
                } else {
    ?>
                    <div class="modal show" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal="true" role="dialog" style="display: block;">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Hibaüzenet</h5>
                                    <button type="button" class="close" aria-label="Close" onclick="closeModal()">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    A jelszavak nem megegyezőek!
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
            } else if ($_POST["name"] = "") {
                $_SESSION["success"] = 0;
                ?>
                <div class="modal show" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal="true" role="dialog" style="display: block;">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Hibaüzenet</h5>
                                <button type="button" class="close" aria-label="Close" onclick="closeModal()">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Hibás kitöltés!
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
        }
    }
    ?>
    
    <script src="script.js"></script>
</body>

</html>