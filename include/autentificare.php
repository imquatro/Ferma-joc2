<?php
session_start();
require_once 'baza_date.php';

// Functie pentru sanitizare input simpla
function curata_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // INREGISTRARE
    if (isset($_POST['reg_submit'])) {
        $username = curata_input($_POST['reg_utilizator']);
        $email = curata_input($_POST['reg_email']);
        $parola = $_POST['reg_parola'];

        // Validări simple
        if (strlen($username) < 3) {
            die('Numele de utilizator trebuie să aibă cel puțin 3 caractere.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die('Email invalid.');
        }
        if (strlen($parola) < 6) {
            die('Parola trebuie să aibă cel puțin 6 caractere.');
        }

        // Verifică dacă username sau email există deja
        $stmt = $pdo->prepare("SELECT * FROM utilizatori WHERE nume_utilizator = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        if ($stmt->rowCount() > 0) {
            die('Nume utilizator sau email deja folosit.');
        }

        // Hash parola
        $hashParola = password_hash($parola, PASSWORD_DEFAULT);

        // Introducere în DB
        $stmt = $pdo->prepare("INSERT INTO utilizatori (nume_utilizator, email, parola) VALUES (:username, :email, :parola)");
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'parola' => $hashParola
        ]);

        echo "Înregistrare realizată cu succes! <a href='../bunvenit.php'>Autentifică-te acum</a>";
        exit;
    }

    // LOGIN
    if (isset($_POST['login_submit'])) {
        $username = curata_input($_POST['login_utilizator']);
        $parola = $_POST['login_parola'];

        // Caută utilizatorul în DB
        $stmt = $pdo->prepare("SELECT * FROM utilizatori WHERE nume_utilizator = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($parola, $user['parola'])) {
            // Setăm sesiunea
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nume'] = $user['nume_utilizator'];

            header('Location: ../game_index.php');
            exit;
        } else {
            die('Nume utilizator sau parolă incorecte.');
        }
    }
}
