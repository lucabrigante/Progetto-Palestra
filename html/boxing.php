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
  <title>Boxing - Jungle Fitness</title>
  <link rel="stylesheet" href="../css/base.css">
  <link rel="stylesheet" href="../css/corso.css">
  <script src="../js/boxing.js" defer></script>
</head>
<body>

  <div class="course-container">
    <div class="course-image">
      <img src="../img/boxing.jpg" alt="Corso di Boxing alla Jungle Fitness">
    </div>

    <h1>Prenota il corso di Boxing</h1>
    <p>Scegli l'orario che preferisci:</p>

    <select id="orari">
      <option value="" disabled selected>Seleziona un orario</option>
    </select>

    <p id="messaggio"></p>

    <div id="riepilogo-prenotazioni">
      <h3>Le tue prenotazioni:</h3>
      <ul id="lista-prenotazioni"></ul>
    </div>

    <button onclick="tornaHome()" class="course-btn">Torna alla Home</button>
  </div>

</body>
</html>