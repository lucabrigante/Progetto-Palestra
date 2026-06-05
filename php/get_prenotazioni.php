<?php
session_start();
require "db.php";

//controllo che l'utente sia loggato
if (!isset($_SESSION['id'])) {

//se non è autenticato restitusico errore in formato JSON
    echo json_encode(["success" => false,
     "error" => "utente non loggato"
     ]);

    //interrompo l'esecuzione dello script
    exit;
}

//recupero l'id dell'utente della sessione
$id_utente = $_SESSION['id'];

//recupero il nome del corso inviato tramite parametro GET
//se il parametro non esiste assegno valore null
$corso = $_GET['corso'] ?? null;

//controllo che il corso sia stato specificato
if (!$corso) {

    //se manca il parametro restituisco errore
    echo json_encode(["success" => false,
    "error" => "dati mancanti"
    ]);

    //interrompo l'esecuzione
    exit;
}

try {
    //preparo la query sql per recuperare le prenotazioni dell'utente relative al corso selezionato

    $stmt = $conn->prepare("
    SELECT id, orario
    FROM prenotazioni 
    WHERE id_utente = ? AND corso = ?
    ");
    //eseguo la query passando i parametri in modo sicuro
    $stmt->execute([$id_utente, $corso]);

    //recupero tutti i risultati come array
    $prenotazioni = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "prenotazioni" => $prenotazioni
        ]);

} catch (PDOException $e) {
    //in caso di errore del database restituisco il messaggio di errore
    echo json_encode([
        "success" => false, 
        "error" => "Errore interno del server durante il recupero delle prenotazioni"
        ]);
}
?>