<?php
session_start();

// Sjekk om bruker er innlogget
if (!isset($_SESSION["bruker_id"])) {
    header("Location: login.php");
    exit();
}

$brukernavn = $_SESSION["brukernavn"];
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Dashboard – Quizio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <a href="dashboard.php">Hjem</a>
        <a href="quiz_create.php">Lag quiz</a>
        <a href="quiz_list.php">Alle quizer</a>
        <a href="logout.php" style="margin-left:auto; color: red;">Logg ut</a>
    </div>

    <div class="content">
        <h1>Velkommen, <?php echo htmlspecialchars($brukernavn); ?>!</h1>
        <p>Du er nå logget inn på Quizio. 🚀</p>

        <div class="cards">
            <a class="card" href="quiz_create.php">✏️ Lag en ny quiz</a>
            <a class="card" href="quiz_list.php">📚 Se alle quizer</a>
        </div>
    </div>
</body>
</html>
