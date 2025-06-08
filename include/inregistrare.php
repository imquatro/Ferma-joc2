<?php
require_once 'include/auth.php';
require_once 'include/actualizeaza_last_seen.php';

// Dacă utilizatorul este deja logat, îl redirecționăm spre profil
if (este_logat()) {
    header("Location: profile.php");
    exit;
}

$eroare = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nume = trim($_POST['nume']);
    $email = trim($_POST['email']);
    $parola = $_POST['parola'];
    $parola_confirm = $_POST['parola_confirm'];

    // Validări simple
    if (empty($nume) || empty($email) || empty($parola) || empty($parola_confirm)) {
        $eroare = 'Toate câmpurile sunt obligatorii.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $eroare = 'Email invalid.';
    } elseif ($parola !== $parola_confirm) {
        $eroare = 'Parolele nu se potrivesc.';
    } else {
        // Încercăm să înregistrăm utilizatorul
        if (inregistrare($nume, $email, $parola)) {
            $succes = 'Înregistrare reușită! Te poți loga acum.';
        } else {
            $eroare = 'Numele de utilizator sau emailul există deja.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8" />
    <title>Înregistrare - Ferma Joc</title>
    <link rel="stylesheet" href="css/login.css" />
</head>
<body>
    <h1>Înregistrare</h1>

    <?php if ($eroare): ?>
        <div style="color: red; font-weight: bold; margin-bottom: 15px;"><?= htmlspecialchars($eroare) ?></div>
    <?php endif; ?>

    <?php if ($succes): ?>
        <div style="color: green; font-weight: bold; margin-bottom: 15px;"><?= htmlspecialchars($succes) ?></div>
    <?php endif; ?>

    <form method="POST" action="inregistrare.php">
        <h2>Crează-ți cont</h2>
        <input type="text" name="nume" placeholder="Nume utilizator" value="<?= isset($nume) ? htmlspecialchars($nume) : '' ?>" required />
        <input type="email" name="email" placeholder="Email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required />
        <input type="password" name="parola" placeholder="Parolă" required />
        <input type="password" name="parola_confirm" placeholder="Confirmă parola" required />
        <button type="submit">Înregistrează-te</button>
    </form>

    <p>Ai deja cont? <a href="login.php">Loghează-te aici</a></p>
</body>
</html>
