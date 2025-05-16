<?php
session_start();
include('database.php');

$feilmelding = "";
$successmelding = "";

// Sjekk om brukeren nettopp har registrert seg:
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $successmelding = "Registrering fullført! Du kan nå logge inn.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $epost = trim($_POST["email"]);
    $passord = $_POST["password"];

    // Sjekk om bruker finnes
    $stmt = $conn->prepare("SELECT id, brukernavn, passord FROM brukere WHERE epost = ?");
    $stmt->bind_param("s", $epost);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $brukernavn, $passord_hash);
        $stmt->fetch();

        // Sjekk passord
        if (password_verify($passord, $passord_hash)) {
            $_SESSION["bruker_id"] = $id;
            $_SESSION["brukernavn"] = $brukernavn;
            header("Location: dashboard.php");
            exit();
        } else {
            $feilmelding = "Feil passord.";
        }
    } else {
        $feilmelding = "Bruker ikke funnet.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Logg inn – Quizio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-form">
        <h2>Logg inn</h2>

        <?php if ($successmelding): ?>
            <p style="color: green;"><?php echo htmlspecialchars($successmelding); ?></p>
        <?php endif; ?>

        <?php if ($feilmelding): ?>
            <p style="color: red;"><?php echo htmlspecialchars($feilmelding); ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <input type="email" name="email" placeholder="E-post" required><br>
            <input type="password" name="password" placeholder="Passord" required><br>
            <button type="submit">Logg inn</button>
        </form>

        <p>Har du ikke en konto? <a href="register.php">Registrer deg her</a>.</p>
    </div>
</body>
</html>
