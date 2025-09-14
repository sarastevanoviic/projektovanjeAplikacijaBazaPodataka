<?php


require_once __DIR__ . '/korisnik.php';


class Auth {
    private Korisnik $korisnik;

    public function __construct(mysqli $conn){
        $this->korisnik = new Korisnik($conn);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function login(string $u, string $p): bool {
        $user = $this->korisnik->getKorisnik($u);
      
        if ($user && $p === $user['lozinka']) { 
            session_regenerate_id(true);
            $_SESSION['korisnik'] = [
                'id'       => $user['id_korisnika'],
                'username' => $user['korisnicko_ime']
            ];
            return true;
        }
        return false;
    }

    public function register(string $u, string $p): bool {
        if ($this->korisnik->getKorisnik($u)) return false;
        
        return $this->korisnik->createKorisnik($u, $p);
    }

    public function logout(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $_SESSION = [];
        session_destroy();
    }

    public function isLoggedIn(): bool {
        return isset($_SESSION['korisnik']);
    }

    public function getUser(): ?array { 
        return $_SESSION['korisnik'] ?? null;
    }
}
?>