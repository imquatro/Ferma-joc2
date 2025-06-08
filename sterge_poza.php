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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['photo_id'])) {
    $photo_id = intval($_POST['photo_id']);

    // Preluăm poza din baza de date să verificăm dacă aparține userului
    $stmt = $pdo->prepare("SELECT filename FROM user_photos WHERE id = :photo_id AND user_id = :user_id");
    $stmt->execute(['photo_id' => $photo_id, 'user_id' => $user_id]);
    $photo = $stmt->fetch();

    if ($photo) {
        // Ștergem fișierul de pe disc
        $file_path = __DIR__ . "/uploads/$user_id/" . $photo['filename'];
        if (file_exists($file_path)) {
            @unlink($file_path); // @ suprimă erorile în caz că nu există
        }

        // Ștergem intrarea din baza de date
        $pdo->prepare("DELETE FROM user_photos WHERE id = :photo_id AND user_id = :user_id")
            ->execute(['photo_id' => $photo_id, 'user_id' => $user_id]);
    }
}

// Redirecționăm mereu înapoi pe profil, fără mesaje
header('Location: profil.php');
exit;
