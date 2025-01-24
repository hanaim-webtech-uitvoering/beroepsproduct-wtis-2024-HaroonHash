<?php
require 'db_connectie.php';

try {
    //Dit wordt gebruikt om de huidige wachtwoorden naar gehashde wachtwoorden te updaten
    $verbinding = maakVerbinding();
    $sql = "SELECT username, password FROM [User]";
    $query = $verbinding->prepare($sql);
    $query->execute();
    $users = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as $user) {
        $username = $user['username'];
        $password = $user['password'];

        if (password_needs_rehash($password, PASSWORD_DEFAULT)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $updateSql = "UPDATE [User] SET password = ? WHERE username = ?";
            $updateQuery = $verbinding->prepare($updateSql);
            $updateQuery->execute([$hashedPassword, $username]);

            echo "Wachtwoord voor gebruiker '$username' is geüpdatet.<br>";
        } else {
            echo "Wachtwoord voor gebruiker '$username' is al veilig gehasht.<br>";
        }
    }

    header("Location: ../presentatielaag/login.php");
} catch (PDOException $ex) {
    die("Fout bij het updaten van wachtwoorden: " . $ex->getMessage());
}
