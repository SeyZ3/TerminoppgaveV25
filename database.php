<?php
$host = '192.168.0.14';  // IP-adressen til MariaDB-serveren (Skole IP: 10.2.3.141)
$db = 'min_database';   // Navnet på databasen
$user = 'Sezer';        // MariaDB-brukernavn
$pass = 'IMKuben1337!'; // MariaDB-passord

// Opprett tilkobling
$conn = new mysqli($host, $user, $pass, $db);

// Sjekk tilkoblingen
if ($conn->connect_error) {
    die("Tilkobling feilet: " . $conn->connect_error);
}
?>
