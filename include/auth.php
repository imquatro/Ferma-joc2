<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'baza_date.php';
date_default_timezone_set('Europe/Bucharest');  // sau timezone-ul tău

// Funcție pentru autentificare
function autentificare($nume, $parola) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM utilizatori WHERE nume_utilizator = ?");
    $stmt->execute([$nume]);
    $user = $stmt->fetch();

    if ($user && password_verify($parola, $user['parola'])) {
        // Setăm datele în sesiune
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nume_utilizator'] = $user['nume_utilizator'];
        return true;
    }
    return false;
}

// Funcție să verificăm dacă userul este logat
function este_logat() {
    return isset($_SESSION['user_id']);
}

// Funcție pentru logout
function delogare() {
    session_unset();
    session_destroy();
}

// Orice alte funcții utile le poți adăuga aici
