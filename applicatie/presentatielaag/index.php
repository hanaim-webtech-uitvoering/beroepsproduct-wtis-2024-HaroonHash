<?php
session_start();
require '../businesslaag/menu.php';
require '../businesslaag/algemenefuncties.php';

includeHeader();

?>

<h2>Menu</h2>
<table border="1">
    <tr>
        <th>Product</th>
        <th>Prijs (€)</th>
        <th>Actie</th>
    </tr>
    <?php
    $products = getProducts();
    foreach ($products as $product) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($product['name']) . "</td>";
        echo "<td>€" . number_format($product['price'], 2, ',', '.') . "</td>";
        echo "<td>
                <form method='POST' action='winkelmandje.php'>
                    <input type='hidden' name='product_name' value='" . htmlspecialchars($product['name']) . "'>
                    <input type='number' name='quantity' value='1' min='1 max='99''>
                    <input type='submit' value='Toevoegen'>
                </form>
              </td>";
        echo "</tr>";
    }

    // echo getAllUsers();
    ?>
</table>

<?php includeFooter(); ?>