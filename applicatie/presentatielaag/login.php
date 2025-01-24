<?php
session_start();
require '../businesslaag/algemenefuncties.php';

includeHeader();



if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
}
?>

<body>
    <h2>Inloggen</h2>
    <form action="../businesslaag/login.php" method="POST">
        <label>Gebruikersnaam:</label>
        <input type="text" name="username" required min="3" max="15"><br>

        <label>Wachtwoord:</label>
        <input type="password" name="password" required min="3" max="15"><br>

        <input type="submit" value="Login">
        <a href="register.php">Nog geen account? Registreer hier.</a>
    </form>
</body>

<?php includeFooter(); ?>