<?php
session_start();
require '../db_connectie.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = htmlspecialchars($_POST['order_id']);
    $status = htmlspecialchars($_POST['status']);
    updateOrder($order_id, $status);
}

function getOrders()
{
    global $verbinding;
    $sql = "SELECT * FROM [Pizza_Order]";
    $query = $verbinding->prepare($sql);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getOrderDetails($order_id)
{
    global $verbinding;
    $sql = "SELECT pop.product_name, pop.quantity, p.price
            FROM Pizza_Order_Product pop
            JOIN Product p ON pop.product_name = p.name
            WHERE pop.order_id = ?";

    $query = $verbinding->prepare($sql);
    $query->execute([$order_id]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getActiveOrders()
{
    global $verbinding;
    $sql = "SELECT * FROM [Pizza_Order] WHERE status != 3";
    $query = $verbinding->prepare($sql);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getOrdersForPersonnel($personnel_username, $activeOnly = false)
{
    global $verbinding;
    $sql = "SELECT * FROM [Pizza_Order] WHERE personnel_username = ?";

    if ($activeOnly) {
        $sql .= " AND status != 3";
    }

    $query = $verbinding->prepare($sql);
    $query->execute([$personnel_username]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function updateOrder($order_id, $status)
{
    global $verbinding;
    $sql = "UPDATE [Pizza_Order] SET status = ? WHERE order_id = ?";
    $query = $verbinding->prepare($sql);
    $query->execute([$status, $order_id]);
    header("Location: ../presentatielaag/bestellingoverzicht.php");
}

function getOrderTable($activeOnly = false, $personnelOnly = false)
{
    global $_SESSION;

    $queryParams = [];
    if ($activeOnly) {
        $queryParams['active'] = 1;
    }
    if ($personnelOnly) {
        $queryParams['personnel'] = 1;
    }

    $orders = $activeOnly ? getActiveOrders() : getOrders();

    if ($personnelOnly && isset($_SESSION['user_id'])) {
        $orders = getOrdersForPersonnel($_SESSION['user_id'], $activeOnly);
    }

    $html = '<h2>Bestellingen Overzicht</h2>';

    $queryWithoutActive = http_build_query(array_diff_key($queryParams, ['active' => 1]));
    $queryWithoutPersonnel = http_build_query(array_diff_key($queryParams, ['personnel' => 1]));

    $toggleActiveLink = $activeOnly ? "bestellingoverzicht.php?$queryWithoutActive" : "bestellingoverzicht.php?" . http_build_query(array_merge($queryParams, ['active' => 1]));
    $togglePersonnelLink = $personnelOnly ? "bestellingoverzicht.php?$queryWithoutPersonnel" : "bestellingoverzicht.php?" . http_build_query(array_merge($queryParams, ['personnel' => 1]));

    $toggleActiveText = $activeOnly ? "Toon alle bestellingen" : "Toon alleen actieve bestellingen";
    $togglePersonnelText = $personnelOnly ? "Toon alle bestellingen" : "Toon alleen jouw bestellingen";

    $html .= '<a href="' . htmlspecialchars($toggleActiveLink) . '">' . htmlspecialchars($toggleActiveText) . '</a> | ';
    $html .= '<a href="' . htmlspecialchars($togglePersonnelLink) . '">' . htmlspecialchars($togglePersonnelText) . '</a>';

    $html .= '<table border="1">';
    $html .= '<tr>
                <th>Order ID</th>
                <th>Klant</th>
                <th>Adres</th>
                <th>Personeel</th>
                <th>Status</th>
                <th>Pizza Product</th>
                <th>Hoeveelheid</th>
                <th>Prijs per stuk (€)</th>
                <th>Totale prijs (€)</th>
                <th>Actie</th>
              </tr>';

    foreach ($orders as $order) {
        $orderDetails = getOrderDetails($order['order_id']);

        foreach ($orderDetails as $detail) {
            $totalPrice = $detail['quantity'] * $detail['price'];
            $status = (int) $order['status'];
            $statusText = match ($status) {
                0 => 'Bestelling ontvangen',
                1 => 'In de oven',
                2 => 'Onderweg',
                3 => 'Bezorgd',
                default => 'Onbekend'
            };

            $client = $order['client_name'];
            $personnel = $order['personnel_username'];
            $address = $order['address'] ?? 'Geen adres beschikbaar';

            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($order['order_id']) . '</td>';
            $html .= '<td>' . htmlspecialchars($client) . '</td>';
            $html .= '<td>' . htmlspecialchars($address) . '</td>';
            $html .= '<td>' . htmlspecialchars($personnel) . '</td>';
            $html .= '<td>' . htmlspecialchars($statusText) . '</td>';
            $html .= '<td>' . htmlspecialchars($detail['product_name']) . '</td>';
            $html .= '<td>' . htmlspecialchars($detail['quantity']) . '</td>';
            $html .= '<td>€' . number_format($detail['price'], 2, ',', '.') . '</td>';
            $html .= '<td>€' . number_format($totalPrice, 2, ',', '.') . '</td>';
            $html .= '<td>';
            $html .= '<form method="POST" action="../businesslaag/bestellingfuncties.php">';
            $html .= '<input type="hidden" name="order_id" value="' . $order['order_id'] . '">';
            $html .= '<select name="status">';
            $html .= '<option value="0">Bestelling ontvangen</option>';
            $html .= '<option value="1">In de oven</option>';
            $html .= '<option value="2">Onderweg</option>';
            $html .= '<option value="3">Bezorgd</option>';
            $html .= '</select>';
            $html .= '<input type="submit" value="Update">';
            $html .= '</form>';
            $html .= '</td>';
            $html .= '</tr>';
        }
    }

    $html .= '</table>';
    return $html;
}
