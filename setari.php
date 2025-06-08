<?php
session_start();
require_once 'include/auth.php';
require_once 'include/actualizeaza_last_seen.php';

if (!este_logat()) {
    header('Location: bunvenit.php');
    exit;
}

require_once 'include/baza_date.php';

$user_id = $_SESSION['user_id'] ?? null;
$nume_utilizator = $_SESSION['nume_utilizator'] ?? 'Jucător';

// Funcție pentru poza profil a unui user
function get_poza_profil($pdo, $uid) {
    $stmt = $pdo->prepare("SELECT filename FROM user_photos WHERE user_id = :uid AND is_profile_pic = 1 LIMIT 1");
    $stmt->execute(['uid' => $uid]);
    $row = $stmt->fetch();
    return $row ? $row['filename'] : 'default.png';
}

?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8" />
    <title>Setări - Ferma lui <?= htmlspecialchars($nume_utilizator) ?></title>
    <link rel="stylesheet" href="css/game_index.css" />
    <link rel="stylesheet" href="css/setari.css" />
</head>
<body>

<header class="header-profil">
    <div class="header-continut">
        <div class="profil-info">
            <span>Bun venit, <?= htmlspecialchars($nume_utilizator) ?></span>
        </div>
        <div class="profil-actiuni">
            <a href="include/logout.php" class="btn-logout">Deconectare</a>
        </div>
    </div>
</header>

<main class="ferma-container">

    <section class="profile-section">
        <div class="profile-avatar">
            <img src="uploads/<?= $user_id ?>/<?= htmlspecialchars(get_poza_profil($pdo, $user_id)) ?>" alt="Poza profil" />
        </div>
        <div class="profile-info">
            <h1><?= htmlspecialchars($nume_utilizator) ?></h1>
        </div>
    </section>

    <section class="setari-box">
        <h2>Setări</h2>
        <div class="buton-membrii-online-container">
            <a href="utilizatori_online.php" class="btn-membrii-online">Vezi membrii online</a>
        </div>
    </section>

    <section class="meniu-box">
        <nav class="meniu-continut">
            <a href="game_index.php">Ferma</a>
            <a href="profil.php">Profil</a>
            <a href="#">Magazin</a>
            <a href="setari.php" class="active">Setări</a>
        </nav>
    </section>

</main>

<footer class="footer-bar"></footer>

</body>
</html>
