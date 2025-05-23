<?php
session_start();
include 'database.php';

if (!isset($_SESSION["bruker_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['svar'])) {
    echo "Ingen svar sendt.";
    exit;
}

$svarFraBruker = $_POST['svar'];
$poeng = 0;
$totalSpm = count($svarFraBruker);

foreach ($svarFraBruker as $spm_id => $svar_id) {
    // Sjekk om svaret er riktig
    $sql = "SELECT er_riktig FROM svar WHERE id = " . intval($svar_id) . " AND sporsmal_id = " . intval($spm_id);
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        if ($row['er_riktig']) {
            $poeng++;
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8" />
    <title>Quiz-resultat</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <h1>Resultat</h1>
    <p>Du fikk <?php echo $poeng; ?> av <?php echo $totalSpm; ?> riktige.</p>
    <a href="dashboard.php">Tilbake til dashboard</a>
</body>
</html>
