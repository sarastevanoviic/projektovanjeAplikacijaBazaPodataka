<?php
require_once __DIR__ . '/../baza/db.php';
require_once __DIR__ . '/../php/auth.php';

$auth = new Auth($conn);

// Ako sesija nije startovana, startuj
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Odjava korisnika
$auth->logout();

// Za svaki slučaj obriši i session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

header("Location: login.php");
exit;
?>
