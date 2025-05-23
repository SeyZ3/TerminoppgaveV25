<?php
session_start();

// Sjekk om bruker er logget inn og er admin
$isAdmin = isset($_SESSION['bruker']) && $_SESSION['bruker']['rolle'] === 'admin';
$erInnlogget = isset($_SESSION['bruker']);
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Quizio | Velkommen</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <a href="index.php">Hjem</a>
    <a href="quiz.php">Start Quiz</a>
    <a href="resultater.php">Resultater</a>

    <?php if ($isAdmin): ?>
        <a href="admin.php">Adminpanel</a>
    <?php endif; ?>

    <?php if ($erInnlogget): ?>
        <a href="logout.php">Logg ut (<?php echo htmlspecialchars($_SESSION['bruker']['brukernavn']); ?>)</a>
    <?php else: ?>
        <a href="login.php">Logg inn</a>
        <a href="register.php">Registrer</a>
    <?php endif; ?>
</div>

<div class="content">
    <h1>Velkommen til Quizio!</h1>
    <p>Test kunnskapen din med gøyale quizer.</p>
</div>

</body>
</html>