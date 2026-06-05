<?php
session_start();
// Se l'utente non è loggato, viene rimbalzato istantaneamente al login
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sala Pesi - Jungle Fitness</title>
  <link rel="stylesheet" href="../css/base.css">
  <link rel="stylesheet" href="../css/sala_pesi.css">
  <script src="../js/sala_pesi.js" defer></script>
</head>
<body>

<div class="main-container">
  <!--prenotazioni-->
  <div class="colonna prenotazioni">
    <div class="booking-container">
      <h1>Prenota la tua sessione</h1>

      <label for="giorno">Seleziona giorno:</label>
      <input type="date" id="giorno">

      <label for="fascia">Seleziona fascia oraria:</label>
      <select id="fascia">
        <option value="" selected>Seleziona fascia</option>
        <option value="00:00-03:00">00:00 - 03:00</option>
        <option value="03:00-06:00">03:00 - 06:00</option>
        <option value="06:00-09:00">06:00 - 09:00</option>
        <option value="09:00-12:00">09:00 - 12:00</option>
        <option value="12:00-15:00">12:00 - 15:00</option>
        <option value="15:00-18:00">15:00 - 18:00</option>
        <option value="18:00-21:00">18:00 - 21:00</option>
        <option value="21:00-24:00">21:00 - 24:00</option>
      </select>

      <button id="prenota-btn">Prenota</button>
      <p id="prenota-msg"></p>

      <div id="riepilogo-prenotazioni">
        <h2>Le tue prenotazioni:</h2>
        <ul id="lista-prenotazioni"></ul>
      </div>
    </div>
  </div>

  <!--esercizio + cronometro-->
  <div class="colonna workout-crono">
    <div class="workout-container">
      <h3>La tua scheda allenamento</h3>
      <div id="workout-grid"></div>
      <button id="aggiungi-esercizio">Aggiungi esercizio</button>
    </div>

    <div class="cronometro-container">
      <h2>Cronometro</h2>
      <div id="display-cronometro">00:00</div>
      <div class="cronometro-buttons">
        <button id="start-crono">Start</button>
        <button id="pause-crono">Pause</button>
        <button id="reset-crono">Reset</button>
      </div>
    </div>
  </div>
</div>

<!--bottone per tornare alla home-->
<button id="torna-home">🏠 Torna alla Home</button>
</body>
</html>