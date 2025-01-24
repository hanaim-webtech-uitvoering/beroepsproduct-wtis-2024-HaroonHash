<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzeria</title>
</head>

<body>
    <header>
        <h1>Pizzeria</h1>
        <nav>
            <a href="index.php">Menu</a> |
            <a href="winkelmandje.php">Winkelmandje</a> |
            <a href="profiel.php">Profiel</a> |
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Personnel') : ?>
                <a href="bestellingoverzicht.php">Bestellingen</a> |
            <?php endif; ?>
            <?php if (isset($_SESSION['user_id'])) : ?>
                <a href="../businesslaag/logout.php">Uitloggen</a> |
            <?php else : ?>
                <a href="../../update_passwords.php">Inloggen</a> |
                <a href="register.php">Registreren</a> |
            <?php endif; ?>
            <a href="privacy.php">Privacyverklaring</a>
        </nav>
    </header>