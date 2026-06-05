<?php
session_start(); //avvio sessione
require "db.php"; //importo il file di connessione al db

header('Content-Type: application/json'); //imposto tipo di risposta come json

//controllo che l'utente sia loggato
if (!isset($_SESSION['id'])) {
    //se non è loggato restituisco errore in formato JSON
    echo json_encode([
        "success" => false, 
        "error" => "utente non loggato"
        ]);
    exit;
}

$id_utente = $_SESSION['id']; //recupero l'id dell'utente loggato dalla sessione

//recupero i dati inviati dal frontend tramite POST
$id = $_POST['id'] ?? null;     //id esercizio
$nome = $_POST['nome'] ?? null; // nome esercizio 
$serie = $_POST['serie'] ?? null; // numero serie 
$ripetizioni = $_POST['ripetizioni'] ?? null; //numero ripetizioni
$carico = $_POST['carico'] ?? null; //carico in kg


//controllo che i dati ovvligatori siano presenti, id può essere null perché serve solo per UPDATE
if (
    !$nome ||
    !isset($serie, $ripetizioni, $carico)
) {
    echo json_encode([
        "success" => false,
        "error" => "dati mancanti"
    ]);
    exit;
}

//èpulizia base
$nome = trim($nome);

//conversione sicura
$serie = filter_var($serie, FILTER_VALIDATE_INT);
$ripetizioni = filter_var($ripetizioni, FILTER_VALIDATE_INT);
$carico = filter_var($carico, FILTER_VALIDATE_INT);

//controllo numeri validi
if (
    $serie === false ||
    $ripetizioni === false ||
    $carico === false ||
    $serie <= 0 ||
    $ripetizioni <= 0 ||
    $carico < 0
) {
    echo json_encode([
        "success" => false,
        "error" => "valori numerici non validi"
    ]);
    exit;
}
try {

//CASO 1:esercizio esistente)
    if ($id) {
        //preparo query di aggiornamento dell'esercizio
        $stmt = $conn->prepare("
            UPDATE esercizi_sala 
            SET nome = ?, serie = ?, ripetizioni = ?, carico = ?
            WHERE id = ? AND id_utente = ?
        ");

        //eseguo update con sicurezza(l'utente può modificare solo i propri esercizi)
        $stmt->execute([$nome, $serie, $ripetizioni, $carico, $id, $id_utente]);
    } else {
        //inserisci nuovo esercizio
        $stmt = $conn->prepare("
            INSERT INTO esercizi_sala (id_utente, nome, serie, ripetizioni, carico)
            VALUES (?, ?, ?, ?, ?)
        ");

        //eseguo l'inserimento associando l'esercizio all'utente loggato
        $stmt->execute([$id_utente, $nome, $serie, $ripetizioni, $carico]);
    }

//se tutto va a buon fine restituisco successo
    echo json_encode([
        "success" => true
        ]);
    exit;

} catch (PDOException $e) {
    //gestione errori del database
    echo json_encode([
        "success" => false, 
        "error" => "Errore interno del server durante il salvataggio dell'esercizio"
        ]);
    exit;
}