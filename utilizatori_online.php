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

// Pragul pentru considerare online: 5 minute (300 secunde)
$interval_online = 5 * 60; // secunde

$stmt = $pdo->query("SELECT id, nume_utilizator, last_seen FROM utilizatori ORDER BY nume_utilizator ASC");
$users = $stmt->fetchAll();

$now = time();

?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8" />
    <title>Utilizatori online - Ferma lui <?= htmlspecialchars($nume_utilizator) ?></title>
    <link rel="stylesheet" href="css/game_index.css" />
    <link rel="stylesheet" href="css/utilizatori_online.css" />
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
            <!-- Poți adăuga alte info personale aici dacă vrei -->
        </div>
    </section>

    <section class="ferma-box">
        <h2>Utilizatori online</h2>
        <div class="lista-utilizatori">
            <?php if (empty($users)): ?>
                <p>Nu există utilizatori înregistrați.</p>
            <?php else: ?>
                <?php foreach ($users as $user):
                    $poza_profil = get_poza_profil($pdo, $user['id']);
                    $last_seen_time = strtotime($user['last_seen']);
                    $online = ($now - $last_seen_time) <= $interval_online;
                ?>
                    <div class="utilizator-mini">
                        <img src="uploads/<?= $user['id'] ?>/<?= htmlspecialchars($poza_profil) ?>" alt="Poza profil" />
                        <div class="nume"><?= htmlspecialchars($user['nume_utilizator']) ?></div>
                        <div class="status <?= $online ? 'online' : 'offline' ?>">
                            <?= $online ? 'Online' : 'Offline' ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="meniu-box">
        <nav class="meniu-continut">
            <a href="game_index.php">Ferma</a>
            <a href="profil.php">Profil</a>
            <a href="#">Magazin</a>
            <a href="utilizatori_online.php" class="active">Utilizatori Online</a>
        </nav>
    </section>

</main>

<footer class="footer-bar"></footer>

</body>
</html>
