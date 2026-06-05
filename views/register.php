<?php
session_start();
require_once "../php/db.php";

if (isset($_SESSION['id'])) {
    header("Location: ../php/home.php");
    exit;
}

$messaggio = "";
$tipo_messaggio = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($email) && !empty($password)) {

        try {
            //username già esistente
            $stmt = $conn->prepare("SELECT id FROM utenti WHERE username = ?");
            $stmt->execute([$username]);

            if ($stmt->rowCount() > 0) {
                $messaggio = "Username già esistente!";
                $tipo_messaggio = "error";
            } else {

                //email già esistente (consigliato)
                $stmt = $conn->prepare("SELECT id FROM utenti WHERE email = ?");
                $stmt->execute([$email]);

                if ($stmt->rowCount() > 0) {
                    $messaggio = "Email già registrata!";
                    $tipo_messaggio = "error";
                } else {

                    //validazione password
                    if (
                        strlen($password) < 6 ||
                        !preg_match('/\d/', $password) ||
                        !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)
                    ) {
                        $messaggio = "La password deve avere almeno 6 caratteri, un numero e un carattere speciale.";
                        $tipo_messaggio = "error";
                    } else {
                        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                        $stmt = $conn->prepare(
                            "INSERT INTO utenti (username, email, password) VALUES (?, ?, ?)"
                        );
                        $stmt->execute([$username, $email, $hashed_password]);
                        $messaggio = "Registrazione completata! Ora puoi fare il login.";
                        $tipo_messaggio = "success";
                    }
                }
            }
        } catch (PDOException $e) {
            $messaggio = "Errore: Si è verificato un errore durante la registrazione." ;//$e->getMessage() è pericoloso mostrare l'errore
            $tipo_messaggio = "error";
        }

    } else {
        $messaggio = "Compila tutti i campi.";
        $tipo_messaggio = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Registrazione - Jungle Fitness</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
<div class="login-container">

    <h1>Crea il tuo Account</h1>

    <?php if (!empty($messaggio)): ?>
        <div class="message <?php echo $tipo_messaggio; ?>"
             style="color: <?php echo $tipo_messaggio == 'success' ? 'green' : 'red'; ?>; margin-bottom: 10px;">
            <?php echo htmlspecialchars($messaggio); ?>
        </div>
    <?php endif; ?>

    <form action="register.php" method="POST">

        <input type="text"
               name="username"
               placeholder="Scegli Username"
               required>

        <input type="email"
               name="email"
               placeholder="Inserisci Email"
               required>

        <div class="form-group password-group">
            <input type="password"
                   id="password"
                   name="password"
                   placeholder="Scegli Password"
                   required>

            <button type="button" id="togglePassword" class="toggle-password">
                👁️
            </button>
        </div>

        <div class="password-rules">
            <small id="length-rule">❌ Almeno 6 caratteri</small>
            <small id="number-rule">❌ Almeno un numero</small>
            <small id="special-rule">❌ Almeno un carattere speciale</small>
        </div>

        <button type="submit">Registrati</button>

    </form>

    <p>Hai già un account? <a href="login.php">Accedi qui</a></p>

</div>

<script>
const passwordInput = document.getElementById('password');

const lengthRule = document.getElementById('length-rule');
const numberRule = document.getElementById('number-rule');
const specialRule = document.getElementById('special-rule');

// validazione live password
passwordInput.addEventListener('input', () => {
    const password = passwordInput.value;

    lengthRule.textContent = password.length >= 6
        ? '✅ Almeno 6 caratteri'
        : '❌ Almeno 6 caratteri';

    numberRule.textContent = /\d/.test(password)
        ? '✅ Almeno un numero'
        : '❌ Almeno un numero';

    specialRule.textContent = /[!@#$%^&*(),.?":{}|<>]/.test(password)
        ? '✅ Almeno un carattere speciale'
        : '❌ Almeno un carattere speciale';
});

//toggle password
document.getElementById('togglePassword').addEventListener('click', function () {
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        this.textContent = '🙈';
    } else {
        passwordInput.type = 'password';
        this.textContent = '👁️';
    }
});
</script>

</body>
</html>