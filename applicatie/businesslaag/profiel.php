<?php
require '../db_connectie.php';

function getUserOrders($username)
{
    global $verbinding;
    $sql = "SELECT * FROM Pizza_Order WHERE client_username = ?";
    $query = $verbinding->prepare($sql);
    $query->execute([$username]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getOrderProducts($order_id)
{
    global $verbinding;
    $sql = "SELECT product_name, quantity FROM Pizza_Order_Product WHERE order_id = ?";
    $query = $verbinding->prepare($sql);
    $query->execute([$order_id]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}
