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

$poza_profil = 'default.png';
$email = '';
$data_inregistrarii = '';

if ($user_id) {
    $stmt = $pdo->prepare("SELECT filename FROM user_photos WHERE user_id = :uid AND is_profile_pic = 1 LIMIT 1");
    $stmt->execute(['uid' => $user_id]);
    $row = $stmt->fetch();
    if ($row) {
        $poza_profil = $row['filename'];
    }

    $stmtInfo = $pdo->prepare("SELECT email, data_inregistrarii, nume_utilizator FROM utilizatori WHERE id = :id");
    $stmtInfo->execute(['id' => $user_id]);
    $user = $stmtInfo->fetch();
    $email = $user['email'] ?? '';
    $data_inregistrarii = $user['data_inregistrarii'] ?? '';
    $nume_utilizator = $user['nume_utilizator'] ?? $nume_utilizator;
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8" />
    <title>Ferma lui <?= htmlspecialchars($nume_utilizator) ?></title>
    <link rel="stylesheet" href="css/game_index.css" />
</head>
<body>

<!-- Bara superioară verde FULL WIDTH -->
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

<!-- Conținut principal -->
<main class="ferma-container">

    <section class="profile-section">
        <div class="profile-avatar">
            <img src="uploads/<?= $user_id ?>/<?= htmlspecialchars($poza_profil) ?>" alt="Poza profil" />
        </div>
        <div class="profile-info">
            <h1><?= htmlspecialchars($nume_utilizator) ?></h1>
            <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
            <p><strong>Membru din:</strong> <?= date('d.m.Y', strtotime($data_inregistrarii)) ?></p>
        </div>
    </section>

    <section class="ferma-box">
        <h2>Ferma ta</h2>
        <div class="sloturi-ferma">
            <?php for ($i = 1; $i <= 9; $i++): ?>
                <div class="slot-ferma">
                    <img src="assets/imagini/ferma.png" alt="Ferma <?= $i ?>" />
                    <p>Slot <?= $i ?></p>
                </div>
            <?php endfor; ?>
        </div>
    </section>

    <!-- Cadru special pentru meniul de navigare -->
<section class="meniu-box">
    <nav class="meniu-continut">
        <a href="game_index.php" class="active">Ferma</a>
        <a href="profil.php">Profil</a>
        <a href="#">Magazin</a>
        <a href="setari.php">Setări</a>
    </nav>
</section>


</main>

<!-- Bara inferioară verde FULL WIDTH -->
<footer class="footer-bar"></footer>

</body>
</html>
