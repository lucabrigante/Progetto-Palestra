<?php
session_start();
require "db.php";

header('Content-Type: application/json');
//controllo che l'utente sia loggato
if (!isset($_SESSION['id'])) {
    //se non è loggato, restituisco errore in formato JSON
    echo json_encode(["success" => false,
     "error" => "utente non loggato"
     ]);
    exit;
}

$id_utente = $_SESSION['id']; //recupero l'id dell'utente della sessione

$id_prenotazione = $_POST['id'] ?? null; //recupero l'id della prenotazione da eliminare inviato tramite POST

//controllo che l'id della prenotazione sia stasto inviato correttamente
if (!$id_prenotazione) {
    //se manca l'id restituisco errore
    echo json_encode(["success" => false,
     "error" => "id mancante"
     ]);
    exit;
}

try {
    //faccio query per eliminare prenotazione dal database con due cond. di sicurezza:
    //-id prenotazione
    //-id utente(assicura che l'utente possa eliminare solo le proprie prenotazioni)
    $stmt = $conn->prepare("DELETE FROM prenotazioni WHERE id = ? AND id_utente = ?");
    //eseguo binding dei parametri(protezione sql injection)
    $stmt->execute([$id_prenotazione, $id_utente]);
// se la cancellazione va a buon fine restituisco risposta positiva
    echo json_encode([
        "success" => true
        ]);

} catch (PDOException $e) {
    //in caso di errore del database, restituisco il messaggio di errore
    echo json_encode(["success" => false,
     "error" => "Errore interno del server durante la cancellazione"
     ]);
}
?>