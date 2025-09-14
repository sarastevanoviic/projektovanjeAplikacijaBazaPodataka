<?php
require_once 'db.php';        
require_once 'Auth.php';

$auth = new Auth($db);
$message = "";


$baza = new Baza();          
$db = $baza->getConnection(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($auth->login($username, $password)) {
        header("Location: index.html"); 
        exit;
    } else {
        $message = "Pogrešno korisničko ime ili lozinka.";
    }
    
}

?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Prijava</title>
</head>
<body>
    <h2>Prijava</h2>
    <form method="POST" action="">
        <label>Korisničko ime:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Lozinka:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Prijavi se</button>
    </form>
    <p><?= $message ?></p>
    <p>Nemaš nalog? <a href="register.php">Registruj se</a></p>
</body>
</html>