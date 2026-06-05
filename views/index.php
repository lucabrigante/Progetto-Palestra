<?php
session_start();

//se l'utente è già loggato, lo mandiamo direttamente alla Home
if (isset($_SESSION['id'])) {
    header("Location: ../php/home.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Jungle Fitness!</title>
    <link rel="stylesheet" href="../css/base.css">
<link rel="stylesheet" href="../css/index.css">

</head>
<body>

  <img src="../img/dumbell.jpg" alt="sinistra" class="side-image left">
  <img src="../img/mike.jpg" alt="destra" class="side-image right">

  <div class="top-image">
    <img src="../img/Logo.png" alt="logo centrale">
  </div>

  <div class="intro-text">
    <p class="welcome">Benvenuto nella Jungle Fitness, la palestra più completa di tutta Italia!</p>
    <p class="description">
      Qui avrai a disposizione un'intera sala pesi di 500 mq e corsi di sport da combattimento come 
      MMA, Boxing e Muay Thai.
    </p>
    <p class="login-link">
      <a href="login.php">Accedi qui per entrare</a>
    </p>
   
  </div>
</body>
</html>