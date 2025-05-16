<?php
session_start();
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Quizio | Velkommen</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="navbar">
        <a href="index.php">Hjem</a>
        <?php if (isset($_SESSION['bruker_id'])): ?>
            <a href="quiz.php">Start Quiz</a>
            <a href="resultater.php">Resultater</a>
            <a href="logout.php">Logg ut (<?php echo htmlspecialchars($_SESSION['brukernavn']); ?>)</a>
        <?php else: ?>
            <a href="login.php">Logg inn</a>
            <a href="register.php">Registrer</a>
        <?php endif; ?>
    </div>

    <h1>Velkommen til Quizio!</h1>
    <p>Test kunnskapen din med gøyale quizer.</p>
</body>
</html>
