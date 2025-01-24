<?php
session_start();
require '../db_connectie.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['username'])) {
        $username = htmlspecialchars($_POST['username']);
    }
    if (isset($_POST['password'])) {
        $password = htmlspecialchars($_POST['password']);
    }

    try {
        $sql = "SELECT * FROM [User] WHERE username = ?";
        $query = $verbinding->prepare($sql);
        $query->execute([$username]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            // echo "Ongeldige gebruikersnaam of wachtwoord!";
            header("Location: ../presentatielaag/login.php");
        } else {
            $_SESSION['user_id'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: ../presentatielaag/index.php");
        }
    } catch (PDOException $ex) {
        die("Fout bij inloggen: " . $ex->getMessage());
    }
}
