<?php
session_start();
require_once 'include/auth.php';
require_once 'include/baza_date.php';
require_once 'include/actualizeaza_last_seen.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: bunvenit.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$nume_utilizator = $_SESSION['nume_utilizator'] ?? 'Jucător';

// Preluare date utilizator
$stmtUser = $pdo->prepare("SELECT nume_utilizator, email, data_inregistrarii FROM utilizatori WHERE id = :id");
$stmtUser->execute(['id' => $user_id]);
$user = $stmtUser->fetch();

// Setare poza profil
if (isset($_GET['set_profile'])) {
    $photo_id = intval($_GET['set_profile']);
    $pdo->prepare("UPDATE user_photos SET is_profile_pic = 0 WHERE user_id = :user_id")->execute(['user_id' => $user_id]);
    $pdo->prepare("UPDATE user_photos SET is_profile_pic = 1 WHERE id = :photo_id AND user_id = :user_id")
        ->execute(['photo_id' => $photo_id, 'user_id' => $user_id]);
    header("Location: profil.php");
    exit;
}

// Preluare poze utilizator
$stmtPhotos = $pdo->prepare("SELECT * FROM user_photos WHERE user_id = :user_id ORDER BY uploaded_at DESC");
$stmtPhotos->execute(['user_id' => $user_id]);
$photos = $stmtPhotos->fetchAll();

// Gasim poza de profil
$profilePic = null;
foreach ($photos as $photo) {
    if ($photo['is_profile_pic']) {
        $profilePic = $photo['filename'];
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8" />
    <title>Profilul lui <?= htmlspecialchars($user['nume_utilizator']) ?></title>
    <link rel="stylesheet" href="css/profile.css" />
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
            <?php if ($profilePic): ?>
                <img src="uploads/<?= $user_id ?>/<?= htmlspecialchars($profilePic) ?>" alt="Poză profil" />
            <?php else: ?>
                <span class="avatar-placeholder">👤</span>
            <?php endif; ?>
        </div>
        <div class="profile-info">
            <h1><?= htmlspecialchars($user['nume_utilizator']) ?></h1>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Membru din:</strong> <?= date('d.m.Y', strtotime($user['data_inregistrarii'])) ?></p>
        </div>
    </section>

    <section class="ferma-box">
        <h2>Galeria ta de poze</h2>
        <div class="sloturi-ferma">
            <?php if (!$photos): ?>
                <p>Nu ai încă poze în galerie.</p>
            <?php endif; ?>

            <?php foreach ($photos as $photo): ?>
                <div class="slot-ferma">
                    <img src="uploads/<?= $user_id ?>/<?= htmlspecialchars($photo['filename']) ?>" alt="Poză" />
                    <?php if (!$photo['is_profile_pic']): ?>
                        <a href="profil.php?set_profile=<?= $photo['id'] ?>" class="btn-logout">Setează ca poză profil</a>
                    <?php else: ?>
                        <p style="color:#004d40; font-weight:bold;">Poza ta de profil</p>
                    <?php endif; ?>
                    <form method="POST" action="sterge_poza.php">
                        <input type="hidden" name="photo_id" value="<?= $photo['id'] ?>" />
                        <button type="submit" class="btn-logout" style="background-color:#e74c3c;">Șterge poza</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="margin-top: 20px;">
            <a href="include/upload.php" class="btn-logout">Încarcă o poză nouă</a>
        </div>
    </section>

    <section class="meniu-box">
        <nav class="meniu-continut">
            <a href="game_index.php">Ferma</a>
            <a href="profil.php" class="active">Profil</a>
            <a href="#">Magazin</a>
            <a href="setari.php">Setări</a>
        </nav>
    </section>

</main>

<footer class="footer-bar"></footer>

</body>
</html>
