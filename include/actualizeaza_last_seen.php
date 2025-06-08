<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'baza_date.php'; // Include conexiunea la baza de date

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("UPDATE utilizatori SET last_seen = NOW() WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);

    // Acum luăm last_seen actualizat
    $stmt2 = $pdo->prepare("SELECT last_seen FROM utilizatori WHERE id = :id");
    $stmt2->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt2->fetch();

    if ($user) {
//        echo "Last seen user: " . $user['last_seen'] . "<br>";
//        echo "Current time: " . date('Y-m-d H:i:s') . "<br>";
//        echo "Diff seconds: " . (time() - strtotime($user['last_seen'])) . "<br>";
    } else {
        echo "Nu am gasit utilizatorul in baza de date.<br>";
    }
} else {
    echo "Nu esti logat.<br>";
}
?>
