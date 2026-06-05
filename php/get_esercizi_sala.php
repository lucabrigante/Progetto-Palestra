<?php
session_start(); //faccio partire la sessione
require "db.php"; //importo file di connessione al database(PDO)

header('Content-Type: application/json'); //imposto il tipo di risposta come JSON

//controllo se l'utente è autenticato tramite sessione
if (!isset($_SESSION['id'])) {
    
//se non è loggato, restituisco errore in formato JSON
    echo json_encode(["success" => false,
     "error" => "utente non loggato"
     ]);

     //interrompo esecuzione script
    exit;
}

$id_utente = $_SESSION['id']; //recupero id dell'utente della sessione

try {

//preparo la query SQL per selezionare gli esercizi dell'utente
//seleziono solo i campi necessari per la visualizzazione
    $stmt = $conn->prepare("SELECT id, nome, serie, ripetizioni, carico
    FROM esercizi_sala
    WHERE id_utente = ?
    ORDER BY id ASC
    ");

    //eseguo la query passando l'id dell'utente in modo sicuro
    $stmt->execute([$id_utente]);

    //recupero tutti i rusltati come array associativo
    $esercizi = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //restituisco i dati in fonrmato JSON al frontend
    echo json_encode([
    "success" => true,
    "esercizi" => $esercizi
     ]);

     //termino lo script
    exit;

} catch (PDOException $e) {
    //in caso di errore nel database, restituisco messaggio di errore
    echo json_encode([
        "success" => false,
         "error" => "Errore interno del server durante il caricamento"
         ]);

         //termino lo script
    exit;
}