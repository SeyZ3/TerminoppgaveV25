<?php
include('database.php'); // kobler til databasen

$feilmelding = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brukernavn = trim($_POST["brukernavn"]);
    $epost = trim($_POST["epost"]);
    $passord = $_POST["passord"];
    $bekreft_passord = $_POST["bekreft_passord"];

    // Validering
    if (strlen($brukernavn) < 3 || strlen($brukernavn) > 20 || !preg_match("/^[a-zA-Z0-9_]+$/", $brukernavn)) {
        $feilmelding = "Brukernavnet må være mellom 3-20 tegn og kun inneholde bokstaver, tall og understrek.";
    } elseif (!filter_var($epost, FILTER_VALIDATE_EMAIL)) {
        $feilmelding = "Ugyldig e-postadresse.";
    } elseif (strlen($passord) < 8 || !preg_match("/[A-Z]/", $passord) || !preg_match("/[a-z]/", $passord) || !preg_match("/[0-9]/", $passord)) {
        $feilmelding = "Passordet må være minst 8 tegn og inneholde stor bokstav, liten bokstav og tall.";
    } elseif ($passord !== $bekreft_passord) {
        $feilmelding = "Passordene matcher ikke.";
    } else {
        // Sjekk om epost eller brukernavn allerede finnes
        $stmt = $conn->prepare("SELECT id FROM brukere WHERE epost = ? OR brukernavn = ?");
        $stmt->bind_param("ss", $epost, $brukernavn);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $feilmelding = "Brukernavn eller e-post er allerede i bruk.";
        } else {
            // Alt OK – lagre bruker
            $hash = password_hash($passord, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO brukere (brukernavn, epost, passord_hash) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $brukernavn, $epost, $hash);
            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $feilmelding = "Noe gikk galt, prøv igjen.";
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Registrer deg – Quizio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="register-form">
    <h2>Opprett konto</h2>
    <?php if ($feilmelding): ?>
        <p style="color: red;"><?php echo $feilmelding; ?></p>
    <?php endif; ?>
    <form method="POST" action="register.php">
        <input type="text" name="brukernavn" placeholder="Brukernavn" required><br>
        <input type="email" name="epost" placeholder="E-post" required><br>
        <input type="password" name="passord" placeholder="Passord" required><br>
        <input type="password" name="bekreft_passord" placeholder="Bekreft passord" required><br>
        <button type="submit">Registrer</button>
    </form>
    <p>Har du allerede en konto? <a href="login.php">Logg inn her</a>.</p>
</div>
</body>
</html>
