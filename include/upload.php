<?php
session_start();
require_once 'auth.php';
require_once 'baza_date.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../bunvenit.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Setăm directorul de upload pentru utilizator
$uploadDir = __DIR__ . '/../uploads/' . $user_id . '/';

// Creăm folderul dacă nu există
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileName = basename($_FILES['photo']['name']);
        $fileSize = $_FILES['photo']['size'];
        $fileType = $_FILES['photo']['type'];

        // Validare extensie - acceptăm doar jpg, jpeg, png, gif
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowedExts)) {
            $message = "Formatul fișierului nu este acceptat. Folosește doar JPG, JPEG, PNG sau GIF.";
        } else {
            // Generează un nume unic pentru fișier pentru a evita suprascrierea
            $newFileName = uniqid('photo_', true) . '.' . $fileExt;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Salvează în baza de date
                $stmt = $pdo->prepare("INSERT INTO user_photos (user_id, filename) VALUES (:user_id, :filename)");
                $stmt->execute(['user_id' => $user_id, 'filename' => $newFileName]);

                // Redirectăm înapoi pe profil cu mesaj
                header("Location: ../profil.php?upload=success");
                exit;
            } else {
                $message = "Eroare la mutarea fișierului uploadat.";
            }
        }
    } else {
        $message = "Nu a fost selectat niciun fișier sau a apărut o eroare la upload.";
    }
}

?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8" />
    <title>Încarcă o poză nouă</title>
    <link rel="stylesheet" href="../css/profil.css" />
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; background: #f5f5f5; padding: 20px; border-radius: 8px; }
        form { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        input[type=file] { margin-bottom: 15px; }
        button { background-color: #00796b; color: white; border: none; padding: 10px 20px; cursor: pointer; font-weight: bold; border-radius: 4px; }
        button:hover { background-color: #004d40; }
        .message { margin-bottom: 15px; color: red; }
        a { color: #00796b; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <h1>Încarcă o poză nouă</h1>

    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="photo" accept=".jpg,.jpeg,.png,.gif" required />
        <br />
        <button type="submit">Încarcă</button>
    </form>

    <p><a href="../profil.php">Înapoi la profil</a></p>

</body>
</html>
