<?php
session_start();
require '../db_connectie.php';
function addToCart($product_name, $quantity)
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_SESSION['cart'][$product_name])) {
        $_SESSION['cart'][$product_name] += $quantity;
    } else {
        $_SESSION['cart'][$product_name] = $quantity;
    }
}

function placeOrder($address)
{
    global $verbinding;

    if (!isset($_SESSION['user_id'])) {
        die("Je moet ingelogd zijn om een bestelling te plaatsen.");
    }

    $username = $_SESSION['user_id'];

    // Orders inserten
    $sql = "INSERT INTO Pizza_Order (client_username, client_name, personnel_username, datetime, status, address) 
            VALUES (?, (SELECT first_name + ' ' + last_name FROM [User] WHERE username = ?), 
            (SELECT TOP 1 username FROM [User] ORDER BY NEWID()), 
            GETDATE(), 0, ?)";

    $query = $verbinding->prepare($sql);
    $query->execute([$username, $username, $address]);
    $order_id = $verbinding->lastInsertId();

    // Ordered producten inserten
    foreach ($_SESSION['cart'] as $product => $quantity) {
        $sql = "INSERT INTO Pizza_Order_Product (order_id, product_name, quantity) VALUES (?, ?, ?)";
        $query = $verbinding->prepare($sql);
        $query->execute([$order_id, $product, $quantity]);
    }
    unset($_SESSION['cart']);
    return true;
}
