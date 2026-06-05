<?php
session_start();
//includiamo il file db.php risalendo di una cartella ed entrando in php
require_once "../php/db.php"; 

//se l'utente è già loggato, lo mandiamo direttamente alla home
if (isset($_SESSION['id'])) {
    header("Location: ../php/home.php");
    exit;
}

$errore = "";

//se il form viene inviato tramite post, elaboriamo i dati qui
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password)) {
        try {
           //cerca sia nella colonna username che nella colonna email
$stmt = $conn->prepare("SELECT id, username, password FROM utenti WHERE username = ? OR email = ?");
//passiamo la variabile due volte perché ci sono due punti interrogativi nella query
$stmt->execute([$username, $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            //verifichiamo la password cifrata
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                header("Location: ../php/home.php");
                exit;
            } else {
                $errore = "Credenziali non valide!";
            }
        } catch (PDOException $e) {
            
            $errore = "Errore interno del server."; 
        }
    } else {
        $errore = "Tutti i campi sono obbligatori.";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jungle Fitness</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/login.css">
    <script src="../js/script.js" defer></script>
</head>
<body>
    <div class="login-container">
        <h1>Accedi a Jungle Fitness</h1>
        
        <?php if (!empty($errore)): ?>
            <div class="error-message" style="color: red; margin-bottom: 10px; text-align: center;">
                <?php echo htmlspecialchars($errore); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
<input type="text" name="username" placeholder="Username o Email" required>            </div>
            
            <div class="form-group password-group">
    <input type="password"
           id="password"
           name="password"
           placeholder="Password"
           required>

    <button type="button" id="togglePassword" class="toggle-password">
        👁️
    </button>
</div>
            <button type="submit" class="login-btn">Accedi</button>
        </form>

        <p class="switch-form">Non sei registrato? <a href="register.php">Registrati qui</a></p>
    </div>

   <script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const password = document.getElementById('password');

    if (password.type === 'password') {
        password.type = 'text';
        this.textContent = '🙈';
    } else {
        password.type = 'password';
        this.textContent = '👁️';
    }
});
</script>
</body>
</html>