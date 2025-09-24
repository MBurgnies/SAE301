<?php
session_start();
require_once "Presentation/AbsencePresenter.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$presenter = new AbsencePresenter();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["justify"])) {
        $presenter->justify($_POST["absenceId"]);
    } elseif (isset($_POST["unlock"])) {
        $presenter->unlock($_POST["absenceId"]);
    }
    header("Location: dashboard.php");
    exit;
}


$username = isset($_SESSION["username"]) ? $_SESSION["username"] : (isset($_COOKIE["username"]) ? $_COOKIE["username"] : "Utilisateur");

$presenter->showDashboard($username);
