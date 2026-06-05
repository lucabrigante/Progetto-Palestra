<?php
session_start();
require "db.php";

header('Content-Type: application/json');

//controllo sessione utente
if (!isset($_SESSION['id'])) {
    echo json_encode(["success" => false, "error" => "utente non loggato"]);
    exit;
}

$id_utente = $_SESSION['id'];
$giorno = $_POST['giorno'] ?? null;
$fascia = $_POST['fascia'] ?? null;

//controllo validità campi obbligatori
if (!$giorno || !$fascia) {
    echo json_encode(["success" => false, "error" => "dati mancanti"]);
    exit;
}

try {
    //verifica se l'utente ha gia una prenotazione nello stesso giorno
    $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM prenotazioni_sala WHERE id_utente = ? AND giorno = ?");
    $stmtCheck->execute([$id_utente, $giorno]);
    $giaPrenotato = $stmtCheck->fetchColumn();

    if ($giaPrenotato > 0) {
        //se esiste già una riga, blocca l'operazione restituendo l'errore al client
        echo json_encode(["success" => false, "error" => "Hai già effettuato una prenotazione per questa giornata."]);
        exit;
    }

    //se il controllo va a buon fine inserisce la prenotazione
    $stmtInsert = $conn->prepare("INSERT INTO prenotazioni_sala (id_utente, giorno, fascia) VALUES (?, ?, ?)");
    $stmtInsert->execute([$id_utente, $giorno, $fascia]);

    echo json_encode(["success" => true]);
    exit;

} catch (PDOException $e) {
    echo json_encode(["success" => false, "error" => "Errore interno del server durante la prenotazione della sala"]);
    exit;
}
?>