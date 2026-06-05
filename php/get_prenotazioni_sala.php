<?php
session_start();
require "db.php";

header('Content-Type: application/json');

//controllo che l'utente sia autenticato
if (!isset($_SESSION['id'])) {
    //se non + cosi, restituisco errore in formato JSON
    echo json_encode(["success" => false,
     "error" => "utente non loggato"
     ]);
    exit;
}

//recupero l'id dell'utente della sessione
$id_utente = $_SESSION['id'];

try {
    //preparo la query sql per recuperare tutte le prenotazioni appartententi all'utente loggato
    $stmt = $conn->prepare("
    SELECT id, giorno, fascia 
    FROM prenotazioni_sala
    WHERE id_utente = ? 
    ORDER BY giorno ASC, fascia ASC
    ");

    //eseguo la query passando l'id dell'utente
    $stmt->execute([$id_utente]);

    //recupero tutte le prenotazioni trovate come array 
    $prenotazioni = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //restituisco la lista delle prenotazioni insieme all'esito positivo dell'oeprazione
    echo json_encode([
        "success" => true, 
        "prenotazioni" => $prenotazioni
        ]);
        
    exit;

} catch (PDOException $e) {

    //in caso di errore del database restituisco il messaggio di errore
    echo json_encode([
        "success" => false,
        "error" => "Errore interno del server durante il caricamento delle prenotazioni"
        ]);
    exit;
}