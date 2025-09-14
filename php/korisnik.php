<?php
class Korisnik {
    private mysqli $conn;
    private string $table = "korisnici";

    public function __construct(mysqli $conn){ 
        $this->conn = $conn; 
    }

    // Vrati korisnika po korisničkom imenu
    public function getKorisnik(string $username): ?array {
        $sql = "SELECT id_korisnika, korisnicko_ime, lozinka 
                FROM {$this->table} 
                WHERE korisnicko_ime = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $rez = $stmt->get_result()->fetch_assoc();
        return $rez ?: null;
    }

    // Kreiraj korisnika (trenutno plain lozinka; po želji pređi na hash)
    public function createKorisnik(string $username, string $password): bool {
        $sql = "INSERT INTO {$this->table} (korisnicko_ime, lozinka) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        return $stmt->execute();
    }
}
?>