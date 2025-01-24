<?php
session_start();
require '../businesslaag/algemenefuncties.php';

includeHeader();


if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
}
?>

<body>
    <h2>Registreren</h2>
    <form action="../businesslaag/register.php" method="POST">
        <label>Voornaam*:</label>
        <input type="text" name="first_name" required min="3" max="15"><br>

        <label>Achternaam*:</label>
        <input type="text" name="last_name" required min="3" max="15"><br>

        <label>Gebruikersnaam*:</label>
        <input type="text" name="username" required min="3" max="15"><br>

        <label>Adres:</label>
        <input type="text" name="address" min="10" max="255"><br>

        <label>Wachtwoord*:</label>
        <input type="password" name="password" required min="3" max="15"><br>

        <label>Bevestig wachtwoord*:</label>
        <input type="password" name="confirmpassword" required min="3" max="15"><br>

        <input type="submit" value="Registreer">
    </form>
</body>

<?php includeFooter(); ?>