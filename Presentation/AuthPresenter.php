<?php
class AuthPresenter {
    private $users = [
        ["id" => 1, "username" => "enzo", "password" => "1234"],
        ["id" => 2, "username" => "axel", "password" => "1234"],
        ["id" => 3, "username" => "antoine", "password" => "1234"],
        ["id" => 4, "username" => "lucas", "password" => "1234"],
        ["id" => 5, "username" => "arthus", "password" => "1234"],


    ];

    public function login($username, $password) {
        foreach ($this->users as $user) {
            if ($user["username"] === $username && $user["password"] === $password) {
                // Session pour la sécurité
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];

                setcookie("username", $user["username"], time() + 3600, "/");

                header("Location: dashboard.php");
                exit;
            }
        }
        return "Identifiants incorrects.";
    }
}
