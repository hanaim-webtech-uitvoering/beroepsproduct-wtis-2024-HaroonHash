<?php
require '../businesslaag/algemenefuncties.php';
require '../businesslaag/winkelmandje.php';

includeHeader();


$userAddress = '';
if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['user_id'];
    $sql = "SELECT address FROM [User] WHERE username = ?";
    $query = $verbinding->prepare($sql);
    $query->execute([$username]);
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result && !empty($result['address'])) {
        $userAddress = htmlspecialchars($result['address']);
    }
}


if (isset($_POST['product_name'], $_POST['quantity'])) {
    addToCart($_POST['product_name'], (int)$_POST['quantity']);
    header('location: index.php');
}

if (isset($_POST['checkout'])) {
    if (!empty($_SESSION['user_id'])) {
        if (placeOrder($_POST['address'])) {
            header('location: winkelmandje.php');
            // echo "<p>Bestelling succesvol geplaatst!</p>";
        }
    } else {
        echo "<h3>Je moet ingelogd zijn om een bestelling te plaatsen</h3>";
    }
}

$totalPrice = 0;
?>

<h2>Winkelmandje</h2>
<table border="1">
    <tr>
        <th>Product</th>
        <th>Hoeveelheid</th>
        <th>Prijs per stuk (€)</th>
        <th>Subtotaal (€)</th>
    </tr>
    <?php
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product => $quantity) {
            $sql = "SELECT price FROM Product WHERE name = ?";
            $query = $verbinding->prepare($sql);
            $query->execute([$product]);
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $price = $result ? (float)$result['price'] : 0;

            $subtotal = $price * $quantity;
            $totalPrice += $subtotal;

            echo "<tr>
                    <td>" . htmlspecialchars($product) . "</td>
                    <td>$quantity</td>
                    <td>€" . number_format($price, 2, ',', '.') . "</td>
                    <td>€" . number_format($subtotal, 2, ',', '.') . "</td>
                  </tr>";
        }
        echo "<tr>
                <td colspan='3'><strong>Totaal:</strong></td>
                <td><strong>€" . number_format($totalPrice, 2, ',', '.') . "</strong></td>
              </tr>";
    } else {
        echo "<tr><td colspan='4'>Je winkelmandje is leeg.</td></tr>";
    }
    ?>
</table>

<?php if (!empty($_SESSION['cart'])) : ?>
    <h3>Bestelling afronden</h3>
    <form method="POST">
        <label>Bezorgadres:</label>
        <input type="text" name="address" value="<?= $userAddress ?>" required min="10" max="255">
        <input type="submit" name="checkout" value="Bestellen">
    </form>
<?php endif; ?>

<?php includeFooter(); ?>