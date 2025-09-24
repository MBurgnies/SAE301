<?php
session_start();
require_once "Presentation/AuthPresenter.php";

$auth = new AuthPresenter();

$error = null;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $error = $auth->login($_POST["username"], $_POST["password"]);
}

include "Vue/loginView.php";
