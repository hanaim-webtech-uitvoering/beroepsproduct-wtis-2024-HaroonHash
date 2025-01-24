<?php
session_start();
require '../db_connectie.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $confirmpassword = htmlspecialchars($_POST['confirmpassword']);
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $address = htmlspecialchars($_POST['address']);
    $role = 'Client'; // Standaard rol voor klanten (voor nu geen manier om personeel te registreren)

    if ($password === $confirmpassword) {
        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

        try {
            $sql = "INSERT INTO [User] (username, password, first_name, last_name, address, role) VALUES (?, ?, ?, ?, ?, ?)";
            $query = $verbinding->prepare($sql);
            $query->execute([$username, $passwordHashed, $first_name, $last_name, $address, $role]);

            header("Location: ../presentatielaag/login.php");
        } catch (PDOException $ex) {
            die("Fout bij registratie: " . $ex->getMessage());
        }
    } else {
        // echo "Wachtwoorden komen niet overeen!";
        header("Location: ../presentatielaag/register.php");
    }
}
?>
