<?php
require_once 'db.php';
require_once 'Korisnik.php';

class Auth {
    private $korisnik;

    public function __construct($conn) {
        $this->korisnik = new Korisnik($conn);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login($username, $password) {
        $user = $this->korisnik->getKorisnik($username);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['korisnik'] = [
                'id' => $user['id'],
                'username' => $user['username']
            ];
            return true;
        }
        return false;
    }

    public function register($username, $password) {
        $user = $this->korisnik->getKorisnik($username);
        if ($user) {
            return false; // već postoji
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        return $this->korisnik->createKorisnik($username, $hashedPassword);
    }

    public function logout() {
        unset($_SESSION['korisnik']);
        session_destroy();
    }

    public function isLoggedIn() {
        return isset($_SESSION['korisnik']);
    }

    public function getUser() {
        return $_SESSION['korisnik'] ?? null;
    }
}
?>
