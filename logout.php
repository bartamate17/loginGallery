<?php
session_start();

unset($_SESSION["userId"]);
header("Locate: login.php");
session_destroy();
header('Location: login.php');
exit;
