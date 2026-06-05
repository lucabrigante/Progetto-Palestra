<?php
session_start();


if (!isset($_SESSION['id'])) {
    header("Location: login.php"); // Si trova nella stessa cartella views/
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Muay Thai - Jungle Fitness</title>
  <link rel="stylesheet" href="../css/base.css">
  <link rel="stylesheet" href="../css/corso.css">
  <script src="../js/mthay.js" defer></script>
</head>
<body>

  <div class="course-container">
    <div class="course-image">
      <img src="../img/muay_thai.jpg" alt="Muay Thai Jungle Fitness">
    </div>

    <h1>Prenota il corso di Muay Thai</h1>
    <p>Scegli l'orario che preferisci:</p>

    <select id="orari">
      <option value="" disabled selected>Seleziona un orario</option>
    </select>

    <p id="messaggio"></p>

    <div id="riepilogo-prenotazioni">
      <h1>Le tue prenotazioni:</h1>
      <ul id="lista-prenotazioni"></ul>
    </div>

    <button onclick="tornaHome()" class="course-btn">Torna alla Home</button>
  </div>

</body>
</html>