<?php
session_start();
require "db.php";

header('Content-Type: application/json');

//controllo sessione
if (!isset($_SESSION['id'])) {
    //se non è loggato restituisco errore in formato JSON
    echo json_encode(["success" => false,
     "error" => "utente non loggato"
     ]);
    exit;
}


$id_utente = $_SESSION['id']; //recupero l'id dell'utente loggato
//recupero i dati della prenotazione inviati dal form(POST)
$corso = $_POST['corso'] ?? null;   //nome del corso
$orario = $_POST['orario'] ?? null; //fascia oraria


//controllo che entrambi i dati siano stati inviati correttamente
if (!$corso || !$orario) {
    //se mancano dati restituisco errore
    echo json_encode([
    "success" => false,
    "error" => "dati mancanti"
    ]);
    exit;
}

try {
    //controllo se esiste già la prenotazione per evitare duplicati nello stesso corso e orario
    $stmt = $conn->prepare("
        SELECT id 
        FROM prenotazioni 
        WHERE id_utente = ? AND corso = ? AND orario = ?
    ");

    //eseguo la query con i parametri in modo sicuro
    $stmt->execute([$id_utente, $corso, $orario]);

    //se esiste gia una prenotazione, blocco l'inserimento
    if ($stmt->fetch()) {

        //restituisco messaggio di errore al frontend
        echo json_encode([
            "success" => false,
            "error" => "Hai già prenotato questa fascia oraria"
        ]);
        exit;
    }

    //inserimento della nuova prenotazione del database
    $stmt = $conn->prepare("
        INSERT INTO prenotazioni (id_utente, corso, orario)
        VALUES (?, ?, ?)
    ");

    //esecuzione query con valori sicuri
    $stmt->execute([$id_utente, $corso, $orario]);

    //se tutto va bene restituisco successo
    echo json_encode([
        "success" => true
        ]);
    exit;

} catch (PDOException $e) {
    //gestione degli errori del database
    echo json_encode([
    "success" => false,
    "error" => "Errore interno del server durante la prenotazione"
    ]);
    exit;
}