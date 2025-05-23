<?php
session_start();
include('database.php');

// Sjekk innlogging, kan tilpasses som du vil
if (!isset($_SESSION["bruker_id"])) {
    header("Location: login.php");
    exit();
}

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Hent quiz-data
    $tittel = trim($_POST["tittel"]);
    $beskrivelse = trim($_POST["beskrivelse"]);

    // Hent spørsmål + svar (for enkelhets skyld, vi begrenser til 2 spørsmål med 3 svar hver)
    $sporsmal_1 = trim($_POST["sporsmal_1"]);
    $sporsmal_2 = trim($_POST["sporsmal_2"]);

    $svar_1_1 = trim($_POST["svar_1_1"]);
    $svar_1_2 = trim($_POST["svar_1_2"]);
    $svar_1_3 = trim($_POST["svar_1_3"]);
    $riktig_1 = $_POST["riktig_1"]; // verdi: 1, 2 eller 3

    $svar_2_1 = trim($_POST["svar_2_1"]);
    $svar_2_2 = trim($_POST["svar_2_2"]);
    $svar_2_3 = trim($_POST["svar_2_3"]);
    $riktig_2 = $_POST["riktig_2"]; // verdi: 1, 2 eller 3

    // Enkel validering
    if (empty($tittel)) $errors[] = "Quiz må ha en tittel.";
    if (empty($sporsmal_1)) $errors[] = "Spørsmål 1 må fylles ut.";
    if (empty($sporsmal_2)) $errors[] = "Spørsmål 2 må fylles ut.";

    if (empty($svar_1_1) || empty($svar_1_2) || empty($svar_1_3)) $errors[] = "Alle svar for spørsmål 1 må fylles ut.";
    if (empty($svar_2_1) || empty($svar_2_2) || empty($svar_2_3)) $errors[] = "Alle svar for spørsmål 2 må fylles ut.";

    if (empty($errors)) {
        // Start transaction for sikkerhet
        $conn->begin_transaction();

        try {
            // Sett inn quiz
            $stmt = $conn->prepare("INSERT INTO quizer (tittel, beskrivelse, opprettet_av) VALUES (?, ?, ?)");
            if (!$stmt) {
                die("Prepare feilet: " . $conn->error);
            }

            $bruker_id = $_SESSION["bruker_id"];
            $stmt->bind_param("ssi", $tittel, $beskrivelse, $bruker_id);
            $stmt->execute();
            $quiz_id = $conn->insert_id;
            $stmt->close();

            // Spørsmål 1
            $stmt = $conn->prepare("INSERT INTO sporsmal (quiz_id, tekst) VALUES (?, ?)");
            $stmt->bind_param("is", $quiz_id, $sporsmal_1);
            $stmt->execute();
            $sporsmal_id_1 = $conn->insert_id;
            $stmt->close();

            // Svar spørsmål 1
            $svar_1 = [$svar_1_1, $svar_1_2, $svar_1_3];
            for ($i = 0; $i < 3; $i++) {
                $er_riktig = ($i + 1 == $riktig_1) ? 1 : 0;
                $stmt = $conn->prepare("INSERT INTO svar (sporsmal_id, tekst, er_riktig) VALUES (?, ?, ?)");
                $stmt->bind_param("isi", $sporsmal_id_1, $svar_1[$i], $er_riktig);
                $stmt->execute();
                $stmt->close();
            }

            // Spørsmål 2
            $stmt = $conn->prepare("INSERT INTO sporsmal (quiz_id, tekst) VALUES (?, ?)");
            $stmt->bind_param("is", $quiz_id, $sporsmal_2);
            $stmt->execute();
            $sporsmal_id_2 = $conn->insert_id;
            $stmt->close();

            // Svar spørsmål 2
            $svar_2 = [$svar_2_1, $svar_2_2, $svar_2_3];
            for ($i = 0; $i < 3; $i++) {
                $er_riktig = ($i + 1 == $riktig_2) ? 1 : 0;
                $stmt = $conn->prepare("INSERT INTO svar (sporsmal_id, tekst, er_riktig) VALUES (?, ?, ?)");
                $stmt->bind_param("isi", $sporsmal_id_2, $svar_2[$i], $er_riktig);
                $stmt->execute();
                $stmt->close();
            }

            $conn->commit();
            $success = "Quiz opprettet!";

        } catch (Exception $e) {
            $conn->rollback();
            $errors[] = "Noe gikk galt: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Lag ny quiz – Quizio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar">
    <a href="dashboard.php">Hjem</a>
    <a href="quiz_list.php">Alle quizer</a>
    <a href="logout.php" style="margin-left:auto; color: red;">Logg ut</a>
</div>

<div class="content">
    <h1>Lag ny quiz</h1>

    <?php if ($success): ?>
        <p class="success-msg"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>

    <?php if ($errors): ?>
        <ul class="error-list">
            <?php foreach ($errors as $e): ?>
                <li><?php echo htmlspecialchars($e); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="quiz_create.php">
        <label for="tittel">Quiz-tittel:</label><br>
        <input type="text" name="tittel" required><br><br>

        <label for="beskrivelse">Beskrivelse:</label><br>
        <textarea name="beskrivelse" rows="3"></textarea><br><br>

        <h3>Spørsmål 1:</h3>
        <input type="text" name="sporsmal_1" placeholder="Skriv spørsmål 1" required><br>
        <input type="text" name="svar_1_1" placeholder="Svar 1" required>
        <input type="radio" name="riktig_1" value="1" required> Riktig<br>
        <input type="text" name="svar_1_2" placeholder="Svar 2" required>
        <input type="radio" name="riktig_1" value="2"> Riktig<br>
        <input type="text" name="svar_1_3" placeholder="Svar 3" required>
        <input type="radio" name="riktig_1" value="3"> Riktig<br><br>

        <h3>Spørsmål 2:</h3>
        <input type="text" name="sporsmal_2" placeholder="Skriv spørsmål 2" required><br>
        <input type="text" name="svar_2_1" placeholder="Svar 1" required>
        <input type="radio" name="riktig_2" value="1" required> Riktig<br>
        <input type="text" name="svar_2_2" placeholder="Svar 2" required>
        <input type="radio" name="riktig_2" value="2"> Riktig<br>
        <input type="text" name="svar_2_3" placeholder="Svar 3" required>
        <input type="radio" name="riktig_2" value="3"> Riktig<br><br>

        <button type="submit">Opprett quiz</button>
    </form>
</div>
</body>
</html>
