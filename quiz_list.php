<?php
session_start();
include('database.php');

// Sjekk om bruker er logget inn (valgfritt, men anbefalt)
if (!isset($_SESSION["bruker_id"])) {
    header("Location: login.php");
    exit();
}

// Hent alle quizer fra databasen
$sql = "SELECT id, tittel, beskrivelse, opprettet_tid FROM quizer ORDER BY opprettet_tid DESC";
$result = $conn->query($sql);

$result = $conn->query($sql);

if (!$result) {
    die("SQL-feil: " . $conn->error);
}


?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Liste over quizer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar">
    <a href="dashboard.php">Dashboard</a>
    <a href="quiz_create.php">Lag ny quiz</a>
    <a href="logout.php" style="margin-left:auto; color:red;">Logg ut</a>
</div>

<div class="content">
    <h1>Alle quizer</h1>

    <?php if ($result->num_rows > 0): ?>
        <ul>
        <?php while ($quiz = $result->fetch_assoc()): ?>
            <li>
                <strong><?php echo htmlspecialchars($quiz['tittel']); ?></strong><br>
                <em><?php echo htmlspecialchars($quiz['beskrivelse']); ?></em><br>
                Opprettet: <?php echo $quiz['opprettet_tid']; ?>
                <!-- Her kan du senere legge link til å "se" eller "redigere" quizen -->
            </li>
        <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Ingen quizer funnet.</p>
    <?php endif; ?>

</div>
</body>
</html>
