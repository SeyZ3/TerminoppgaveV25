<?php
session_start();
include 'database.php';

// Sjekk om bruker er logget inn (valgfritt, men anbefales)
if (!isset($_SESSION["bruker_id"])) {
    header("Location: login.php");
    exit();
}

$quiz_id = 1; // For enkelhetens skyld hardkodet quiz id, kan gjøres dynamisk senere

// Hent quiz info
$resultQuiz = $conn->query("SELECT * FROM quizer WHERE id = $quiz_id");
if ($resultQuiz->num_rows == 0) {
    echo "Quiz ikke funnet.";
    exit;
}
$quiz = $resultQuiz->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8" />
    <title><?php echo htmlspecialchars($quiz['tittel']); ?></title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<h1><?php echo htmlspecialchars($quiz['tittel']); ?></h1>
<p><?php echo nl2br(htmlspecialchars($quiz['beskrivelse'])); ?></p>

<form action="quiz_resultat.php" method="post">
<?php
// Hent spørsmål
$resultSpm = $conn->query("SELECT * FROM sporsmal WHERE quiz_id = $quiz_id");

while ($spm = $resultSpm->fetch_assoc()) {
    echo "<fieldset>";
    echo "<legend>" . htmlspecialchars($spm['tekst']) . "</legend>";

    // Hent svaralternativer
    $resultSvar = $conn->query("SELECT * FROM svar WHERE sporsmal_id = " . $spm['id']);

    while ($svar = $resultSvar->fetch_assoc()) {
        echo '<label>';
        echo '<input type="radio" name="svar[' . $spm['id'] . ']" value="' . $svar['id'] . '" required> ';
        echo htmlspecialchars($svar['tekst']);
        echo '</label><br>';
    }
    echo "</fieldset><br>";
}

$conn->close();
?>
<input type="submit" value="Fullfør quiz">
</form>
</body>
</html>
