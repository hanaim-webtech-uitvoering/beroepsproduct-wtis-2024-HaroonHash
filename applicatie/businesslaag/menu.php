<?php
require '../db_connectie.php';

function getProducts()
{
    global $verbinding;
    $sql = "SELECT * FROM Product";
    $query = $verbinding->prepare($sql);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// function getAllUsers()
// {
//     global $verbinding;
//     $sql = "SELECT * FROM [User]";
//     $query = $verbinding->prepare($sql);
//     $query->execute();
//     $users = $query->fetchAll(PDO::FETCH_ASSOC);
//     $output = "<table border='1'>";
//     $output .= "<tr><th>Gebruikersnaam</th><th>Voornaam</th><th>Achternaam</th><th>Adres</th><th>Wachtwoord</th></tr>";
//     foreach ($users as $user) {
//         $output .= "<tr>";
//         $output .= "<td>" . htmlspecialchars($user['username']) . "</td>";
//         $output .= "<td>" . htmlspecialchars($user['first_name']) . "</td>";
//         $output .= "<td>" . htmlspecialchars($user['last_name']) . "</td>";
//         $output .= "<td>" . htmlspecialchars($user['address']) . "</td>";
//         $output .= "<td>" . htmlspecialchars($user['password']) . "</td>";
//         $output .= "</tr>";
//     }
//     $output .= "</table>";
//     return $output;
// }
