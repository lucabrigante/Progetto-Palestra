<?php
$host = "localhost"; //definzione dell'host del database(in locale è sempre localhost)
$db   = "brigante_689733"; //nome del database a cui ci si vuole connettere
$user = "root"; //nome utente del database
$pass = ""; //password del database(vuota in ambiente locale)
$charset = "utf8mb4";//charset utilizzato per la connessione(supporta caratteri speciali e utf-8 completo)

$dsn = "mysql:host=$host;dbname=$db;charset=$charset"; //stringa di connessione(data source name-dsn), contiene host, nome db e charset

try {

//creazione della connessione al database tramite PDO
    $conn = new PDO($dsn, $user, $pass);

    //ompostazione modalita di gestione errori:
    //ERRMODE_EXCEPTION fa si che gli errori generino eccezioni
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    //in caso di errore di connessione, interrompe lo script
    //e mostra il messaggio di errore
    die("Errore DB: Connessione al server non disponibile");
}
?>