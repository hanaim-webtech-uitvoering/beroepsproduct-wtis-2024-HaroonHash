<?php
session_start();
require '../businesslaag/profiel.php';
require '../businesslaag/algemenefuncties.php';

includeHeader();


if (!isset($_SESSION['user_id'])) {
    die("Je moet ingelogd zijn om je profiel te bekijken.");
}

$username = $_SESSION['user_id'];
$orders = getUserOrders($username);
?>

<h2>Mijn Bestellingen</h2>
<table border="1">
    <tr>
        <th>Bestelling ID</th>
        <th>Status</th>
        <th>Bezorgadres</th>
        <th>Bestelde Producten</th>
    </tr>
    <?php
    foreach ($orders as $order) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($order['order_id']) . "</td>";
        echo "<td>" . match ((int)$order['status']) {
            0 => 'Bestelling ontvangen',
            1 => 'In de oven',
            2 => 'Onderweg',
            3 => 'Bezorgd',
            default => 'Onbekend'
        } . "</td>";
        echo "<td>" . htmlspecialchars($order['address']) . "</td>";
        echo "<td>";

        $products = getOrderProducts($order['order_id']);
        foreach ($products as $product) {
            echo htmlspecialchars($product['product_name']) . " (" . $product['quantity'] . ")<br>";
        }

        echo "</td></tr>";
    }
    ?>
</table>

<?php includeFooter(); ?>