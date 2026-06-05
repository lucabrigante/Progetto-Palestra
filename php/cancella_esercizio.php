<?php
session_start(); //avvia sessione
require "db.php"; //include il file di connessione al database

//imposta l'header della risposta come JSON
header('Content-Type: application/json');


//controllo di sicurezza: verifico se l'utente è loggato
if (!isset($_SESSION['id'])) {

    //se non è loggato, restituisco errore in formato json
    echo json_encode([
        "success" => false,
        "error" => "utente non loggato"
        ]);
    //termino l'esecuzione dello script
    exit;
}

//recupero l'id dell'utente della sessione (utente autenticato)
$id_utente = $_SESSION['id'];

//recupero l'id dell'esercizio da eliminare inviatro tamite POST
$id = $_POST['id'] ?? null;

//controllo se l'id è stato effettivamente inviato
if (!$id) {
    //Se manca l'id, restituisco errore
    echo json_encode([
        "success" => false,
        "error" => "id mancante"
        ]);

    //interrompo lo script
    exit;
}

try {

    //preparo la query sql per eliminare un esercizio
    //condizione doppia: id esercizio + id utente
    //serve per evitare che un tente cancelli esercizi di altri utenti
    $stmt = $conn->prepare("DELETE FROM esercizi_sala WHERE id = ? AND id_utente = ?");

    //eseguo la query passando i parametri in modo sicuro (protezione sql injection)
    $stmt->execute([$id, $id_utente]);

    //se tutto va a buon fine, restituisco successo
    echo json_encode([
        "success" => true
        ]);

        //termino lo script
    exit;

} catch (PDOException $e) {

    //in caso di errore sql, restituisco il messaggio di errore
    echo json_encode(["success" => false,
     "error" => "Errore interno del server durante la cancellazione"
     ]);
    exit;
}