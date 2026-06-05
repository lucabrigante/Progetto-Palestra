<?php
session_start(); //avvio sessione per accedere ai dati dell'utente autenticato
require_once "db.php"; //importo il file di connessione al database

//controllo di sicurezza: se l'utente non è autenticato lo reindirizzo alla nuova pagina di login
if (!isset($_SESSION['id'])) { 
    header("Location: ../views/login.php"); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home - Jungle Fitness</title>
  <link rel="stylesheet" href="../css/base.css">
  <link rel="stylesheet" href="../css/home.css">
  <link rel="stylesheet" href="../css/corso.css">
  <script src="../js/home.js" defer></script>
</head>
<body>

  <header>
    <h1>🏋️‍♂️ Jungle Fitness</h1>
    <p>Benvenuto, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    <p>I corsi per gli sport da combattimento sono disponibili solo Lunedì, Mercoledì e Venerdì</p>
    
    <a href="logout.php" class="logout-btn">Logout</a>
  </header>

  <main>
    <section class="courses-section">
      <h2>Scegli il tuo corso</h2>
      <div class="courses-grid">
        <button class="course-btn" data-page="../views/mma.php">MMA</button>
        <button class="course-btn" data-page="../views/boxing.php">Boxing</button>
        <button class="course-btn" data-page="../views/muay-thai.php">Muay Thai</button>
        <button class="course-btn" data-page="../views/sala_pesi.php">Sala Pesi</button>
      </div>
    </section>
  </main>

  <footer>
    <p>&copy; 2026 Jungle Fitness. Tutti i diritti riservati.</p>
  </footer>

</body>
</html>