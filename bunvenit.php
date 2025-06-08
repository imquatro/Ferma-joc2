<?php
session_start();
require_once 'include/actualizeaza_last_seen.php';
// Dacă utilizatorul e deja logat, îl redirecționăm către joc
if (isset($_SESSION['user_id'])) {
    header('Location: game_index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8" />
    <title>Bine ai venit la Ferma Ta!</title>
    <link rel="stylesheet" href="css/welcome.css" />
</head>
<body>

<header class="header-welcome">
    <div class="header-continut">
        <div class="logo">🐄 Ferma Ta</div>
    </div>
</header>

<main class="welcome-container">
    <div class="form-container">

        <!-- Login form -->
        <form id="login-form" class="form-active" method="POST" action="include/autentificare.php">
            <h2>Autentificare</h2>
            <input type="text" name="login_utilizator" placeholder="Nume utilizator" required />
            <input type="password" name="login_parola" placeholder="Parolă" required />
            <button type="submit" name="login_submit">Autentifică-te</button>
            <p class="toggle-text">Nu ai cont? <a href="#" id="show-register">Crează unul aici</a></p>
        </form>

        <!-- Register form -->
        <form id="register-form" class="form-hidden" method="POST" action="include/autentificare.php">
            <h2>Înregistrare</h2>
            <input type="text" name="reg_utilizator" placeholder="Nume utilizator" required />
            <input type="email" name="reg_email" placeholder="Email" required />
            <input type="password" name="reg_parola" placeholder="Parolă" required />
            <button type="submit" name="reg_submit">Înregistrează-te</button>
            <p class="toggle-text">Ai cont? <a href="#" id="show-login">Autentifică-te aici</a></p>
        </form>

    </div>
</main>

<footer class="footer-bar">
    &copy; 2025 Ferma Ta. Toate drepturile rezervate.
</footer>

<script>
    document.getElementById('show-register').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('login-form').classList.remove('form-active');
        document.getElementById('login-form').classList.add('form-hidden');
        document.getElementById('register-form').classList.remove('form-hidden');
        document.getElementById('register-form').classList.add('form-active');
    });

    document.getElementById('show-login').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('register-form').classList.remove('form-active');
        document.getElementById('register-form').classList.add('form-hidden');
        document.getElementById('login-form').classList.remove('form-hidden');
        document.getElementById('login-form').classList.add('form-active');
    });
</script>

</body>
</html>
