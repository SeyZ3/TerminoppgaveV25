Brukerstøtte for Quizio
Introduksjon
Quizio er en quiz-nettside laget med PHP, MariaDB og enkel frontend (HTML/CSS). Den kjører lokalt via WampServer og en MariaDB-database i en Ubuntu VM.

Komme i gang
1. Systemkrav
Windows-maskin med WampServer installert

Ubuntu VM med MariaDB kjørende

PHP 7 eller nyere

Nettleser (f.eks. Chrome)

2. Oppsett
Start WampServer og sørg for at Apache og PHP kjører

Start Ubuntu VM og sørg for at MariaDB kjører

Koble til MariaDB i database.php ved å sette riktig IP, databasebruker og passord

Bruk av Quizio
Innlogging og registrering
Gå til login.php for å logge inn

Ny bruker kan registrere seg via register.php

Passord må være minst 8 tegn, inneholde stor bokstav og tall

Opprett quiz
Etter innlogging kan du lage nye quizer via quiz_create.php

Skriv inn quiz-tittel, beskrivelse, spørsmål og svaralternativer

Spille quiz
Quizene vises i quiz_list.php

Velg en quiz og start via quiz.php (hvis implementert)

Vanlige problemer og løsninger
Problem	Løsning
Kan ikke koble til database	Sjekk IP-adresse, brukernavn og passord i database.php
Feilmelding "Unknown column" i SQL	Kjør SQL-scriptet for å oppdatere tabellstruktur (legg til manglende kolonner)
Får feilmelding om sessions eller cookies	Sørg for at nettleser tillater cookies, og at session_start() er øverst i PHP-filer
Quiz vises ikke i listen	Sjekk at tabellen quizer har data, og at spørringen i quiz_list.php er korrekt

Kontakt og hjelp
Hvis du har spørsmål eller trenger hjelp, ta kontakt med utvikleren:
Sezer — sezer@example.com
