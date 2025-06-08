<?php
session_start();
session_unset();
session_destroy();

header("Location: ../bunvenit.php");  // pentru a reveni în folderul principal
exit;
