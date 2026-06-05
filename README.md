Jungle Fitness


-- Descrizione
Jungle Fitness è una web application per la gestione di una palestra.
Gli utenti possono registrarsi, accedere al sistema e prenotare corsi o utilizzare la sala pesi.
Ogni utente ha una propria area personale dove può vedere e gestire le proprie prenotazioni.


-- Struttura del progetto
Il progetto è diviso in tre parti principali:

Frontend: HTML, CSS, JavaScript
Backend: PHP
Database: MySQL


-- Accesso al sistema
L’accesso avviene tramite login.
Ogni utente ha una sessione attiva che permette di utilizzare le funzionalità del sito in modo sicuro.
Se non si è autenticati, non è possibile accedere alle pagine principali.

-- Database
Il database contiene i dati degli utenti e le loro prenotazioni.
Ogni prenotazione è collegata all’utente che l’ha creata.


-- Funzionalita principali
Registrazione e login utenti
Prenotazione corsi palestra
Prenotazione sala pesi
Visualizzazione prenotazioni
Eliminazione prenotazioni
Gestione dati personale utente

-- Comunicazione
Il sito comunica tra frontend e backend tramite richieste HTTP.
I dati vengono scambiati in formato JSON per aggiornare la pagina senza ricaricarla.

-- Organizzazione
Il progetto è organizzato in modo separato per rendere il codice più chiaro:
file HTML per le pagine
file CSS per lo stile
file JavaScript per le interazioni
file PHP per la logica server
database MySQL per la gestione dei dati

-- Obiettivo
L’obiettivo del progetto è simulare un sistema reale di gestione di una palestra con autenticazione e gestione delle prenotazioni.