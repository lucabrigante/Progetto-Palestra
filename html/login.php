<?php
session_start();
// Se è già loggato, lo mandiamo direttamente alla home
if (isset($_SESSION['id'])) {
    header("Location: home.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Jungle Fitness</title>
  <link rel="stylesheet" href="../css/base.css">
<link rel="stylesheet" href="../css/login.css">
</head>
<body>
  <div class="login-container">
    <h1>Login</h1>

    <form action="../php/login.php" method="post">
      <label>Username o Email</label>
      <input type="text" name="username" placeholder="Username o Email" required>

      <label>Password</label>
      <div class="password-container">
        <input type="password" name="password" id="password" placeholder="Password" required>
        <button type="button" id="togglePassword">Mostra</button>
      </div>

      <button type="submit">Accedi</button>
    </form>

    <p>Non sei registrato? <a href="register.html">Registrati qui</a></p>
  </div>

  <!--script mostra/nascondi password-->
  <script>
    const passwordInput = document.getElementById("password"); //prende input password
    const toggleButton = document.getElementById("togglePassword"); //prende bottone mostra password

    toggleButton.addEventListener("click", () => { //evento click bottone
      const type = passwordInput.getAttribute("type") === "password" ? "text" : "password"; //cambia tipo input
      passwordInput.setAttribute("type", type); //aggiorna tipo input
      toggleButton.textContent = type === "password" ? "Mostra" : "Nascondi"; //cambia testo bottone
    });
  </script>
</body>
</html>