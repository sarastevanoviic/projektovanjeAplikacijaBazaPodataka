<?php
require_once 'db.php';
require_once 'Korisnik.php';

class Auth {
    private $korisnik;

    public function __construct($db) {
        
        $this->korisnik = new Korisnik($db);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login($username, $password) { //login
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
        return false; 
    }
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    return $this->korisnik->createKorisnik($username, $hashedPassword);
   }

    public function logout() { //logout
        unset($_SESSION['korisnik']);
        session_destroy();
    }

    public function isLoggedIn() { //proverava da li je korisnik ulog
        return isset($_SESSION['korisnik']);
    }

    public function getUser() { //vraća podatke o k0r
        return $_SESSION['korisnik'] ?? null;
    }
}
?>