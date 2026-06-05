<?php
session_start(); //avvio sessione per accedere alle variabili dell'utente loggato
require "db.php"; //importo sempre il file di connessione al database

header('Content-Type: application/json'); //imposto il tipo di risposta come JSON

//controllo se l'utente è autenticato tramite sessione
if (!isset($_SESSION['id'])) { 

    //se non è loggato, restituisco un errore in formato JSON
    echo json_encode(["success" => false,
     "error" => "utente non loggato"
     ]);

     //interrompo l'esecuzione dello script
    exit;
}

//recupero l'id dell'utente loggato dalla sessione
$id_utente = $_SESSION['id'];

//recupero l'id della prenotazione da eliminare inviato via POST
$id_prenotazione = $_POST['id'] ?? null;

//Controllo che l'id sia stato effettivamente inviato
if (!$id_prenotazione) {

//se manca l'id restituisco errore
    echo json_encode(["success" => false,
     "error" => "id mancante"
     ]);

     //interrompo lo script
    exit;
}

try {

//preparo la query sql per eliminare la prenotazione ed uso doppia condizione per sicurezza:
//- id prenotazione
//- id utente(evita cancellazioni non autorizzate)
    $stmt = $conn->prepare("DELETE FROM prenotazioni_sala WHERE id = ? AND id_utente = ?");
    //eseguo la query in modo sicuro con binding dei parametri(protezione sql injection)
    $stmt->execute([$id_prenotazione, $id_utente]);

    //se la cancellazione va a buon fine, restituisco successo
    echo json_encode([
        "success" => true
        ]);

        //termino lo script
    exit;

} catch (PDOException $e) {

//in caso di errore del database, restituisco messaggio di errore
    echo json_encode(["success" => false,
     "error" => "Errore interno del server durante la cancellazione"
     ]);

     //interrompo lo script
    exit;
}

//la logica che sto usando è sempre controllo sessione -> input POST -> query sicura -> risposta JSON