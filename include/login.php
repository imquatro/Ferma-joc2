<?php
session_start();
require_once 'include/auth.php';
require_once 'include/actualizeaza_last_seen.php';

// Dacă utilizatorul este deja logat, redirecționăm spre game_index.php
if (este_logat()) {
    header("Location: game_index.php");
    exit;
}

$eroare = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nume = trim($_POST['nume']);
    $parola = $_POST['parola'];

    if (empty($nume) || empty($parola)) {
        $eroare = 'Completează toate câmpurile.';
    } else {
        if (autentificare($nume, $parola)) {
            header("Location: game_index.php");
            exit;
        } else {
            $eroare = 'Nume utilizator sau parolă incorectă.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8" />
    <title>Login - Ferma Joc</title>
    <link rel="stylesheet" href="css/login.css" />
</head>
<body>
    <div class="login-container">
        <h1>Autentificare</h1>

        <?php if ($eroare): ?>
            <div class="error"><?= htmlspecialchars($eroare) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php" novalidate>
            <input type="text" name="nume" placeholder="Nume utilizator" value="<?= isset($nume) ? htmlspecialchars($nume) : '' ?>" required />
            <input type="password" name="parola" placeholder="Parolă" required />
            <button type="submit">Loghează-te</button>
        </form>

        <p>Nu ai cont? <a href="bunvenit.php">Înregistrează-te aici</a></p>
    </div>
</body>
</html>
