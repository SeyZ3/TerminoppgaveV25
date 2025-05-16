<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizio | Velkommen</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <a href="index.php">🏠 Hjem</a>
        <a href="quiz.php">🧠 Start Quiz</a>
        <a href="resultater.php">📊 Resultater</a>
        <a href="admin.php">⚙️ Admin</a>
    </div>

    <h1>Velkommen til Quizio!</h1>
    <p>Test kunnskapen din med gøyale quizer.</p>


    <?php
    // Inkluder database-tilkoblingen
    include('database.php');  // Inkluderer filen med databaskobling

    // SQL-spørring for å hente alle brukere
    $sql = "SELECT id, navn, epost FROM brukere";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $row["id"] . " - Navn: " . $row["navn"] . " - Epost: " . $row["epost"] . "<br>";
        }
    } else {
        echo "Ingen resultater funnet";
    }

    // Lukk tilkoblingen
    $conn->close();
    ?>

</body>
</html>