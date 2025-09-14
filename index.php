<?php
require_once 'db.php';
require_once 'Auth.php';

$auth = new Auth($conn);

if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user = $auth->getUser();
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Glavna stranica</title>
</head>
<body>
    <h2>Dobrodošao, <?= htmlspecialchars($user['username']) ?>!</h2>
    <p>Ovo je glavna stranica tvoje aplikacije.</p>
    <a href="logout.php">Odjavi se</a>
</body>
</html>