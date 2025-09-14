<?php
require_once 'db.php';
require_once 'Auth.php';

$auth = new Auth($conn);
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($auth->register($username, $password)) {
        $message = "Uspešno ste se registrovali! Možete se prijaviti.";
    } else {
        $message = "Korisničko ime već postoji ili je došlo do greške.";
    }
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Registracija</title>
</head>
<body>
    <h2>Registracija</h2>
    <form method="POST" action="">
        <label>Korisničko ime:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Lozinka:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Registruj se</button>
    </form>
    <p><?= $message ?></p>
    <p>Već imate nalog? <a href="login.php">Prijavite se</a></p>
</body>
</html>