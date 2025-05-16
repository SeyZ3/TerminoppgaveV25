<?php
include("database.php");

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    // Enkle valideringer
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Ugyldig e-postadresse.";
    }

    if (strlen($username) < 3 || strlen($username) > 20) {
        $errors[] = "Brukernavn må være mellom 3 og 20 tegn.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passordene er ikke like.";
    }

    if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        $errors[] = "Passordet må være minst 8 tegn, inneholde minst én stor bokstav og ett tall.";
    }

    // Sjekk om e-post eller brukernavn allerede finnes
    $stmt = $conn->prepare("SELECT id FROM brukere WHERE epost = ? OR brukernavn = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $errors[] = "Brukernavn eller e-post er allerede i bruk.";
    }
    $stmt->close();

    // Hvis ingen feil: registrer bruker
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO brukere (brukernavn, epost, passord) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            header("Location: login.php?success=1");
            exit();
        } else {
            $errors[] = "Noe gikk galt under registrering.";
        }

        $stmt->close();
    }

    $conn->close();
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
    <h2>Registrer deg på Quizio</h2>

    <?php
    if (!empty($errors)) {
        echo "<ul style='color: red;'>";
        foreach ($errors as $e) {
            echo "<li>$e</li>";
        }
        echo "</ul>";
    }
    ?>

    <form method="POST" action="register.php">
        <label for="username">Brukernavn:</label><br>
        <input type="text" name="username" required><br><br>

        <label for="email">E-post:</label><br>
        <input type="email" name="email" required><br><br>

        <label for="password">Passord:</label><br>
        <input type="password" name="password" required><br><br>

        <label for="confirm_password">Bekreft passord:</label><br>
        <input type="password" name="confirm_password" required><br><br>

        <button type="submit">Registrer</button>
    </form>

    <p>Har du allerede en bruker? <a href="login.php">Logg inn her</a>.</p>
</body>
</html>
