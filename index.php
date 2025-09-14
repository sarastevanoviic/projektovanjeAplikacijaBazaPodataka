<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>


<?php
require_once 'db.php';
require_once 'Auth.php';

// Napravi konekciju na bazu
$baza = new Baza();
$db = $baza->getConnection();

$auth = new Auth($db);

// Ako korisnik NIJE ulogovan → prebaci ga na login
if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// U suprotnom, dohvati podatke o korisniku
$user = $auth->getUser();
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Glavna stranica</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 20px 30px;
            border-radius: 10px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        h2 {
            color: #333;
        }
        a {
            color: #0066cc;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Dobrodošao, <?= htmlspecialchars($user['username']) ?>!</h2>
        <p>Ovo je glavna stranica tvoje aplikacije.</p>
        <p><a href="logout.php">Odjavi se</a></p>
    </div>
</body>
</html>