<?php
$folder = __DIR__ . '/include';

if (!is_dir($folder)) {
    die("Folderul 'include' nu există.");
}

$files = scandir($folder);

echo "<h2>Fișiere din folderul 'include'</h2>";
echo "<ul>";

foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;
    
    $filePath = $folder . '/' . $file;
    $isHidden = (substr($file, 0, 1) === '.') ? ' (ascuns)' : '';
    
    echo "<li>" . htmlspecialchars($file) . $isHidden;
    if (is_file($filePath)) {
        echo " — fișier";
    } elseif (is_dir($filePath)) {
        echo " — folder";
    }
    echo "</li>";
}

echo "</ul>";
