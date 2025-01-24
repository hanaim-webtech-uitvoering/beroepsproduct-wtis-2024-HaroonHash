<?php
require '../businesslaag/bestellingfuncties.php';
require '../businesslaag/algemenefuncties.php';

includeHeader();


if ($_SESSION['role'] !== 'Personnel') {
    header('Location: index.php');
}
$activeOnly = isset($_GET['active']) && $_GET['active'] == 1;
$personnelOnly = isset($_GET['personnel']) && $_GET['personnel'] == 1;
?>
<?php
echo getOrderTable($activeOnly, $personnelOnly);
includeFooter();
?>