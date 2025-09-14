<?php
require_once 'db.php';
require_once 'Korisnik.php';

class Auth {
    private $korisnik;

    public function __construct($conn){
        $this->korisnik = new Korisnik($conn);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function login($u, $p){
        $user = $this->korisnik->getKorisnik($u);
        if ($user && $p === $user['lozinka']) { 
            // ako želiš hash: password_verify($p, $user['lozinka'])
            session_regenerate_id(true);
            $_SESSION['korisnik'] = [
                'id' => $user['id_korisnika'],
                'username' => $user['korisnicko_ime']
            ];
            return true;
        }
        return false;
    }

    public function register($u, $p){
        if($this->korisnik->getKorisnik($u)) return false;
        // ako koristiš plain tekst lozinke, onda: $hash = $p;
        // preporuka: $hash = password_hash($p, PASSWORD_DEFAULT);
        $hash = $p;
        return $this->korisnik->createKorisnik($u, $hash);
    }

    public function logout(){
        $_SESSION = [];
        session_destroy();
    }

    public function isLoggedIn(){
        return isset($_SESSION['korisnik']);
    }

    public function getUser(){ 
        return $_SESSION['korisnik'] ?? null;
    }
}
